<?php

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

if ($userdata['user_level'] != ADMIN)
{
	message_die(GENERAL_ERROR, "You are not Authorised to do this"); 
}


// Config Options.

$table_1 = 'nav_menus';
$table_2 = 'nav_blocks';

// Delete this file when complete?
$delete_me = false;


// SQL Stuff
$sql_array = array();

$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_left', '1');";
$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_right', '1');";
$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_version', '1.0.24');";
$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_left_w', '160');";
$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_right_w', '150');";
$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_exclude_forums', '');";
$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_exclude_report', '0');";

$sql_array[] = "DROP TABLE IF EXISTS " . $table_prefix . $table_1 . ";";
$sql_array[] = "DROP TABLE IF EXISTS " . $table_prefix . $table_2 . ";";
$sql_array[] = "CREATE TABLE `" . $table_prefix . $table_1 . "` (
		`menu_id` mediumint(8) unsigned NOT NULL auto_increment,
		`block_id` mediumint(8) unsigned NOT NULL default '0',
		`menu_order` smallint(5) NOT NULL default '0',
		`menu_title` varchar(50) default NULL,
		`menu_bullet` varchar(255) default NULL,
		`menu_is_sub` tinyint(1) NOT NULL default '0',
		`menu_is_image` tinyint(1) NOT NULL default '0',
		`menu_link` varchar(255) default NULL,
		`menu_link_target` varchar(10) default NULL,
		`menu_image` varchar(255) default NULL,
		`menu_image_w` varchar(3) NOT NULL default '0',
		`menu_image_h` varchar(3) NOT NULL default '0',
		`menu_ver` varchar(10) default NULL,
		PRIMARY KEY (`menu_id`),
		KEY `block_id` (`block_id`),
		KEY `menu_order` (`menu_order`)
		) TYPE=MyISAM;";
$sql_array[] = "CREATE TABLE `" . $table_prefix . $table_2 . "` (
		`block_id` mediumint(8) unsigned NOT NULL auto_increment,
		`block_title` varchar(50) default NULL,
		`block_order` smallint(5) NOT NULL default '0',
		`block_pos` tinyint(1) NOT NULL default '0',
		`block_type` tinyint(1) NOT NULL default '0',
		`block_display` tinyint(1) NOT NULL default '1',
		`block_footer_display` tinyint(1) NOT NULL default '0',
		`block_footer` varchar(255) default NULL,
		PRIMARY KEY (`block_id`),
		KEY `block_order` (`block_order`)
		) TYPE=MyISAM;";

//
// Populate Tables with Example Blocks and Menus
//
// Setup Blocks
$sql_array[] = "INSERT INTO " . $table_prefix . $table_2 . " (block_title, block_order, block_pos, block_type, block_display, block_footer_display, block_footer) VALUES ('Block 1', '10', '0', '0', '1', '2', '');";
$sql_array[] = "INSERT INTO " . $table_prefix . $table_2 . " (block_title, block_order, block_pos, block_type, block_display, block_footer_display, block_footer) VALUES ('Block 2', '20', '1', '0', '1', '0', '');";
$sql_array[] = "INSERT INTO " . $table_prefix . $table_2 . " (block_title, block_order, block_pos, block_type, block_display, block_footer_display, block_footer) VALUES ('Block 3', '30', '0', '0', '1', '0', '');";
$sql_array[] = "INSERT INTO " . $table_prefix . $table_2 . " (block_title, block_order, block_pos, block_type, block_display, block_footer_display, block_footer) VALUES ('Search Google', '40', '1', '2', '1', '1', '<b>Google</b> is your friend...');";

// Setup Menus for Block 1
$sql_array[] = "INSERT INTO " . $table_prefix . $table_1 . " (block_id, menu_order, menu_title, menu_bullet, menu_is_sub, menu_is_image, menu_link, menu_link_target, menu_image, menu_image_w, menu_image_h, menu_ver)
		VALUES ('1', '10', 'subtitle', '', '1', '0', '', '', '', '', '', '');";
$sql_array[] = "INSERT INTO " . $table_prefix . $table_1 . " (block_id, menu_order, menu_title, menu_bullet, menu_is_sub, menu_is_image, menu_link, menu_link_target, menu_image, menu_image_w, menu_image_h, menu_ver)
		VALUES ('1', '20', 'Link 1', 'images/bullets/arrow_right_blue.gif', '0', '0', './index.php', '_self', '', '', '', '');";
$sql_array[] = "INSERT INTO " . $table_prefix . $table_1 . " (block_id, menu_order, menu_title, menu_bullet, menu_is_sub, menu_is_image, menu_link, menu_link_target, menu_image, menu_image_w, menu_image_h, menu_ver)
		VALUES ('1', '30', 'Link 2', 'images/bullets/arrow_right_red.gif', '0', '0', './groupcp.php', '_self', '', '', '', '');";
$sql_array[] = "INSERT INTO " . $table_prefix . $table_1 . " (block_id, menu_order, menu_title, menu_bullet, menu_is_sub, menu_is_image, menu_link, menu_link_target, menu_image, menu_image_w, menu_image_h, menu_ver)
		VALUES ('1', '40', 'subtitle', '', '1', '0', '', '', '', '', '', '');";
$sql_array[] = "INSERT INTO " . $table_prefix . $table_1 . " (block_id, menu_order, menu_title, menu_bullet, menu_is_sub, menu_is_image, menu_link, menu_link_target, menu_image, menu_image_w, menu_image_h, menu_ver)
		VALUES ('1', '50', 'Link 1', 'images/bullets/arrow_right_purple.gif', '0', '0', './index.php', '_self', '', '', '', '');";

// Setup Menus for Block 2
$sql_array[] = "INSERT INTO " . $table_prefix . $table_1 . " (block_id, menu_order, menu_title, menu_bullet, menu_is_sub, menu_is_image, menu_link, menu_link_target, menu_image, menu_image_w, menu_image_h, menu_ver)
		VALUES ('2', '10', 'subtitle', '', '1', '0', '', '', '', '', '', '');";
$sql_array[] = "INSERT INTO " . $table_prefix . $table_1 . " (block_id, menu_order, menu_title, menu_bullet, menu_is_sub, menu_is_image, menu_link, menu_link_target, menu_image, menu_image_w, menu_image_h, menu_ver)
		VALUES ('2', '20', 'Link 1', 'images/bullets/arrow_right_blue.gif', '0', '0', './index.php', '_self', '', '', '', '');";
$sql_array[] = "INSERT INTO " . $table_prefix . $table_1 . " (block_id, menu_order, menu_title, menu_bullet, menu_is_sub, menu_is_image, menu_link, menu_link_target, menu_image, menu_image_w, menu_image_h, menu_ver)
		VALUES ('2', '30', 'Link 2', 'images/bullets/arrow_right_red.gif', '0', '0', './groupcp.php', '_self', '', '', '', '');";
$sql_array[] = "INSERT INTO " . $table_prefix . $table_1 . " (block_id, menu_order, menu_title, menu_bullet, menu_is_sub, menu_is_image, menu_link, menu_link_target, menu_image, menu_image_w, menu_image_h, menu_ver)
		VALUES ('2', '40', 'subtitle', '', '1', '0', '', '', '', '', '', '');";
$sql_array[] = "INSERT INTO " . $table_prefix . $table_1 . " (block_id, menu_order, menu_title, menu_bullet, menu_is_sub, menu_is_image, menu_link, menu_link_target, menu_image, menu_image_w, menu_image_h, menu_ver)
		VALUES ('2', '50', 'Link 1', 'images/bullets/arrow_right_orange.gif', '0', '0', './index.php', '_self', '', '', '', '');";

// Setup Menus for Block 3
$sql_array[] = "INSERT INTO " . $table_prefix . $table_1 . " (block_id, menu_order, menu_title, menu_bullet, menu_is_sub, menu_is_image, menu_link, menu_link_target, menu_image, menu_image_w, menu_image_h, menu_ver)
		VALUES ('3', '10', 'phpBB', '', '0', '1', 'http://www.phpbb.com', '_self', 'images/links/phpBB_88a.gif', '88', '31', '');";
$sql_array[] = "INSERT INTO " . $table_prefix . $table_1 . " (block_id, menu_order, menu_title, menu_bullet, menu_is_sub, menu_is_image, menu_link, menu_link_target, menu_image, menu_image_w, menu_image_h, menu_ver)
		VALUES ('3', '20', 'phpBBStyles', '', '0', '1', 'http://www.phpbbstyles.com', '_self', 'images/links/phpbbstyles.gif', '88', '31', '');";
$sql_array[] = "INSERT INTO " . $table_prefix . $table_1 . " (block_id, menu_order, menu_title, menu_bullet, menu_is_sub, menu_is_image, menu_link, menu_link_target, menu_image, menu_image_w, menu_image_h, menu_ver)
		VALUES ('3', '30', 'phpBB Hacks', '', '0', '1', 'http://www.phpbbhacks.com', '_blank', 'images/links/phpbbhacks.gif', '88', '31', '');";

$error = false;
$error_one = false;

for ( $i = 0; $i < count($sql_array); $i++ )
{
	if ( trim($sql_array[$i]) != '' )
        {
        	if ( !$db->sql_query($sql_array[$i]) )
                {
                        $error = true;
                        $error_sql[] = $sql_array[$i];
                        $error_msg[] = $db->sql_error();
                }
                else
                {
                	$error_one = true;
                }
        }
}

if ( $error && !$error_one )
{
	$msg = 'The queries failed.';
}
else if ( $error )
{
	$msg = 'Some queries failed.';
}
else
{
	$delete = false;
	if ( $delete_me )
	{
		$delete = @unlink(__FILE__);
	}
	$msg = 'The Mod was installed successful. ' . ( $delete ? 'This file was deleted successful.' : 'Don\'t forget to delete this file.' );
}

if ( $error )
{
        $msg .= '<br/><br/>Error list:';
        for ( $i = 0; $i < count($error_sql); $i++ )
        {
        	$msg .= '<br />SQL Error: ' . $error_msg[$i]['code'] . ' ' . $error_msg[$i]['message'] . '<br />SQL Query: ' . $error_sql[$i] . '<br />';
        }
}
message_die( ( $error ) ? GENERAL_ERROR : GENERAL_MESSAGE, $msg);
?>