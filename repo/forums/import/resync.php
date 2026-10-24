<?php
/** Re-sync forum counters and last-post data after import. Run in the web container as www-data. */
define('IN_PHPBB', true);
$phpbb_root_path = '/var/www/html/'; $phpEx = 'php'; chdir($phpbb_root_path);
require $phpbb_root_path . 'common.php';
require_once $phpbb_root_path . 'includes/functions_admin.php';
$user->session_begin(); $auth->acl($user->data); $user->setup();
$ids = [];
$res = $db->sql_query('SELECT forum_id FROM ' . FORUMS_TABLE . ' WHERE forum_type = ' . FORUM_POST);
while ($r = $db->sql_fetchrow($res)) $ids[] = (int) $r['forum_id'];
$db->sql_freeresult($res);
$tids = [];
$res = $db->sql_query('SELECT topic_id FROM ' . TOPICS_TABLE);
while ($r = $db->sql_fetchrow($res)) $tids[] = (int) $r['topic_id'];
$db->sql_freeresult($res);
sync('topic', 'topic_id', $tids);
sync('forum', 'forum_id', $ids, true, true);
$cache->purge();
echo "resynced " . count($ids) . " forums\n";
