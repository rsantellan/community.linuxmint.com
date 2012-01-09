        <div class="art-content">
               
                        <div class="art-Post">
                            <div class="art-Post-body">
                        <div class="art-Post-inner">
                            <h2 class="art-PostHeaderIcon-wrapper">
                                <span class="art-PostHeader"><img src="/img/icons/hardware.png" align="absmiddle"/>&nbsp;<?=$device_name?></span>				
                            </h2>
				<center>Brand: <?=$device_brand?> (<?=$device_category?>)</center>
                            <div class="art-PostContent">
				<a onClick="history.go(-1)">Back</a>

            <?php if ($this->dx_auth->is_moderator() || $this->dx_auth->is_administrator()) { ?>
					
						<br/>	<div align="right">
						<img src='/img/delete.png' align=absmiddle>&nbsp;<?=anchor("hardware/delete/$device_id", "<font color='#660000'>Delete this device</font>", "onclick=\"return confirm('Are you sure you want to delete this device?');\"")?><br/>
						<img src='/img/edit.png' align=absmiddle>&nbsp;<?=anchor("hardware/review/$device_id", "<font color='#660000'>Review this device</font>")?>												
                        </div>					
				<?php } ?>

				<p>
				<table border="0" cellspacing="0" cellpadding="0">
                                  <tbody>
                                    <tr><td colspan="2"><b>Release:</b> <?=$device_release?></td></tr>
				    <tr><td colspan="2"><b>Status:</b> <?=$device_status?></td></tr>
				    <tr><td colspan="2">&nbsp;</td></tr>
                                    <tr><td><b>Owner:</b></td>
					<td align=center>
				        <img height=50 src="<?=$avatar?>"/><br/>
					<?=anchor("user/view/$owner_id", "$owner_name")?></td>
                                    </tr>
				</tbody>
				</table>
				</p>

				<table border="0" cellspacing="0" cellpadding="0">
                                  <tbody>
				    <?php if ($what_works != ""):?>
	                                    <tr><th>What works:</th><td><blockquote><p><?=$what_works?></p></blockquote></td></tr>
				    <?php endif;?>
				    <?php if ($what_doesnt_work != ""):?>
					    <tr><th>What doesn't work:</th><td><blockquote style="background-color:#F5E2D1;border:solid 1px #E6BB93;"><p><?=$what_doesnt_work?></p></blockquote></td></tr>
				    <?php endif;?>
				    <?php if ($what_i_did_to_make_it_work != ""):?>
					    <tr><th>What was done to make it work:</th><td><blockquote style="background-color:#E2E2E2;border:solid 1px #BBBBBB;"><p><?=$what_i_did_to_make_it_work?></p></blockquote></td></tr>
				    <?php endif;?>
				    <?php if ($additional_notes != ""):?>
					    <tr><th>Additional notes:</th><td><blockquote style="background-color:#E2E2E2;border:solid 1px #BBBBBB;"><p><?=$additional_notes?></p></blockquote></td></tr>
				    <?php endif;?>
				</tbody>
				</table>
				
				<hr/>	
				Created: <?php 	$array = preg_split("/,/", timespan($created, time())); echo strtolower($array[0]);?> ago.<br/>
				Last edited: <?php 	$array = preg_split("/,/", timespan($last_edited, time())); echo strtolower($array[0]);?> ago.<br/>
				Read <?=$views?> times.<br/>
                                
<?php if ($mine) { ?>
<p>
	<span class="art-button-wrapper">
		<span class="l"> </span>
		<span class="r"> </span>
		<?=anchor("hardware/edit/$device_id", "Edit", 'class="art-button"')?>
	</span>
	<span class="art-button-wrapper">
		<span class="l"> </span>
		<span class="r"> </span>
		<?=anchor("hardware/delete/$device_id", "Delete", 'class="art-button"')?>
	</span>
</p>

<?php } ?>


<h3>Other devices from <?=$owner_name?></h3>
<ul>
<?php foreach($other_devices->result() as $device):?>
	<li><?=anchor("hardware/view/$device->id", "$device->name")?></li>
<?php endforeach;?>
</ul>
<?php if($other_devices->num_rows == 0):?>
	<p><i>No other devices.</i></p>
<?php endif;?>
                                  
                                  
                                  
                                	
                                    
                            </div>
                            <div class="cleared"></div>
                        </div>
                        
                            </div>
                        </div>
                    </div>
                </div>

