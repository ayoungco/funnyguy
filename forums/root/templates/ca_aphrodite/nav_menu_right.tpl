<!-- BEGIN rightmenublock -->
<table width="100%" cellspacing="0" cellpadding="0" class="forumline">
<tr>
	<td class="row-header"><span><a href="javascript:ShowHide('blk_{rightmenublock.BLOCK_ID}','blk2_{rightmenublock.BLOCK_ID}','block_{rightmenublock.BLOCK_ID}');" onmouseover="showStatus('blk_{rightmenublock.BLOCK_ID}'); return true;" onmouseout="window.status=' '; return true;">{rightmenublock.L_BLOCK_TITLE}</a></span></td>
</tr>
<tr><td>
<div id="blk_{rightmenublock.BLOCK_ID}" style="display: ''; position: relative;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<!-- BEGIN menurow -->
<!-- BEGIN switch_has_subtitle -->
<tr><th>{rightmenublock.menurow.L_SUBTITLE}</th></tr>
<!-- END switch_has_subtitle -->
<tr><td valign="top" colspan="3" class="row">
	<table width="100%" cellspacing="1" cellpadding="1" border="0">
	<!-- BEGIN linkrow -->
	<tr valign="top">
	<?php
	if(!empty($linkrow_item['BULLET']))
	{
	?>
	<td align="center" valign="middle" width="11"><img src="{rightmenublock.menurow.linkrow.BULLET}" width="11" height="11" alt="" border="0"></td>
	<?php
	}
	else
	{
	?>
	<td align="center" valign="middle" width="11">&nbsp;</td>
	<?php
	}
	?>
	<td align="left" class="gensmall"><a href="{rightmenublock.menurow.linkrow.U_LINK}" target="{rightmenublock.menurow.linkrow.U_TARGET}">{rightmenublock.menurow.linkrow.L_LINK}</a><td>
	<?php
	if(!empty($linkrow_item['L_VER']))
	{
	?>
	<td align="left" class="gensmall">{rightmenublock.menurow.linkrow.L_VER}<td>
	<?php
	}
	?>
	</tr>
	<!-- END linkrow -->
	</table>
</td>
</tr>
<!-- END menurow -->

<!-- BEGIN last_topic -->
<tr><th>{rightmenublock.last_topic.L_SUBTITLE}</th></tr>
<tr><td class="row">
<marquee id="recent_topics" behavior="scroll" direction="{rightmenublock.last_topic.SCROLL_DIR}" height="{rightmenublock.last_topic.SCROLL_HEIGHT}" scrolldelay="{rightmenublock.last_topic.SCROLL_DELAY}" scrollamount="{rightmenublock.last_topic.SCROLL_AMOUNT}" onmouseover="document.all.recent_topics.stop()" onmouseout="document.all.recent_topics.start()">
<table cellpadding="3" cellspacing="2" border="0">  
<!-- BEGIN topic_row -->
<tr><td class="row"><a href="{rightmenublock.last_topic.topic_row.U_TOPIC_LINK}" title="{rightmenublock.last_topic.topic_row.L_TOPIC_TITLE}">{rightmenublock.last_topic.topic_row.L_TOPIC_LINK_TEXT}</a></td></tr>
<!-- END topic_row -->
</table></marquee>
</td></tr>
<!-- END last_topic -->

<!-- BEGIN switch_google_adsense -->
<tr><td class="row" align="center">
<script type="text/javascript"><!--  
   google_ad_client = "{rightmenublock.GOOGLE_AD_CLIENT}";  
   google_ad_width = {rightmenublock.GOOGLE_AD_WIDTH};  
   google_ad_height = {rightmenublock.GOOGLE_AD_HEIGHT};  
   google_ad_format = "{rightmenublock.GOOGLE_AD_FORMAT}";  
   google_ad_type = "{rightmenublock.GOOGLE_AD_TYPE}";
   google_ad_channel = "{rightmenublock.GOOGLE_AD_CHANNEL}";
<!-- BEGIN switch_url -->
   google_alternate_ad_url = "{rightmenublock.GOOGLE_ALTERNATE_URL}";
<!-- END switch_url -->
<!-- BEGIN switch_colour -->
   google_alternate_color = "{rightmenublock.GOOGLE_ALTERNATE_COLOUR}";
<!-- END switch_colour -->
   google_color_border = "{rightmenublock.GOOGLE_COLOUR_BORDER}";  
   google_color_bg = "{rightmenublock.GOOGLE_COLOUR_BG}";  
   google_color_link = "{rightmenublock.GOOGLE_COLOUR_LINK}";  
   google_color_url = "{rightmenublock.GOOGLE_COLOUR_URL}";  
   google_color_text = "{rightmenublock.GOOGLE_COLOUR_TEXT}";  
//--></script>  
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</td></tr>
<!-- END switch_google_adsense -->

<!-- BEGIN switch_forumgoogle -->
<tr><th>{rightmenublock.L_FORUM}</th></tr>
<tr><td class="row" align="center">
<!-- Quick Search -->
<form action="search.php?mode=results" method="post" target="_self">
<table width="100%" cellspacing="2" cellpadding="1" border="0">
<!-- BEGIN switch_forum_select_on -->
<tr><td align="left" valign="middle" class="row1">
<select name="search_forum" size="1" class="gensmall">
	<option value="-1">{rightmenublock.L_SEARCH_ALL}</option>
	<option value="{rightmenublock.FORUM_ID}" selected="selected">{rightmenublock.L_SEARCH_THIS}</option>
</select>
</td></tr>
<!-- END switch_forum_select_on -->
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
<!-- BEGIN switch_forum_select_off -->
<input type="hidden" name="search_forum" value="{rightmenublock.FORUM_ID}">
<!-- END switch_forum_select_off -->
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
<tr><th>{rightmenublock.L_GOOGLE}</th></tr>
<tr><td class="row" align="center">
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
<!-- END switch_forumgoogle -->

<!-- BEGIN switch_google -->
<tr><td class="row" align="center">
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
<!-- END switch_google -->

<!-- BEGIN switch_searchforum -->
<tr><td class="row" align="center">
<!-- Quick Search -->
<form action="search.php?mode=results" method="post" target="_self">
<table width="100%" cellspacing="2" cellpadding="1" border="0">
<!-- BEGIN switch_forum_select_on -->
<tr><td align="left" valign="middle" class="row1">
<select name="search_forum" size="1" class="gensmall">
	<option value="-1">{rightmenublock.L_SEARCH_ALL}</option>
	<option value="{rightmenublock.FORUM_ID}" selected="selected">{rightmenublock.L_SEARCH_THIS}</option>
</select>
</td></tr>
<!-- END switch_forum_select_on -->
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
<!-- BEGIN switch_forum_select_off -->
<input type="hidden" name="search_forum" value="{rightmenublock.FORUM_ID}">
<!-- END switch_forum_select_off -->
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
<!-- END switch_searchforum -->

<!-- BEGIN switch_whoisonline -->
<tr><td colspan="3" class="row" align="center">
<!-- Who's Online -->                  
<table width="100%" cellpadding="2" cellspacing="1" border="0">  
<tr><td align="center">
	<span class="gensmall">[ {L_WHOSONLINE_ADMIN} ]<br />[ {L_WHOSONLINE_MOD} ]</span>
	<hr>
	<span class="gensmall">{LOGGED_IN_USER_LIST}</span>
</td></tr>  
</table>  
<br />  
<!-- Who's Online -->
</td></tr>
<!-- END switch_whoisonline -->

<!-- BEGIN switch_footer_display -->
<tr><td colspan="3" class="row-post"><span class="gensmall">{rightmenublock.L_BLOCK_FOOTER}<span></td></tr>
<!-- END switch_footer_display -->
</table></div><div id="blk2_{rightmenublock.BLOCK_ID}" style="display: none; position: relative;"><table width="100%" cellpadding="0" cellspacing="1" border="0" class="forumline"><tr><td class="spacerow" width="100%" height="3" bgcolor="#888888"><img src="{T_TEMPLATE_PATH}/images/spacer.gif" width="1" height="3" border="0" alt="" /></td></tr></table></div>
</td></tr>
</table>
<script language="javascript" type="text/javascript">
<!--
tmp = 'block_{rightmenublock.BLOCK_ID}';
if(GetCookie(tmp) == '2')
{
	ShowHide('blk_{rightmenublock.BLOCK_ID}','blk2_{rightmenublock.BLOCK_ID}','block_{rightmenublock.BLOCK_ID}');
}
//-->
</script>
<!-- END rightmenublock -->

<!-- BEGIN rightimageblock -->
<table width="100%" cellspacing="0" cellpadding="0" class="forumline">
<tr>
	<td class="row-header"><span><a href="javascript:ShowHide('blk_{rightimageblock.BLOCK_ID}','blk2_{rightimageblock.BLOCK_ID}','block_{rightimageblock.BLOCK_ID}');" onmouseover="showStatus('blk_{rightimageblock.BLOCK_ID}'); return true;" onmouseout="window.status=' '; return true;">{rightimageblock.L_BLOCK_TITLE}</a></span></td>
</tr>
<tr><td>
<div id="blk_{rightimageblock.BLOCK_ID}" style="display: ''; position: relative;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td valign="top" colspan="3" class="row">
	<table width="100%" cellspacing="1" cellpadding="1" border="0">
	<!-- BEGIN imagerow -->
	<tr valign="top">
	<td align="center" class="gensmall">{rightimageblock.imagerow.U_IMG}<td>
	</tr>
	<!-- END imagerow -->
	</table>
</td>
</tr>
<!-- BEGIN switch_footer_display -->
<tr><td colspan="3" align="center" class="row-post"><span class="gensmall">{rightimageblock.L_BLOCK_FOOTER}<span></td></tr>
<!-- END switch_footer_display -->
</table></div><div id="blk2_{rightimageblock.BLOCK_ID}" style="display: none; position: relative;"><table width="100%" cellpadding="0" cellspacing="1" border="0" class="forumline"><tr><td class="spacerow" width="100%" height="3" bgcolor="#888888"><img src="{T_TEMPLATE_PATH}/images/spacer.gif" width="1" height="3" border="0" alt="" /></td></tr></table></div>
</td></tr>
</table>
<script language="javascript" type="text/javascript">
<!--
tmp = 'block_{rightimageblock.BLOCK_ID}';
if(GetCookie(tmp) == '2')
{
	ShowHide('blk_{rightimageblock.BLOCK_ID}','blk2_{rightimageblock.BLOCK_ID}','block_{rightimageblock.BLOCK_ID}');
}
//-->
</script>
<!-- END rightimageblock -->
