<?php
/*
	filename: admin_nav_menus.php
	 version: 1.0.8
	
	  author: UseLess
	    date: January 2005
	
	   notes: This is a based (heavily) on the forum_admin as supplied with
	   	  phpBB

*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Extreme_Styles']['Navigation_Menus'] = $filename;
	return;
}

//
// Load default header
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

// check if XSmod is installed
if(empty($template->xs_version) || $template->xs_version < 6)
{
	message_die(GENERAL_ERROR, 'Either eXtreme Styles mod is not installed, or you forgot to upload includes/template.php');
}

// load language file
$nav_lang_file = $phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_xs_navmenus.'.$phpEx;
if( !@file_exists($nav_lang_file) )
{	// load english version if there is no translation to current language
	$nav_lang_file = $phpbb_root_path . 'language/lang_english/lang_xs_navmenus.'.$phpEx;
}

include($nav_lang_file);

define('XS_TPL_PATH', '../../xs_mod/tpl_nav/');

$confirm = ( $HTTP_POST_VARS['confirm'] ) ? TRUE : 0;

//
// Mode setting
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = "";
}

if ( isset($HTTP_POST_VARS['cancel']) )
{
	$mode = '';
}

$html_entities_match = array('#&(?!(\#[0-9]+;))#', '#<#', '#>#');
$html_entities_replace = array('&amp;', '&lt;', '&gt;');

$unhtml_specialchars_match = array('#&gt;#', '#&lt;#', '#&quot;#', '#&amp;#');
$unhtml_specialchars_replace = array('>', '<', '"', '&');

$allowed_html_tags = 'b,i,u,pre';
// ------------------
// Begin function block
//
function nav_get_info($mode, $id)
{
	global $db;

	switch($mode)
	{
		case 'block':
			$table = NAV_BLOCKS_TABLE;
			$idfield = 'block_id';
			$namefield = 'block_title';
			break;

		case 'menu':
			$table = NAV_MENUS_TABLE;
			$idfield = 'menu_id';
			$namefield = 'menu_title';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}
	$sql = "SELECT count(*) as total
		FROM $table";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get Block/Menu information", "", __LINE__, __FILE__, $sql);
	}
	$count = $db->sql_fetchrow($result);
	$count = $count['total'];

	$sql = "SELECT *
		FROM $table
		WHERE $idfield = $id"; 

	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get Block/Menu information", "", __LINE__, __FILE__, $sql);
	}

	if( $db->sql_numrows($result) != 1 )
	{
		message_die(GENERAL_ERROR, "Block/Menu doesn't exist or multiple blocks/menus with ID $id", "", __LINE__, __FILE__);
	}

	$return = $db->sql_fetchrow($result);
	$return['number'] = $count;
	return $return;
}

function nav_get_list($mode, $id, $select)
{
	global $db;

	switch($mode)
	{
		case 'block':
			$table = NAV_BLOCKS_TABLE;
			$idfield = 'block_id';
			$namefield = 'block_title';
			break;

		case 'menu':
			$table = NAV_MENUS_TABLE;
			$idfield = 'menu_id';
			$namefield = 'menu_title';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}

	$sql = "SELECT *
		FROM $table";
	if( $select == 1 && $mode == 'menu')
	{
		$sql .= " WHERE menu_is_sub <> 1";
	}
	elseif( $select == 0 )
	{
		$sql .= " WHERE $idfield <> $id";
	}

	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get list of Blocks/Menus", "", __LINE__, __FILE__, $sql);
	}

	$block_list = "";

	while( $row = $db->sql_fetchrow($result) )
	{
		$s = "";
		if ($row[$idfield] == $id)
		{
			$s = " selected=\"selected\"";
		}
		$block_list .= "<option value=\"$row[$idfield]\"$s>" . $row[$namefield] . "</option>\n";
	}

	return($block_list);
}

function nav_renumber_order($mode, $block = 0)
{
	global $db;

	switch($mode)
	{
		case 'block':
			$table = NAV_BLOCKS_TABLE;
			$idfield = 'block_id';
			$orderfield = 'block_order';
			$block = 0;
			break;

		case 'menu':
			$table = NAV_MENUS_TABLE;
			$idfield = 'menu_id';
			$orderfield = 'menu_order';
			$blockfield = 'block_id';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}

	$sql = "SELECT * FROM $table";
	if( $block != 0)
	{
		$sql .= " WHERE $blockfield = $block";
	}
	$sql .= " ORDER BY $orderfield ASC";


	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get list of Blocks", "", __LINE__, __FILE__, $sql);
	}

	$i = 10;
	$inc = 10;

	while( $row = $db->sql_fetchrow($result) )
	{
		$sql = "UPDATE $table
			SET $orderfield = $i
			WHERE $idfield = " . $row[$idfield];
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't update order fields", "", __LINE__, __FILE__, $sql);
		}
		$i += 10;
	}

}

function nav_prepare_message($message)
{
	global $allowed_html_tags, $html_entities_match, $html_entities_replace;

	//
	// Clean up the message
	//
	$message = trim($message);

	$message = preg_replace($html_entities_match, $html_entities_replace, $message);

	return $message;
}

function nav_unprepare_message($message)
{
	global $unhtml_specialchars_match, $unhtml_specialchars_replace;

	return preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, $message);
}

function bullet_picker($bullet_dir)
{
	$files = array();
	$names = array();
	
	$dir = @opendir($bullet_dir);  
	
	while(($file = @readdir($dir)) !== false)  
	{  
		if($file !== '.' && $file !== '..' && !is_dir($file))  
		{  
			$files[] = $file;
			$tmp = substr($file, 0 , strrpos($file, "."));
			$names[] = ucwords(str_replace('_', ' ', $tmp));
		}  
	}  
	@closedir($dir);
	
	sort($files);
	sort($names);
	
	return array($files, $names);
}

//
// End function block
// ------------------

//
// Begin program proper
//
if( isset($HTTP_POST_VARS['addmenu']) || isset($HTTP_POST_VARS['addblock']) )
{
	$mode = ( isset($HTTP_POST_VARS['addmenu']) ) ? "addmenu" : "addblock";

	if( $mode == "addmenu" )
	{
		list($block_id) = each($HTTP_POST_VARS['addmenu']);
		// 
		// stripslashes needs to be run on this because slashes are added when the menu name is posted
		//
		$menu_title = stripslashes($HTTP_POST_VARS['menu_title'][$block_id]);
	}

	if( $mode == "addblock" )
	{
		// 
		// stripslashes needs to be run on this because slashes are added when the block name is posted
		//
		$block_title = stripslashes($HTTP_POST_VARS['block_title']);
	}
}

if( !empty($mode) ) 
{
	switch($mode)
	{
		case 'addmenu':
		case 'editmenu':
			//
			// Show form to create/modify a menu
			//
			if ($mode == 'editmenu')
			{
				// $newmode determines if we are going to INSERT or UPDATE after posting?

				$l_title = $lang['nm_edit_menu'];
				$newmode = 'modmenu';
				$buttonvalue = $lang['Update'];

				$menu_id = intval($HTTP_GET_VARS[POST_MENU_URL]);

				$row = nav_get_info('menu', $menu_id);

				$block_id = $row['block_id'];
				$menu_title = $row['menu_title'];
				
				$menu_link = nav_unprepare_message($row['menu_link']);
				$menu_link_target = $row['menu_link_target'];
				$menu_image = nav_unprepare_message($row['menu_image']);
				$menu_image_w = $row['menu_image_w'];
				$menu_image_h = $row['menu_image_h'];
				$menu_ver = $row['menu_ver'];
				$menu_bullet = $row['menu_bullet'];
				
				$menu_is_sub_yes = ( $row['menu_is_sub'] ) ? 'checked="checked"' : '';
				$menu_is_sub_no = ( !$row['menu_is_sub'] ) ? 'checked="checked"' : '';
				$menu_is_image_yes = ($row['menu_is_image']) ? 'checked="checked"' : '';
				$menu_is_image_no = (!$row['menu_is_image']) ? 'checked="checked"' : '';
				
			}
			else
			{
				$l_title = $lang['nm_create_menu'];
				$newmode = 'createmenu';
				$buttonvalue = $lang['nm_create_menu'];

				$menu_bullet = '';
				$menu_link = '';
				$menu_link_target = '';
				$menu_image = '';
				$menu_image_w = '0';
				$menu_image_h = '0';
				$menu_ver = '';
				$menu_is_sub_yes = '';
				$menu_is_sub_no = 'checked="checked"';
				$menu_is_image_yes = '';
				$menu_is_image_no = 'checked="checked"';

			}

			$blocklist = nav_get_list('block', $block_id, TRUE);

			$template->set_filenames(array(
				"body" =>  XS_TPL_PATH . "nav_menu_edit_body.tpl")
			);

			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode .'" /><input type="hidden" name="' . POST_MENU_URL . '" value="' . $menu_id . '" />';

			$target_select = '<select name="menu_link_target" size="1">';
			
			$available_targets = array(
				'_blank'=> array(
					'LABEL'	=> '_blank',
					'COMMENTS' => 'A new empty Browser'
				), 

				'_self'=> array(
					'LABEL'	=> '_self',
					'COMMENTS' => 'Open link in this window'
				), 
				'_new'=> array(
					'LABEL'	=> '_new',
					'COMMENTS' => 'A new Browser'
				)
			);

			$target_select = '<select name="menu_link_target" size="1">';
			while (list($target_name, $details) = @each($available_targets))
			{
				$selected = ($target_name == $menu_link_target) ? 'selected="selected"' : '';
				$target_select .= '<option value="' . $target_name . '"' . $selected . '>' . $details['LABEL'] . '</option>';
			}
			$target_select .= '</select>';

			// Get list of Bullet Images
			$bullet_path = 'images/bullets/';
			
			list($bullet_files, $bullet_names) = bullet_picker('../' . $bullet_path);
			$bullet_picker = '<select name="dummy" size="1" onchange="if (this.options[this.selectedIndex].value != -1) {this.form.menu_bullet.value=this.options[this.selectedIndex].value;};">';
			$bullet_picker .= '<option value="-1">Bullet List</option>';
			for($i = 0; $i < count($bullet_files); $i++)
			{
				$selected = (strpos($row['menu_bullet'], $bullet_files[$i])) ? 'selected="selected"' : '';
				$bullet_picker .= '<option value="' . $bullet_path . $bullet_files[$i] . '"' . $selected . '>' . $bullet_names[$i] . '</options>';
			}
			$bullet_picker .= '</select>';

			$template->assign_vars(array(
				'S_FORUM_ACTION' => append_sid("admin_nav_menus.$phpEx"),
				'S_HIDDEN_FIELDS' => $s_hidden_fields,
				'S_SUBMIT_VALUE' => $buttonvalue, 
				'S_BLOCK_LIST' => $blocklist,

				'L_PAGE_TITLE' => ( $newmode == 'modmenu' ) ? $lang['nm_edit_page_title'] : $lang['nm_add_page_title'],
				'L_PAGE_TITLE_EXPLAIN' => ( $newmode == 'modmenu' ) ? $lang['nm_edit_page_title_explain'] : $lang['nm_add_page_title_explain'], 
				'L_MENU_SETTINGS' => ( $newmode == 'modmenu' ) ? $lang['nm_edit_header'] : $lang['nm_add_header'],
				
				'L_MENU_TITLE' => $lang['nm_menu_title'], 
				'L_BLOCK' => $lang['nm_menu_block'], 
				'L_MENU_SUBTITLE' => $lang['nm_menu_is_sub'],
				'L_MENU_IMAGE_TITLE' => $lang['nm_menu_image_title'],
				'L_MENU_IMAGE_TITLE_EXPLAIN' => $lang['nm_menu_image_title_explain'],
				'L_MENU_IMAGE' => $lang['nm_menu_is_image'],
				'L_MENU_IMAGE_SIZE' => $lang['nm_menu_image_size'],
				'L_MENU_VER' => $lang['nm_menu_ver'],
				'L_MENU_BULLET' => $lang['nm_menu_bullet'],
				'L_MENU_BULLET_EXPLAIN' => $lang['nm_menu_bullet_explain'],
				'L_MENU_BULLET_QUICK' => $lang['nm_menu_bullet_quick'],
				'L_MENU_LINK' => $lang['nm_menu_link'],
				'L_MENU_LINK_TARGET' => $lang['nm_menu_link_target'],
								
				'MENU_TITLE' => $menu_title,
				'MENU_BULLET' => $menu_bullet,
				'MENU_VER' => $menu_ver,
				'MENU_LINK' => $menu_link,
				'MENU_LINK_TARGET' => $target_select,
				'MENU_IMAGE' => $menu_image,
				'MENU_IMAGE_W' => $menu_image_w,
				'MENU_IMAGE_H' => $menu_image_h,
				'MENU_BULLET_QUICK' => $bullet_picker,
				
				'MENU_IS_SUBTITLE_YES' => $menu_is_sub_yes,
				'MENU_IS_SUBTITLE_NO' => $menu_is_sub_no,
				'MENU_IS_IMAGE_YES' => $menu_is_image_yes,
				'MENU_IS_IMAGE_NO' => $menu_is_image_no,
			));
			
			$template->pparse("body");
			break;

		case 'createmenu':
			//
			// Create a menu in the DB
			//
			if( trim($HTTP_POST_VARS['menu_title']) == "" )
			{
				message_die(GENERAL_ERROR, "Can't create a menu without a name");
			}

			$sql = "SELECT MAX(menu_order) AS max_order
				FROM " . NAV_MENUS_TABLE . "
				WHERE block_id = " . intval($HTTP_POST_VARS[POST_BLOCK_URL]);
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get order number from menus table", "", __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$max_order = $row['max_order'];
			$next_order = $max_order + 10;
			
			$sql = "SELECT MAX(menu_id) AS max_id
				FROM " . NAV_MENUS_TABLE;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get order number from menus table", "", __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$max_id = $row['max_id'];
			$next_id = $max_id + 1;

			$menu_link = nav_prepare_message($HTTP_POST_VARS['menu_link']);
			$menu_image = nav_prepare_message($HTTP_POST_VARS['menu_image']);
			
			// There is no problem having duplicate menu names so we won't check for it.
			$sql = "INSERT INTO " . NAV_MENUS_TABLE . " (menu_id, block_id, menu_order, menu_title, menu_bullet, menu_is_sub, menu_is_image, menu_link, menu_link_target, menu_image, menu_image_w, menu_image_h, menu_ver" . ")
				VALUES ('" . $next_id . "', " . intval($HTTP_POST_VARS[POST_BLOCK_URL]) . ", $next_order, '" . str_replace("\'", "''", $HTTP_POST_VARS['menu_title']) . "', '" . str_replace("\'", "''", $HTTP_POST_VARS['menu_bullet']) . "', " . intval($HTTP_POST_VARS['menu_is_sub']) . ", " . intval($HTTP_POST_VARS['menu_is_image']) . ", '" . str_replace("\'", "''", $menu_link) . "', '" . str_replace("\'", "''", $HTTP_POST_VARS['menu_link_target']) . "', '" . str_replace("\'", "''", $menu_image) . "', '" . str_replace("\'", "''", $HTTP_POST_VARS['menu_image_w']) . "', '" . str_replace("\'", "''", $HTTP_POST_VARS['menu_image_h']) . "', '" . str_replace("\'", "''", $HTTP_POST_VARS['menu_ver']) . "')";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't insert row in menu table", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['nm_menus_updated'] . "<br /><br />" . sprintf($lang['nm_click_return_navmenus'], "<a href=\"" . append_sid("admin_nav_menus.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;

		case 'modmenu':
			// Modify a menu in the DB
			$menu_link = nav_prepare_message($HTTP_POST_VARS['menu_link']);
			$menu_image = nav_prepare_message($HTTP_POST_VARS['menu_image']);
			
			$sql = "UPDATE " . NAV_MENUS_TABLE . "
				SET menu_title = '" . str_replace("\'", "''", $HTTP_POST_VARS['menu_title']) . "', block_id = " . intval($HTTP_POST_VARS[POST_BLOCK_URL]) . ", menu_bullet = '" . str_replace("\'", "''", $HTTP_POST_VARS['menu_bullet']) . "', menu_is_sub = '" . intval($HTTP_POST_VARS['menu_is_sub']) . "', menu_is_image = '" . intval($HTTP_POST_VARS['menu_is_image']) . "', menu_link = '" . str_replace("\'", "''", $menu_link) . "', menu_link_target = '" . str_replace("\'", "''", $HTTP_POST_VARS['menu_link_target']) . "', menu_image = '" . str_replace("\'", "''", $menu_image) . "', menu_image_w = '" . str_replace("\'", "''", $HTTP_POST_VARS['menu_image_w']) . "', menu_image_h = '" . str_replace("\'", "''", $HTTP_POST_VARS['menu_image_h']) . "', menu_ver = '" . str_replace("\'", "''", $HTTP_POST_VARS['menu_ver']) . "'
				WHERE menu_id = " . intval($HTTP_POST_VARS[POST_MENU_URL]);
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't update menu information", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['nm_menus_updated'] . "<br /><br />" . sprintf($lang['nm_click_return_navmenus'], "<a href=\"" . append_sid("admin_nav_menus.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;
			
		case 'addblock':
		case 'editblock':
			//
			// Show form to create/modify a block
			//
			if ($mode == 'editblock')
			{
				// $newmode determines if we are going to INSERT or UPDATE after posting?

				$l_title = $lang['nm_edit_menu'];
				$newmode = 'modblock';
				$buttonvalue = $lang['Update'];

				$block_id = intval($HTTP_GET_VARS[POST_BLOCK_URL]);

				$row = nav_get_info('block', $block_id);
				
				$block_title = $row['block_title'];
				$block_left = ( !$row['block_pos'] ) ? 'checked="checked"' : '';
				$block_right = ( $row['block_pos'] ) ? 'checked="checked"' : '';
				$block_type = $row['block_type'];
				$block_display_yes = ( $row['block_display'] ) ? 'checked="checked"' : '';
				$block_display_no = ( !$row['block_display'] ) ? 'checked="checked"' : '';
				$block_footer = nav_unprepare_message($row['block_footer']);
				$block_footer_display = $row['block_footer_display'];
				
				switch($block_footer_display)
				{
					case 0:
						$block_footer_display_yes = '';
						$block_footer_display_no = 'checked="checked"';
						$block_footer_display_ver = '';
						break;
					
					case 1:
						$block_footer_display_yes = 'checked="checked"';
						$block_footer_display_no = '';
						$block_footer_display_ver = '';
						break;

					case 2:
						$block_footer_display_yes = '';
						$block_footer_display_no = '';
						$block_footer_display_ver = 'checked="checked"';
						break;
				}
			}
			else
			{
				$l_title = $lang['nm_create_block'];
				$newmode = 'createblock';
				$buttonvalue = $lang['nm_create_block'];

				$block_left = 'checked="checked"';
				$block_right = '';
				$block_type = 0;
				$block_display_yes = 'checked="checked"';
				$block_display_no = '';
				$block_footer_display_yes = '';
				$block_footer_display_no = 'checked="checked"';
				$block_footer_display_ver = '';
				$block_footer = '';
			}

			$available_blocks = array(
				'0'=> array(
					'LABEL'	=> 'Menu',
					'COMMENTS' => 'Normal Block Menu'
				), 

				'1'=> array(
					'LABEL'	=> 'Forum Search',
					'COMMENTS' => 'Block with a quick search'
				), 
				'2'=> array(
					'LABEL'	=> 'Google Search',
					'COMMENTS' => 'Block with a google search'
				), 
				'3'=> array(
					'LABEL'	=> 'Who\'s Online',
					'COMMENTS' => 'Block with a Who\'s Online list'
				), 
				'4'=> array(
					'LABEL'	=> 'Forum/Google Search',
					'COMMENTS' => 'Combined Forum/Google Search'
				),
				'5'=> array(
					'LABEL'	=> 'Last # Topics',
					'COMMENTS' => 'Last # Topics'
				)
			);

			$block_type_select = '<select name="block_type" size="1">';
			while (list($block_name, $details) = @each($available_blocks))
			{
				$selected = ($block_name == $block_type) ? 'selected="selected"' : '';
				$block_type_select .= '<option value="' . $block_name . '"' . $selected . '>' . $details['LABEL'] . '</option>';
			}
			$block_type_select .= '</select>';
			
			$template->set_filenames(array(
				"body" =>  XS_TPL_PATH . "nav_block_edit_body.tpl")
			);

			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="' . POST_BLOCK_URL . '" value="' . $block_id . '" />';

			$template->assign_vars(array(
				'BLOCK_TITLE' => $block_title,

				'L_PAGE_TITLE' => ( $newmode == 'modblock' ) ? $lang['nm_editb_page_title'] : $lang['nm_addb_page_title'],
				'L_PAGE_TITLE_EXPLAIN' => ( $newmode == 'modblock' ) ? $lang['nm_editb_page_title_explain'] : $lang['nm_addb_page_title_explain'], 
				
				'L_BLOCK' => $lang['nm_menu_block'],				
				'L_BLOCK_POS' => $lang['nm_block_pos'],
				'L_BLOCK_TYPE' => $lang['nm_block_type'],
				'L_BLOCK_DISPLAY' => $lang['nm_block_display'],
				'L_BLOCK_DISPLAY_SETTINGS' => $lang['nm_block_display_settings'],
				'L_EDIT_BLOCK' => $lang['nm_block_edit'],
				'L_BLOCK_FOOTER_SETTINGS' => $lang['nm_block_footer_settings'],
				'L_BLOCK_FOOTER' => $lang['nm_block_footer'],
				'L_BLOCK_FOOTER_DISPLAY' => $lang['nm_block_footer_display'],
				'L_VERSION' => $lang['nm_block_show_version'],
				
				'BLOCK_DISPLAY_YES' => $block_display_yes,
				'BLOCK_DISPLAY_NO' => $block_display_no,
				'BLOCK_FOOTER_DISPLAY_YES' => $block_footer_display_yes,
				'BLOCK_FOOTER_DISPLAY_NO' => $block_footer_display_no,
				'BLOCK_FOOTER_DISPLAY_VER' => $block_footer_display_ver,
				'BLOCK_FOOTER' => $block_footer,
				'BLOCK_LEFT' => $block_left,
				'BLOCK_RIGHT' => $block_right,
				'BLOCK_TYPE_SELECT' => $block_type_select,
				
				'S_HIDDEN_FIELDS' => $s_hidden_fields, 
				'S_SUBMIT_VALUE' => $buttonvalue, 
				'S_FORUM_ACTION' => append_sid("admin_nav_menus.$phpEx"))
			);

			$template->pparse("body");
			break;

		case 'createblock':
			// Create a block in the DB
			
			$block_footer = nav_prepare_message($HTTP_POST_VARS['block_footer']);
			
			if( trim($HTTP_POST_VARS['block_title']) == '')
			{
				message_die(GENERAL_ERROR, "Can't create a block without a name");
			}

			$sql = "SELECT MAX(block_order) AS max_order
				FROM " . NAV_BLOCKS_TABLE;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get order number from blocks table", "", __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$max_order = $row['max_order'];
			$next_order = $max_order + 10;

			
			//
			// There is no problem having duplicate blocks names so we won't check for it.
			//
			$sql = "INSERT INTO " . NAV_BLOCKS_TABLE . " (block_title, block_order, block_pos, block_type, block_display, block_footer_display, block_footer)
				VALUES ('" . str_replace("\'", "''", $HTTP_POST_VARS['block_title']) . "', $next_order, '" . intval($HTTP_POST_VARS['block_pos']) . "', '" . intval($HTTP_POST_VARS['block_type']) . "', '" . intval($HTTP_POST_VARS['block_display']) . "', '" . intval($HTTP_POST_VARS['block_footer_display']) . "','" . str_replace("\'", "''", $block_footer) . "' )";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't insert row in blocks table", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['nm_menus_updated'] . "<br /><br />" . sprintf($lang['nm_click_return_navmenus'], "<a href=\"" . append_sid("admin_nav_menus.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;

		case 'modblock':
			// Modify a block in the DB
			$block_footer = nav_prepare_message($HTTP_POST_VARS['block_footer']);
			
			$sql = "UPDATE " . NAV_BLOCKS_TABLE . "
				SET block_title = '" . str_replace("\'", "''", $HTTP_POST_VARS['block_title']) . "', block_pos = '" . intval($HTTP_POST_VARS['block_pos']) . "', block_type = '" . intval($HTTP_POST_VARS['block_type']) . "', block_display = '" . intval($HTTP_POST_VARS['block_display']) . "', block_footer_display = '" . intval($HTTP_POST_VARS['block_footer_display']) . "', block_footer = '" . str_replace("\'", "''", $block_footer) . "'
				WHERE block_id = " . intval($HTTP_POST_VARS[POST_BLOCK_URL]);
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't update block information", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['nm_menus_updated'] . "<br /><br />" . sprintf($lang['nm_click_return_navmenus'], "<a href=\"" . append_sid("admin_nav_menus.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;
			
		case 'deletemenu':
			// Show form to delete a menu
			$menu_id = intval($HTTP_GET_VARS[POST_MENU_URL]);

			$select_to = '<select name="to_id">';
			$select_to .= "<option value=\"-1\"$s>" . $lang['Delete_all_posts'] . "</option>\n";
			$select_to .= nav_get_list('menu', $menu_id, 1);
			$select_to .= '</select>';

			$buttonvalue = $lang['Move_and_Delete'];

			//$newmode = 'movedelmenu';
			$newmode = 'deletemenu';
			
			$menu_info = nav_get_info('menu', $menu_id);
			$name = $menu_info['menu_title'];

			if( $confirm )
			{
				$sql = "DELETE FROM " . NAV_MENUS_TABLE . "
					WHERE menu_id = $menu_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't delete menu", "", __LINE__, __FILE__, $sql);
				}
			
				$message = $lang['nm_menus_updated'] . "<br /><br />" . sprintf($lang['nm_click_return_navmenus'], "<a href=\"" . append_sid("admin_nav_menus.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

				message_die(GENERAL_MESSAGE, $message);
			}
//			elseif($menu_info['menu_is_sub'] == 1)
//			{
//				$message = $lang['nm_menu_delete_subtitle'] . "<br /><br />" . sprintf($lang['nm_click_return_navmenus'], "<a href=\"" . append_sid("admin_nav_menus.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
//				
//				message_die(GENERAL_MESSAGE, $message);
//			}
			else
			{
				//
				// Set template files
				//
				$template->set_filenames(array(
					'confirm' =>  XS_TPL_PATH . 'nav_menu_confirm_body.tpl')
				);

				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="menu_id" value="' . $menu_id . '" />';
			
				$template->assign_vars(array(
					'MESSAGE_TITLE' => $lang['Confirm'],
					'MESSAGE_TEXT' => sprintf($lang['nm_confirm_delete_menu'], $name),

					'L_YES' => $lang['Yes'],
					'L_NO' => $lang['No'],

					'S_CONFIRM_ACTION' => append_sid("admin_nav_menus.$phpEx?" . POST_MENU_URL . "=$menu_id"),
					'S_HIDDEN_FIELDS' => $s_hidden_fields
				));

				$template->pparse('confirm');
			}
			break;

		case 'deleteblock':
			//
			// Show form to delete a block
			//
			$block_id = intval($HTTP_GET_VARS[POST_BLOCK_URL]);

			$buttonvalue = $lang['Move_and_Delete'];
			$newmode = 'movedelblock';
			$blockinfo = nav_get_info('block', $block_id);
			$name = $blockinfo['block_title'];

			if ($blockinfo['number'] == 1)
			{
				$sql = "SELECT count(*) as total
					FROM ". NAV_MENUS_TABLE;
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't get Menu count", "", __LINE__, __FILE__, $sql);
				}
				$count = $db->sql_fetchrow($result);
				$count = $count['total'];

				if ($count > 0)
				{
					message_die(GENERAL_ERROR, $lang['nm_must_delete_menus']);
				}
				else
				{
					$select_to = $lang['Nowhere_to_move'];
				}
			}
			else
			{
				$select_to = '<select name="to_id">';
				$select_to .= nav_get_list('block', $block_id, 0);
				$select_to .= '</select>';
			}

			$template->set_filenames(array(
				"body" =>  XS_TPL_PATH . "nav_menu_delete_body.tpl")
			);

			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="from_id" value="' . $block_id . '" />';

			$template->assign_vars(array(
				'NAME' => $name, 

				'L_BLOCK_DELETE' => $lang['nm_menu_block_delete'], 
				'L_BLOCK_DELETE_EXPLAIN' => $lang['nm_menu_block_delete_explain'], 
				'L_MOVE_CONTENTS' => $lang['Move_contents'], 
				'L_BLOCK_NAME' => $lang['nm_menu_block'], 
				
				'S_HIDDEN_FIELDS' => $s_hidden_fields,
				'S_FORUM_ACTION' => append_sid("admin_nav_menus.$phpEx"), 
				'S_SELECT_TO' => $select_to,
				'S_SUBMIT_VALUE' => $buttonvalue)
			);

			$template->pparse("body");
			break;

		case 'movedelblock':
			//
			// Move or delete a block in the DB
			//
			$from_id = intval($HTTP_POST_VARS['from_id']);
			$to_id = intval($HTTP_POST_VARS['to_id']);

			if (!empty($to_id))
			{
				$sql = "SELECT *
					FROM " . NAV_BLOCKS_TABLE . "
					WHERE block_id IN ($from_id, $to_id)";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't verify existence of blocks", "", __LINE__, __FILE__, $sql);
				}
				if($db->sql_numrows($result) != 2)
				{
					message_die(GENERAL_ERROR, "Ambiguous block ID's", "", __LINE__, __FILE__);
				}

				$sql = "UPDATE " . NAV_MENUS_TABLE . "
					SET block_id = $to_id
					WHERE block_id = $from_id
					AND menu_is_sub <> 1";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't move menus to other block", "", __LINE__, __FILE__, $sql);
				}
			}

			$sql = "DELETE FROM " . NAV_BLOCKS_TABLE ."
				WHERE block_id = $from_id";
				
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete block", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['nm_menus_updated'] . "<br /><br />" . sprintf($lang['nm_click_return_navmenus'], "<a href=\"" . append_sid("admin_nav_menus.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;

		case 'menu_order':
			//
			// Change order of forums in the DB
			//
			$move = intval($HTTP_GET_VARS['move']);
			$menu_id = intval($HTTP_GET_VARS[POST_MENU_URL]);

			$menu_info = nav_get_info('menu', $menu_id);

			$block_id = $menu_info['block_id'];

			$sql = "UPDATE " . NAV_MENUS_TABLE . "
				SET menu_order = menu_order + $move
				WHERE menu_id = $menu_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't change menu order", "", __LINE__, __FILE__, $sql);
			}

			nav_renumber_order('menu', $menu_info['block_id']);
			$show_index = TRUE;

			break;
			
		case 'block_order':
			//
			// Change order of blocks in the DB
			//
			$move = intval($HTTP_GET_VARS['move']);
			$block_id = intval($HTTP_GET_VARS[POST_BLOCK_URL]);

			$sql = "UPDATE " . NAV_BLOCKS_TABLE . "
				SET block_order = block_order + $move
				WHERE block_id = $block_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't change block order", "", __LINE__, __FILE__, $sql);
			}

			nav_renumber_order('block');
			$show_index = TRUE;

			break;

		default:
			message_die(GENERAL_MESSAGE, $lang['No_mode']);
			break;
	}

	if ($show_index != TRUE)
	{
		include('./page_footer_admin.'.$phpEx);
		exit;
	}
}

//
// Start page proper
//
$template->set_filenames(array(
	"body" => XS_TPL_PATH . 'nav_menu_body.tpl')
);

$template->assign_vars(array(
	'S_FORUM_ACTION' => append_sid("admin_nav_menus.$phpEx"),
	'L_MENU_TITLE' => $lang['nm_title'], 
	'L_MENU_EXPLAIN' => $lang['nm_main_title_explain'],
	'L_MENU_SETTINGS' => $lang['nm_main_title'],
	'L_CREATE_MENU' => $lang['nm_create_menu'], 
	'L_CREATE_BLOCK' => $lang['nm_create_block'], 
	'L_EDIT' => $lang['Edit'], 
	'L_DELETE' => $lang['Delete'], 
	'L_MOVE_UP' => $lang['Move_up'], 
	'L_MOVE_DOWN' => $lang['Move_down'],
	'XS_PATH' => '../xs_mod/',
));

$sql = "SELECT block_id, block_title, block_order, block_pos, block_display
	FROM " . NAV_BLOCKS_TABLE . "
	ORDER BY block_order";
if( !$q_block = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, "Could not query blocks list", "", __LINE__, __FILE__, $sql);
}

if( $total_blocks = $db->sql_numrows($q_blocks) )
{
	$block_rows = $db->sql_fetchrowset($q_blocks);

	$sql = "SELECT *
		FROM " . NAV_MENUS_TABLE . "
		ORDER BY block_id, menu_order";
	if(!$q_menus = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query menu information", "", __LINE__, __FILE__, $sql);
	}

	if( $total_menus = $db->sql_numrows($q_menus) )
	{
		$menu_rows = $db->sql_fetchrowset($q_menus);
	}

	//
	// Okay, let's build the index
	//
	$gen_cat = array();

	for($i = 0; $i < $total_blocks; $i++)
	{
		$block_id = $block_rows[$i]['block_id'];
		$block_title = $block_rows[$i]['block_title'];
		
		$block_location = ' - ' . $lang['nm_block_location'] . ' [ ' . (( $block_rows[$i]['block_pos'] ) ? 'Right' : 'Left') . ' ]';
		$block_hidden = ' - ' . $lang['nm_block_hidden'] . ' [ ' . (( !$block_rows[$i]['block_display'] ) ? 'Yes' : 'No') . ' ]';
		$block_status = $lang['nm_block_status'] . ' ' . $block_location . $block_hidden;
		
		$template->assign_block_vars("blockrow", array( 
			'S_ADD_MENU_SUBMIT' => "addmenu[$block_id]", 
			'S_ADD_MENU_TITLE' => "menu_title[$block_id]", 

			'BLOCK_ID' => $block_id,
			'BLOCK_TITLE' => $lang['nm_menu_block'] . ': ' . $block_title,
			'BLOCK_STATUS' => $block_status,

			'U_BLOCK_EDIT' => append_sid("admin_nav_menus.$phpEx?mode=editblock&amp;" . POST_BLOCK_URL . "=$block_id"),
			'U_BLOCK_DELETE' => append_sid("admin_nav_menus.$phpEx?mode=deleteblock&amp;" . POST_BLOCK_URL . "=$block_id"),
			'U_BLOCK_MOVE_UP' => append_sid("admin_nav_menus.$phpEx?mode=block_order&amp;move=-15&amp;" . POST_BLOCK_URL . "=$block_id"),
			'U_BLOCK_MOVE_DOWN' => append_sid("admin_nav_menus.$phpEx?mode=block_order&amp;move=15&amp;" . POST_BLOCK_URL . "=$block_id")
		));

		for($j = 0; $j < $total_menus; $j++)
		{
			$menu_id = $menu_rows[$j]['menu_id'];
			
			if ($menu_rows[$j]['block_id'] == $block_id)
			{

				$template->assign_block_vars("blockrow.menurow",	array(
					'MENU_TITLE' => $menu_rows[$j]['menu_title'],
					'ROW_COLOR' => $row_color,
					'SUB_TITLE' => ($menu_rows[$j]['menu_is_sub']) ? 'icon_subtitle.gif' : 'spacer.gif',

					'U_MENU_EDIT' => append_sid("admin_nav_menus.$phpEx?mode=editmenu&amp;" . POST_MENU_URL . "=$menu_id"),
					'U_MENU_DELETE' => append_sid("admin_nav_menus.$phpEx?mode=deletemenu&amp;" . POST_MENU_URL . "=$menu_id"),
					'U_MENU_MOVE_UP' => append_sid("admin_nav_menus.$phpEx?mode=menu_order&amp;move=-15&amp;" . POST_MENU_URL . "=$menu_id"),
					'U_MENU_MOVE_DOWN' => append_sid("admin_nav_menus.$phpEx?mode=menu_order&amp;move=15&amp;" . POST_MENU_URL . "=$menu_id")
				));

			}// if ... menuid == blockid
			
		} // for ... menus

	} // for ... blocks

}// if ... total_blocks

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
