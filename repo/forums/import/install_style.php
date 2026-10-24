<?php
/**
 * Register a style directory already copied into phpBB3/styles/ and optionally make it the board default.
 *   php install_style.php <dir-name> "<Display name>" <parent-dir-name|-> [--default]
 * Mirrors what ACP > Styles > Install writes: one row in the styles table (3.1+ has no separate template/theme rows).
 */
define('IN_PHPBB', true);
$phpbb_root_path = '/var/www/html/';
$phpEx = 'php';
chdir($phpbb_root_path);
require $phpbb_root_path . 'common.php';
$user->session_begin(); $auth->acl($user->data); $user->setup();

[, $dir, $name, $parent] = $argv;
$cfg = parse_ini_file($phpbb_root_path . "styles/$dir/style.cfg");
if (!$cfg) { fwrite(STDERR, "no style.cfg in styles/$dir\n"); exit(1); }
$pid = 0; $tree = '';
if ($parent !== '-') {
    $res = $db->sql_query("SELECT style_id, style_path, style_parent_tree FROM " . STYLES_TABLE . " WHERE style_path = '" . $db->sql_escape($parent) . "'");
    $p = $db->sql_fetchrow($res); $db->sql_freeresult($res);
    if (!$p) { fwrite(STDERR, "parent style $parent not installed\n"); exit(1); }
    $pid = (int) $p['style_id']; $tree = $p['style_path'] . ($p['style_parent_tree'] ? '/' . $p['style_parent_tree'] : '');
}
$res = $db->sql_query("SELECT style_id FROM " . STYLES_TABLE . " WHERE style_path = '" . $db->sql_escape($dir) . "'");
$id = (int) $db->sql_fetchfield('style_id'); $db->sql_freeresult($res);
$row = ['style_name' => $name, 'style_copyright' => $cfg['copyright'], 'style_active' => 1, 'style_path' => $dir,
        'bbcode_bitfield' => 'kNg=', 'style_parent_id' => $pid, 'style_parent_tree' => $tree];
if ($id) { $db->sql_query('UPDATE ' . STYLES_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $row) . " WHERE style_id = $id"); }
else { $db->sql_query('INSERT INTO ' . STYLES_TABLE . ' ' . $db->sql_build_array('INSERT', $row)); $id = (int) $db->sql_nextid(); }
if (in_array('--default', $argv, true)) {
    $config->set('default_style', $id);
    $db->sql_query('UPDATE ' . USERS_TABLE . ' SET user_style = ' . $id);
}
$cache->purge();
echo "style $name = id $id" . (in_array('--default', $argv, true) ? " (board default)" : "") . "\n";
