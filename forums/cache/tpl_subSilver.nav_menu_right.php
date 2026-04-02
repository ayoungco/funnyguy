<?php

// eXtreme Styles mod cache. Generated on Fri, 25 Mar 2005 16:23:47 -0800 (time=1111796627)

?><?php

$rightmenublock_count = ( isset($this->_tpldata['rightmenublock.']) ) ?  sizeof($this->_tpldata['rightmenublock.']) : 0;
for ($rightmenublock_i = 0; $rightmenublock_i < $rightmenublock_count; $rightmenublock_i++)
{
 $rightmenublock_item = &$this->_tpldata['rightmenublock.'][$rightmenublock_i];
 $rightmenublock_item['S_ROW_COUNT'] = $rightmenublock_i;
 $rightmenublock_item['S_NUM_ROWS'] = $rightmenublock_count;

?>
<table width="100%" cellspacing="0" cellpadding="0" class="forumline">
<tr>
	<td class="catLeft" align="center"><span class="cattitle"><a href="javascript:ShowHide('blk_<?php echo isset($rightmenublock_item['BLOCK_ID']) ? $rightmenublock_item['BLOCK_ID'] : ''; ?>','blk2_<?php echo isset($rightmenublock_item['BLOCK_ID']) ? $rightmenublock_item['BLOCK_ID'] : ''; ?>','block_<?php echo isset($rightmenublock_item['BLOCK_ID']) ? $rightmenublock_item['BLOCK_ID'] : ''; ?>');" onmouseover="showStatus('blk_<?php echo isset($rightmenublock_item['BLOCK_ID']) ? $rightmenublock_item['BLOCK_ID'] : ''; ?>'); return true;" onmouseout="window.status=' '; return true;"><?php echo isset($rightmenublock_item['L_BLOCK_TITLE']) ? $rightmenublock_item['L_BLOCK_TITLE'] : ''; ?></a></span></td>
</tr>
<tr><td>
<div id="blk_<?php echo isset($rightmenublock_item['BLOCK_ID']) ? $rightmenublock_item['BLOCK_ID'] : ''; ?>" style="display: ''; position: relative;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php

$switch_avatar_block_count = ( isset($rightmenublock_item['switch_avatar_block.']) ) ? sizeof($rightmenublock_item['switch_avatar_block.']) : 0;
for ($switch_avatar_block_i = 0; $switch_avatar_block_i < $switch_avatar_block_count; $switch_avatar_block_i++)
{
 $switch_avatar_block_item = &$rightmenublock_item['switch_avatar_block.'][$switch_avatar_block_i];
 $switch_avatar_block_item['S_ROW_COUNT'] = $switch_avatar_block_i;
 $switch_avatar_block_item['S_NUM_ROWS'] = $switch_avatar_block_count;

?>
<tr>
	<td class="nav-subtitle" align="center"><span style="font-size: 14px; font-weight: bold; color: #000000;"><?php echo isset($rightmenublock_item['U_NAME_LINK']) ? $rightmenublock_item['U_NAME_LINK'] : ''; ?></span></td>
</tr>
<tr><td class="nav-row">
	<form method="post" action="<?php echo isset($this->vars['S_LOGIN_ACTION']) ? $this->vars['S_LOGIN_ACTION'] : $this->lang('S_LOGIN_ACTION'); ?>">
	<table width="100%" cellpadding="2" cellspacing="1" border="0">
	<tr>
		<td class="nav-row" align="left"><span class="gensmall">
			<?php

$switch_user_logged_in_count = ( isset($switch_avatar_block_item['switch_user_logged_in.']) ) ? sizeof($switch_avatar_block_item['switch_user_logged_in.']) : 0;
for ($switch_user_logged_in_i = 0; $switch_user_logged_in_i < $switch_user_logged_in_count; $switch_user_logged_in_i++)
{
 $switch_user_logged_in_item = &$switch_avatar_block_item['switch_user_logged_in.'][$switch_user_logged_in_i];
 $switch_user_logged_in_item['S_ROW_COUNT'] = $switch_user_logged_in_i;
 $switch_user_logged_in_item['S_NUM_ROWS'] = $switch_user_logged_in_count;

?>
			<div align="center"><br /><?php echo isset($rightmenublock_item['AVATAR_IMG']) ? $rightmenublock_item['AVATAR_IMG'] : ''; ?></div>
			<br /><?php echo isset($this->vars['LAST_VISIT_DATE']) ? $this->vars['LAST_VISIT_DATE'] : $this->lang('LAST_VISIT_DATE'); ?><br /><br />
			<a href="<?php echo isset($this->vars['U_SEARCH_NEW']) ? $this->vars['U_SEARCH_NEW'] : $this->lang('U_SEARCH_NEW'); ?>" class="gensmall"><?php echo isset($this->vars['L_SEARCH_NEW']) ? $this->vars['L_SEARCH_NEW'] : $this->lang('L_SEARCH_NEW'); ?></a><br />
			<?php

} // END switch_user_logged_in

if(isset($switch_user_logged_in_item)) { unset($switch_user_logged_in_item); } 

?>
			<br /><?php echo isset($this->vars['CURRENT_TIME']) ? $this->vars['CURRENT_TIME'] : $this->lang('CURRENT_TIME'); ?><br /><br /><?php echo isset($this->vars['S_TIMEZONE']) ? $this->vars['S_TIMEZONE'] : $this->lang('S_TIMEZONE'); ?></span>
		</td>
	</tr>
	<?php

$switch_user_logged_out_count = ( isset($switch_avatar_block_item['switch_user_logged_out.']) ) ? sizeof($switch_avatar_block_item['switch_user_logged_out.']) : 0;
for ($switch_user_logged_out_i = 0; $switch_user_logged_out_i < $switch_user_logged_out_count; $switch_user_logged_out_i++)
{
 $switch_user_logged_out_item = &$switch_avatar_block_item['switch_user_logged_out.'][$switch_user_logged_out_i];
 $switch_user_logged_out_item['S_ROW_COUNT'] = $switch_user_logged_out_i;
 $switch_user_logged_out_item['S_NUM_ROWS'] = $switch_user_logged_out_count;

?>
	<tr>
		<td class="nav-subtitle" align="center"><span style="font-size: 14px; font-weight: bold;"><?php echo isset($this->vars['L_LOGIN']) ? $this->vars['L_LOGIN'] : $this->lang('L_LOGIN'); ?></span></td>
	</tr>
	<tr>
		<td class="nav-row"><span class="gensmall" style="line-height=150%">
			<input type="hidden" name="redirect" value="<?php echo isset($this->vars['U_INDEX']) ? $this->vars['U_INDEX'] : $this->lang('U_INDEX'); ?>" />
			<?php echo isset($this->vars['L_USERNAME']) ? $this->vars['L_USERNAME'] : $this->lang('L_USERNAME'); ?>:<br /><input class="text" type="text" name="username" size="15" /><br />
			<?php echo isset($this->vars['L_PASSWORD']) ? $this->vars['L_PASSWORD'] : $this->lang('L_PASSWORD'); ?>:<br /><input class="text" type="password" name="password" size="15" /><br />
			<input class="text" type="checkbox" name="autologin" checked="checked"/>&nbsp;<?php echo isset($this->vars['L_REMEMBER_ME']) ? $this->vars['L_REMEMBER_ME'] : $this->lang('L_REMEMBER_ME'); ?><br/>
			<input type="submit" class="mainoption" name="login" value="<?php echo isset($this->vars['L_LOGIN']) ? $this->vars['L_LOGIN'] : $this->lang('L_LOGIN'); ?>" /><br /><br /><a href="<?php echo isset($rightmenublock_item['U_SEND_PASSWORD']) ? $rightmenublock_item['U_SEND_PASSWORD'] : ''; ?>"><?php echo isset($this->vars['L_SEND_PASSWORD']) ? $this->vars['L_SEND_PASSWORD'] : $this->lang('L_SEND_PASSWORD'); ?></a><br /><br /><?php echo isset($rightmenublock_item['L_REGISTER_NEW_ACCOUNT']) ? $rightmenublock_item['L_REGISTER_NEW_ACCOUNT'] : ''; ?></span>
		</td>
	</tr>
	<?php

} // END switch_user_logged_out

if(isset($switch_user_logged_out_item)) { unset($switch_user_logged_out_item); } 

?>	
	</table>
	</form>
</td></tr>
<?php

} // END switch_avatar_block

if(isset($switch_avatar_block_item)) { unset($switch_avatar_block_item); } 

?>

<?php

$menurow_count = ( isset($rightmenublock_item['menurow.']) ) ? sizeof($rightmenublock_item['menurow.']) : 0;
for ($menurow_i = 0; $menurow_i < $menurow_count; $menurow_i++)
{
 $menurow_item = &$rightmenublock_item['menurow.'][$menurow_i];
 $menurow_item['S_ROW_COUNT'] = $menurow_i;
 $menurow_item['S_NUM_ROWS'] = $menurow_count;

?>
<?php

$switch_has_subtitle_count = ( isset($menurow_item['switch_has_subtitle.']) ) ? sizeof($menurow_item['switch_has_subtitle.']) : 0;
for ($switch_has_subtitle_i = 0; $switch_has_subtitle_i < $switch_has_subtitle_count; $switch_has_subtitle_i++)
{
 $switch_has_subtitle_item = &$menurow_item['switch_has_subtitle.'][$switch_has_subtitle_i];
 $switch_has_subtitle_item['S_ROW_COUNT'] = $switch_has_subtitle_i;
 $switch_has_subtitle_item['S_NUM_ROWS'] = $switch_has_subtitle_count;

?>
<tr><td class="nav-subtitle"><?php echo isset($menurow_item['L_SUBTITLE']) ? $menurow_item['L_SUBTITLE'] : ''; ?></td></tr>
<?php

} // END switch_has_subtitle

if(isset($switch_has_subtitle_item)) { unset($switch_has_subtitle_item); } 

?>
<tr><td valign="top" colspan="3" class="nav-row">
	<table width="100%" cellspacing="1" cellpadding="1" border="0">
	<?php

$linkrow_count = ( isset($menurow_item['linkrow.']) ) ? sizeof($menurow_item['linkrow.']) : 0;
for ($linkrow_i = 0; $linkrow_i < $linkrow_count; $linkrow_i++)
{
 $linkrow_item = &$menurow_item['linkrow.'][$linkrow_i];
 $linkrow_item['S_ROW_COUNT'] = $linkrow_i;
 $linkrow_item['S_NUM_ROWS'] = $linkrow_count;

?>
	<tr valign="top">
	<?php
	if(!empty($linkrow_item['BULLET']))
	{
	?>
	<td align="center" valign="middle" width="11"><img src="<?php echo isset($linkrow_item['BULLET']) ? $linkrow_item['BULLET'] : ''; ?>" width="11" height="11" alt="" border="0"></td>
	<td align="left" class="gensmall"><a href="<?php echo isset($linkrow_item['U_LINK']) ? $linkrow_item['U_LINK'] : ''; ?>" target="<?php echo isset($linkrow_item['U_TARGET']) ? $linkrow_item['U_TARGET'] : ''; ?>"><?php echo isset($linkrow_item['L_LINK']) ? $linkrow_item['L_LINK'] : ''; ?></a></td>
	<?php
	}
	else
	{
	?>
	<td colspan="2" align="left" class="gensmall"><a href="<?php echo isset($linkrow_item['U_LINK']) ? $linkrow_item['U_LINK'] : ''; ?>" target="<?php echo isset($linkrow_item['U_TARGET']) ? $linkrow_item['U_TARGET'] : ''; ?>"><?php echo isset($linkrow_item['L_LINK']) ? $linkrow_item['L_LINK'] : ''; ?></a></td>
	<?php
	}

	if(!empty($linkrow_item['L_VER']))
	{
	?>
	<td align="left" class="gensmall"><?php echo isset($linkrow_item['L_VER']) ? $linkrow_item['L_VER'] : ''; ?></td>
	<?php
	}
	?>
	</tr>
	<?php

} // END linkrow

if(isset($linkrow_item)) { unset($linkrow_item); } 

?>
	</table>
</td>
</tr>
<?php

} // END menurow

if(isset($menurow_item)) { unset($menurow_item); } 

?>

<?php

$last_topic_count = ( isset($rightmenublock_item['last_topic.']) ) ? sizeof($rightmenublock_item['last_topic.']) : 0;
for ($last_topic_i = 0; $last_topic_i < $last_topic_count; $last_topic_i++)
{
 $last_topic_item = &$rightmenublock_item['last_topic.'][$last_topic_i];
 $last_topic_item['S_ROW_COUNT'] = $last_topic_i;
 $last_topic_item['S_NUM_ROWS'] = $last_topic_count;

?>
<tr><td class="nav-subtitle"><?php echo isset($last_topic_item['L_SUBTITLE']) ? $last_topic_item['L_SUBTITLE'] : ''; ?></td></tr>
<tr><td class="nav-row">
<marquee id="recent_topics" behavior="scroll" direction="<?php echo isset($last_topic_item['SCROLL_DIR']) ? $last_topic_item['SCROLL_DIR'] : ''; ?>" height="<?php echo isset($last_topic_item['SCROLL_HEIGHT']) ? $last_topic_item['SCROLL_HEIGHT'] : ''; ?>" scrolldelay="<?php echo isset($last_topic_item['SCROLL_DELAY']) ? $last_topic_item['SCROLL_DELAY'] : ''; ?>" scrollamount="<?php echo isset($last_topic_item['SCROLL_AMOUNT']) ? $last_topic_item['SCROLL_AMOUNT'] : ''; ?>" onmouseover="document.all.recent_topics.stop()" onmouseout="document.all.recent_topics.start()">
<table cellpadding="3" cellspacing="2" border="0">  
<?php

$topic_row_count = ( isset($last_topic_item['topic_row.']) ) ? sizeof($last_topic_item['topic_row.']) : 0;
for ($topic_row_i = 0; $topic_row_i < $topic_row_count; $topic_row_i++)
{
 $topic_row_item = &$last_topic_item['topic_row.'][$topic_row_i];
 $topic_row_item['S_ROW_COUNT'] = $topic_row_i;
 $topic_row_item['S_NUM_ROWS'] = $topic_row_count;

?>
<tr><td class="nav-row"><a href="<?php echo isset($topic_row_item['U_TOPIC_LINK']) ? $topic_row_item['U_TOPIC_LINK'] : ''; ?>" title="<?php echo isset($topic_row_item['L_TOPIC_TITLE']) ? $topic_row_item['L_TOPIC_TITLE'] : ''; ?>"><?php echo isset($topic_row_item['L_TOPIC_LINK_TEXT']) ? $topic_row_item['L_TOPIC_LINK_TEXT'] : ''; ?></a></td></tr>
<?php

} // END topic_row

if(isset($topic_row_item)) { unset($topic_row_item); } 

?>
</table></marquee>
</td></tr>
<?php

} // END last_topic

if(isset($last_topic_item)) { unset($last_topic_item); } 

?>

<?php

$switch_html_block_count = ( isset($rightmenublock_item['switch_html_block.']) ) ? sizeof($rightmenublock_item['switch_html_block.']) : 0;
for ($switch_html_block_i = 0; $switch_html_block_i < $switch_html_block_count; $switch_html_block_i++)
{
 $switch_html_block_item = &$rightmenublock_item['switch_html_block.'][$switch_html_block_i];
 $switch_html_block_item['S_ROW_COUNT'] = $switch_html_block_i;
 $switch_html_block_item['S_NUM_ROWS'] = $switch_html_block_count;

?>
<tr><td class="row">
	<?php echo isset($rightmenublock_item['BLOCK_HTML']) ? $rightmenublock_item['BLOCK_HTML'] : ''; ?>
</td></tr>
<?php

} // END switch_html_block

if(isset($switch_html_block_item)) { unset($switch_html_block_item); } 

?>

<?php

$switch_forumgoogle_count = ( isset($rightmenublock_item['switch_forumgoogle.']) ) ? sizeof($rightmenublock_item['switch_forumgoogle.']) : 0;
for ($switch_forumgoogle_i = 0; $switch_forumgoogle_i < $switch_forumgoogle_count; $switch_forumgoogle_i++)
{
 $switch_forumgoogle_item = &$rightmenublock_item['switch_forumgoogle.'][$switch_forumgoogle_i];
 $switch_forumgoogle_item['S_ROW_COUNT'] = $switch_forumgoogle_i;
 $switch_forumgoogle_item['S_NUM_ROWS'] = $switch_forumgoogle_count;

?>
<tr><td class="nav-subtitle"><?php echo isset($rightmenublock_item['L_FORUM']) ? $rightmenublock_item['L_FORUM'] : ''; ?></td></tr>
<tr><td class="nav-row" align="center">
<!-- Quick Search -->
<form action="search.php?mode=results" method="post" target="_self">
<table width="100%" cellspacing="2" cellpadding="1" border="0">
<?php

$switch_forum_select_on_count = ( isset($switch_forumgoogle_item['switch_forum_select_on.']) ) ? sizeof($switch_forumgoogle_item['switch_forum_select_on.']) : 0;
for ($switch_forum_select_on_i = 0; $switch_forum_select_on_i < $switch_forum_select_on_count; $switch_forum_select_on_i++)
{
 $switch_forum_select_on_item = &$switch_forumgoogle_item['switch_forum_select_on.'][$switch_forum_select_on_i];
 $switch_forum_select_on_item['S_ROW_COUNT'] = $switch_forum_select_on_i;
 $switch_forum_select_on_item['S_NUM_ROWS'] = $switch_forum_select_on_count;

?>
<tr><td align="left" valign="middle" class="row1">
<select name="search_forum" size="1" class="gensmall">
	<option value="-1"><?php echo isset($rightmenublock_item['L_SEARCH_ALL']) ? $rightmenublock_item['L_SEARCH_ALL'] : ''; ?></option>
	<option value="<?php echo isset($rightmenublock_item['FORUM_ID']) ? $rightmenublock_item['FORUM_ID'] : ''; ?>" selected="selected"><?php echo isset($rightmenublock_item['L_SEARCH_THIS']) ? $rightmenublock_item['L_SEARCH_THIS'] : ''; ?></option>
</select>
</td></tr>
<?php

} // END switch_forum_select_on

if(isset($switch_forum_select_on_item)) { unset($switch_forum_select_on_item); } 

?>
<tr><td nowrap="nowrap" align="center" valign="middle">
<input type="text" class="post" name="search_keywords" size="19" value="Quick Search" onFocus="this.value=''"/>
</td></tr>
<tr><td align="center" valign="middle">
<input type="submit" class="mainoption" value="Search" />
</td></tr>
<tr><td align="center" valign="middle">
<span class="gensmall"><a href="search.php" class="gensmall">Advanced Search</a></span>
</td></tr>
</table>
<input type="hidden" name="searchuser" value="">
<?php

$switch_forum_select_off_count = ( isset($switch_forumgoogle_item['switch_forum_select_off.']) ) ? sizeof($switch_forumgoogle_item['switch_forum_select_off.']) : 0;
for ($switch_forum_select_off_i = 0; $switch_forum_select_off_i < $switch_forum_select_off_count; $switch_forum_select_off_i++)
{
 $switch_forum_select_off_item = &$switch_forumgoogle_item['switch_forum_select_off.'][$switch_forum_select_off_i];
 $switch_forum_select_off_item['S_ROW_COUNT'] = $switch_forum_select_off_i;
 $switch_forum_select_off_item['S_NUM_ROWS'] = $switch_forum_select_off_count;

?>
<input type="hidden" name="search_forum" value="<?php echo isset($rightmenublock_item['FORUM_ID']) ? $rightmenublock_item['FORUM_ID'] : ''; ?>">
<?php

} // END switch_forum_select_off

if(isset($switch_forum_select_off_item)) { unset($switch_forum_select_off_item); } 

?>
<input type="hidden" name="search_author" value="">
<input type="hidden" name="search_time" value="0">
<input type="hidden" name="search_cat" value="-1">
<input type="hidden" name="sort_by" value="0">
<input type="hidden" name="search_fields" value="all"> 
<input type="hidden" name="show_results" value="topics">
<input type="hidden" name="return_chars" value="-1">
</form>
<!-- Quick Search -->
</td></tr>
<tr><td class="nav-subtitle"><?php echo isset($rightmenublock_item['L_GOOGLE']) ? $rightmenublock_item['L_GOOGLE'] : ''; ?></td></tr>
<tr><td class="nav-row" align="center">
<!-- Search Google -->
<center>
<form method="get" action="http://www.google.com/custom" target="_blank">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td nowrap="nowrap" valign="top" align="center" height="32">
<a href="http://www.google.com/">
<img src="http://www.google.com/logos/Logo_25wht.gif" border="0" alt="Google" align="middle"></img></a><br>
<input type="text" class="post" name="q" size="19" maxlength="255" value="Enter Query" onFocus="this.value=''"></input>
</td></tr>
<tr><td valign="middle" align="center" height="25">
<input type="submit" name="sa" value="Search" class="mainoption"></input>
<input type="hidden" name="client" value="pub-1668403251585160"></input>
<input type="hidden" name="forid" value="1"></input>
<input type="hidden" name="ie" value="ISO-8859-1"></input>
<input type="hidden" name="oe" value="ISO-8859-1"></input>
<input type="hidden" name="cof" value="GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1;"></input>
<input type="hidden" name="hl" value="en"></input>
</td></tr></table>
</form>
</center>
<!-- Search Google -->
</td></tr>
<?php

} // END switch_forumgoogle

if(isset($switch_forumgoogle_item)) { unset($switch_forumgoogle_item); } 

?>

<?php

$switch_google_count = ( isset($rightmenublock_item['switch_google.']) ) ? sizeof($rightmenublock_item['switch_google.']) : 0;
for ($switch_google_i = 0; $switch_google_i < $switch_google_count; $switch_google_i++)
{
 $switch_google_item = &$rightmenublock_item['switch_google.'][$switch_google_i];
 $switch_google_item['S_ROW_COUNT'] = $switch_google_i;
 $switch_google_item['S_NUM_ROWS'] = $switch_google_count;

?>
<tr><td class="nav-row" align="center">
<!-- Search Google -->
<center>
<form method="get" action="http://www.google.com/custom" target="_blank">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td nowrap="nowrap" valign="top" align="center" height="32">
<a href="http://www.google.com/">
<img src="http://www.google.com/logos/Logo_25wht.gif" border="0" alt="Google" align="middle"></img></a><br>
<input type="text" class="post" name="q" size="19" maxlength="255" value="Enter Query" onFocus="this.value=''"></input>
</td></tr>
<tr><td valign="middle" align="center" height="25">
<input type="submit" name="sa" value="Search" class="mainoption"></input>
<input type="hidden" name="client" value="pub-1668403251585160"></input>
<input type="hidden" name="forid" value="1"></input>
<input type="hidden" name="ie" value="ISO-8859-1"></input>
<input type="hidden" name="oe" value="ISO-8859-1"></input>
<input type="hidden" name="cof" value="GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1;"></input>
<input type="hidden" name="hl" value="en"></input>
</td></tr></table>
</form>
</center>
<!-- Search Google -->
</td></tr>
<?php

} // END switch_google

if(isset($switch_google_item)) { unset($switch_google_item); } 

?>

<?php

$switch_searchforum_count = ( isset($rightmenublock_item['switch_searchforum.']) ) ? sizeof($rightmenublock_item['switch_searchforum.']) : 0;
for ($switch_searchforum_i = 0; $switch_searchforum_i < $switch_searchforum_count; $switch_searchforum_i++)
{
 $switch_searchforum_item = &$rightmenublock_item['switch_searchforum.'][$switch_searchforum_i];
 $switch_searchforum_item['S_ROW_COUNT'] = $switch_searchforum_i;
 $switch_searchforum_item['S_NUM_ROWS'] = $switch_searchforum_count;

?>
<tr><td class="nav-row" align="center">
<!-- Quick Search -->
<form action="search.php?mode=results" method="post" target="_self">
<table width="100%" cellspacing="2" cellpadding="1" border="0">
<?php

$switch_forum_select_on_count = ( isset($switch_searchforum_item['switch_forum_select_on.']) ) ? sizeof($switch_searchforum_item['switch_forum_select_on.']) : 0;
for ($switch_forum_select_on_i = 0; $switch_forum_select_on_i < $switch_forum_select_on_count; $switch_forum_select_on_i++)
{
 $switch_forum_select_on_item = &$switch_searchforum_item['switch_forum_select_on.'][$switch_forum_select_on_i];
 $switch_forum_select_on_item['S_ROW_COUNT'] = $switch_forum_select_on_i;
 $switch_forum_select_on_item['S_NUM_ROWS'] = $switch_forum_select_on_count;

?>
<tr><td align="left" valign="middle" class="row1">
<select name="search_forum" size="1" class="gensmall">
	<option value="-1"><?php echo isset($rightmenublock_item['L_SEARCH_ALL']) ? $rightmenublock_item['L_SEARCH_ALL'] : ''; ?></option>
	<option value="<?php echo isset($rightmenublock_item['FORUM_ID']) ? $rightmenublock_item['FORUM_ID'] : ''; ?>" selected="selected"><?php echo isset($rightmenublock_item['L_SEARCH_THIS']) ? $rightmenublock_item['L_SEARCH_THIS'] : ''; ?></option>
</select>
</td></tr>
<?php

} // END switch_forum_select_on

if(isset($switch_forum_select_on_item)) { unset($switch_forum_select_on_item); } 

?>
<tr><td nowrap="nowrap" align="center" valign="middle">
<input type="text" class="post" name="search_keywords" size="19" value="Quick Search" onFocus="this.value=''"/>
</td></tr>
<tr><td align="center" valign="middle">
<input type="submit" class="mainoption" value="Search" />
</td></tr>
<tr><td align="center" valign="middle">
<span class="gensmall"><a href="search.php" class="gensmall">Advanced Search</a></span>
</td></tr>
</table>
<input type="hidden" name="searchuser" value="">
<?php

$switch_forum_select_off_count = ( isset($switch_searchforum_item['switch_forum_select_off.']) ) ? sizeof($switch_searchforum_item['switch_forum_select_off.']) : 0;
for ($switch_forum_select_off_i = 0; $switch_forum_select_off_i < $switch_forum_select_off_count; $switch_forum_select_off_i++)
{
 $switch_forum_select_off_item = &$switch_searchforum_item['switch_forum_select_off.'][$switch_forum_select_off_i];
 $switch_forum_select_off_item['S_ROW_COUNT'] = $switch_forum_select_off_i;
 $switch_forum_select_off_item['S_NUM_ROWS'] = $switch_forum_select_off_count;

?>
<input type="hidden" name="search_forum" value="<?php echo isset($rightmenublock_item['FORUM_ID']) ? $rightmenublock_item['FORUM_ID'] : ''; ?>">
<?php

} // END switch_forum_select_off

if(isset($switch_forum_select_off_item)) { unset($switch_forum_select_off_item); } 

?>
<input type="hidden" name="search_author" value="">
<input type="hidden" name="search_time" value="0">
<input type="hidden" name="search_cat" value="-1">
<input type="hidden" name="sort_by" value="0">
<input type="hidden" name="search_fields" value="all"> 
<input type="hidden" name="show_results" value="topics">
<input type="hidden" name="return_chars" value="-1">
</form>
<!-- Quick Search -->
</td></tr>
<?php

} // END switch_searchforum

if(isset($switch_searchforum_item)) { unset($switch_searchforum_item); } 

?>

<?php

$switch_whoisonline_count = ( isset($rightmenublock_item['switch_whoisonline.']) ) ? sizeof($rightmenublock_item['switch_whoisonline.']) : 0;
for ($switch_whoisonline_i = 0; $switch_whoisonline_i < $switch_whoisonline_count; $switch_whoisonline_i++)
{
 $switch_whoisonline_item = &$rightmenublock_item['switch_whoisonline.'][$switch_whoisonline_i];
 $switch_whoisonline_item['S_ROW_COUNT'] = $switch_whoisonline_i;
 $switch_whoisonline_item['S_NUM_ROWS'] = $switch_whoisonline_count;

?>
<tr><td class="nav-row" align="center">
<!-- Who's Online -->                  
<table width="100%" cellpadding="2" cellspacing="1" border="0">  
<tr><td align="center">
	<span class="gensmall"><?php echo isset($this->vars['TOTAL_USERS_ONLINE']) ? $this->vars['TOTAL_USERS_ONLINE'] : $this->lang('TOTAL_USERS_ONLINE'); ?></span>
	<hr>
	<span class="gensmall">[ <?php echo isset($this->vars['L_WHOSONLINE_ADMIN']) ? $this->vars['L_WHOSONLINE_ADMIN'] : $this->lang('L_WHOSONLINE_ADMIN'); ?> ]<br />[ <?php echo isset($this->vars['L_WHOSONLINE_MOD']) ? $this->vars['L_WHOSONLINE_MOD'] : $this->lang('L_WHOSONLINE_MOD'); ?> ]</span>
	<hr>
	<span class="gensmall"><?php echo isset($this->vars['LOGGED_IN_USER_LIST']) ? $this->vars['LOGGED_IN_USER_LIST'] : $this->lang('LOGGED_IN_USER_LIST'); ?></span>
	<hr>
	<span class="gensmall"><?php echo isset($this->vars['RECORD_USERS']) ? $this->vars['RECORD_USERS'] : $this->lang('RECORD_USERS'); ?></span>
</td></tr>  
</table>  
<!-- Who's Online -->
</td></tr>
<?php

} // END switch_whoisonline

if(isset($switch_whoisonline_item)) { unset($switch_whoisonline_item); } 

?>

<?php

$switch_statistics_count = ( isset($rightmenublock_item['switch_statistics.']) ) ? sizeof($rightmenublock_item['switch_statistics.']) : 0;
for ($switch_statistics_i = 0; $switch_statistics_i < $switch_statistics_count; $switch_statistics_i++)
{
 $switch_statistics_item = &$rightmenublock_item['switch_statistics.'][$switch_statistics_i];
 $switch_statistics_item['S_ROW_COUNT'] = $switch_statistics_i;
 $switch_statistics_item['S_NUM_ROWS'] = $switch_statistics_count;

?>
<tr><td class="nav-row" align="center">
<!-- Forum Quick Stats -->                  
<table width="100%" cellpadding="2" cellspacing="1" border="0">
<tr>
	<td class="row1" align="left"><span class="gensmall"><?php echo isset($this->vars['TOTAL_USERS']) ? $this->vars['TOTAL_USERS'] : $this->lang('TOTAL_USERS'); ?><br /><?php echo isset($this->vars['NEWEST_USER']) ? $this->vars['NEWEST_USER'] : $this->lang('NEWEST_USER'); ?><br /><br/><?php echo isset($this->vars['TOTAL_POSTS']) ? $this->vars['TOTAL_POSTS'] : $this->lang('TOTAL_POSTS'); ?> <?php echo isset($this->vars['TOTAL_TOPICS']) ? $this->vars['TOTAL_TOPICS'] : $this->lang('TOTAL_TOPICS'); ?></span></td>
</tr>
</table>
</td></tr>
<?php

} // END switch_statistics

if(isset($switch_statistics_item)) { unset($switch_statistics_item); } 

?>

<?php

$switch_footer_display_count = ( isset($rightmenublock_item['switch_footer_display.']) ) ? sizeof($rightmenublock_item['switch_footer_display.']) : 0;
for ($switch_footer_display_i = 0; $switch_footer_display_i < $switch_footer_display_count; $switch_footer_display_i++)
{
 $switch_footer_display_item = &$rightmenublock_item['switch_footer_display.'][$switch_footer_display_i];
 $switch_footer_display_item['S_ROW_COUNT'] = $switch_footer_display_i;
 $switch_footer_display_item['S_NUM_ROWS'] = $switch_footer_display_count;

?>
<tr><td colspan="3" class="nav-ftr"><span class="gensmall"><?php echo isset($rightmenublock_item['L_BLOCK_FOOTER']) ? $rightmenublock_item['L_BLOCK_FOOTER'] : ''; ?><span></td></tr>
<?php

} // END switch_footer_display

if(isset($switch_footer_display_item)) { unset($switch_footer_display_item); } 

?>
</table></div><div id="blk2_<?php echo isset($rightmenublock_item['BLOCK_ID']) ? $rightmenublock_item['BLOCK_ID'] : ''; ?>" style="display: none; position: relative;"><table width="100%" cellpadding="0" cellspacing="1" border="0" class="forumline"><tr><td class="spacerow" width="100%" height="3" bgcolor="#888888"><img src="<?php echo isset($this->vars['T_TEMPLATE_PATH']) ? $this->vars['T_TEMPLATE_PATH'] : $this->lang('T_TEMPLATE_PATH'); ?>/images/spacer.gif" width="1" height="3" border="0" alt="" /></td></tr></table></div>
</td></tr>
</table>
<script language="javascript" type="text/javascript">
<!--
tmp = 'block_<?php echo isset($rightmenublock_item['BLOCK_ID']) ? $rightmenublock_item['BLOCK_ID'] : ''; ?>';
if(GetCookie(tmp) == '2')
{
	ShowHide('blk_<?php echo isset($rightmenublock_item['BLOCK_ID']) ? $rightmenublock_item['BLOCK_ID'] : ''; ?>','blk2_<?php echo isset($rightmenublock_item['BLOCK_ID']) ? $rightmenublock_item['BLOCK_ID'] : ''; ?>','block_<?php echo isset($rightmenublock_item['BLOCK_ID']) ? $rightmenublock_item['BLOCK_ID'] : ''; ?>');
}
//-->
</script>
<br />
<?php

} // END rightmenublock

if(isset($rightmenublock_item)) { unset($rightmenublock_item); } 

?>


<?php

$rightimageblock_count = ( isset($this->_tpldata['rightimageblock.']) ) ?  sizeof($this->_tpldata['rightimageblock.']) : 0;
for ($rightimageblock_i = 0; $rightimageblock_i < $rightimageblock_count; $rightimageblock_i++)
{
 $rightimageblock_item = &$this->_tpldata['rightimageblock.'][$rightimageblock_i];
 $rightimageblock_item['S_ROW_COUNT'] = $rightimageblock_i;
 $rightimageblock_item['S_NUM_ROWS'] = $rightimageblock_count;

?>
<table width="100%" cellspacing="0" cellpadding="0" class="forumline">
<tr>
	<td class="catLeft" align="center"><span class="cattitle"><a href="javascript:ShowHide('blk_<?php echo isset($rightimageblock_item['BLOCK_ID']) ? $rightimageblock_item['BLOCK_ID'] : ''; ?>','blk2_<?php echo isset($rightimageblock_item['BLOCK_ID']) ? $rightimageblock_item['BLOCK_ID'] : ''; ?>','block_<?php echo isset($rightimageblock_item['BLOCK_ID']) ? $rightimageblock_item['BLOCK_ID'] : ''; ?>');" onmouseover="showStatus('blk_<?php echo isset($rightimageblock_item['BLOCK_ID']) ? $rightimageblock_item['BLOCK_ID'] : ''; ?>'); return true;" onmouseout="window.status=' '; return true;"><?php echo isset($rightimageblock_item['L_BLOCK_TITLE']) ? $rightimageblock_item['L_BLOCK_TITLE'] : ''; ?></a></span></td>
</tr>
<tr><td>
<div id="blk_<?php echo isset($rightimageblock_item['BLOCK_ID']) ? $rightimageblock_item['BLOCK_ID'] : ''; ?>" style="display: ''; position: relative;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td valign="top" colspan="3" class="row1">
	<table width="100%" cellspacing="1" cellpadding="1" border="0">
	<?php

$imagerow_count = ( isset($rightimageblock_item['imagerow.']) ) ? sizeof($rightimageblock_item['imagerow.']) : 0;
for ($imagerow_i = 0; $imagerow_i < $imagerow_count; $imagerow_i++)
{
 $imagerow_item = &$rightimageblock_item['imagerow.'][$imagerow_i];
 $imagerow_item['S_ROW_COUNT'] = $imagerow_i;
 $imagerow_item['S_NUM_ROWS'] = $imagerow_count;

?>
	<tr valign="top">
	<td align="center" class="gensmall"><?php echo isset($imagerow_item['U_IMG']) ? $imagerow_item['U_IMG'] : ''; ?><td>
	</tr>
	<?php

} // END imagerow

if(isset($imagerow_item)) { unset($imagerow_item); } 

?>
	</table>
</td>
</tr>
<?php

$switch_footer_display_count = ( isset($rightimageblock_item['switch_footer_display.']) ) ? sizeof($rightimageblock_item['switch_footer_display.']) : 0;
for ($switch_footer_display_i = 0; $switch_footer_display_i < $switch_footer_display_count; $switch_footer_display_i++)
{
 $switch_footer_display_item = &$rightimageblock_item['switch_footer_display.'][$switch_footer_display_i];
 $switch_footer_display_item['S_ROW_COUNT'] = $switch_footer_display_i;
 $switch_footer_display_item['S_NUM_ROWS'] = $switch_footer_display_count;

?>
<tr><td colspan="3" align="center" class="nav-ftr"><span class="gensmall"><?php echo isset($rightimageblock_item['L_BLOCK_FOOTER']) ? $rightimageblock_item['L_BLOCK_FOOTER'] : ''; ?><span></td></tr>
<?php

} // END switch_footer_display

if(isset($switch_footer_display_item)) { unset($switch_footer_display_item); } 

?>
</table></div><div id="blk2_<?php echo isset($rightimageblock_item['BLOCK_ID']) ? $rightimageblock_item['BLOCK_ID'] : ''; ?>" style="display: none; position: relative;"><table width="100%" cellpadding="0" cellspacing="1" border="0" class="forumline"><tr><td class="spacerow" width="100%" height="3" bgcolor="#888888"><img src="<?php echo isset($this->vars['T_TEMPLATE_PATH']) ? $this->vars['T_TEMPLATE_PATH'] : $this->lang('T_TEMPLATE_PATH'); ?>/images/spacer.gif" width="1" height="3" border="0" alt="" /></td></tr></table></div>
</td></tr>
</table>
<script language="javascript" type="text/javascript">
<!--
tmp = 'block_<?php echo isset($rightimageblock_item['BLOCK_ID']) ? $rightimageblock_item['BLOCK_ID'] : ''; ?>';
if(GetCookie(tmp) == '2')
{
	ShowHide('blk_<?php echo isset($rightimageblock_item['BLOCK_ID']) ? $rightimageblock_item['BLOCK_ID'] : ''; ?>','blk2_<?php echo isset($rightimageblock_item['BLOCK_ID']) ? $rightimageblock_item['BLOCK_ID'] : ''; ?>','block_<?php echo isset($rightimageblock_item['BLOCK_ID']) ? $rightimageblock_item['BLOCK_ID'] : ''; ?>');
}
//-->
</script>
<?php

} // END rightimageblock

if(isset($rightimageblock_item)) { unset($rightimageblock_item); } 

?>
