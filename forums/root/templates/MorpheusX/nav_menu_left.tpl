<!-- BEGIN leftmenublock -->
{TPL_NAVMENU_HDR1}<a href="javascript:ShowHide('blk_{leftmenublock.BLOCK_ID}','blk2_{leftmenublock.BLOCK_ID}','block_{leftmenublock.BLOCK_ID}');" onmouseover="showStatus('blk_{leftmenublock.BLOCK_ID}'); return true;" onmouseout="window.status=' '; return true;">{leftmenublock.L_BLOCK_TITLE}</a>{TPL_NAVMENU_HDR2}
<div id="blk_{leftmenublock.BLOCK_ID}" style="display: ''; position: relative;">
<table width="100%" class="forumline" border="0" cellspacing="1" cellpadding="2">
<!-- BEGIN menurow -->
<!-- BEGIN switch_has_subtitle -->
<tr><td class="row-nav" align="left">&nbsp;{leftmenublock.menurow.L_SUBTITLE}</td></tr>
<!-- END switch_has_subtitle -->
<tr><td valign="top" colspan="3" class="row">
	<table width="100%" cellspacing="1" cellpadding="1" border="0">
	<!-- BEGIN linkrow -->
	<tr valign="top">
	<?php
	if(!empty($linkrow_item['BULLET']))
	{
	?>
	<td align="center" valign="middle" width="11"><img src="{leftmenublock.menurow.linkrow.BULLET}" width="11" height="11" alt="" border="0"></td>
	<?php
	}
	else
	{
	?>
	<td align="center" valign="middle" width="11">&nbsp;</td>
	<?php
	}
	?>
	<td align="left" class="gensmall"><a href="{leftmenublock.menurow.linkrow.U_LINK}" target="{leftmenublock.menurow.linkrow.U_TARGET}">{leftmenublock.menurow.linkrow.L_LINK}</a><td>
	<?php
	if(!empty($linkrow_item['L_VER']))
	{
	?>
	<td align="left" class="gensmall">{leftmenublock.menurow.linkrow.L_VER}<td>
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
<tr><td class="row-nav" align="left">&nbsp;{leftmenublock.last_topic.L_SUBTITLE}</td></tr>
<tr><td class="row">
<marquee id="recent_topics" behavior="scroll" direction="{leftmenublock.last_topic.SCROLL_DIR}" height="{leftmenublock.last_topic.SCROLL_HEIGHT}" scrolldelay="{leftmenublock.last_topic.SCROLL_DELAY}" scrollamount="{leftmenublock.last_topic.SCROLL_AMOUNT}" onmouseover="document.all.recent_topics.stop()" onmouseout="document.all.recent_topics.start()">
<table cellpadding="3" cellspacing="2" border="0">  
<!-- BEGIN topic_row -->
<tr><td class="row"><a href="{leftmenublock.last_topic.topic_row.U_TOPIC_LINK}" title="{leftmenublock.last_topic.topic_row.L_TOPIC_TITLE}">{leftmenublock.last_topic.topic_row.L_TOPIC_LINK_TEXT}</a></td></tr>
<!-- END topic_row -->
</table></marquee>
</td></tr>
<!-- END last_topic -->

<!-- BEGIN switch_forumgoogle -->
<tr><td class="row-nav" align="left">&nbsp;{leftmenublock.L_FORUM}</td></tr>
<tr><td class="row-nav" align="center">
<!-- Quick Search -->
<form action="search.php?mode=results" method="post" target="_self">
<table width="100%" cellspacing="2" cellpadding="1" border="0">
<!-- BEGIN switch_forum_select_on -->
<tr><td align="left" valign="middle" class="row1">
<select name="search_forum" size="1" class="gensmall">
	<option value="-1">{leftmenublock.L_SEARCH_ALL}</option>
	<option value="{leftmenublock.FORUM_ID}" selected="selected">{leftmenublock.L_SEARCH_THIS}</option>
</select>
</td></tr>
<!-- END switch_forum_select_on -->
<tr><td nowrap="nowrap" align="center" valign="middle">
<input type="text" class="post" name="search_keywords" size="20" value="Quick Search" onFocus="this.value=''"/>
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
<input type="hidden" name="search_forum" value="{leftmenublock.FORUM_ID}">
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
<tr><td class="row-nav" align="left">&nbsp;{leftmenublock.L_GOOGLE}</td></tr>
<tr><td class="row-nav" align="center">
<!-- Search Google -->
<center>
<form method="get" action="http://www.google.com/custom" target="_blank">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td nowrap="nowrap" valign="top" align="center" height="32">
<a href="http://www.google.com/">
<img src="http://www.google.com/logos/Logo_25wht.gif" border="0" alt="Google" align="middle"></img></a><br>
<input type="text" name="q" size="19" maxlength="255" value="Enter Query" onFocus="this.value=''"></input>
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
<tr><td class="row-nav" align="center">
<!-- Search Google -->
<center>
<form method="get" action="http://www.google.com/custom" target="_blank">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td nowrap="nowrap" valign="top" align="center" height="32">
<a href="http://www.google.com/">
<img src="http://www.google.com/logos/Logo_25wht.gif" border="0" alt="Google" align="middle"></img></a><br>
<input type="text" name="q" size="19" maxlength="255" value="Enter Query" onFocus="this.value=''"></input>
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
<tr><td class="row-nav" align="center">
<!-- Quick Search -->
<form action="search.php?mode=results" method="post" target="_self">
<table width="100%" cellspacing="2" cellpadding="1" border="0">
<!-- BEGIN switch_forum_select_on -->
<tr><td align="left" valign="middle" class="row1">
<select name="search_forum" size="1" class="gensmall">
	<option value="-1">{leftmenublock.L_SEARCH_ALL}</option>
	<option value="{leftmenublock.FORUM_ID}" selected="selected">{leftmenublock.L_SEARCH_THIS}</option>
</select>
</td></tr>
<!-- END switch_forum_select_on -->
<tr><td nowrap="nowrap" align="center" valign="middle">
<input type="text" class="post" name="search_keywords" size="20" value="Quick Search" onFocus="this.value=''"/>
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
<input type="hidden" name="search_forum" value="{leftmenublock.FORUM_ID}">
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
<tr><td colspan="3" class="row-nav" align="center">
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
<tr><td colspan="3" align="center" class="row"><span class="gensmall">{leftmenublock.L_BLOCK_FOOTER}<span></td></tr>
<!-- END switch_footer_display -->
</table></div>{TPL_NAVMENU_FTR}
<script language="javascript" type="text/javascript">
<!--
tmp = 'block_{leftmenublock.BLOCK_ID}';
if(GetCookie(tmp) == '2')
{
	ShowHide('blk_{leftmenublock.BLOCK_ID}','blk2_{leftmenublock.BLOCK_ID}','block_{leftmenublock.BLOCK_ID}');
}
//-->
</script>
<!-- END leftmenublock -->

<!-- BEGIN leftimageblock -->
{TPL_NAVMENU_HDR1}<a href="javascript:ShowHide('blk_{leftimageblock.BLOCK_ID}','blk2_{leftimageblock.BLOCK_ID}','block_{leftimageblock.BLOCK_ID}');" onmouseover="showStatus('blk_{leftimageblock.BLOCK_ID}'); return true;" onmouseout="window.status=' '; return true;">{leftimageblock.L_BLOCK_TITLE}</a>{TPL_NAVMENU_HDR2}
<div id="blk_{leftimageblock.BLOCK_ID}" style="display: ''; position: relative;">
<table width="100%" class="forumline" border="0" cellspacing="1" cellpadding="2"
<tr><td valign="top" colspan="3" class="row-nav">
	<table width="100%" cellspacing="1" cellpadding="1" border="0">
	<!-- BEGIN imagerow -->
	<tr valign="top">
	<td align="center" class="gensmall">{leftimageblock.imagerow.U_IMG}<td>
	</tr>
	<!-- END imagerow -->
	</table>
</td>
</tr>
<!-- BEGIN switch_footer_display -->
<tr><td colspan="3" align="center" class="row"><span class="gensmall">{leftimageblock.L_BLOCK_FOOTER}<span></td></tr>
<!-- END switch_footer_display -->
</table></div>{TPL_NAVMENU_FTR}
<script language="javascript" type="text/javascript">
<!--
tmp = 'block_{leftimageblock.BLOCK_ID}';
if(GetCookie(tmp) == '2')
{
	ShowHide('blk_{leftimageblock.BLOCK_ID}','blk2_{leftimageblock.BLOCK_ID}','block_{leftimageblock.BLOCK_ID}');
}
//-->
</script>
<!-- END leftimageblock -->
