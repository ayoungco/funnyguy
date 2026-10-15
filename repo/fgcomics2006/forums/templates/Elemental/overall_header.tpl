<?php include('skinkeeper.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="{S_CONTENT_DIRECTION}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
<meta http-equiv="Content-Style-Type" content="text/css">
{META}
{NAV_LINKS}
<title>FGComics.com :: Forums :: {PAGE_TITLE}</title>
<script language="Javascript" type="text/javascript">
<!--
<!-- BEGIN switch_enable_pm_popup -->
if ( {PRIVATE_MESSAGE_NEW_FLAG} )
{
	window.open('{U_PRIVATEMSGS_POPUP}', '_phpbbprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400');;
}
<!-- END switch_enable_pm_popup -->
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
			  	<td><a href="{U_FAQ}" class="mainmenu"><img src="templates/Elemental/images/icon_mini_faq.gif" width="12" height="13" border="0" alt="{L_FAQ}" hspace="3" /></a></td>
                <td><a href="{U_FAQ}" class="mainmenu">{L_FAQ}</a></td>
              </tr>
              <tr>
                <td><a href="{U_SEARCH}" class="mainmenu"><img src="templates/Elemental/images/icon_mini_search.gif" width="12" height="13" border="0" alt="{L_SEARCH}" hspace="3" /></a></td>
                <td><a href="{U_SEARCH}" class="mainmenu">{L_SEARCH}</a></td>
              </tr>
              <tr>
                <td><a href="{U_MEMBERLIST}" class="mainmenu"><img src="templates/Elemental/images/icon_mini_members.gif" width="12" height="13" border="0" alt="{L_MEMBERLIST}" hspace="3" /></a></td>
                <td><a href="{U_MEMBERLIST}" class="mainmenu">{L_MEMBERLIST}</a></td>
              </tr>
              <tr>
                <td><a href="{U_GROUP_CP}" class="mainmenu"><img src="templates/Elemental/images/icon_mini_groups.gif" width="12" height="13" border="0" alt="{L_USERGROUPS}" hspace="3" /></a></td>
                <td><a href="{U_GROUP_CP}" class="mainmenu">{L_USERGROUPS}</a></td>
              </tr>
              <tr>
			  <!-- BEGIN switch_user_logged_out -->
				<td><a href="{U_REGISTER}" class="mainmenu"><img src="templates/Elemental/images/icon_mini_register.gif" width="12" height="13" border="0" alt="{L_REGISTER}" hspace="3" /></a></td>
                <td><a href="{U_REGISTER}" class="mainmenu">{L_REGISTER}</a></td>
			  <!-- END switch_user_logged_out -->
              </tr>
              <tr>
				<td><a href="{U_PROFILE}" class="mainmenu"><img src="templates/Elemental/images/icon_mini_profile.gif" width="12" height="13" border="0" alt="{L_PROFILE}" hspace="3" /></a></td>
                <td><a href="{U_PROFILE}" class="mainmenu">{L_PROFILE}</a></td>
              </tr>
              <tr>
                <td><a href="{U_PRIVATEMSGS}" class="mainmenu_small"><img src="templates/Elemental/images/icon_mini_message.gif" width="12" height="13" border="0" alt="{PRIVATE_MESSAGE_INFO}" hspace="3" /></a></td>
                <td><a href="{U_PRIVATEMSGS}" class="mainmenu_small">{PRIVATE_MESSAGE_INFO}</a></td>
              </tr>
              <tr>
                <td><a href="{U_LOGIN_LOGOUT}" class="mainmenu"><img src="templates/Elemental/images/icon_mini_login.gif" width="12" height="13" border="0" alt="{L_LOGIN_LOGOUT}" hspace="3" /></a></td>
                <td><a href="{U_LOGIN_LOGOUT}" class="mainmenu">{L_LOGIN_LOGOUT}</a></td>
              </tr>
            </table>
	
	<!-- BEGIN FGCOMICS TEMPLATE PART 4 -->
	</td>
    <td width="10">&nbsp;
	</td>
	<td valign="top" class="textDefault">
	<!-- END FGCOMICS TEMPLATE PART 4 -->