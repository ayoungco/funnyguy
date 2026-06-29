<?php

// eXtreme Styles mod cache. Generated on Thu, 17 Mar 2005 14:41:41 -0800 (time=1111099301)

?>
<form method="post" action="<?php echo isset($this->vars['S_POST_DAYS_ACTION']) ? $this->vars['S_POST_DAYS_ACTION'] : $this->lang('S_POST_DAYS_ACTION'); ?>">
  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr> 
	  <td align="left" valign="bottom" colspan="2"><a class="maintitle" href="<?php echo isset($this->vars['U_VIEW_FORUM']) ? $this->vars['U_VIEW_FORUM'] : $this->lang('U_VIEW_FORUM'); ?>"><?php echo isset($this->vars['FORUM_NAME']) ? $this->vars['FORUM_NAME'] : $this->lang('FORUM_NAME'); ?></a><br /><span class="gensmall"><b><?php echo isset($this->vars['L_MODERATOR']) ? $this->vars['L_MODERATOR'] : $this->lang('L_MODERATOR'); ?>: <?php echo isset($this->vars['MODERATORS']) ? $this->vars['MODERATORS'] : $this->lang('MODERATORS'); ?><br /><br /><?php echo isset($this->vars['LOGGED_IN_USER_LIST']) ? $this->vars['LOGGED_IN_USER_LIST'] : $this->lang('LOGGED_IN_USER_LIST'); ?></b></span></td>
	  <td align="right" valign="bottom" nowrap="nowrap"><span class="gensmall"><b><?php echo isset($this->vars['PAGINATION']) ? $this->vars['PAGINATION'] : $this->lang('PAGINATION'); ?></b></span></td>
	</tr>
	<tr> 
	  <td align="left" valign="middle" width="50"><a href="<?php echo isset($this->vars['U_POST_NEW_TOPIC']) ? $this->vars['U_POST_NEW_TOPIC'] : $this->lang('U_POST_NEW_TOPIC'); ?>"><img src="<?php echo isset($this->vars['POST_IMG']) ? $this->vars['POST_IMG'] : $this->lang('POST_IMG'); ?>" border="0" alt="<?php echo isset($this->vars['L_POST_NEW_TOPIC']) ? $this->vars['L_POST_NEW_TOPIC'] : $this->lang('L_POST_NEW_TOPIC'); ?>" /></a></td>
	  <td align="left" valign="middle" class="nav" width="100%"><span class="nav">&nbsp;&nbsp;&nbsp;<a href="<?php echo isset($this->vars['U_INDEX']) ? $this->vars['U_INDEX'] : $this->lang('U_INDEX'); ?>" class="nav"><?php echo isset($this->vars['L_INDEX']) ? $this->vars['L_INDEX'] : $this->lang('L_INDEX'); ?></a> -> <a class="nav" href="<?php echo isset($this->vars['U_VIEW_FORUM']) ? $this->vars['U_VIEW_FORUM'] : $this->lang('U_VIEW_FORUM'); ?>"><?php echo isset($this->vars['FORUM_NAME']) ? $this->vars['FORUM_NAME'] : $this->lang('FORUM_NAME'); ?></a></span></td>
	  <td align="right" valign="bottom" class="nav" nowrap="nowrap"><span class="gensmall"><a href="<?php echo isset($this->vars['U_MARK_READ']) ? $this->vars['U_MARK_READ'] : $this->lang('U_MARK_READ'); ?>"><?php echo isset($this->vars['L_MARK_TOPICS_READ']) ? $this->vars['L_MARK_TOPICS_READ'] : $this->lang('L_MARK_TOPICS_READ'); ?></a></span></td>
	</tr>
  </table>

  <table border="0" cellpadding="4" cellspacing="1" width="100%" class="forumline">
	<tr> 
	  <th colspan="2" align="center" height="25" class="thCornerL" nowrap="nowrap">&nbsp;<?php echo isset($this->vars['L_TOPICS']) ? $this->vars['L_TOPICS'] : $this->lang('L_TOPICS'); ?>&nbsp;</th>
	  <th width="50" align="center" class="thTop" nowrap="nowrap">&nbsp;<?php echo isset($this->vars['L_REPLIES']) ? $this->vars['L_REPLIES'] : $this->lang('L_REPLIES'); ?>&nbsp;</th>
	  <th width="100" align="center" class="thTop" nowrap="nowrap">&nbsp;<?php echo isset($this->vars['L_AUTHOR']) ? $this->vars['L_AUTHOR'] : $this->lang('L_AUTHOR'); ?>&nbsp;</th>
	  <th width="50" align="center" class="thTop" nowrap="nowrap">&nbsp;<?php echo isset($this->vars['L_VIEWS']) ? $this->vars['L_VIEWS'] : $this->lang('L_VIEWS'); ?>&nbsp;</th>
	  <th align="center" class="thCornerR" nowrap="nowrap">&nbsp;<?php echo isset($this->vars['L_LASTPOST']) ? $this->vars['L_LASTPOST'] : $this->lang('L_LASTPOST'); ?>&nbsp;</th>
	</tr>
	<?php

$topicrow_count = ( isset($this->_tpldata['topicrow.']) ) ?  sizeof($this->_tpldata['topicrow.']) : 0;
for ($topicrow_i = 0; $topicrow_i < $topicrow_count; $topicrow_i++)
{
 $topicrow_item = &$this->_tpldata['topicrow.'][$topicrow_i];
 $topicrow_item['S_ROW_COUNT'] = $topicrow_i;
 $topicrow_item['S_NUM_ROWS'] = $topicrow_count;

?>
	<tr> 
	  <td class="row1" align="center" valign="middle" width="20"><img src="<?php echo isset($topicrow_item['TOPIC_FOLDER_IMG']) ? $topicrow_item['TOPIC_FOLDER_IMG'] : ''; ?>" width="19" height="18" alt="<?php echo isset($topicrow_item['L_TOPIC_FOLDER_ALT']) ? $topicrow_item['L_TOPIC_FOLDER_ALT'] : ''; ?>" title="<?php echo isset($topicrow_item['L_TOPIC_FOLDER_ALT']) ? $topicrow_item['L_TOPIC_FOLDER_ALT'] : ''; ?>" /></td>
	  <td class="row1" width="100%"><span class="topictitle"><?php echo isset($topicrow_item['NEWEST_POST_IMG']) ? $topicrow_item['NEWEST_POST_IMG'] : ''; ?><?php echo isset($topicrow_item['TOPIC_TYPE']) ? $topicrow_item['TOPIC_TYPE'] : ''; ?><a href="<?php echo isset($topicrow_item['U_VIEW_TOPIC']) ? $topicrow_item['U_VIEW_TOPIC'] : ''; ?>" class="topictitle"><?php echo isset($topicrow_item['TOPIC_TITLE']) ? $topicrow_item['TOPIC_TITLE'] : ''; ?></a></span><span class="gensmall"><br />
		<?php echo isset($topicrow_item['GOTO_PAGE']) ? $topicrow_item['GOTO_PAGE'] : ''; ?></span></td>
	  <td class="row2" align="center" valign="middle"><span class="postdetails"><?php echo isset($topicrow_item['REPLIES']) ? $topicrow_item['REPLIES'] : ''; ?></span></td>
	  <td class="row3" align="center" valign="middle"><span class="name"><?php echo isset($topicrow_item['TOPIC_AUTHOR']) ? $topicrow_item['TOPIC_AUTHOR'] : ''; ?></span></td>
	  <td class="row2" align="center" valign="middle"><span class="postdetails"><?php echo isset($topicrow_item['VIEWS']) ? $topicrow_item['VIEWS'] : ''; ?></span></td>
	  <td class="row3Right" align="center" valign="middle" nowrap="nowrap"><span class="postdetails"><?php echo isset($topicrow_item['LAST_POST_TIME']) ? $topicrow_item['LAST_POST_TIME'] : ''; ?><br /><?php echo isset($topicrow_item['LAST_POST_AUTHOR']) ? $topicrow_item['LAST_POST_AUTHOR'] : ''; ?> <?php echo isset($topicrow_item['LAST_POST_IMG']) ? $topicrow_item['LAST_POST_IMG'] : ''; ?></span></td>
	</tr>
	<?php

} // END topicrow

if(isset($topicrow_item)) { unset($topicrow_item); } 

?>
	<?php

$switch_no_topics_count = ( isset($this->_tpldata['switch_no_topics.']) ) ?  sizeof($this->_tpldata['switch_no_topics.']) : 0;
for ($switch_no_topics_i = 0; $switch_no_topics_i < $switch_no_topics_count; $switch_no_topics_i++)
{
 $switch_no_topics_item = &$this->_tpldata['switch_no_topics.'][$switch_no_topics_i];
 $switch_no_topics_item['S_ROW_COUNT'] = $switch_no_topics_i;
 $switch_no_topics_item['S_NUM_ROWS'] = $switch_no_topics_count;

?>
	<tr> 
	  <td class="row1" colspan="6" height="30" align="center" valign="middle"><span class="gen"><?php echo isset($this->vars['L_NO_TOPICS']) ? $this->vars['L_NO_TOPICS'] : $this->lang('L_NO_TOPICS'); ?></span></td>
	</tr>
	<?php

} // END switch_no_topics

if(isset($switch_no_topics_item)) { unset($switch_no_topics_item); } 

?>
	<tr> 
	  <td class="catBottom" align="center" valign="middle" colspan="6" height="28"><span class="genmed"><?php echo isset($this->vars['L_DISPLAY_TOPICS']) ? $this->vars['L_DISPLAY_TOPICS'] : $this->lang('L_DISPLAY_TOPICS'); ?>:&nbsp;<?php echo isset($this->vars['S_SELECT_TOPIC_DAYS']) ? $this->vars['S_SELECT_TOPIC_DAYS'] : $this->lang('S_SELECT_TOPIC_DAYS'); ?>&nbsp; 
		<input type="submit" class="liteoption" value="<?php echo isset($this->vars['L_GO']) ? $this->vars['L_GO'] : $this->lang('L_GO'); ?>" name="submit" />
		</span></td>
	</tr>
  </table>

  <table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
	<tr> 
	  <td align="left" valign="middle" width="50"><a href="<?php echo isset($this->vars['U_POST_NEW_TOPIC']) ? $this->vars['U_POST_NEW_TOPIC'] : $this->lang('U_POST_NEW_TOPIC'); ?>"><img src="<?php echo isset($this->vars['POST_IMG']) ? $this->vars['POST_IMG'] : $this->lang('POST_IMG'); ?>" border="0" alt="<?php echo isset($this->vars['L_POST_NEW_TOPIC']) ? $this->vars['L_POST_NEW_TOPIC'] : $this->lang('L_POST_NEW_TOPIC'); ?>" /></a></td>
	  <td align="left" valign="middle" width="100%"><span class="nav">&nbsp;&nbsp;&nbsp;<a href="<?php echo isset($this->vars['U_INDEX']) ? $this->vars['U_INDEX'] : $this->lang('U_INDEX'); ?>" class="nav"><?php echo isset($this->vars['L_INDEX']) ? $this->vars['L_INDEX'] : $this->lang('L_INDEX'); ?></a> -> <a class="nav" href="<?php echo isset($this->vars['U_VIEW_FORUM']) ? $this->vars['U_VIEW_FORUM'] : $this->lang('U_VIEW_FORUM'); ?>"><?php echo isset($this->vars['FORUM_NAME']) ? $this->vars['FORUM_NAME'] : $this->lang('FORUM_NAME'); ?></a></span></td>
	  <td align="right" valign="middle" nowrap="nowrap"><span class="gensmall"><?php echo isset($this->vars['S_TIMEZONE']) ? $this->vars['S_TIMEZONE'] : $this->lang('S_TIMEZONE'); ?></span><br /><span class="nav"><?php echo isset($this->vars['PAGINATION']) ? $this->vars['PAGINATION'] : $this->lang('PAGINATION'); ?></span> 
		</td>
	</tr>
	<tr>
	  <td align="left" colspan="3"><span class="nav"><?php echo isset($this->vars['PAGE_NUMBER']) ? $this->vars['PAGE_NUMBER'] : $this->lang('PAGE_NUMBER'); ?></span></td>
	</tr>
  </table>
</form>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
	<td align="right"><?php echo isset($this->vars['JUMPBOX']) ? $this->vars['JUMPBOX'] : $this->lang('JUMPBOX'); ?></td>
  </tr>
</table>

<table width="100%" cellspacing="0" border="0" align="center" cellpadding="0">
	<tr>
		<td align="left" valign="top"><table cellspacing="3" cellpadding="0" border="0">
			<tr>
				<td width="20" align="left"><img src="<?php echo isset($this->vars['FOLDER_NEW_IMG']) ? $this->vars['FOLDER_NEW_IMG'] : $this->lang('FOLDER_NEW_IMG'); ?>" alt="<?php echo isset($this->vars['L_NEW_POSTS']) ? $this->vars['L_NEW_POSTS'] : $this->lang('L_NEW_POSTS'); ?>" width="19" height="18" /></td>
				<td class="gensmall"><?php echo isset($this->vars['L_NEW_POSTS']) ? $this->vars['L_NEW_POSTS'] : $this->lang('L_NEW_POSTS'); ?></td>
				<td>&nbsp;&nbsp;</td>
				<td width="20" align="center"><img src="<?php echo isset($this->vars['FOLDER_IMG']) ? $this->vars['FOLDER_IMG'] : $this->lang('FOLDER_IMG'); ?>" alt="<?php echo isset($this->vars['L_NO_NEW_POSTS']) ? $this->vars['L_NO_NEW_POSTS'] : $this->lang('L_NO_NEW_POSTS'); ?>" width="19" height="18" /></td>
				<td class="gensmall"><?php echo isset($this->vars['L_NO_NEW_POSTS']) ? $this->vars['L_NO_NEW_POSTS'] : $this->lang('L_NO_NEW_POSTS'); ?></td>
				<td>&nbsp;&nbsp;</td>
				<td width="20" align="center"><img src="<?php echo isset($this->vars['FOLDER_ANNOUNCE_IMG']) ? $this->vars['FOLDER_ANNOUNCE_IMG'] : $this->lang('FOLDER_ANNOUNCE_IMG'); ?>" alt="<?php echo isset($this->vars['L_ANNOUNCEMENT']) ? $this->vars['L_ANNOUNCEMENT'] : $this->lang('L_ANNOUNCEMENT'); ?>" width="19" height="18" /></td>
				<td class="gensmall"><?php echo isset($this->vars['L_ANNOUNCEMENT']) ? $this->vars['L_ANNOUNCEMENT'] : $this->lang('L_ANNOUNCEMENT'); ?></td>
			</tr>
			<tr> 
				<td width="20" align="center"><img src="<?php echo isset($this->vars['FOLDER_HOT_NEW_IMG']) ? $this->vars['FOLDER_HOT_NEW_IMG'] : $this->lang('FOLDER_HOT_NEW_IMG'); ?>" alt="<?php echo isset($this->vars['L_NEW_POSTS_HOT']) ? $this->vars['L_NEW_POSTS_HOT'] : $this->lang('L_NEW_POSTS_HOT'); ?>" width="19" height="18" /></td>
				<td class="gensmall"><?php echo isset($this->vars['L_NEW_POSTS_HOT']) ? $this->vars['L_NEW_POSTS_HOT'] : $this->lang('L_NEW_POSTS_HOT'); ?></td>
				<td>&nbsp;&nbsp;</td>
				<td width="20" align="center"><img src="<?php echo isset($this->vars['FOLDER_HOT_IMG']) ? $this->vars['FOLDER_HOT_IMG'] : $this->lang('FOLDER_HOT_IMG'); ?>" alt="<?php echo isset($this->vars['L_NO_NEW_POSTS_HOT']) ? $this->vars['L_NO_NEW_POSTS_HOT'] : $this->lang('L_NO_NEW_POSTS_HOT'); ?>" width="19" height="18" /></td>
				<td class="gensmall"><?php echo isset($this->vars['L_NO_NEW_POSTS_HOT']) ? $this->vars['L_NO_NEW_POSTS_HOT'] : $this->lang('L_NO_NEW_POSTS_HOT'); ?></td>
				<td>&nbsp;&nbsp;</td>
				<td width="20" align="center"><img src="<?php echo isset($this->vars['FOLDER_STICKY_IMG']) ? $this->vars['FOLDER_STICKY_IMG'] : $this->lang('FOLDER_STICKY_IMG'); ?>" alt="<?php echo isset($this->vars['L_STICKY']) ? $this->vars['L_STICKY'] : $this->lang('L_STICKY'); ?>" width="19" height="18" /></td>
				<td class="gensmall"><?php echo isset($this->vars['L_STICKY']) ? $this->vars['L_STICKY'] : $this->lang('L_STICKY'); ?></td>
			</tr>
			<tr>
				<td class="gensmall"><img src="<?php echo isset($this->vars['FOLDER_LOCKED_NEW_IMG']) ? $this->vars['FOLDER_LOCKED_NEW_IMG'] : $this->lang('FOLDER_LOCKED_NEW_IMG'); ?>" alt="<?php echo isset($this->vars['L_NEW_POSTS_LOCKED']) ? $this->vars['L_NEW_POSTS_LOCKED'] : $this->lang('L_NEW_POSTS_LOCKED'); ?>" width="19" height="18" /></td>
				<td class="gensmall"><?php echo isset($this->vars['L_NEW_POSTS_LOCKED']) ? $this->vars['L_NEW_POSTS_LOCKED'] : $this->lang('L_NEW_POSTS_LOCKED'); ?></td>
				<td>&nbsp;&nbsp;</td>
				<td class="gensmall"><img src="<?php echo isset($this->vars['FOLDER_LOCKED_IMG']) ? $this->vars['FOLDER_LOCKED_IMG'] : $this->lang('FOLDER_LOCKED_IMG'); ?>" alt="<?php echo isset($this->vars['L_NO_NEW_POSTS_LOCKED']) ? $this->vars['L_NO_NEW_POSTS_LOCKED'] : $this->lang('L_NO_NEW_POSTS_LOCKED'); ?>" width="19" height="18" /></td>
				<td class="gensmall"><?php echo isset($this->vars['L_NO_NEW_POSTS_LOCKED']) ? $this->vars['L_NO_NEW_POSTS_LOCKED'] : $this->lang('L_NO_NEW_POSTS_LOCKED'); ?></td>
			</tr>
		</table></td>
		<td align="right"><span class="gensmall"><?php echo isset($this->vars['S_AUTH_LIST']) ? $this->vars['S_AUTH_LIST'] : $this->lang('S_AUTH_LIST'); ?></span></td>
	</tr>
</table>
