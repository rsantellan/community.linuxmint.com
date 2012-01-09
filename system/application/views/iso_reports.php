        <div class="art-content">
               
                        <div class="art-Post">
                            <div class="art-Post-body">
				<div align="right">
				</div>
                        <div class="art-Post-inner">
                            <h2 class="art-PostHeaderIcon-wrapper">
                                <span class="art-PostHeader"><img src="/img/icons/iso.png" align="absmiddle"/>&nbsp;<?=$testcase->name?></span>
                            </h2>
                            <div class="art-PostContent">
				<?=anchor("iso/view/$iso_id", "Back")?>
				
<h3>Test case</h3>							
				<table cellspacing=5 cellpadding=5>																	
					<tr><th nowrap>How to reproduce:</th><td><?=$testcase->reproducing?></td></tr>
					<tr><th nowrap>What to expect:</th><td><?=$testcase->expecting?></td></tr>					
					<tr><th nowrap>Additional notes:</th><td><?=$testcase->notes?></td></tr>					
				</table>


<?php if ($this->dx_auth->is_tester()) { ?>
	<h3>Your report</h3>				

	<?php
			
			if ($vote == -2) {
				$myvote = "You didn't report on this test case yet";
			}
			else if ($vote == -1) {
				$myvote = "<font color=red>You marked this test case as a failure</font>";
			}
			else if ($vote == 0) {
				$myvote = "<font color=orange>You marked this test case as a warning</font>";
			}
			else if ($vote == 1) {
				$myvote = "<font color=green>You marked this test case as a success</font>";
			}
			?>

								
																																							
			<p align=center>
				Your current vote: <?=$myvote?><br/>
				<span class="art-button-wrapper">
					<span class="l"> </span>
					<span class="r"> </span>
					<?=anchor("iso/report/$iso_id/$testcase->id/success", "Report Success", 'class="art-button"')?>
				</span>
				<span class="art-button-wrapper">
					<span class="l"> </span>
					<span class="r"> </span>
					<?=anchor("iso/report/$iso_id/$testcase->id/warning", "Report Warning", 'class="art-button"')?>
				</span>
				<span class="art-button-wrapper">
					<span class="l"> </span>
					<span class="r"> </span>
					<?=anchor("iso/report/$iso_id/$testcase->id/failure", "Report Failure", 'class="art-button"')?>
				</span>
			</p>
<?php } ?>
				
<h3>Reports</h3>

<table summary="Comments" class="commentsTable" cellspacing="0">
<?php foreach($results->result() as $result):
	$avatar = '/img/default_avatar.jpg';
	if (file_exists(FCPATH.'uploads/avatars/'.$result->tester.".jpg")) {
		$avatar = '/uploads/avatars/'.$result->tester.".jpg";			
    }
	if ($result->result == -1) {
		$verdict = '<font color="red">failure</font>';
	}
	elseif ($result->result == 1) {
		$verdict = '<font color="green">success</font>';
	}
	else {
		$verdict = '<font color="orange">warning</font>';
	}
?>
	<tr>
	<td nowrap><font color="orange"><?php
		$array = preg_split("/,/", timespan($result->timestamp, time()));
		echo strtolower($array[0]);
		?> ago</font></td> 
	<td>
		<center>
		<?=anchor("user/view/$result->tester", "<img height=30 src=$avatar>")?><br/>
		<?=anchor("user/view/$result->tester", "$result->username")?>
		</center>
	</td>
	<td>
		<?=$verdict?>
	</td>	
	</tr>
<?php endforeach;?>
</table>
<?php if($results->num_rows == 0):?>
	<p><i>No reports so far.</i></p>
<?php endif;?>                            
                          
                                    
                            </div>
                            <div class="cleared"></div>
                        </div>
                        
                            </div>
                        </div>
                    </div>
                </div>

