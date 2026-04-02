<?php
/*
	filename: xs_navmenu.php
	 version: 1.0.23
	
	  author: UseLess
	
	   notes: This is a based on the Nav Links done by;
	   
	   		CyberAlien (http://www.phpbbstyles.com)
*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

//
// Start Config
//
$topic_number = 10;	// Number of topics to show
$scroll_dir = "up";	// scroll direction of topics
$scroll_height = 130;	// height of scroll window
$scroll_amount = 2;	// amount to scroll by
$scroll_delay = 100;	// scroll delay
//
// End Config
//

//
// Start Script
//
//
// Get some information from the config table
//
$nav_left_width = ( isset($board_config['xs_nav_left_w']) ? intval($board_config['xs_nav_left_w']) : 0 );
$nav_right_width = ( isset($board_config['xs_nav_right_w']) ? intval($board_config['xs_nav_right_w']) : 0 );
$nav_show_left = (isset($board_config['xs_nav_left']) && $board_config['xs_nav_left'] == 1 ) ? TRUE : 0;
$nav_show_right = (isset($board_config['xs_nav_right']) && $board_config['xs_nav_right'] == 1 ) ? TRUE : 0;

// Assign col widths to template
$template->assign_vars(array(
	'XS_NAV_LEFT_WIDTH' => $nav_left_width,
	'XS_NAV_RIGHT_WIDTH' => $nav_right_width
));

//
// Should the left menu be shown?
//
if( $nav_show_left && $nav_left_width != 0 )
{
	// Setup the menu location, menu template file etc
	$menu_location = 'left';
	$menu_template = 'nav_menu_left.tpl';
	$menu_name = 'n_menu';
	$menu_tpl_name = strtoupper($menu_name);

	// Call the function to display the menus setup in the ACP
	display_nav_menu($menu_location, $menu_template, $menu_name, $menu_tpl_name);
	
	// Set the switch to show the left menu
	$template->assign_block_vars('switch_left_nav', array() );
}

//
// Should the right menu be shown?
//
if( $nav_show_right && $nav_right_width != 0 )
{
	// Setup the menu location, menu template file etc
	$menu_location = 'right';
	$menu_template = 'nav_menu_right.tpl';
	$menu_name = 'r_menu';
	$menu_tpl_name = strtoupper($menu_name);

	// Call the function to display the menus setup in the ACP
	display_nav_menu($menu_location, $menu_template, $menu_name, $menu_tpl_name);
	
	// Set the switch to show the right menu
	$template->assign_block_vars('switch_right_nav', array() );
}

//
// Start Function Block
//
function nav_unprepare_message($message)
{
	$unhtml_specialchars_match = array('#&gt;#', '#&lt;#', '#&quot;#', '#&amp;#');
	$unhtml_specialchars_replace = array('>', '<', '"', '&');

	return preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, $message);
}

//
// Using the specified template and the menu block information assigned in the ACP
// build a menu using this information
//
function display_nav_menu($menu_location, $menu_template, $menu_name, $menu_tpl_name)
{
	global $db, $template, $board_config, $lang, $phpEx, $userdata, $forum_id;
	global $exclude_forums, $topic_number;
	global $scroll_dir, $scroll_height, $scroll_amount, $scroll_delay;
	
	$menu_block_id = $menu_location . 'menublock';
	$menu_image_id = $menu_location . 'imageblock';

	if( !isset($forum_id) )
	{
		$forum_id = '-1';
	}

	$sql = "SELECT * FROM " . NAV_BLOCKS_TABLE . "
		ORDER BY block_order";
	if( !$q_blocks = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Could not query blocks list", "", __LINE__, __FILE__, $sql);
	}

	if( $total_blocks = $db->sql_numrows($q_blocks) )
	{
		$block_rows = $db->sql_fetchrowset($q_blocks);

		$sql = "SELECT * FROM " . NAV_MENUS_TABLE . "
			ORDER BY menu_order, block_id";
		if(!$q_menus = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not query menu information", "", __LINE__, __FILE__, $sql);
		}

		if( $total_menus = $db->sql_numrows($q_menus) )
		{
			$menu_rows = $db->sql_fetchrowset($q_menus);
		}

		//
		// Set the template file to use
		//
		$template->set_filenames(array($menu_name => $menu_template));

		//
		// Okay, let's build the menu blocks
		//
		for($i = 0; $i < $total_blocks; $i++)
		{
			$block_id = $block_rows[$i]['block_id'];
			$block_pos = ( ($block_rows[$i]['block_pos'] == 0) ? 'left' : 'right');
			$block_type = $block_rows[$i]['block_type'];
			$block_title = $block_rows[$i]['block_title'];
			$block_display = $block_rows[$i]['block_display'];
			$block_footer_display = $block_rows[$i]['block_footer_display'];

			// Flag used to indicate if the Block Title has been assigned to
			// the template, once assigned this is set to TRUE which stops it
			// being assigned again...
			//
			$done_title = false;

			switch($block_footer_display)
			{
				case 0:
				case 1:
					$block_footer = nav_unprepare_message($block_rows[$i]['block_footer']);
					break;

				case 2:
					$block_footer = sprintf($lang['nm_version_info'], $board_config['xs_nav_version']);
					break;
			}

			if( ($block_pos == $menu_location) && $block_display)
			{
				if( !$block_type )
				{
					for($j = 0; $j < $total_menus; $j++)
					{
						if ($menu_rows[$j]['block_id'] == $block_id)
						{
							$is_subtitle = ($menu_rows[$j]['menu_is_sub'] == 1) ? TRUE : false;
							$is_image = ($menu_rows[$j]['menu_is_image'] == 1) ? TRUE : false;

							if( $is_subtitle )
							{
								if( !$done_title )
								{
									$template->assign_block_vars($menu_block_id, array(
										'L_BLOCK_TITLE' => $block_title,
										'L_BLOCK_FOOTER' => $block_footer,
										'BLOCK_ID' => $block_id
									));

									$done_title = TRUE;
								}

								$template->assign_block_vars($menu_block_id . '.menurow', array(
									'L_SUBTITLE' => $menu_rows[$j]['menu_title']
								));
								$template->assign_block_vars($menu_block_id . '.menurow.switch_has_subtitle', array());
							}
							elseif( $is_image )
							{
								if( !$done_title )
								{
									$template->assign_block_vars($menu_image_id, array(
										'L_BLOCK_TITLE' => $block_title,
										'L_BLOCK_FOOTER' => $block_footer,
										'BLOCK_ID' => $block_id
									));

									$done_title = TRUE;
								}

								$template->assign_block_vars($menu_image_id . '.imagerow', array(
									'U_IMG' => '<a href="' . $menu_rows[$j]['menu_link'] . '" target="' . $menu_rows[$j]['menu_link_target'] . '"><img src="' . $menu_rows[$j]['menu_image'] . '" alt="' . $menu_rows[$j]['menu_title'] . '" width="' . $menu_rows[$j]['menu_image_w'] . '" height="' . $menu_rows[$j]['menu_image_h'] . '" border="0"></a>'
								));
							}
							else
							{
								if( !$done_title )
								{
									$template->assign_block_vars($menu_block_id, array(
										'L_BLOCK_TITLE' => $block_title,
										'L_BLOCK_FOOTER' => $block_footer,
										'BLOCK_ID' => $block_id
									));

									$done_title = TRUE;
								}

								if(!$is_subtitle && $menu_rows[$j]['menu_order'] == 10)
								{
									$template->assign_block_vars($menu_block_id . '.menurow', array(
										'L_SUBTITLE' => $menu_rows[$j]['menu_title']
									));
								}							

								$template->assign_block_vars($menu_block_id . '.menurow.linkrow', array(
									'U_LINK' => $menu_rows[$j]['menu_link'],
									'U_TARGET' => $menu_rows[$j]['menu_link_target'],
									'L_LINK' => $menu_rows[$j]['menu_title'],
									'L_VER' => ( !empty($menu_rows[$j]['menu_ver']) ) ? $menu_rows[$j]['menu_ver'] : '',
									'BULLET' => $menu_rows[$j]['menu_bullet']							
								));
							}

						}// if ... menuid == blockid

					} // for ... menus

					if ( $block_footer_display && !$is_image )
					{
						$template->assign_block_vars($menu_block_id . '.switch_footer_display', array());
					}
					elseif ( $block_footer_display && $is_image )
					{
						$template->assign_block_vars($menu_image_id . '.switch_footer_display', array());
					}
				}
				else
				{
					// Handle non menu blocks
					$template->assign_block_vars($menu_block_id, array(
						'L_BLOCK_TITLE' => $block_title,
						'L_BLOCK_FOOTER' => $block_footer,
						'BLOCK_ID' => $block_id,
						'L_GOOGLE' => $lang['nm_google_search'],
						'L_FORUM' => $lang['nm_forum_search'],
						'L_SEARCH_ALL' => $lang['nm_search_all'],
						'L_SEARCH_THIS' => $lang['nm_search_this'],
						'FORUM_ID' => $forum_id
					));

					switch($block_type)
					{
						case 1:
							// forum search
							$template->assign_block_vars($menu_block_id . '.switch_searchforum', array());
							break;

						case 2:
							// google search
							$template->assign_block_vars($menu_block_id . '.switch_google', array());
							break;

						case 3:
							// who's online
							$template->assign_block_vars($menu_block_id . '.switch_whoisonline', array());
							break;

						case 4:
							// Combined Forum/Google Searches
							$template->assign_block_vars($menu_block_id . '.switch_forumgoogle', array());

							if( $forum_id != '-1' )
							{
								$template->assign_block_vars($menu_block_id . '.switch_forumgoogle.switch_forum_select_on', array());
							}
							else
							{
								$template->assign_block_vars($menu_block_id . '.switch_forumgoogle.switch_forum_select_off', array());
							}
							break;

						case 5:
							// Last # Topics
							$date_format = $board_config['default_dateformat'];

							// get the path to the forum..
							$server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
							$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['server_name']));
							$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) : '';
							$script_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['script_path']));
							$script_name = ($script_name == '') ? $script_name : '/' . $script_name;

							// Forum URL
							$nav_forum_url = $server_protocol . $server_name . $server_port . $script_name;
							$nav_viewtopic_url = $nav_forum_url . '/viewtopic.' . $phpEx;
							$nav_viewforum_url = $nav_forum_url . '/viewforum.' . $phpEx;

							$sql = "SELECT f.*, p.post_time, p.post_username, u.username, u.user_id, u.user_level
								FROM (( " . FORUMS_TABLE . " f
								LEFT JOIN " . POSTS_TABLE . " p ON p.post_id = f.forum_last_post_id )
								LEFT JOIN " . USERS_TABLE . " u ON u.user_id = p.poster_id )
								ORDER BY f.cat_id, f.forum_order";

							if ( !($result = $db->sql_query($sql)) )
							{
								message_die(GENERAL_ERROR, 'Could not query forums information');
							}

							$forum_data = array();
							while( $row = $db->sql_fetchrow($result) )
							{
								$forum_data[] = $row;
							}
							$db->sql_freeresult($result);

							if ( !($total_forums = count($forum_data)) )
							{
								message_die(GENERAL_ERROR, $lang['No_forums']);
							}

							//
							// Find which forums are visible for this user
							//
							$is_auth_ary = array();
							$is_auth_ary = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata, $forum_data);

							$auth_sql = '';
							$exclude_forums = '';
							$exclude_forums_sql = '';
							for($j = 0; $j < $total_forums; $j++)
							{
								$nm_forum_id = $forum_data[$j]['forum_id'];

								if ( !$is_auth_ary[$nm_forum_id]['auth_view'] )
								{
									$exclude_forums_sql .= ( ( $exclude_forums_sql != '' ) ? ', ' : '' ) . $nm_forum_id;
								}
							}

							// Exclude Report forum (only available if Yellow Card Mod installed)
							$exclude_report = ( $board_config['xs_nav_exclude_report'] == 1 ) ? TRUE : FALSE;

							if( $exclude_report && (!empty($board_config['report_forum'])) )
							{
								$exclude_forums .= ( ( $exclude_forums != '' ) ? ',' : '' ) . intval($board_config['report_forum']);
							}

							if( strstr($board_config['xs_nav_exclude_forums'], ',') )
							{
								$f_ids = explode(',', $board_config['xs_nav_exclude_forums']);
								
								foreach($f_ids as $id)
								{
									$exclude_fourms .= (( $exclude_forums != '') ? ',' : '') . trim($id);
								}
							}
							else
							{
								$exclude_fourms .= (( $exclude_forums != '') ? ',' : '') . trim($board_config['xs_nav_exclude_forums']);
							}
								
							$exclude_forums_sql .= ( ( $exclude_forums_sql != '' && $exclude_forums != '' ) ? ',' : '' ) . $exclude_forums;

							if( $exclude_forums_sql != '' )
							{
								$auth_sql .= " AND f.forum_id NOT IN ($exclude_forums_sql) ";
							}

							$sql = "SELECT f.forum_name, f.forum_id, t.topic_id, t.topic_title, t.topic_first_post_id, t.forum_id, p.post_id, p.poster_id, p.post_time, u.user_id, u.username  
								FROM " . TOPICS_TABLE . " t," . FORUMS_TABLE . " f," . POSTS_TABLE . " p," . USERS_TABLE . " u  
								WHERE t.topic_id = p.topic_id
								AND f.forum_id = t.forum_id 
								AND t.topic_status <> " . TOPIC_MOVED . " 
								AND p.post_id = t.topic_last_post_id 
								AND p.poster_id = u.user_id
								$auth_sql
								ORDER BY p.post_id DESC LIMIT $topic_number";  						

							if ( !($result = $db->sql_query($sql)) )
							{
								message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
							}

							if( $total_topics = $db->sql_numrows($result) )
							{
								$template->assign_block_vars($menu_block_id . '.last_topic', array(
									'SCROLL_DIR' => $scroll_dir,
									'SCROLL_HEIGHT' => $scroll_height,
									'SCROLL_AMOUNT' => $scroll_amount,
									'SCROLL_DELAY' => $scroll_delay,
									'L_SUBTITLE' => sprintf($lang['nm_last_topic_title'], $total_topics)
								));

								while ( $row = $db->sql_fetchrow($result) )
								{  
									$topic_id = $row['topic_id'];
									$topic_title = $row['topic_title'];

									if( $row['post_id'] != $row['topic_first_post_id'] )
									{
										$topic_title = 'RE: ' . $topic_title;
										$topic_link = $nav_viewtopic_url . '?p=' . $row['post_id'] . '#' . $row['post_id'];
									}
									else
									{
										$topic_link = $nav_viewtopic_url . '?t=' . $row['topic_id'];
									}

									$template->assign_block_vars($menu_block_id . '.last_topic.topic_row', array(
										'U_TOPIC_LINK' => $topic_link,
										'L_TOPIC_LINK_TEXT' => $topic_title,
										'L_TOPIC_TITLE' => $lang['Forum'] . ': ' . $row['forum_name'] . "\n" . $lang['Sort_Topic_Title'] . ': ' . $topic_title . "\n" . $lang['Poster'] . $row["username"] . "\n" . $lang['Posted'] . ': ' . create_date($date_format, $row['post_time'], $board_config['board_timezone'])
									));
								}
							}
							else
							{
								$template->assign_block_vars($menu_block_id . '.last_topic', array(
									'SCROLL_DIR' => $scroll_dir,
									'SCROLL_HEIGHT' => $scroll_height,
									'SCROLL_AMOUNT' => $scroll_amount,
									'SCROLL_DELAY' => $scroll_delay,
									'L_SUBTITLE' => $lang['nm_last_topic_title_none']
								));
							}
							break;
					}

					if ( $block_footer_display )
					{
						$template->assign_block_vars($menu_block_id . '.switch_footer_display', array());
					}			

				} // !$block_type

			} // !$block_pos && $block_display

		} // for ... blocks

		$template->assign_var_from_handle($menu_tpl_name, $menu_name);

	}// if ... total_blocks

} // end function
?>