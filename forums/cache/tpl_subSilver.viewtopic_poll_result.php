<?php

// eXtreme Styles mod cache. Generated on Thu, 17 Mar 2005 15:08:44 -0800 (time=1111100924)

?> 
<tr> 
  <td class="row2" colspan="2"><br clear="all" />
	<table cellspacing="0" cellpadding="4" border="0" align="center">
	  <tr> 
		<td colspan="4" align="center"><span class="gen"><b><?php echo isset($this->vars['POLL_QUESTION']) ? $this->vars['POLL_QUESTION'] : $this->lang('POLL_QUESTION'); ?></b></span></td>
	  </tr>
	  <tr> 
		<td align="center"> 
		  <table cellspacing="0" cellpadding="2" border="0">
			<?php

$poll_option_count = ( isset($this->_tpldata['poll_option.']) ) ?  sizeof($this->_tpldata['poll_option.']) : 0;
for ($poll_option_i = 0; $poll_option_i < $poll_option_count; $poll_option_i++)
{
 $poll_option_item = &$this->_tpldata['poll_option.'][$poll_option_i];
 $poll_option_item['S_ROW_COUNT'] = $poll_option_i;
 $poll_option_item['S_NUM_ROWS'] = $poll_option_count;

?>
			<tr> 
			  <td><span class="gen"><?php echo isset($poll_option_item['POLL_OPTION_CAPTION']) ? $poll_option_item['POLL_OPTION_CAPTION'] : ''; ?></span></td>
			  <td> 
				<table cellspacing="0" cellpadding="0" border="0">
				  <tr> 
					<td><img src="templates/subSilver/images/vote_lcap.gif" width="4" alt="" height="12" /></td>
					<td><img src="<?php echo isset($poll_option_item['POLL_OPTION_IMG']) ? $poll_option_item['POLL_OPTION_IMG'] : ''; ?>" width="<?php echo isset($poll_option_item['POLL_OPTION_IMG_WIDTH']) ? $poll_option_item['POLL_OPTION_IMG_WIDTH'] : ''; ?>" height="12" alt="<?php echo isset($poll_option_item['POLL_OPTION_PERCENT']) ? $poll_option_item['POLL_OPTION_PERCENT'] : ''; ?>" /></td>
					<td><img src="templates/subSilver/images/vote_rcap.gif" width="4" alt="" height="12" /></td>
				  </tr>
				</table>
			  </td>
			  <td align="center"><b><span class="gen">&nbsp;<?php echo isset($poll_option_item['POLL_OPTION_PERCENT']) ? $poll_option_item['POLL_OPTION_PERCENT'] : ''; ?>&nbsp;</span></b></td>
			  <td align="center"><span class="gen">[ <?php echo isset($poll_option_item['POLL_OPTION_RESULT']) ? $poll_option_item['POLL_OPTION_RESULT'] : ''; ?> ]</span></td>
			</tr>
			<?php

} // END poll_option

if(isset($poll_option_item)) { unset($poll_option_item); } 

?>
		  </table>
		</td>
	  </tr>
	  <tr> 
		<td colspan="4" align="center"><span class="gen"><b><?php echo isset($this->vars['L_TOTAL_VOTES']) ? $this->vars['L_TOTAL_VOTES'] : $this->lang('L_TOTAL_VOTES'); ?> : <?php echo isset($this->vars['TOTAL_VOTES']) ? $this->vars['TOTAL_VOTES'] : $this->lang('TOTAL_VOTES'); ?></b></span></td>
	  </tr>
	</table>
	<br clear="all" />
  </td>
</tr>
