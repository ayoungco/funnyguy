<?php

// eXtreme Styles mod cache. Generated on Tue, 06 Sep 2005 18:28:58 -0700 (time=1126056538)

?>
<h1><?php echo isset($this->vars['L_DATABASE_BACKUP']) ? $this->vars['L_DATABASE_BACKUP'] : $this->lang('L_DATABASE_BACKUP'); ?></h1>

<P><?php echo isset($this->vars['L_BACKUP_EXPLAIN']) ? $this->vars['L_BACKUP_EXPLAIN'] : $this->lang('L_BACKUP_EXPLAIN'); ?></p>

<form method="post" action="<?php echo isset($this->vars['S_DBUTILS_ACTION']) ? $this->vars['S_DBUTILS_ACTION'] : $this->lang('S_DBUTILS_ACTION'); ?>"><table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr>
		<th colspan="2" class="thHead"><?php echo isset($this->vars['L_BACKUP_OPTIONS']) ? $this->vars['L_BACKUP_OPTIONS'] : $this->lang('L_BACKUP_OPTIONS'); ?></th>
	</tr>
	<tr>
		<td class="row2"><?php echo isset($this->vars['L_FULL_BACKUP']) ? $this->vars['L_FULL_BACKUP'] : $this->lang('L_FULL_BACKUP'); ?></td>
		<td class="row2"><input type="radio" name="backup_type" value="full" checked /></td>
	</tr>
	<tr>
		<td class="row1"><?php echo isset($this->vars['L_STRUCTURE_BACKUP']) ? $this->vars['L_STRUCTURE_BACKUP'] : $this->lang('L_STRUCTURE_BACKUP'); ?></td>
		<td class="row1"><input type="radio" name="backup_type" value="structure" /></td>
	</tr>
	<tr>
		<td class="row2"><?php echo isset($this->vars['L_DATA_BACKUP']) ? $this->vars['L_DATA_BACKUP'] : $this->lang('L_DATA_BACKUP'); ?></td>
		<td class="row2"><input type="radio" name="backup_type" value="data" /></td>
	</tr>
	<tr>
		<td class="row1"><?php echo isset($this->vars['L_ADDITIONAL_TABLES']) ? $this->vars['L_ADDITIONAL_TABLES'] : $this->lang('L_ADDITIONAL_TABLES'); ?></td>
		<td class="row1"><input class="post" type="text" name="additional_tables" /></td>
	</tr>
	<tr>
		<td class="row1"><?php echo isset($this->vars['L_GZIP_COMPRESS']) ? $this->vars['L_GZIP_COMPRESS'] : $this->lang('L_GZIP_COMPRESS'); ?></td>
		<td class="row1"><?php echo isset($this->vars['L_NO']) ? $this->vars['L_NO'] : $this->lang('L_NO'); ?> <input type="radio" name="gzipcompress" value="0" checked /> &nbsp;<?php echo isset($this->vars['L_YES']) ? $this->vars['L_YES'] : $this->lang('L_YES'); ?> <input type="radio" name="gzipcompress" value="1" /></td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center"><?php echo isset($this->vars['S_HIDDEN_FIELDS']) ? $this->vars['S_HIDDEN_FIELDS'] : $this->lang('S_HIDDEN_FIELDS'); ?><input type="submit" name="backupstart" value="<?php echo isset($this->vars['L_START_BACKUP']) ? $this->vars['L_START_BACKUP'] : $this->lang('L_START_BACKUP'); ?>" class="mainoption" /></td>
	</tr>
</table></form>
