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
// mod not installed
if( !isset($board_config['xs_nav_version']) )
{
	$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_left', '1');";
	$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_right', '1');";
	$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_version', '1.0.24');";
	$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_left_w', '160');";
	$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_right_w', '160');";
	$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_exclude_forums', '');";
	$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_exclude_report', '0');";
}
elseif( !isset($board_config['xs_nav_left_w']) )
{
	// mod installed
	$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_left_w', '160');";
	$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_right_w', '160');";
	$sql_array[] = "UPDATE " . CONFIG_TABLE . " SET config_value = '1.0.24' WHERE config_name = 'xs_nav_version';";
	$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_exclude_forums', '');";
	$sql_array[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_nav_exclude_report', '0');";
}
else
{
	$sql_array[] = "UPDATE " . CONFIG_TABLE . " SET config_value = '1.0.24' WHERE config_name = 'xs_nav_version';";
}


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