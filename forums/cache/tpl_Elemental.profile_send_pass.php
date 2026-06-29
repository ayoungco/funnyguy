<?php

// eXtreme Styles mod cache. Generated on Fri, 12 Aug 2005 00:58:11 -0700 (time=1123833491)

?>
<form action="<?php echo isset($this->vars['S_PROFILE_ACTION']) ? $this->vars['S_PROFILE_ACTION'] : $this->lang('S_PROFILE_ACTION'); ?>" method="post">
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
  <tr> 
	<td align="left"><span class="nav"><a href="<?php echo isset($this->vars['U_INDEX']) ? $this->vars['U_INDEX'] : $this->lang('U_INDEX'); ?>" class="nav"><?php echo isset($this->vars['L_INDEX']) ? $this->vars['L_INDEX'] : $this->lang('L_INDEX'); ?></a></span></td>
  </tr>
</table>
  <table border="0" cellpadding="3" cellspacing="1" width="100%" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2" height="25" valign="middle"><?php echo isset($this->vars['L_SEND_PASSWORD']) ? $this->vars['L_SEND_PASSWORD'] : $this->lang('L_SEND_PASSWORD'); ?></th>
	</tr>
	<tr> 
	  <td class="row2" colspan="2"><span class="gensmall"><?php echo isset($this->vars['L_ITEMS_REQUIRED']) ? $this->vars['L_ITEMS_REQUIRED'] : $this->lang('L_ITEMS_REQUIRED'); ?></span></td>
	</tr>
	<tr> 
	  <td class="row1" width="38%"><span class="gen"><?php echo isset($this->vars['L_USERNAME']) ? $this->vars['L_USERNAME'] : $this->lang('L_USERNAME'); ?>: *</span></td>
	  <td class="row2"> 
		<input type="text" class="post" style="width: 200px" name="username" size="25" maxlength="40" value="<?php echo isset($this->vars['USERNAME']) ? $this->vars['USERNAME'] : $this->lang('USERNAME'); ?>" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen"><?php echo isset($this->vars['L_EMAIL_ADDRESS']) ? $this->vars['L_EMAIL_ADDRESS'] : $this->lang('L_EMAIL_ADDRESS'); ?>: *</span></td>
	  <td class="row2"> 
		<input type="text" class="post" style="width: 200px" name="email" size="25" maxlength="255" value="<?php echo isset($this->vars['EMAIL']) ? $this->vars['EMAIL'] : $this->lang('EMAIL'); ?>" />
	  </td>
	</tr>
	<tr> 
	  <td class="catBottom" colspan="2" align="center" height="28"><?php echo isset($this->vars['S_HIDDEN_FIELDS']) ? $this->vars['S_HIDDEN_FIELDS'] : $this->lang('S_HIDDEN_FIELDS'); ?> 
		<input type="submit" name="submit" value="<?php echo isset($this->vars['L_SUBMIT']) ? $this->vars['L_SUBMIT'] : $this->lang('L_SUBMIT'); ?>" class="mainoption" />
		&nbsp;&nbsp; 
		<input type="reset" value="<?php echo isset($this->vars['L_RESET']) ? $this->vars['L_RESET'] : $this->lang('L_RESET'); ?>" name="reset" class="liteoption" />
	  </td>
	</tr>
  </table>
</form>
