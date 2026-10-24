<?php
/**
 * Load the archived FGComics forums into a phpBB 3.3 board.
 *
 * Run inside the web container as www-data:
 *   php import_archive.php /path/to/import.json
 * (made by scripts/forum_export_for_phpbb3.py). Replaces the installer's sample forum/topic/post,
 * creates one category per era, one account per person with an unusable password (reclaim =
 * "I forgot my password" by email), archive-only stubs for authors with no member row, topics,
 * and posts with their original timestamps. No IPs are stored. PMs are not imported.
 */
define('IN_PHPBB', true);
$phpbb_root_path = '/var/www/html/';
$phpEx = 'php';
chdir($phpbb_root_path);
require $phpbb_root_path . 'common.php';
require_once $phpbb_root_path . 'includes/functions_user.php';
require_once $phpbb_root_path . 'includes/functions_admin.php';
require_once $phpbb_root_path . 'includes/functions_content.php';
$user->session_begin();
$auth->acl($user->data);
$user->setup();

$data = json_decode(file_get_contents($argv[1]), true);
if (!$data) { fwrite(STDERR, "bad json\n"); exit(1); }
$db->sql_query('SELECT COUNT(*) AS n FROM ' . POSTS_TABLE);
if ((int) $db->sql_fetchfield('n') > 1) { fwrite(STDERR, "board already has posts; run dev.sh reset first\n"); exit(1); }

$q = function ($sql) use ($db) { $db->sql_query($sql); };
$ins = function ($table, array $row) use ($db) { $db->sql_query('INSERT INTO ' . $table . ' ' . $db->sql_build_array('INSERT', $row)); return (int) $db->sql_nextid(); };
$store = function ($text) {
    $uid = $bitfield = '';
    $opts = 7; // bbcode + smilies + urls
    generate_text_for_storage($text, $uid, $bitfield, $opts, true, true, true);
    return [$text, $uid, $bitfield];
};

// ---- forum permissions by role (stock phpBB defaults), independent of the sample forum's rows
$roleId = [];
$res = $db->sql_query('SELECT role_id, role_name FROM ' . ACL_ROLES_TABLE . " WHERE role_type = 'f_'");
while ($r = $db->sql_fetchrow($res)) $roleId[$r['role_name']] = (int) $r['role_id'];
$db->sql_freeresult($res);
$grpRole = ['GUESTS' => 'ROLE_FORUM_READONLY', 'REGISTERED' => 'ROLE_FORUM_STANDARD', 'REGISTERED_COPPA' => 'ROLE_FORUM_STANDARD',
            'GLOBAL_MODERATORS' => 'ROLE_FORUM_STANDARD', 'ADMINISTRATORS' => 'ROLE_FORUM_FULL', 'BOTS' => 'ROLE_FORUM_BOT', 'NEWLY_REGISTERED' => 'ROLE_FORUM_NEW_MEMBER'];
$tmpl = [];
$res = $db->sql_query('SELECT group_id, group_name FROM ' . GROUPS_TABLE . ' WHERE group_type = ' . GROUP_SPECIAL);
while ($r = $db->sql_fetchrow($res)) {
    if (isset($grpRole[$r['group_name']], $roleId[$grpRole[$r['group_name']]]))
        $tmpl[] = ['group_id' => $r['group_id'], 'auth_option_id' => 0, 'auth_role_id' => $roleId[$grpRole[$r['group_name']]], 'auth_setting' => 0];
}
$db->sql_freeresult($res);
if (count($tmpl) < 5) { fwrite(STDERR, "could not resolve forum roles\n"); exit(1); }
foreach ([FORUMS_TABLE, TOPICS_TABLE, POSTS_TABLE] as $t) $q('DELETE FROM ' . $t);
$q('DELETE FROM ' . ACL_GROUPS_TABLE . ' WHERE forum_id > 0');
$q('DELETE FROM ' . ACL_USERS_TABLE . ' WHERE forum_id > 0');

$fd = ['forum_parents' => '', 'forum_rules' => '', 'forum_rules_link' => '', 'forum_image' => '', 'forum_desc_bitfield' => '', 'forum_desc_uid' => '',
    'forum_rules_bitfield' => '', 'forum_rules_uid' => '', 'forum_last_post_subject' => '', 'forum_last_poster_name' => '', 'forum_last_poster_colour' => ''];
$n = 1; $forumMap = []; $forumIds = [];
foreach ($data['categories'] as $cat) {
    $left = $n++;
    $cid = $ins(FORUMS_TABLE, $fd + ['parent_id' => 0, 'left_id' => $left, 'right_id' => 0, 'forum_name' => $cat['name'], 'forum_desc' => '',
        'forum_type' => FORUM_CAT, 'forum_status' => ITEM_UNLOCKED, 'display_on_index' => 1, 'enable_indexing' => 1, 'forum_flags' => 48, 'forum_options' => 0]);
    foreach ($cat['forums'] as $f) {
        $fid = $ins(FORUMS_TABLE, $fd + ['parent_id' => $cid, 'left_id' => $n++, 'right_id' => $n++, 'forum_name' => $f['name'], 'forum_desc' => '',
            'forum_type' => FORUM_POST, 'forum_status' => ITEM_UNLOCKED, 'display_on_index' => 1, 'enable_indexing' => 1, 'forum_flags' => 48, 'forum_options' => 0]);
        $forumMap[$cat['src'] . ':' . $f['id']] = $fid; $forumIds[] = $fid;
        foreach ($tmpl as $r) $ins(ACL_GROUPS_TABLE, ['group_id' => $r['group_id'], 'forum_id' => $fid, 'auth_option_id' => $r['auth_option_id'], 'auth_role_id' => $r['auth_role_id'], 'auth_setting' => $r['auth_setting']]);
    }
    foreach ($tmpl as $r) $ins(ACL_GROUPS_TABLE, ['group_id' => $r['group_id'], 'forum_id' => $cid, 'auth_option_id' => $r['auth_option_id'], 'auth_role_id' => $r['auth_role_id'], 'auth_setting' => $r['auth_setting']]);
    $q('UPDATE ' . FORUMS_TABLE . ' SET right_id = ' . $n++ . ' WHERE forum_id = ' . $cid);
}

// ---- accounts
$config->set('allow_emailreuse', 1);
$db->sql_query('SELECT group_id FROM ' . GROUPS_TABLE . " WHERE group_name = 'REGISTERED' AND group_type = " . GROUP_SPECIAL);
$regGroup = (int) $db->sql_fetchfield('group_id');
$pfCols = [];
$res = $db->sql_query("SELECT column_name FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = '" . PROFILE_FIELDS_DATA_TABLE . "'");
while ($r = $db->sql_fetchrow($res)) $pfCols[$r['column_name']] = true;
$db->sql_freeresult($res);
$pfMap = ['location' => 'pf_phpbb_location', 'website' => 'pf_phpbb_website', 'interests' => 'pf_phpbb_interests', 'occupation' => 'pf_phpbb_occupation',
          'icq' => 'pf_phpbb_icq', 'yahoo' => 'pf_phpbb_yahoo', 'aim' => 'pf_phpbb_aol'];
$uid = []; $names = [];
$mk = function ($a, $stub) use (&$uid, $regGroup, $store, $pfMap, $pfCols) {
    $sig = !empty($a['sig']) ? $store($a['sig']) : ['', '', ''];
    $row = ['username' => $a['username'], 'user_password' => '*', 'user_email' => $stub ? '' : ($a['email'] ?? ''), 'group_id' => $regGroup,
        'user_type' => $stub ? USER_INACTIVE : USER_NORMAL, 'user_regdate' => $a['joined'] ?? 0, 'user_lang' => 'en', 'user_timezone' => 'UTC',
        'user_sig' => $sig[0], 'user_sig_bbcode_uid' => $sig[1], 'user_sig_bbcode_bitfield' => $sig[2]];
    if ($stub) { $row['user_inactive_reason'] = INACTIVE_MANUAL; $row['user_inactive_time'] = time(); }
    $cp = [];
    foreach (($a['profile'] ?? []) as $k => $v) if (isset($pfMap[$k]) && isset($pfCols[$pfMap[$k]])) $cp[$pfMap[$k]] = $v;
    return user_add($row, $cp);
};
foreach ($data['accounts'] as $a) { $uid[mb_strtolower($a['username'])] = $mk($a, false); }
foreach ($data['stubs'] as $a)   { $uid[mb_strtolower($a['username'])] = $mk($a, true); }

// ---- topics and posts
$topicMap = []; $topicIds = [];
foreach ($data['topics'] as $t) {
    $poster = $uid[mb_strtolower($t['starter'])] ?? ANONYMOUS;
    $tid = $ins(TOPICS_TABLE, ['forum_id' => $forumMap[$t['forum']], 'topic_title' => $t['title'], 'topic_poster' => $poster, 'topic_time' => $t['ts'],
        'topic_visibility' => ITEM_APPROVED, 'topic_first_poster_name' => $t['starter']]);
    $topicMap[$t['key']] = [$tid, $forumMap[$t['forum']], $t['title']]; $topicIds[] = $tid;
}
$db->sql_transaction('begin');
$seen = [];
foreach ($data['posts'] as $p) {
    [$tid, $fid, $title] = $topicMap[$p['topic']];
    $poster = $uid[mb_strtolower($p['author'])] ?? ANONYMOUS;
    [$text, $buid, $bbits] = $store($p['text']);
    $subject = $p['subject'] !== '' ? $p['subject'] : (isset($seen[$tid]) ? 'Re: ' . $title : $title);
    $seen[$tid] = true;
    $ins(POSTS_TABLE, ['topic_id' => $tid, 'forum_id' => $fid, 'poster_id' => $poster, 'post_time' => $p['ts'], 'poster_ip' => '',
        'post_username' => $poster == ANONYMOUS ? $p['author'] : '', 'post_subject' => $subject, 'post_text' => $text, 'bbcode_uid' => $buid, 'bbcode_bitfield' => $bbits,
        'enable_bbcode' => 1, 'enable_smilies' => 1, 'enable_magic_url' => 1, 'enable_sig' => 1, 'post_checksum' => md5($p['text']), 'post_visibility' => ITEM_APPROVED, 'post_postcount' => 1]);
}
$db->sql_transaction('commit');

// ---- derived counters
sync('topic', 'topic_id', $topicIds);
sync('forum', 'forum_id', $forumIds);
$q('UPDATE ' . USERS_TABLE . ' u SET user_posts = (SELECT COUNT(*) FROM ' . POSTS_TABLE . ' p WHERE p.poster_id = u.user_id), '
   . 'user_lastpost_time = COALESCE((SELECT MAX(post_time) FROM ' . POSTS_TABLE . ' p WHERE p.poster_id = u.user_id), 0) WHERE u.user_type <> ' . USER_IGNORE);
$cnt = function ($sql) use ($db) { $db->sql_query($sql); return (int) $db->sql_fetchfield('c'); };
$config->set('num_posts', $cnt('SELECT COUNT(*) AS c FROM ' . POSTS_TABLE), false);
$config->set('num_topics', $cnt('SELECT COUNT(*) AS c FROM ' . TOPICS_TABLE), false);
$config->set('num_users', $cnt('SELECT COUNT(*) AS c FROM ' . USERS_TABLE . ' WHERE user_type IN (0,3)'), false);
$cache->purge();
$auth->acl_clear_prefetch();
printf("imported %d forums, %d accounts (+%d archive stubs), %d topics, %d posts\n", count($forumIds), count($data['accounts']), count($data['stubs']), count($topicIds), count($data['posts']));
