        <div class="art-content">
               
                        <div class="art-Post">
                            <div class="art-Post-body">
				<div align="right">
				<?php if ($this->dx_auth->is_logged_in()) { ?>
					<span class="art-button-wrapper" align="right">
					<span class="l"> </span>
					<span class="r"> </span>	
					<?php 
						if ($subscribed) {
							echo anchor("tutorial/unsubscribe/$tutorial_id", "Unsubscribe", 'class="art-button"');
						}
						else {
							echo anchor("tutorial/subscribe/$tutorial_id", "Subscribe", 'class="art-button"');
						}
					?>
					</span>
				<? } ?>
				</div>
<a onClick="history.go(-1)">Back</a>
                        <div class="art-Post-inner">
                            
                            <div class="art-PostContent">

				<table><tr><td valign="top"><b>Written by:</b></td><td align="center"><?=anchor("user/view/$author_id", "<img height=50 src=$avatar>")?><br/>
					<?=anchor("user/view/$author_id", "$author_name")?></td>
				<td valign="top">
					<b>Score:</b> <?=$score?><br/>
				    <b>votes:</b> <?=$votes?><br/>
				    <?php if ($this->dx_auth->is_administrator() or $this->dx_auth->is_moderator()) {?>				
					<b>Format:</b>
						
						<?php echo form_open("tutorial/change_status/".$tutorial_id); ?>
							<select name="current_status">
								<?php foreach($statuses->result() as $status):
									echo "<option value=\"".$status->id."\" ";
									if ($current_status == $status->id) {
										echo "SELECTED";
									}
									echo ">".$status->name."</option>";
								endforeach;?>
							</select><input type="submit" value="ok"/></form><br/>						
				   <?php } else { ?>
				    	<b>Format:</b> <?=$tutorial_status?><br/>
			            <?php } ?>
                        
                   
                        
                    
                   
				</td>
				</tr>
				</table>
				
                                <hr/>
					<h2 class="art-PostHeaderIcon-wrapper">
                                <span class="art-PostHeader"><img src="/img/icons/tutorials.png" align="absmiddle"/>&nbsp;<?=$tutorial_title?></span>
                            </h2>
				        
					
                                <hr/>
				     <?=$tutorial_body?>
				<hr/>	
				Tags: <i><?=$tags?></i><br/>
				Created: <?php 	$array = preg_split("/,/", timespan($created, time())); echo strtolower($array[0]);?> ago.<br/>
				Last edited: <?php 	$array = preg_split("/,/", timespan($last_edited, time())); echo strtolower($array[0]);?> ago.<br/>
                 <?php if ($reviewer > 0) { ?>Reviewed: <?php 	$array = preg_split("/,/", timespan($last_status_changed, time())); echo strtolower($array[0]);?> ago by <?=anchor("user/view/$reviewer", "$reviewer_name")?>.<br/> <?php } ?>
				Read <?=$views?> times.<br/>

<?php if ($this->dx_auth->is_logged_in()) { ?>
		                        
	<?php if ($this->dx_auth->is_moderator() && intval($score) < 5) { ?>
	<p>		
		<span class="art-button-wrapper">
			<span class="l"> </span>
			<span class="r"> </span>
			<?=anchor("tutorial/delete/$tutorial_id", "<font color='#660000'>Moderate (delete)</font>", 'class="art-button"')?>
		</span>
	</p>

	<?php } 
	
	if ($mine) { ?>
	<p>
		<span class="art-button-wrapper">
			<span class="l"> </span>
			<span class="r"> </span>
			<?=anchor("tutorial/edit/$tutorial_id", "Edit", 'class="art-button"')?>
		</span>
		<span class="art-button-wrapper">
			<span class="l"> </span>
			<span class="r"> </span>
			<?=anchor("tutorial/delete/$tutorial_id", "Delete", 'class="art-button"')?>
		</span>
	</p>

	<?php } else { ?>
		                           
		<?php
		if ($vote == 0) {
			$myvote = "You didn't vote";
		}
		else if ($vote == 1) {
			$myvote = "<font color=red>You are demoting this tutorial</font>";
		}
		else if ($vote == 2) {
			$myvote = "You don't care about this tutorial";
		}
		else if ($vote == 3) {
			$myvote = "<font color=green>You are promoting this tutorial</font>";
		}
		?>

							
		                                                                                                                                                
		<p align=center>
			Your current vote: <?=$myvote?><br/>
			<span class="art-button-wrapper">
				<span class="l"> </span>
				<span class="r"> </span>
				<?=anchor("tutorial/vote/$tutorial_id/promote", "Promote", 'class="art-button"')?>
			</span>
			<span class="art-button-wrapper">
				<span class="l"> </span>
				<span class="r"> </span>
				<?=anchor("tutorial/vote/$tutorial_id/dontcare", "Don't care", 'class="art-button"')?>
			</span>
			<span class="art-button-wrapper">
				<span class="l"> </span>
				<span class="r"> </span>
				<?=anchor("tutorial/vote/$tutorial_id/demote", "Demote", 'class="art-button"')?>
			</span>
		</p>
	<?php } ?>

<?php } ?>

<?php if ($this->dx_auth->is_logged_in()) { ?>

	<p>
	<?= form_open("tutorial/comment/$tutorial_id") ?>
	<textarea rows=8 cols="75%" name="body"></textarea><br/>
	<span class="art-button-wrapper">
		<span class="l"> </span>
		<span class="r"> </span>
		<input class="art-button" type="submit" name="mysubmit" value="Add comment"/>
	</span>
	</form>
	</p>                              	

<?php } ?>         
<br/>

<table summary="Comments" class="commentsTable" cellspacing="0">
<tr><td colspan="4" class="commentsTableHeader">Comments</td></tr>
<?php foreach($comments->result() as $comment):

	$avtr = '/img/default_avatar.jpg';
	if (file_exists(FCPATH.'uploads/avatars/'.$comment->author_id.".jpg")) {
                        $avtr = '/uploads/avatars/'.$comment->author_id.".jpg";
        }
	$pattern    = '/\@([a-zA-Z0-9_]+) /';
	$replace    = '<a href="/user/profile/'.strtolower('\1').'">@\1</a>&nbsp;';
	$comment_str = preg_replace($pattern,$replace,$comment->body);

	$pattern    = '/\@([a-zA-Z0-9_]+)\:/';
	$replace    = '<a href="/user/profile/'.strtolower('\1').'">@\1</a>:';
	$comment_str = preg_replace($pattern,$replace,$comment_str);

?>
	<tr>
	<td nowrap><font color="orange"><?php
		$array = preg_split("/,/", timespan($comment->timestamp, time()));
		echo strtolower($array[0]);
		?> ago</font></td> 
	<td>		
		<center>
		<?=anchor("user/view/$comment->author_id", "<img height=30 src=$avtr>")?><br/>
		<?=anchor("user/view/$comment->author_id", "$comment->author_name")?>
		</center>
	</td>
	<td>
		<?=nl2br($comment_str)?>
	</td>
	<td>
		<?php if($comment->author_id == $this->dx_auth->get_user_id()) {?>
			<?=anchor("tutorial/delete_comment/$comment->id/$tutorial_id", "<img src=/img/delete.png>")?>
		<?php }	else { ?>
			&nbsp;
		<?php }?>
	</td>
	</tr>
<?php endforeach;?>
</table>
<?php if($comments->num_rows == 0):?>
	<p><i>No comments so far.</i></p>
<?php endif;?>

                           
                          

<h3>Other tutorials from <?=$author_name?></h3>
<ul>
<?php foreach($other_tutorials->result() as $tutorial):?>
	<li><?=anchor("tutorial/view/$tutorial->id", "$tutorial->title")?></li>
<?php endforeach;?>
</ul>
<?php if($other_tutorials->num_rows == 0):?>
	<p><i>No other tutorials.</i></p>
<?php endif;?>
                                  
                                  
                                  
                                	
                                    
                            </div>
                            <div class="cleared"></div>
                        </div>
                        
                            </div>
                        </div>
                    </div>
                </div>

