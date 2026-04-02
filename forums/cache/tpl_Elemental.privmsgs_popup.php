<?php

// eXtreme Styles mod cache. Generated on Thu, 11 Aug 2005 18:23:17 -0700 (time=1123809797)

?>
<script language="javascript" type="text/javascript">
<!--
function jump_to_inbox()
{
	opener.document.location.href = "<?php echo isset($this->vars['U_PRIVATEMSGS']) ? $this->vars['U_PRIVATEMSGS'] : $this->lang('U_PRIVATEMSGS'); ?>";
	window.close();
}
//-->
</script>

  <table width="100%" border="0" cellspacing="0" cellpadding="10">
	<tr>
	  <td>
		<table width="100%" border="0" cellspacing="1" cellpadding="4" class="forumline">
		  <tr> 
			<td valign="top" class="row1" align="center"><br /><span class="gen"><?php echo isset($this->vars['L_MESSAGE']) ? $this->vars['L_MESSAGE'] : $this->lang('L_MESSAGE'); ?></span><br /><br /><span class="genmed"><a href="javascript:window.close();" class="genmed"><?php echo isset($this->vars['L_CLOSE_WINDOW']) ? $this->vars['L_CLOSE_WINDOW'] : $this->lang('L_CLOSE_WINDOW'); ?></a></span><br /><br /></td>
		  </tr>
		</table>
	  </td>
	</tr>
  </table>
