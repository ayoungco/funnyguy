<script language="JavaScript" type="text/javascript">
<!--
function unselect(thisform, thisitem)
{
	if(thisform[0].checked)
	{
		thisform[0].checked = false;
		thisform[1].checked = true;
	}
}
//-->
</script>

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_TITLE_EXPLAIN}</p>

<form name="form" action="{S_FORUM_ACTION}" method="post">
  <table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline" align="center">
	<tr> 
	  <th class="thHead" colspan="2">{L_MENU_SETTINGS}</th>
	</tr>
	<tr> 
	  <td class="row1" width="200">{L_MENU_TITLE}</td>
	  <td class="row2"><input type="text" size="25" name="menu_title" value="{MENU_TITLE}" class="post" /></td>
	</tr>
	<tr>
		<td class="row1">{L_MENU_SUBTITLE}</td>
		<td class="row2"><input type="radio" onclick="unselect(this.form.menu_is_image);" name="menu_is_sub" value="1" {MENU_IS_SUBTITLE_YES} /> {L_YES}&nbsp;&nbsp;&nbsp;<input type="radio" name="menu_is_sub" value="0" {MENU_IS_SUBTITLE_NO} /> {L_NO}</td>
	</tr>

	<tr> 
	  <td class="row1">{L_BLOCK}</td>
	  <td class="row2"><select name="b">{S_BLOCK_LIST}</select></td>
	</tr>

	<tr> 
	  <td class="row1">{L_MENU_LINK}</td>
	  <td class="row2"><input type="text" name="menu_link" size="35" value="{MENU_LINK}"></td>
	</tr>
	<tr> 
	  <td class="row1">{L_MENU_LINK_TARGET}</td>
	  <td class="row2">{MENU_LINK_TARGET}</td>
	</tr>

	<tr>
		<td class="row1">{L_MENU_IMAGE}</td>
		<td class="row2"><input type="radio" onclick="unselect(this.form.menu_is_sub);" name="menu_is_image" value="1" {MENU_IS_IMAGE_YES} /> {L_YES}&nbsp;&nbsp;&nbsp;<input type="radio" name="menu_is_image" value="0" {MENU_IS_IMAGE_NO} /> {L_NO}</td>
	</tr>
	<tr> 
	  <td class="row1">{L_MENU_IMAGE_TITLE}<br /><span class="gensmall">{L_MENU_IMAGE_TITLE_EXPLAIN}</span></td>
	  <td class="row2"><input type="text" name="menu_image" size="35" value="{MENU_IMAGE}"></td>
	</tr>

	<tr> 
	  <td class="row1">{L_MENU_IMAGE_SIZE}</td>
	  <td class="row2"><input type="text" name="menu_image_w" size="5" value="{MENU_IMAGE_W}">&nbsp;x&nbsp;<input type="text" name="menu_image_h" size="5" value="{MENU_IMAGE_H}"></td>
	</tr>
	<tr> 
	  <td class="row1">{L_MENU_BULLET}<br /><span class="gensmall">{L_MENU_BULLET_EXPLAIN}</span></td>
	  <td class="row2"><input type="text" name="menu_bullet" size="35" value="{MENU_BULLET}"></td>
	</tr>
	<tr> 
	  <td class="row1">{L_MENU_BULLET_QUICK}</td>
	  <td class="row2">{MENU_BULLET_QUICK}</td>
	</tr>
	<tr> 
	  <td class="row1">{L_MENU_VER}</td>
	  <td class="row2"><input type="text" name="menu_ver" size="10" value="{MENU_VER}"></td>
	</tr>

	<tr> 
	  <td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{S_SUBMIT_VALUE}" class="mainoption" /></td>
	</tr>
  </table>
</form>
<br clear="all" />
