<?php

// eXtreme Styles mod cache. Generated on Wed, 10 Aug 2005 19:28:54 -0700 (time=1123727334)

?>
<h1><?php echo isset($this->vars['L_USER_TITLE']) ? $this->vars['L_USER_TITLE'] : $this->lang('L_USER_TITLE'); ?></h1>

<p><?php echo isset($this->vars['L_USER_EXPLAIN']) ? $this->vars['L_USER_EXPLAIN'] : $this->lang('L_USER_EXPLAIN'); ?></p>

<?php echo isset($this->vars['ERROR_BOX']) ? $this->vars['ERROR_BOX'] : $this->lang('ERROR_BOX'); ?>

<form action="<?php echo isset($this->vars['S_PROFILE_ACTION']) ? $this->vars['S_PROFILE_ACTION'] : $this->lang('S_PROFILE_ACTION'); ?>" <?php echo isset($this->vars['S_FORM_ENCTYPE']) ? $this->vars['S_FORM_ENCTYPE'] : $this->lang('S_FORM_ENCTYPE'); ?> method="post"><table width="98%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2"><?php echo isset($this->vars['L_REGISTRATION_INFO']) ? $this->vars['L_REGISTRATION_INFO'] : $this->lang('L_REGISTRATION_INFO'); ?></th>
	</tr>
	<tr> 
	  <td class="row2" colspan="2"><span class="gensmall"><?php echo isset($this->vars['L_ITEMS_REQUIRED']) ? $this->vars['L_ITEMS_REQUIRED'] : $this->lang('L_ITEMS_REQUIRED'); ?></span></td>
	</tr>
	<tr> 
	  <td class="row1" width="38%"><span class="gen"><?php echo isset($this->vars['L_USERNAME']) ? $this->vars['L_USERNAME'] : $this->lang('L_USERNAME'); ?>: *</span></td>
	  <td class="row2"> 
		<input class="post" type="text" name="username" size="35" maxlength="40" value="<?php echo isset($this->vars['USERNAME']) ? $this->vars['USERNAME'] : $this->lang('USERNAME'); ?>" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_EMAIL_ADDRESS']) ? $this->vars['L_EMAIL_ADDRESS'] : $this->lang('L_EMAIL_ADDRESS'); ?>: *</span></td>
	  <td class="row2"> 
		<input class="post" type="text" name="email" size="35" maxlength="255" value="<?php echo isset($this->vars['EMAIL']) ? $this->vars['EMAIL'] : $this->lang('EMAIL'); ?>" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_NEW_PASSWORD']) ? $this->vars['L_NEW_PASSWORD'] : $this->lang('L_NEW_PASSWORD'); ?>: *</span><br />
		<span class="gensmall"><?php echo isset($this->vars['L_PASSWORD_IF_CHANGED']) ? $this->vars['L_PASSWORD_IF_CHANGED'] : $this->lang('L_PASSWORD_IF_CHANGED'); ?></span></td>
	  <td class="row2"> 
		<input class="post" type="password" name="password" size="35" maxlength="32" value="" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_CONFIRM_PASSWORD']) ? $this->vars['L_CONFIRM_PASSWORD'] : $this->lang('L_CONFIRM_PASSWORD'); ?>: * </span><br />
		<span class="gensmall"><?php echo isset($this->vars['L_PASSWORD_CONFIRM_IF_CHANGED']) ? $this->vars['L_PASSWORD_CONFIRM_IF_CHANGED'] : $this->lang('L_PASSWORD_CONFIRM_IF_CHANGED'); ?></span></td>
	  <td class="row2"> 
		<input class="post" type="password" name="password_confirm" size="35" maxlength="32" value="" />
	  </td>
	</tr>
	<tr> 
	  <td class="catsides" colspan="2">&nbsp;</td>
	</tr>
	<tr> 
	  <th class="thSides" colspan="2"><?php echo isset($this->vars['L_PROFILE_INFO']) ? $this->vars['L_PROFILE_INFO'] : $this->lang('L_PROFILE_INFO'); ?></th>
	</tr>
	<tr> 
	  <td class="row2" colspan="2"><span class="gensmall"><?php echo isset($this->vars['L_PROFILE_INFO_NOTICE']) ? $this->vars['L_PROFILE_INFO_NOTICE'] : $this->lang('L_PROFILE_INFO_NOTICE'); ?></span></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_ICQ_NUMBER']) ? $this->vars['L_ICQ_NUMBER'] : $this->lang('L_ICQ_NUMBER'); ?></span></td>
	  <td class="row2"> 
		<input class="post" type="text" name="icq" size="10" maxlength="15" value="<?php echo isset($this->vars['ICQ']) ? $this->vars['ICQ'] : $this->lang('ICQ'); ?>" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_AIM']) ? $this->vars['L_AIM'] : $this->lang('L_AIM'); ?></span></td>
	  <td class="row2"> 
		<input class="post" type="text" name="aim" size="20" maxlength="255" value="<?php echo isset($this->vars['AIM']) ? $this->vars['AIM'] : $this->lang('AIM'); ?>" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_MESSENGER']) ? $this->vars['L_MESSENGER'] : $this->lang('L_MESSENGER'); ?></span></td>
	  <td class="row2"> 
		<input class="post" type="text" name="msn" size="20" maxlength="255" value="<?php echo isset($this->vars['MSN']) ? $this->vars['MSN'] : $this->lang('MSN'); ?>" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_YAHOO']) ? $this->vars['L_YAHOO'] : $this->lang('L_YAHOO'); ?></span></td>
	  <td class="row2"> 
		<input class="post" type="text" name="yim" size="20" maxlength="255" value="<?php echo isset($this->vars['YIM']) ? $this->vars['YIM'] : $this->lang('YIM'); ?>" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_WEBSITE']) ? $this->vars['L_WEBSITE'] : $this->lang('L_WEBSITE'); ?></span></td>
	  <td class="row2"> 
		<input class="post" type="text" name="website" size="35" maxlength="255" value="<?php echo isset($this->vars['WEBSITE']) ? $this->vars['WEBSITE'] : $this->lang('WEBSITE'); ?>" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_LOCATION']) ? $this->vars['L_LOCATION'] : $this->lang('L_LOCATION'); ?></span></td>
	  <td class="row2"> 
		<input class="post" type="text" name="location" size="35" maxlength="100" value="<?php echo isset($this->vars['LOCATION']) ? $this->vars['LOCATION'] : $this->lang('LOCATION'); ?>" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_OCCUPATION']) ? $this->vars['L_OCCUPATION'] : $this->lang('L_OCCUPATION'); ?></span></td>
	  <td class="row2"> 
		<input class="post" type="text" name="occupation" size="35" maxlength="100" value="<?php echo isset($this->vars['OCCUPATION']) ? $this->vars['OCCUPATION'] : $this->lang('OCCUPATION'); ?>" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_INTERESTS']) ? $this->vars['L_INTERESTS'] : $this->lang('L_INTERESTS'); ?></span></td>
	  <td class="row2"> 
		<input class="post" type="text" name="interests" size="35" maxlength="150" value="<?php echo isset($this->vars['INTERESTS']) ? $this->vars['INTERESTS'] : $this->lang('INTERESTS'); ?>" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_SIGNATURE']) ? $this->vars['L_SIGNATURE'] : $this->lang('L_SIGNATURE'); ?></span><br />
		<span class="gensmall"><?php echo isset($this->vars['L_SIGNATURE_EXPLAIN']) ? $this->vars['L_SIGNATURE_EXPLAIN'] : $this->lang('L_SIGNATURE_EXPLAIN'); ?><br />
		<br />
		<?php echo isset($this->vars['HTML_STATUS']) ? $this->vars['HTML_STATUS'] : $this->lang('HTML_STATUS'); ?><br />
		<?php echo isset($this->vars['BBCODE_STATUS']) ? $this->vars['BBCODE_STATUS'] : $this->lang('BBCODE_STATUS'); ?><br />
		<?php echo isset($this->vars['SMILIES_STATUS']) ? $this->vars['SMILIES_STATUS'] : $this->lang('SMILIES_STATUS'); ?></span></td>
	  <td class="row2"> 
		<textarea class="post" name="signature" rows="6" cols="45"><?php echo isset($this->vars['SIGNATURE']) ? $this->vars['SIGNATURE'] : $this->lang('SIGNATURE'); ?></textarea>
	  </td>
	</tr>
	<tr> 
	  <td class="catsides" colspan="2"><span class="cattitle">&nbsp;</span></td>
	</tr>
	<tr> 
	  <th class="thSides" colspan="2"><?php echo isset($this->vars['L_PREFERENCES']) ? $this->vars['L_PREFERENCES'] : $this->lang('L_PREFERENCES'); ?></th>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_PUBLIC_VIEW_EMAIL']) ? $this->vars['L_PUBLIC_VIEW_EMAIL'] : $this->lang('L_PUBLIC_VIEW_EMAIL'); ?></span></td>
	  <td class="row2"> 
		<input type="radio" name="viewemail" value="1" <?php echo isset($this->vars['VIEW_EMAIL_YES']) ? $this->vars['VIEW_EMAIL_YES'] : $this->lang('VIEW_EMAIL_YES'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_YES']) ? $this->vars['L_YES'] : $this->lang('L_YES'); ?></span>&nbsp;&nbsp; 
		<input type="radio" name="viewemail" value="0" <?php echo isset($this->vars['VIEW_EMAIL_NO']) ? $this->vars['VIEW_EMAIL_NO'] : $this->lang('VIEW_EMAIL_NO'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_NO']) ? $this->vars['L_NO'] : $this->lang('L_NO'); ?></span></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_HIDE_USER']) ? $this->vars['L_HIDE_USER'] : $this->lang('L_HIDE_USER'); ?></span></td>
	  <td class="row2"> 
		<input type="radio" name="hideonline" value="1" <?php echo isset($this->vars['HIDE_USER_YES']) ? $this->vars['HIDE_USER_YES'] : $this->lang('HIDE_USER_YES'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_YES']) ? $this->vars['L_YES'] : $this->lang('L_YES'); ?></span>&nbsp;&nbsp; 
		<input type="radio" name="hideonline" value="0" <?php echo isset($this->vars['HIDE_USER_NO']) ? $this->vars['HIDE_USER_NO'] : $this->lang('HIDE_USER_NO'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_NO']) ? $this->vars['L_NO'] : $this->lang('L_NO'); ?></span></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_NOTIFY_ON_REPLY']) ? $this->vars['L_NOTIFY_ON_REPLY'] : $this->lang('L_NOTIFY_ON_REPLY'); ?></span></td>
	  <td class="row2"> 
		<input type="radio" name="notifyreply" value="1" <?php echo isset($this->vars['NOTIFY_REPLY_YES']) ? $this->vars['NOTIFY_REPLY_YES'] : $this->lang('NOTIFY_REPLY_YES'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_YES']) ? $this->vars['L_YES'] : $this->lang('L_YES'); ?></span>&nbsp;&nbsp; 
		<input type="radio" name="notifyreply" value="0" <?php echo isset($this->vars['NOTIFY_REPLY_NO']) ? $this->vars['NOTIFY_REPLY_NO'] : $this->lang('NOTIFY_REPLY_NO'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_NO']) ? $this->vars['L_NO'] : $this->lang('L_NO'); ?></span></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_NOTIFY_ON_PRIVMSG']) ? $this->vars['L_NOTIFY_ON_PRIVMSG'] : $this->lang('L_NOTIFY_ON_PRIVMSG'); ?></span></td>
	  <td class="row2"> 
		<input type="radio" name="notifypm" value="1" <?php echo isset($this->vars['NOTIFY_PM_YES']) ? $this->vars['NOTIFY_PM_YES'] : $this->lang('NOTIFY_PM_YES'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_YES']) ? $this->vars['L_YES'] : $this->lang('L_YES'); ?></span>&nbsp;&nbsp; 
		<input type="radio" name="notifypm" value="0" <?php echo isset($this->vars['NOTIFY_PM_NO']) ? $this->vars['NOTIFY_PM_NO'] : $this->lang('NOTIFY_PM_NO'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_NO']) ? $this->vars['L_NO'] : $this->lang('L_NO'); ?></span></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_POPUP_ON_PRIVMSG']) ? $this->vars['L_POPUP_ON_PRIVMSG'] : $this->lang('L_POPUP_ON_PRIVMSG'); ?></span></td>
	  <td class="row2"> 
		<input type="radio" name="popup_pm" value="1" <?php echo isset($this->vars['POPUP_PM_YES']) ? $this->vars['POPUP_PM_YES'] : $this->lang('POPUP_PM_YES'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_YES']) ? $this->vars['L_YES'] : $this->lang('L_YES'); ?></span>&nbsp;&nbsp; 
		<input type="radio" name="popup_pm" value="0" <?php echo isset($this->vars['POPUP_PM_NO']) ? $this->vars['POPUP_PM_NO'] : $this->lang('POPUP_PM_NO'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_NO']) ? $this->vars['L_NO'] : $this->lang('L_NO'); ?></span></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_ALWAYS_ADD_SIGNATURE']) ? $this->vars['L_ALWAYS_ADD_SIGNATURE'] : $this->lang('L_ALWAYS_ADD_SIGNATURE'); ?></span></td>
	  <td class="row2"> 
		<input type="radio" name="attachsig" value="1" <?php echo isset($this->vars['ALWAYS_ADD_SIGNATURE_YES']) ? $this->vars['ALWAYS_ADD_SIGNATURE_YES'] : $this->lang('ALWAYS_ADD_SIGNATURE_YES'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_YES']) ? $this->vars['L_YES'] : $this->lang('L_YES'); ?></span>&nbsp;&nbsp; 
		<input type="radio" name="attachsig" value="0" <?php echo isset($this->vars['ALWAYS_ADD_SIGNATURE_NO']) ? $this->vars['ALWAYS_ADD_SIGNATURE_NO'] : $this->lang('ALWAYS_ADD_SIGNATURE_NO'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_NO']) ? $this->vars['L_NO'] : $this->lang('L_NO'); ?></span></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_ALWAYS_ALLOW_BBCODE']) ? $this->vars['L_ALWAYS_ALLOW_BBCODE'] : $this->lang('L_ALWAYS_ALLOW_BBCODE'); ?></span></td>
	  <td class="row2"> 
		<input type="radio" name="allowbbcode" value="1" <?php echo isset($this->vars['ALWAYS_ALLOW_BBCODE_YES']) ? $this->vars['ALWAYS_ALLOW_BBCODE_YES'] : $this->lang('ALWAYS_ALLOW_BBCODE_YES'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_YES']) ? $this->vars['L_YES'] : $this->lang('L_YES'); ?></span>&nbsp;&nbsp; 
		<input type="radio" name="allowbbcode" value="0" <?php echo isset($this->vars['ALWAYS_ALLOW_BBCODE_NO']) ? $this->vars['ALWAYS_ALLOW_BBCODE_NO'] : $this->lang('ALWAYS_ALLOW_BBCODE_NO'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_NO']) ? $this->vars['L_NO'] : $this->lang('L_NO'); ?></span></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_ALWAYS_ALLOW_HTML']) ? $this->vars['L_ALWAYS_ALLOW_HTML'] : $this->lang('L_ALWAYS_ALLOW_HTML'); ?></span></td>
	  <td class="row2"> 
		<input type="radio" name="allowhtml" value="1" <?php echo isset($this->vars['ALWAYS_ALLOW_HTML_YES']) ? $this->vars['ALWAYS_ALLOW_HTML_YES'] : $this->lang('ALWAYS_ALLOW_HTML_YES'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_YES']) ? $this->vars['L_YES'] : $this->lang('L_YES'); ?></span>&nbsp;&nbsp; 
		<input type="radio" name="allowhtml" value="0" <?php echo isset($this->vars['ALWAYS_ALLOW_HTML_NO']) ? $this->vars['ALWAYS_ALLOW_HTML_NO'] : $this->lang('ALWAYS_ALLOW_HTML_NO'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_NO']) ? $this->vars['L_NO'] : $this->lang('L_NO'); ?></span></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_ALWAYS_ALLOW_SMILIES']) ? $this->vars['L_ALWAYS_ALLOW_SMILIES'] : $this->lang('L_ALWAYS_ALLOW_SMILIES'); ?></span></td>
	  <td class="row2"> 
		<input type="radio" name="allowsmilies" value="1" <?php echo isset($this->vars['ALWAYS_ALLOW_SMILIES_YES']) ? $this->vars['ALWAYS_ALLOW_SMILIES_YES'] : $this->lang('ALWAYS_ALLOW_SMILIES_YES'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_YES']) ? $this->vars['L_YES'] : $this->lang('L_YES'); ?></span>&nbsp;&nbsp; 
		<input type="radio" name="allowsmilies" value="0" <?php echo isset($this->vars['ALWAYS_ALLOW_SMILIES_NO']) ? $this->vars['ALWAYS_ALLOW_SMILIES_NO'] : $this->lang('ALWAYS_ALLOW_SMILIES_NO'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_NO']) ? $this->vars['L_NO'] : $this->lang('L_NO'); ?></span></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_BOARD_LANGUAGE']) ? $this->vars['L_BOARD_LANGUAGE'] : $this->lang('L_BOARD_LANGUAGE'); ?></span></td>
	  <td class="row2"><?php echo isset($this->vars['LANGUAGE_SELECT']) ? $this->vars['LANGUAGE_SELECT'] : $this->lang('LANGUAGE_SELECT'); ?></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_BOARD_STYLE']) ? $this->vars['L_BOARD_STYLE'] : $this->lang('L_BOARD_STYLE'); ?></span></td>
	  <td class="row2"><?php echo isset($this->vars['STYLE_SELECT']) ? $this->vars['STYLE_SELECT'] : $this->lang('STYLE_SELECT'); ?></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_TIMEZONE']) ? $this->vars['L_TIMEZONE'] : $this->lang('L_TIMEZONE'); ?></span></td>
	  <td class="row2"><?php echo isset($this->vars['TIMEZONE_SELECT']) ? $this->vars['TIMEZONE_SELECT'] : $this->lang('TIMEZONE_SELECT'); ?></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_DATE_FORMAT']) ? $this->vars['L_DATE_FORMAT'] : $this->lang('L_DATE_FORMAT'); ?></span><br />
		<span class="gensmall"><?php echo isset($this->vars['L_DATE_FORMAT_EXPLAIN']) ? $this->vars['L_DATE_FORMAT_EXPLAIN'] : $this->lang('L_DATE_FORMAT_EXPLAIN'); ?></span></td>
	  <td class="row2"> 
		<input class="post" type="text" name="dateformat" value="<?php echo isset($this->vars['DATE_FORMAT']) ? $this->vars['DATE_FORMAT'] : $this->lang('DATE_FORMAT'); ?>" maxlength="16" />
	  </td>
	</tr>
	<tr> 
	  <td class="catSides" colspan="2"><span class="cattitle">&nbsp;</span></td>
	</tr>
	<tr> 
	  <th class="thSides" colspan="2" height="12" valign="middle"><?php echo isset($this->vars['L_AVATAR_PANEL']) ? $this->vars['L_AVATAR_PANEL'] : $this->lang('L_AVATAR_PANEL'); ?></th>
	</tr>
	<tr align="center"> 
	  <td class="row1" colspan="2"> 
		<table width="70%" cellspacing="2" cellpadding="0" border="0">
		  <tr> 
			<td width="65%"><span class="gensmall"><?php echo isset($this->vars['L_AVATAR_EXPLAIN']) ? $this->vars['L_AVATAR_EXPLAIN'] : $this->lang('L_AVATAR_EXPLAIN'); ?></span></td>
			<td align="center"><span class="gensmall"><?php echo isset($this->vars['L_CURRENT_IMAGE']) ? $this->vars['L_CURRENT_IMAGE'] : $this->lang('L_CURRENT_IMAGE'); ?></span><br />
			  <?php echo isset($this->vars['AVATAR']) ? $this->vars['AVATAR'] : $this->lang('AVATAR'); ?><br />
			  <input type="checkbox" name="avatardel" />
			  &nbsp;<span class="gensmall"><?php echo isset($this->vars['L_DELETE_AVATAR']) ? $this->vars['L_DELETE_AVATAR'] : $this->lang('L_DELETE_AVATAR'); ?></span></td>
		  </tr>
		</table>
	  </td>
	</tr>

	<?php

$avatar_local_upload_count = ( isset($this->_tpldata['avatar_local_upload.']) ) ?  sizeof($this->_tpldata['avatar_local_upload.']) : 0;
for ($avatar_local_upload_i = 0; $avatar_local_upload_i < $avatar_local_upload_count; $avatar_local_upload_i++)
{
 $avatar_local_upload_item = &$this->_tpldata['avatar_local_upload.'][$avatar_local_upload_i];
 $avatar_local_upload_item['S_ROW_COUNT'] = $avatar_local_upload_i;
 $avatar_local_upload_item['S_NUM_ROWS'] = $avatar_local_upload_count;

?>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_UPLOAD_AVATAR_FILE']) ? $this->vars['L_UPLOAD_AVATAR_FILE'] : $this->lang('L_UPLOAD_AVATAR_FILE'); ?></span></td>
	  <td class="row2"> 
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo isset($this->vars['AVATAR_SIZE']) ? $this->vars['AVATAR_SIZE'] : $this->lang('AVATAR_SIZE'); ?>" />
		<input type="file" name="avatar" class="post" style="width: 200px"  />
	  </td>
	</tr>
	<?php

} // END avatar_local_upload

if(isset($avatar_local_upload_item)) { unset($avatar_local_upload_item); } 

?>
	<?php

$avatar_remote_upload_count = ( isset($this->_tpldata['avatar_remote_upload.']) ) ?  sizeof($this->_tpldata['avatar_remote_upload.']) : 0;
for ($avatar_remote_upload_i = 0; $avatar_remote_upload_i < $avatar_remote_upload_count; $avatar_remote_upload_i++)
{
 $avatar_remote_upload_item = &$this->_tpldata['avatar_remote_upload.'][$avatar_remote_upload_i];
 $avatar_remote_upload_item['S_ROW_COUNT'] = $avatar_remote_upload_i;
 $avatar_remote_upload_item['S_NUM_ROWS'] = $avatar_remote_upload_count;

?>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_UPLOAD_AVATAR_URL']) ? $this->vars['L_UPLOAD_AVATAR_URL'] : $this->lang('L_UPLOAD_AVATAR_URL'); ?></span></td>
	  <td class="row2"> 
		<input class="post" type="text" name="avatarurl" size="40" class="post" style="width: 200px"  />
	  </td>
	</tr>
	<?php

} // END avatar_remote_upload

if(isset($avatar_remote_upload_item)) { unset($avatar_remote_upload_item); } 

?>
	<?php

$avatar_remote_link_count = ( isset($this->_tpldata['avatar_remote_link.']) ) ?  sizeof($this->_tpldata['avatar_remote_link.']) : 0;
for ($avatar_remote_link_i = 0; $avatar_remote_link_i < $avatar_remote_link_count; $avatar_remote_link_i++)
{
 $avatar_remote_link_item = &$this->_tpldata['avatar_remote_link.'][$avatar_remote_link_i];
 $avatar_remote_link_item['S_ROW_COUNT'] = $avatar_remote_link_i;
 $avatar_remote_link_item['S_NUM_ROWS'] = $avatar_remote_link_count;

?>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_LINK_REMOTE_AVATAR']) ? $this->vars['L_LINK_REMOTE_AVATAR'] : $this->lang('L_LINK_REMOTE_AVATAR'); ?></span></td>
	  <td class="row2"> 
		<input class="post" type="text" name="avatarremoteurl" size="40" class="post" style="width: 200px"  />
	  </td>
	</tr>
	<?php

} // END avatar_remote_link

if(isset($avatar_remote_link_item)) { unset($avatar_remote_link_item); } 

?>
	<?php

$avatar_local_gallery_count = ( isset($this->_tpldata['avatar_local_gallery.']) ) ?  sizeof($this->_tpldata['avatar_local_gallery.']) : 0;
for ($avatar_local_gallery_i = 0; $avatar_local_gallery_i < $avatar_local_gallery_count; $avatar_local_gallery_i++)
{
 $avatar_local_gallery_item = &$this->_tpldata['avatar_local_gallery.'][$avatar_local_gallery_i];
 $avatar_local_gallery_item['S_ROW_COUNT'] = $avatar_local_gallery_i;
 $avatar_local_gallery_item['S_NUM_ROWS'] = $avatar_local_gallery_count;

?>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_AVATAR_GALLERY']) ? $this->vars['L_AVATAR_GALLERY'] : $this->lang('L_AVATAR_GALLERY'); ?></span></td>
	  <td class="row2"> 
		<input type="submit" name="avatargallery" value="<?php echo isset($this->vars['L_SHOW_GALLERY']) ? $this->vars['L_SHOW_GALLERY'] : $this->lang('L_SHOW_GALLERY'); ?>" class="liteoption" />
	  </td>
	</tr>
	<?php

} // END avatar_local_gallery

if(isset($avatar_local_gallery_item)) { unset($avatar_local_gallery_item); } 

?>

	<tr> 
	  <td class="catSides" colspan="2">&nbsp;</td>
	</tr>
	<tr>
	  <th class="thSides" colspan="2"><?php echo isset($this->vars['L_SPECIAL']) ? $this->vars['L_SPECIAL'] : $this->lang('L_SPECIAL'); ?></th>
	</tr>
	<tr>
	  <td class="row1" colspan="2"><span class="gensmall"><?php echo isset($this->vars['L_SPECIAL_EXPLAIN']) ? $this->vars['L_SPECIAL_EXPLAIN'] : $this->lang('L_SPECIAL_EXPLAIN'); ?></span></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_USER_ACTIVE']) ? $this->vars['L_USER_ACTIVE'] : $this->lang('L_USER_ACTIVE'); ?></span></td>
	  <td class="row2"> 
		<input type="radio" name="user_status" value="1" <?php echo isset($this->vars['USER_ACTIVE_YES']) ? $this->vars['USER_ACTIVE_YES'] : $this->lang('USER_ACTIVE_YES'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_YES']) ? $this->vars['L_YES'] : $this->lang('L_YES'); ?></span>&nbsp;&nbsp; 
		<input type="radio" name="user_status" value="0" <?php echo isset($this->vars['USER_ACTIVE_NO']) ? $this->vars['USER_ACTIVE_NO'] : $this->lang('USER_ACTIVE_NO'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_NO']) ? $this->vars['L_NO'] : $this->lang('L_NO'); ?></span></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_ALLOW_PM']) ? $this->vars['L_ALLOW_PM'] : $this->lang('L_ALLOW_PM'); ?></span></td>
	  <td class="row2"> 
		<input type="radio" name="user_allowpm" value="1" <?php echo isset($this->vars['ALLOW_PM_YES']) ? $this->vars['ALLOW_PM_YES'] : $this->lang('ALLOW_PM_YES'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_YES']) ? $this->vars['L_YES'] : $this->lang('L_YES'); ?></span>&nbsp;&nbsp; 
		<input type="radio" name="user_allowpm" value="0" <?php echo isset($this->vars['ALLOW_PM_NO']) ? $this->vars['ALLOW_PM_NO'] : $this->lang('ALLOW_PM_NO'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_NO']) ? $this->vars['L_NO'] : $this->lang('L_NO'); ?></span></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_ALLOW_AVATAR']) ? $this->vars['L_ALLOW_AVATAR'] : $this->lang('L_ALLOW_AVATAR'); ?></span></td>
	  <td class="row2"> 
		<input type="radio" name="user_allowavatar" value="1" <?php echo isset($this->vars['ALLOW_AVATAR_YES']) ? $this->vars['ALLOW_AVATAR_YES'] : $this->lang('ALLOW_AVATAR_YES'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_YES']) ? $this->vars['L_YES'] : $this->lang('L_YES'); ?></span>&nbsp;&nbsp; 
		<input type="radio" name="user_allowavatar" value="0" <?php echo isset($this->vars['ALLOW_AVATAR_NO']) ? $this->vars['ALLOW_AVATAR_NO'] : $this->lang('ALLOW_AVATAR_NO'); ?> />
		<span class="gen"><?php echo isset($this->vars['L_NO']) ? $this->vars['L_NO'] : $this->lang('L_NO'); ?></span></td>
	</tr>
	<tr>
		<td class="row1"><span class="gen"><?php echo isset($this->vars['L_SELECT_RANK']) ? $this->vars['L_SELECT_RANK'] : $this->lang('L_SELECT_RANK'); ?></span></td>
		<td class="row2"><select name="user_rank"><?php echo isset($this->vars['RANK_SELECT_BOX']) ? $this->vars['RANK_SELECT_BOX'] : $this->lang('RANK_SELECT_BOX'); ?></select></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_DELETE_USER']) ? $this->vars['L_DELETE_USER'] : $this->lang('L_DELETE_USER'); ?>?</span></td>
	  <td class="row2"> 
		<input type="checkbox" name="deleteuser">
		<?php echo isset($this->vars['L_DELETE_USER_EXPLAIN']) ? $this->vars['L_DELETE_USER_EXPLAIN'] : $this->lang('L_DELETE_USER_EXPLAIN'); ?></td>
	</tr>
	<tr> 
	  <td class="catBottom" colspan="2" align="center"><?php echo isset($this->vars['S_HIDDEN_FIELDS']) ? $this->vars['S_HIDDEN_FIELDS'] : $this->lang('S_HIDDEN_FIELDS'); ?> 
		<input type="submit" name="submit" value="<?php echo isset($this->vars['L_SUBMIT']) ? $this->vars['L_SUBMIT'] : $this->lang('L_SUBMIT'); ?>" class="mainoption" />
		&nbsp;&nbsp; 
		<input type="reset" value="<?php echo isset($this->vars['L_RESET']) ? $this->vars['L_RESET'] : $this->lang('L_RESET'); ?>" class="liteoption" />
	  </td>
	</tr>
</table></form>
