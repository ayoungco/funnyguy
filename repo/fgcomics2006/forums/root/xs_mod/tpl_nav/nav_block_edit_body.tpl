<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_TITLE_EXPLAIN}</p>

<form name="nav_block" action="{S_FORUM_ACTION}" method="post">
<table cellspacing="0" cellpadding="0" border="0" align="center">
<tr><td>
	<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_EDIT_BLOCK}</th>
	</tr>
	<tr> 
	  <td width="130" class="row1">{L_BLOCK}</td>
	  <td class="row2"><input class="post" type="text" size="25" name="block_title" value="{BLOCK_TITLE}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_BLOCK_TYPE}</td>
		<td class="row2">{BLOCK_TYPE_SELECT}</td>
	</tr>
	<tr> 
		<th class="thHead" colspan="2">{L_BLOCK_DISPLAY_SETTINGS}</th>
	</tr>
	<tr>
		<td width="130" class="row1">{L_BLOCK_DISPLAY}</td>
		<td class="row2"><input type="radio" name="block_display" value="1" {BLOCK_DISPLAY_YES} /> {L_YES}&nbsp;&nbsp;&nbsp;<input type="radio" name="block_display" value="0" {BLOCK_DISPLAY_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1">{L_BLOCK_POS}</td>
		<td class="row2"><input type="radio" name="block_pos" value="0" {BLOCK_LEFT} /> {L_LEFT}&nbsp;&nbsp;&nbsp;<input type="radio" name="block_pos" value="1" {BLOCK_RIGHT} /> {L_RIGHT}</td>
	</tr>
	<tr> 
		<th class="thHead" colspan="2">{L_BLOCK_FOOTER_SETTINGS}</th>
	</tr>
	<tr>
		<td class="row1" align="left" valign="top">{L_BLOCK_FOOTER}</td>
		<td class="row2"><textarea rows="4" cols="25" wrap="virtual" style="width:250px" name="block_footer" class="post">{BLOCK_FOOTER}</textarea></td>
	</tr>
	<tr>
		<td class="row1">{L_BLOCK_FOOTER_DISPLAY}</td>
		<td class="row2"><input type="radio" name="block_footer_display" value="1" {BLOCK_FOOTER_DISPLAY_YES} /> {L_YES}&nbsp;&nbsp;&nbsp;<input type="radio" name="block_footer_display" value="0" {BLOCK_FOOTER_DISPLAY_NO} /> {L_NO}&nbsp;&nbsp;&nbsp;<input type="radio" name="block_footer_display" value="2" onclick="this.form.block_footer.value='';" {BLOCK_FOOTER_DISPLAY_VER} /> {L_VERSION}</td></td>
	</tr>
	<tr> 
	  <td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{S_SUBMIT_VALUE}" class="mainoption" /></td>
	</tr>
  	</table>
</td></tr>
</table>
</form>
		
<br clear="all" />