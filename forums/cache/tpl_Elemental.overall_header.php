<?php

// eXtreme Styles mod cache. Generated on Sun, 18 Sep 2005 10:30:53 -0700 (time=1127064653)

?><?php include('skinkeeper.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="<?php echo isset($this->vars['S_CONTENT_DIRECTION']) ? $this->vars['S_CONTENT_DIRECTION'] : $this->lang('S_CONTENT_DIRECTION'); ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo isset($this->vars['S_CONTENT_ENCODING']) ? $this->vars['S_CONTENT_ENCODING'] : $this->lang('S_CONTENT_ENCODING'); ?>">
<meta http-equiv="Content-Style-Type" content="text/css">
<?php echo isset($this->vars['META']) ? $this->vars['META'] : $this->lang('META'); ?>
<?php echo isset($this->vars['NAV_LINKS']) ? $this->vars['NAV_LINKS'] : $this->lang('NAV_LINKS'); ?>
<title>FGComics.com :: Forums :: <?php echo isset($this->vars['PAGE_TITLE']) ? $this->vars['PAGE_TITLE'] : $this->lang('PAGE_TITLE'); ?></title>
<script language="Javascript" type="text/javascript">
<!--
<?php

$switch_enable_pm_popup_count = ( isset($this->_tpldata['switch_enable_pm_popup.']) ) ?  sizeof($this->_tpldata['switch_enable_pm_popup.']) : 0;
for ($switch_enable_pm_popup_i = 0; $switch_enable_pm_popup_i < $switch_enable_pm_popup_count; $switch_enable_pm_popup_i++)
{
 $switch_enable_pm_popup_item = &$this->_tpldata['switch_enable_pm_popup.'][$switch_enable_pm_popup_i];
 $switch_enable_pm_popup_item['S_ROW_COUNT'] = $switch_enable_pm_popup_i;
 $switch_enable_pm_popup_item['S_NUM_ROWS'] = $switch_enable_pm_popup_count;

?>
if ( <?php echo isset($this->vars['PRIVATE_MESSAGE_NEW_FLAG']) ? $this->vars['PRIVATE_MESSAGE_NEW_FLAG'] : $this->lang('PRIVATE_MESSAGE_NEW_FLAG'); ?> )
{
	window.open('<?php echo isset($this->vars['U_PRIVATEMSGS_POPUP']) ? $this->vars['U_PRIVATEMSGS_POPUP'] : $this->lang('U_PRIVATEMSGS_POPUP'); ?>', '_phpbbprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400');;
}
<?php

} // END switch_enable_pm_popup

if(isset($switch_enable_pm_popup_item)) { unset($switch_enable_pm_popup_item); } 

?>
function MM_jumpMenu(targ,selObj,restore)
{ 
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head>
<body>
<a name="top"></a>

<!-- BEGIN FGCOMICS TEMPLATE PART 1 -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="4">
		<table width="100%" class="headBackground" border="0" cellpadding="0" cellspacing="0">
      		<tr>
        		<td><div class="logo">&nbsp;</div></td>
      		</tr>
    	</table>
	</td>
  </tr>
  <tr>
  	<td colspan="2" class="barBackground"><img src="images/spacer.gif" width="30" height="30"></td>
    <td colspan="2" class="barBackground">
	<!-- END FGCOMICS TEMPLATE PART 1 -->
	
		<!-- BEGIN FGCOMICS SKINCHANGER -->
			<div align="right">
	 			<select name="skinpref" class="skinchangerText" onChange="skinSubmit('parent',this,0)">
  					<!-- Add new options as necessary... -->
					<option selected>Temporarily Offline</option>
    				<!-- <option value="?skin_id=blue">Blue</option>
    				<option value="?skin_id=red">Red</option>
					<option value="?skin_id=green">Green</option> //-->
					<!-- End options -->
  				</select>
				<img src="images/spacer.gif" width="20" height="10">
			</div>
		<!-- END FGCOMICS SKINCHANGER -->
		
		<!-- BEGIN FGCOMICS TEMPLATE PART 2 -->
	</td>
  </tr>
  <tr>
    <td height="31" colspan="2" class="contentGrad"><div class="layoutCurve">&nbsp;</div></td>
    <td width="100%" align="left" valign="top" class="contentGrad">&nbsp;
    </td>
    <td width="10" rowspan="3" class="contentGrad">&nbsp;</td>
  </tr>  
  <tr>
    <td width="150" valign="top" class="submenuColor">
	<!-- END FGCOMICS TEMPLATE PART 2 -->
	
	<!-- BEGIN FGCOMICS TEMPLATE PART 3 -->
		<div align="center">
			<a href="http://www.fgcomics.com" class="mainlinks_home mainlinks_margin">&nbsp;</a>
			<a href="http://www.fgcomics.com/sagas.php" class="mainlinks_sagas mainlinks_margin"></a>
			<a href="http://www.fgcomics.com/games.php" class="mainlinks_games mainlinks_margin">&nbsp;</a>
			<a href="http://www.fgcomics.com/toons.php" class="mainlinks_toons mainlinks_margin">&nbsp;</a>
			<a href="http://www.fgcomics.com/forums/" class="mainlinks_forums mainlinks_margin">&nbsp;</a>
		</div>
		<div class="menuGroove"><img src="http://www.fgcomics.com/images/spacer.gif" height="10" width="10"></div>
	<!-- END FGCOMICS TEMPLATE PART 3 -->
	
				<table width="100%" border="0">
			  <!-- This table is MINE and it is teh coolest -->
			  <tr>
			  	<td><a href="<?php echo isset($this->vars['U_FAQ']) ? $this->vars['U_FAQ'] : $this->lang('U_FAQ'); ?>" class="mainmenu"><img src="templates/Elemental/images/icon_mini_faq.gif" width="12" height="13" border="0" alt="<?php echo isset($this->vars['L_FAQ']) ? $this->vars['L_FAQ'] : $this->lang('L_FAQ'); ?>" hspace="3" /></a></td>
                <td><a href="<?php echo isset($this->vars['U_FAQ']) ? $this->vars['U_FAQ'] : $this->lang('U_FAQ'); ?>" class="mainmenu"><?php echo isset($this->vars['L_FAQ']) ? $this->vars['L_FAQ'] : $this->lang('L_FAQ'); ?></a></td>
              </tr>
              <tr>
                <td><a href="<?php echo isset($this->vars['U_SEARCH']) ? $this->vars['U_SEARCH'] : $this->lang('U_SEARCH'); ?>" class="mainmenu"><img src="templates/Elemental/images/icon_mini_search.gif" width="12" height="13" border="0" alt="<?php echo isset($this->vars['L_SEARCH']) ? $this->vars['L_SEARCH'] : $this->lang('L_SEARCH'); ?>" hspace="3" /></a></td>
                <td><a href="<?php echo isset($this->vars['U_SEARCH']) ? $this->vars['U_SEARCH'] : $this->lang('U_SEARCH'); ?>" class="mainmenu"><?php echo isset($this->vars['L_SEARCH']) ? $this->vars['L_SEARCH'] : $this->lang('L_SEARCH'); ?></a></td>
              </tr>
              <tr>
                <td><a href="<?php echo isset($this->vars['U_MEMBERLIST']) ? $this->vars['U_MEMBERLIST'] : $this->lang('U_MEMBERLIST'); ?>" class="mainmenu"><img src="templates/Elemental/images/icon_mini_members.gif" width="12" height="13" border="0" alt="<?php echo isset($this->vars['L_MEMBERLIST']) ? $this->vars['L_MEMBERLIST'] : $this->lang('L_MEMBERLIST'); ?>" hspace="3" /></a></td>
                <td><a href="<?php echo isset($this->vars['U_MEMBERLIST']) ? $this->vars['U_MEMBERLIST'] : $this->lang('U_MEMBERLIST'); ?>" class="mainmenu"><?php echo isset($this->vars['L_MEMBERLIST']) ? $this->vars['L_MEMBERLIST'] : $this->lang('L_MEMBERLIST'); ?></a></td>
              </tr>
              <tr>
                <td><a href="<?php echo isset($this->vars['U_GROUP_CP']) ? $this->vars['U_GROUP_CP'] : $this->lang('U_GROUP_CP'); ?>" class="mainmenu"><img src="templates/Elemental/images/icon_mini_groups.gif" width="12" height="13" border="0" alt="<?php echo isset($this->vars['L_USERGROUPS']) ? $this->vars['L_USERGROUPS'] : $this->lang('L_USERGROUPS'); ?>" hspace="3" /></a></td>
                <td><a href="<?php echo isset($this->vars['U_GROUP_CP']) ? $this->vars['U_GROUP_CP'] : $this->lang('U_GROUP_CP'); ?>" class="mainmenu"><?php echo isset($this->vars['L_USERGROUPS']) ? $this->vars['L_USERGROUPS'] : $this->lang('L_USERGROUPS'); ?></a></td>
              </tr>
              <tr>
			  <?php

$switch_user_logged_out_count = ( isset($this->_tpldata['switch_user_logged_out.']) ) ?  sizeof($this->_tpldata['switch_user_logged_out.']) : 0;
for ($switch_user_logged_out_i = 0; $switch_user_logged_out_i < $switch_user_logged_out_count; $switch_user_logged_out_i++)
{
 $switch_user_logged_out_item = &$this->_tpldata['switch_user_logged_out.'][$switch_user_logged_out_i];
 $switch_user_logged_out_item['S_ROW_COUNT'] = $switch_user_logged_out_i;
 $switch_user_logged_out_item['S_NUM_ROWS'] = $switch_user_logged_out_count;

?>
				<td><a href="<?php echo isset($this->vars['U_REGISTER']) ? $this->vars['U_REGISTER'] : $this->lang('U_REGISTER'); ?>" class="mainmenu"><img src="templates/Elemental/images/icon_mini_register.gif" width="12" height="13" border="0" alt="<?php echo isset($this->vars['L_REGISTER']) ? $this->vars['L_REGISTER'] : $this->lang('L_REGISTER'); ?>" hspace="3" /></a></td>
                <td><a href="<?php echo isset($this->vars['U_REGISTER']) ? $this->vars['U_REGISTER'] : $this->lang('U_REGISTER'); ?>" class="mainmenu"><?php echo isset($this->vars['L_REGISTER']) ? $this->vars['L_REGISTER'] : $this->lang('L_REGISTER'); ?></a></td>
			  <?php

} // END switch_user_logged_out

if(isset($switch_user_logged_out_item)) { unset($switch_user_logged_out_item); } 

?>
              </tr>
              <tr>
				<td><a href="<?php echo isset($this->vars['U_PROFILE']) ? $this->vars['U_PROFILE'] : $this->lang('U_PROFILE'); ?>" class="mainmenu"><img src="templates/Elemental/images/icon_mini_profile.gif" width="12" height="13" border="0" alt="<?php echo isset($this->vars['L_PROFILE']) ? $this->vars['L_PROFILE'] : $this->lang('L_PROFILE'); ?>" hspace="3" /></a></td>
                <td><a href="<?php echo isset($this->vars['U_PROFILE']) ? $this->vars['U_PROFILE'] : $this->lang('U_PROFILE'); ?>" class="mainmenu"><?php echo isset($this->vars['L_PROFILE']) ? $this->vars['L_PROFILE'] : $this->lang('L_PROFILE'); ?></a></td>
              </tr>
              <tr>
                <td><a href="<?php echo isset($this->vars['U_PRIVATEMSGS']) ? $this->vars['U_PRIVATEMSGS'] : $this->lang('U_PRIVATEMSGS'); ?>" class="mainmenu_small"><img src="templates/Elemental/images/icon_mini_message.gif" width="12" height="13" border="0" alt="<?php echo isset($this->vars['PRIVATE_MESSAGE_INFO']) ? $this->vars['PRIVATE_MESSAGE_INFO'] : $this->lang('PRIVATE_MESSAGE_INFO'); ?>" hspace="3" /></a></td>
                <td><a href="<?php echo isset($this->vars['U_PRIVATEMSGS']) ? $this->vars['U_PRIVATEMSGS'] : $this->lang('U_PRIVATEMSGS'); ?>" class="mainmenu_small"><?php echo isset($this->vars['PRIVATE_MESSAGE_INFO']) ? $this->vars['PRIVATE_MESSAGE_INFO'] : $this->lang('PRIVATE_MESSAGE_INFO'); ?></a></td>
              </tr>
              <tr>
                <td><a href="<?php echo isset($this->vars['U_LOGIN_LOGOUT']) ? $this->vars['U_LOGIN_LOGOUT'] : $this->lang('U_LOGIN_LOGOUT'); ?>" class="mainmenu"><img src="templates/Elemental/images/icon_mini_login.gif" width="12" height="13" border="0" alt="<?php echo isset($this->vars['L_LOGIN_LOGOUT']) ? $this->vars['L_LOGIN_LOGOUT'] : $this->lang('L_LOGIN_LOGOUT'); ?>" hspace="3" /></a></td>
                <td><a href="<?php echo isset($this->vars['U_LOGIN_LOGOUT']) ? $this->vars['U_LOGIN_LOGOUT'] : $this->lang('U_LOGIN_LOGOUT'); ?>" class="mainmenu"><?php echo isset($this->vars['L_LOGIN_LOGOUT']) ? $this->vars['L_LOGIN_LOGOUT'] : $this->lang('L_LOGIN_LOGOUT'); ?></a></td>
              </tr>
            </table>
	
	<!-- BEGIN FGCOMICS TEMPLATE PART 4 -->
	</td>
    <td width="10">&nbsp;
	</td>
	<td valign="top" class="textDefault">
	<!-- END FGCOMICS TEMPLATE PART 4 -->