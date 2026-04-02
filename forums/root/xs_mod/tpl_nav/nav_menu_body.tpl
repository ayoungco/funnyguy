
<h1>{L_MENU_TITLE}</h1>

<p>{L_MENU_EXPLAIN}</p>

<form method="post" action="{S_FORUM_ACTION}"><table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline" align="center">
	<tr>
		<th class="thHead" colspan="7">{L_MENU_SETTINGS}</th>
	</tr>
	<!-- BEGIN blockrow -->
	<tr>
		<td class="catLeft" colspan="2"><span class="cattitle"><b>{blockrow.BLOCK_TITLE}</b></span></td>
		<td class="cat" align="center" valign="middle"><span class="gen"><a href="{blockrow.U_BLOCK_EDIT}">{L_EDIT}</a></span></td>
		<td class="cat" align="center" valign="middle"><span class="gen"><a href="{blockrow.U_BLOCK_DELETE}">{L_DELETE}</a></span></td>
		<td class="cat" align="center" valign="middle" nowrap="nowrap"><span class="gen"><a href="{blockrow.U_BLOCK_MOVE_UP}">{L_MOVE_UP}</a> <a href="{blockrow.U_BLOCK_MOVE_DOWN}">{L_MOVE_DOWN}</a></span></td>
	</tr>
	<tr>
		<td class="catLeft" colspan="5"><span class="cattitle"><b>{blockrow.BLOCK_STATUS}</b></span></td>
	</tr>

	<!-- BEGIN menurow -->
	<tr> 
		<td class="row2" width="100%"><span class="gen">{blockrow.menurow.MENU_TITLE}</span></td>
		<td class="row1" align="center" valign="middle"><span class="gen"><img src="{XS_PATH}images/{blockrow.menurow.SUB_TITLE}"></span></td>
		<td class="row1" align="center" valign="middle"><span class="gen"><a href="{blockrow.menurow.U_MENU_EDIT}">{L_EDIT}</a></span></td>
		<td class="row2" align="center" valign="middle"><span class="gen"><a href="{blockrow.menurow.U_MENU_DELETE}">{L_DELETE}</a></span></td>
		<td class="row1" align="center" valign="middle"><span class="gen"><a href="{blockrow.menurow.U_MENU_MOVE_UP}">{L_MOVE_UP}</a> <br /> <a href="{blockrow.menurow.U_MENU_MOVE_DOWN}">{L_MOVE_DOWN}</a></span></td>
	</tr>
	<!-- END menurow -->
	<tr>
		<td colspan="7" class="row2"><input class="post" type="text" name="{blockrow.S_ADD_MENU_TITLE}" /> <input type="submit" class="liteoption"  name="{blockrow.S_ADD_MENU_SUBMIT}" value="{L_CREATE_MENU}" /></td>
	</tr>
	<tr>
		<td colspan="7" height="1" class="spaceRow"><img src="../templates/subSilver/images/spacer.gif" alt="" width="1" height="1" /></td>
	</tr>
	<!-- END blockrow -->
	<tr>
		<td colspan="7" class="catBottom"><input class="post" type="text" name="block_title" /> <input type="submit" class="liteoption"  name="addblock" value="{L_CREATE_BLOCK}" /></td>
	</tr>
</table></form>
