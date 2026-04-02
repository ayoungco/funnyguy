<?php

// eXtreme Styles mod cache. Generated on Thu, 11 Aug 2005 12:13:48 -0700 (time=1123787628)

?>			<tr>
				<td class="row2" colspan="2"><br clear="all" /><form method="POST" action="<?php echo isset($this->vars['S_POLL_ACTION']) ? $this->vars['S_POLL_ACTION'] : $this->lang('S_POLL_ACTION'); ?>"><table cellspacing="0" cellpadding="4" border="0" align="center">
					<tr>
						<td align="center"><span class="gen"><b><?php echo isset($this->vars['POLL_QUESTION']) ? $this->vars['POLL_QUESTION'] : $this->lang('POLL_QUESTION'); ?></b></span></td>
					</tr>
					<tr>
						<td align="center"><table cellspacing="0" cellpadding="2" border="0">
							<?php

$poll_option_count = ( isset($this->_tpldata['poll_option.']) ) ?  sizeof($this->_tpldata['poll_option.']) : 0;
for ($poll_option_i = 0; $poll_option_i < $poll_option_count; $poll_option_i++)
{
 $poll_option_item = &$this->_tpldata['poll_option.'][$poll_option_i];
 $poll_option_item['S_ROW_COUNT'] = $poll_option_i;
 $poll_option_item['S_NUM_ROWS'] = $poll_option_count;

?>
							<tr>
								<td><input type="radio" name="vote_id" value="<?php echo isset($poll_option_item['POLL_OPTION_ID']) ? $poll_option_item['POLL_OPTION_ID'] : ''; ?>" />&nbsp;</td>
								<td><span class="gen"><?php echo isset($poll_option_item['POLL_OPTION_CAPTION']) ? $poll_option_item['POLL_OPTION_CAPTION'] : ''; ?></span></td>
							</tr>
							<?php

} // END poll_option

if(isset($poll_option_item)) { unset($poll_option_item); } 

?>
						</table></td>
					</tr>
					<tr>
						<td align="center">
			<input type="submit" name="submit" value="<?php echo isset($this->vars['L_SUBMIT_VOTE']) ? $this->vars['L_SUBMIT_VOTE'] : $this->lang('L_SUBMIT_VOTE'); ?>" class="liteoption" />
		  </td>
					</tr>
					<tr>
						
		  <td align="center"><span class="gensmall"><b><a href="<?php echo isset($this->vars['U_VIEW_RESULTS']) ? $this->vars['U_VIEW_RESULTS'] : $this->lang('U_VIEW_RESULTS'); ?>" class="gensmall"><?php echo isset($this->vars['L_VIEW_RESULTS']) ? $this->vars['L_VIEW_RESULTS'] : $this->lang('L_VIEW_RESULTS'); ?></a></b></span></td>
					</tr>
				</table><?php echo isset($this->vars['S_HIDDEN_FIELDS']) ? $this->vars['S_HIDDEN_FIELDS'] : $this->lang('S_HIDDEN_FIELDS'); ?></form></td>
			</tr>