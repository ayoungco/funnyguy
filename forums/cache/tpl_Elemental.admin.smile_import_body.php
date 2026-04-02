<?php

// eXtreme Styles mod cache. Generated on Wed, 12 Oct 2005 14:58:46 -0700 (time=1129154326)

?>
<h1><?php echo isset($this->vars['L_SMILEY_TITLE']) ? $this->vars['L_SMILEY_TITLE'] : $this->lang('L_SMILEY_TITLE'); ?></h1>

<p><?php echo isset($this->vars['L_SMILEY_EXPLAIN']) ? $this->vars['L_SMILEY_EXPLAIN'] : $this->lang('L_SMILEY_EXPLAIN'); ?></p>

<form method="post" action="<?php echo isset($this->vars['S_SMILEY_ACTION']) ? $this->vars['S_SMILEY_ACTION'] : $this->lang('S_SMILEY_ACTION'); ?>"><table class="forumline" cellspacing="1" cellpadding="4" border="0" align="center">
	<tr>
		<th class="thHead" colspan="2"><?php echo isset($this->vars['L_SMILEY_IMPORT']) ? $this->vars['L_SMILEY_IMPORT'] : $this->lang('L_SMILEY_IMPORT'); ?></th>
	</tr>
	<tr>
		<td class="row2"><?php echo isset($this->vars['L_SELECT_LBL']) ? $this->vars['L_SELECT_LBL'] : $this->lang('L_SELECT_LBL'); ?></td>
		<td class="row2"><?php echo isset($this->vars['S_SMILE_SELECT']) ? $this->vars['S_SMILE_SELECT'] : $this->lang('S_SMILE_SELECT'); ?></td>
	</tr>
	<tr>
		<td class="row1"><?php echo isset($this->vars['L_DEL_EXISTING']) ? $this->vars['L_DEL_EXISTING'] : $this->lang('L_DEL_EXISTING'); ?></td>
		<td class="row1"><input type="checkbox" name="clear_current" value="1" /></td>
	</tr>
	<tr>
		<td class="row2" colspan="2" align="center"><?php echo isset($this->vars['L_CONFLICTS']) ? $this->vars['L_CONFLICTS'] : $this->lang('L_CONFLICTS'); ?><br /><input type="radio" name="replace" value="1" checked="checked"/> <?php echo isset($this->vars['L_REPLACE_EXISTING']) ? $this->vars['L_REPLACE_EXISTING'] : $this->lang('L_REPLACE_EXISTING'); ?> &nbsp; <input type="radio" name="replace" value="0" /> <?php echo isset($this->vars['L_KEEP_EXISTING']) ? $this->vars['L_KEEP_EXISTING'] : $this->lang('L_KEEP_EXISTING'); ?></td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center"><?php echo isset($this->vars['S_HIDDEN_FIELDS']) ? $this->vars['S_HIDDEN_FIELDS'] : $this->lang('S_HIDDEN_FIELDS'); ?><input class="mainoption" name="import_pack" type="submit" value="<?php echo isset($this->vars['L_IMPORT']) ? $this->vars['L_IMPORT'] : $this->lang('L_IMPORT'); ?>" /></td>
	</tr>
</table></form>
