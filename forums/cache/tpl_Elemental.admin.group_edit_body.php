<?php

// eXtreme Styles mod cache. Generated on Mon, 12 Sep 2005 17:48:00 -0700 (time=1126572480)

?>
<h1><?php echo isset($this->vars['L_GROUP_TITLE']) ? $this->vars['L_GROUP_TITLE'] : $this->lang('L_GROUP_TITLE'); ?></h1>

<form action="<?php echo isset($this->vars['S_GROUP_ACTION']) ? $this->vars['S_GROUP_ACTION'] : $this->lang('S_GROUP_ACTION'); ?>" method="post" name="post"><table border="0" cellpadding="3" cellspacing="1" class="forumline" align="center">
	<tr> 
	  <th class="thHead" colspan="2"><?php echo isset($this->vars['L_GROUP_EDIT_DELETE']) ? $this->vars['L_GROUP_EDIT_DELETE'] : $this->lang('L_GROUP_EDIT_DELETE'); ?></th>
	</tr>
	<tr>
	  <td class="row1" colspan="2"><span class="gensmall"><?php echo isset($this->vars['L_ITEMS_REQUIRED']) ? $this->vars['L_ITEMS_REQUIRED'] : $this->lang('L_ITEMS_REQUIRED'); ?></span></td>
	</tr>
	<tr> 
	  <td class="row1" width="38%"><span class="gen"><?php echo isset($this->vars['L_GROUP_NAME']) ? $this->vars['L_GROUP_NAME'] : $this->lang('L_GROUP_NAME'); ?>:</span></td>
	  <td class="row2" width="62%"> 
		<input class="post" type="text" name="group_name" size="35" maxlength="40" value="<?php echo isset($this->vars['GROUP_NAME']) ? $this->vars['GROUP_NAME'] : $this->lang('GROUP_NAME'); ?>" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1" width="38%"><span class="gen"><?php echo isset($this->vars['L_GROUP_DESCRIPTION']) ? $this->vars['L_GROUP_DESCRIPTION'] : $this->lang('L_GROUP_DESCRIPTION'); ?>:</span></td>
	  <td class="row2" width="62%"> 
		<textarea class="post" name="group_description" rows="5" cols="51"><?php echo isset($this->vars['GROUP_DESCRIPTION']) ? $this->vars['GROUP_DESCRIPTION'] : $this->lang('GROUP_DESCRIPTION'); ?></textarea>
	  </td>
	</tr>
	<tr> 
	  <td class="row1" width="38%"><span class="gen"><?php echo isset($this->vars['L_GROUP_MODERATOR']) ? $this->vars['L_GROUP_MODERATOR'] : $this->lang('L_GROUP_MODERATOR'); ?>:</span></td>
	  <td class="row2" width="62%"><input class="post" type="text" class="post" name="username" maxlength="50" size="20" value="<?php echo isset($this->vars['GROUP_MODERATOR']) ? $this->vars['GROUP_MODERATOR'] : $this->lang('GROUP_MODERATOR'); ?>" /> &nbsp; <input type="submit" name="usersubmit" value="<?php echo isset($this->vars['L_FIND_USERNAME']) ? $this->vars['L_FIND_USERNAME'] : $this->lang('L_FIND_USERNAME'); ?>" class="liteoption" onClick="window.open('<?php echo isset($this->vars['U_SEARCH_USER']) ? $this->vars['U_SEARCH_USER'] : $this->lang('U_SEARCH_USER'); ?>', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" /></td>
	</tr>

	<tr> 
	  <td class="row1" width="38%"><span class="gen"><?php echo isset($this->vars['L_GROUP_STATUS']) ? $this->vars['L_GROUP_STATUS'] : $this->lang('L_GROUP_STATUS'); ?>:</span></td>
	  <td class="row2" width="62%"> 
		<input type="radio" name="group_type" value="<?php echo isset($this->vars['S_GROUP_OPEN_TYPE']) ? $this->vars['S_GROUP_OPEN_TYPE'] : $this->lang('S_GROUP_OPEN_TYPE'); ?>" <?php echo isset($this->vars['S_GROUP_OPEN_CHECKED']) ? $this->vars['S_GROUP_OPEN_CHECKED'] : $this->lang('S_GROUP_OPEN_CHECKED'); ?> /> <?php echo isset($this->vars['L_GROUP_OPEN']) ? $this->vars['L_GROUP_OPEN'] : $this->lang('L_GROUP_OPEN'); ?> &nbsp;&nbsp;<input type="radio" name="group_type" value="<?php echo isset($this->vars['S_GROUP_CLOSED_TYPE']) ? $this->vars['S_GROUP_CLOSED_TYPE'] : $this->lang('S_GROUP_CLOSED_TYPE'); ?>" <?php echo isset($this->vars['S_GROUP_CLOSED_CHECKED']) ? $this->vars['S_GROUP_CLOSED_CHECKED'] : $this->lang('S_GROUP_CLOSED_CHECKED'); ?> />	<?php echo isset($this->vars['L_GROUP_CLOSED']) ? $this->vars['L_GROUP_CLOSED'] : $this->lang('L_GROUP_CLOSED'); ?> &nbsp;&nbsp;<input type="radio" name="group_type" value="<?php echo isset($this->vars['S_GROUP_HIDDEN_TYPE']) ? $this->vars['S_GROUP_HIDDEN_TYPE'] : $this->lang('S_GROUP_HIDDEN_TYPE'); ?>" <?php echo isset($this->vars['S_GROUP_HIDDEN_CHECKED']) ? $this->vars['S_GROUP_HIDDEN_CHECKED'] : $this->lang('S_GROUP_HIDDEN_CHECKED'); ?> />	<?php echo isset($this->vars['L_GROUP_HIDDEN']) ? $this->vars['L_GROUP_HIDDEN'] : $this->lang('L_GROUP_HIDDEN'); ?></td> 
	</tr>
	<?php

$group_edit_count = ( isset($this->_tpldata['group_edit.']) ) ?  sizeof($this->_tpldata['group_edit.']) : 0;
for ($group_edit_i = 0; $group_edit_i < $group_edit_count; $group_edit_i++)
{
 $group_edit_item = &$this->_tpldata['group_edit.'][$group_edit_i];
 $group_edit_item['S_ROW_COUNT'] = $group_edit_i;
 $group_edit_item['S_NUM_ROWS'] = $group_edit_count;

?>
	<tr> 
	  <td class="row1" width="38%"><span class="gen"><?php echo isset($this->vars['L_DELETE_MODERATOR']) ? $this->vars['L_DELETE_MODERATOR'] : $this->lang('L_DELETE_MODERATOR'); ?></span>
	  <br />
	  <span class="gensmall"><?php echo isset($this->vars['L_DELETE_MODERATOR_EXPLAIN']) ? $this->vars['L_DELETE_MODERATOR_EXPLAIN'] : $this->lang('L_DELETE_MODERATOR_EXPLAIN'); ?></span></td>
	  <td class="row2" width="62%"> 
		<input type="checkbox" name="delete_old_moderator" value="1">
		<?php echo isset($this->vars['L_YES']) ? $this->vars['L_YES'] : $this->lang('L_YES'); ?></td>
	</tr>
	<tr> 
	  <td class="row1" width="38%"><span class="gen"><?php echo isset($this->vars['L_GROUP_DELETE']) ? $this->vars['L_GROUP_DELETE'] : $this->lang('L_GROUP_DELETE'); ?>:</span></td>
	  <td class="row2" width="62%"> 
		<input type="checkbox" name="group_delete" value="1">
		<?php echo isset($this->vars['L_GROUP_DELETE_CHECK']) ? $this->vars['L_GROUP_DELETE_CHECK'] : $this->lang('L_GROUP_DELETE_CHECK'); ?></td>
	</tr>
	<?php

} // END group_edit

if(isset($group_edit_item)) { unset($group_edit_item); } 

?>
	<tr> 
	  <td class="catBottom" colspan="2" align="center"><span class="cattitle"> 
		<input type="submit" name="group_update" value="<?php echo isset($this->vars['L_SUBMIT']) ? $this->vars['L_SUBMIT'] : $this->lang('L_SUBMIT'); ?>" class="mainoption" />
		&nbsp;&nbsp; 
		<input type="reset" value="<?php echo isset($this->vars['L_RESET']) ? $this->vars['L_RESET'] : $this->lang('L_RESET'); ?>" name="reset" class="liteoption" />
		</span></td>
	</tr>
</table><?php echo isset($this->vars['S_HIDDEN_FIELDS']) ? $this->vars['S_HIDDEN_FIELDS'] : $this->lang('S_HIDDEN_FIELDS'); ?></form>
