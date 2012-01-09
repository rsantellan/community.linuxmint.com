        <div class="art-content">
               
                        <div class="art-Post">
                            <div class="art-Post-body">
				<div align="right">
				</div>
                        <div class="art-Post-inner">
                            <h2 class="art-PostHeaderIcon-wrapper">
                                <span class="art-PostHeader"><img src="/img/icons/iso.png" align="absmiddle"/>&nbsp;<?=$iso_name?></span>
                            </h2>
                            <div class="art-PostContent">
				<?=anchor("iso", "Back")?>	
				<table border="0" cellspacing="4" cellpadding="4">
                    <tbody>
                    <tr><th>Maintainer: </th><td align=center>
						<?php
							$avatar = '/img/default_avatar.jpg';
							if (file_exists(FCPATH.'uploads/avatars/'.$iso->maintainer.".jpg")) {
								$avatar = '/uploads/avatars/'.$iso->maintainer.".jpg";			
							}
						?>							
						<img height=50 src="<?=$avatar?>"/><br/>
						<?=anchor("user/view/$iso->maintainer", "$iso->username")?></td>          
					</tr>                                 
					<?php if ($this->dx_auth->is_administrator()) {?>					
					<tr><th>Status: </th><td>
					<?php echo form_open("iso/change_status/".$iso->id); ?>
						<select name="current_status">
							<?php foreach($statuses->result() as $status):
								echo "<option value=\"".$status->id."\" ";
								if ($iso->status == $status->id) {
									echo "SELECTED";
								} 
								echo ">".$status->name."</option>";
							endforeach;?>
						</select><input type="submit" value="ok"/></form></td></tr>
					<?php } else { ?>
					    <tr><th>Status: </th><td><?=$iso->status_name?></td></tr>
					<?php } ?>
					<tr><th>Registered: </th><td><?php $array = preg_split("/,/", timespan($iso->created, time())); echo strtolower($array[0]);?> ago</td></tr>      
					
					<?php if ($this->dx_auth->is_tester()) { ?>
						<tr><th>Download: </th><td><?=anchor($iso->url, $iso->url)?> (login: maintainer, password: maintainer)</td></tr>
						<tr><th>MD5sum: </th><td><?=$iso->md5?></td></tr>
					<?php } ?>   
				</tbody>
				</table>
				

<?php if ($this->dx_auth->is_logged_in()) { ?>

	<?php
	if ($iso->maintainer == $this->dx_auth->get_user_id()) { ?>
	<p>
		<span class="art-button-wrapper">
			<span class="l"> </span>
			<span class="r"> </span>
			<?=anchor("iso/edit/$iso->id", "Edit", 'class="art-button"')?>
		</span>
				
		<span class="art-button-wrapper">
			<span class="l"> </span>
			<span class="r"> </span>
			<?=anchor("iso/delete/$iso->id", "Delete", 'class="art-button"')?>
		</span>
	</p>

	<?php } ?>
		                           
<?php } ?>

<h3>Tests</h3>

<?php 
	$untested = array();
	$successes = array();
	$warnings = array();
	$failures = array();
	
	if($testcases->num_rows > 0) {
		foreach($testcases->result() as $testcase) {				
			if ($testcase->tests == 0) {
				$untested[] = $testcase;
			}
			if ($testcase->success > 0) {
				$successes[] = $testcase;
			}
			if ($testcase->warnings > 0) {
				$warnings[] = $testcase;
			}
			if ($testcase->failures > 0) {
				$failures[] = $testcase;
			}
		}
	}
	
?>

<?php if(count($untested) > 0) {?>
<h4>Untested cases (<?=count($untested)?>)</h4>
	<table>	
	<th>Test case</th><th>Reports</th><th>Success</th><th>Warnings</th><th>Failures</th>
	
	<?php foreach($untested as $testcase):?>						
		<tr>						    
			<td><?=anchor("iso/reports/$iso->id/$testcase->id", "$testcase->name")?></td><td><?=$testcase->tests?></td><td><?=$testcase->success?></td><td><?=$testcase->warnings?></td><td><?=$testcase->failures?></td>
		</tr>
	<?php endforeach;?>
	</table>
<?php } ?>	

<?php 
if($this->dx_auth->is_tester()) {
	if($myremainingtestcases->num_rows() > 0) {
?>
<h4>Cases I did not test (<?=$myremainingtestcases->num_rows()?>)</h4>
	<table>	
	<th>Test case</th><th>Reports</th><th>Success</th><th>Warnings</th><th>Failures</th>
	
	<?php foreach($myremainingtestcases->result() as $testcase):?>						
		<tr>						    
			<td><?=anchor("iso/reports/$iso->id/$testcase->id", "$testcase->name")?></td><td><?=$testcase->tests?></td><td><?=$testcase->success?></td><td><?=$testcase->warnings?></td><td><?=$testcase->failures?></td>
		</tr>
	<?php endforeach;?>
	</table>
<?php }
}
?>	

<?php if(count($failures) > 0) {?>
<h4>Failed cases (<?=count($failures)?>)</h4>
	<table>	
	<th>Test case</th><th>Reports</th><th>Success</th><th>Warnings</th><th>Failures</th>
	
	<?php foreach($failures as $testcase):?>						
		<tr>						    
			<td><?=anchor("iso/reports/$iso->id/$testcase->id", "$testcase->name")?></td><td><?=$testcase->tests?></td><td><?=$testcase->success?></td><td><?=$testcase->warnings?></td><td><?=$testcase->failures?></td>
		</tr>
	<?php endforeach;?>
	</table>
<?php } ?>	

<?php if(count($warnings) > 0) {?>
<h4>Warning cases (<?=count($warnings)?>)</h4>
	<table>	
	<th>Test case</th><th>Reports</th><th>Success</th><th>Warnings</th><th>Failures</th>
	
	<?php foreach($warnings as $testcase):?>						
		<tr>						    
			<td><?=anchor("iso/reports/$iso->id/$testcase->id", "$testcase->name")?></td><td><?=$testcase->tests?></td><td><?=$testcase->success?></td><td><?=$testcase->warnings?></td><td><?=$testcase->failures?></td>
		</tr>
	<?php endforeach;?>
	</table>
<?php } ?>	

<?php if(count($successes) > 0) {?>
<h4>Succeeded cases (<?=count($successes)?>)</h4>
	<table>	
	<th>Test case</th><th>Reports</th><th>Success</th><th>Warnings</th><th>Failures</th>
	
	<?php foreach($successes as $testcase):?>						
		<tr>						    
			<td><?=anchor("iso/reports/$iso->id/$testcase->id", "$testcase->name")?></td><td><?=$testcase->tests?></td><td><?=$testcase->success?></td><td><?=$testcase->warnings?></td><td><?=$testcase->failures?></td>
		</tr>
	<?php endforeach;?>
	</table>
<?php } ?>	

<h3>Comments</h3>

<?php if ($this->dx_auth->is_tester()) { ?>

	<p>
	<?= form_open("iso/comment/$iso->id") ?>
	<textarea rows=8 cols="75%" name="body"></textarea><br/>
	<span class="art-button-wrapper">
		<span class="l"> </span>
		<span class="r"> </span>
		<input class="art-button" type="submit" name="mysubmit" value="Add comment"/>
	</span>
	</form>
	</p>    
	
	<script type="text/javascript">
	CKEDITOR.replace( 'body' );	
	</script>                          	

<?php } ?>        
<br/>

<table summary="Comments" class="commentsTable" cellspacing="0">
<?php foreach($comments->result() as $comment):
	$avatar = '/img/default_avatar.jpg';
	if (file_exists(FCPATH.'uploads/avatars/'.$comment->author_id.".jpg")) {
		$avatar = '/uploads/avatars/'.$comment->author_id.".jpg";			
    }
	$pattern    = '/\@([a-zA-Z0-9_]+) /';
	$replace    = '<a href="/user/profile/'.strtolower('\1').'">@\1</a>&nbsp;';
	$comment_str = preg_replace($pattern,$replace,$comment->body);

	$pattern    = '/\@([a-zA-Z0-9_]+)\:/';
	$replace    = '<a href="/user/profile/'.strtolower('\1').'">@\1</a>:';
	$comment_str = preg_replace($pattern,$replace,$comment_str);

?>
	<tr>
	<td>
		<?php if($comment->author_id == $this->dx_auth->get_user_id()) {?>
			<?=anchor("iso/delete_comment/$comment->id/$iso->id", "<img src=/img/delete.png>")?>
		<?php }	else { ?>
			&nbsp;
		<?php }?>
	</td>
	<td nowrap><font color="orange"><?php
		$array = preg_split("/,/", timespan($comment->timestamp, time()));
		echo strtolower($array[0]);
		?> ago</font></td> 
	<td>
		<center>
		<?=anchor("user/view/$comment->author_id", "<img height=30 src=$avatar>")?><br/>
		<?=anchor("user/view/$comment->author_id", "$comment->author_name")?>
		</center>
	</td>
	<td>
		<?=$comment_str?>
	</td>	
	</tr>
<?php endforeach;?>
</table>
<?php if($comments->num_rows == 0):?>
	<p><i>No comments so far.</i></p>
<?php endif;?>                            
                          
                                    
                            </div>
                            <div class="cleared"></div>
                        </div>
                        
                            </div>
                        </div>
                    </div>
                </div>


