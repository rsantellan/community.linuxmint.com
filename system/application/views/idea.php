        <div class="art-content">
               
                        <div class="art-Post">
                            <div class="art-Post-body">
                            
                            
				<a onClick="history.go(-1)">Back</a>

                        <div class="art-Post-inner">
                            <h2 class="art-PostHeaderIcon-wrapper">
                                <span class="art-PostHeader"><img src="/img/icons/ideas.png" align="absmiddle"/>&nbsp;<?=$idea_title?></span>				
                            </h2>
				<center>
				<font color="#999999" size="1"><i>
				Created <?php 	$array = preg_split("/,/", timespan($created, time())); echo strtolower($array[0]);?> ago, edited <?php 	$array = preg_split("/,/", timespan($last_edited, time())); echo strtolower($array[0]);?> ago.
                 <?php if ($reviewer > 0) { ?><br/>Status changed by <?=anchor("user/view/$reviewer", "$reviewer_name")?> <?php $array = preg_split("/,/", timespan($last_status_changed, time())); echo strtolower($array[0]);?> ago<?php } ?>
				</i></font>								
				</center>
							       <div align="right">		
							       
							       <?php if ($this->dx_auth->is_moderator() == 1 && intval($score) < 5) { ?>
										<span class="art-button-wrapper">
											<span class="l"> </span>
											<span class="r"> </span>
											<?=anchor("idea/delete/$idea_id", "<font color='#660000'>Moderate (delete)</font>", 'class="art-button"')?>
										</span>										
									<?php } ?>
							       
							       
								<?php if ($this->dx_auth->is_logged_in()) { ?>
									<span class="art-button-wrapper" align="right">
									<span class="l"> </span>
									<span class="r"> </span>	
									<?php 
										if ($subscribed) {
											echo anchor("idea/unsubscribe/$idea_id", "Unsubscribe", 'class="art-button"');
										}
										else {
											echo anchor("idea/subscribe/$idea_id", "Subscribe", 'class="art-button"');
										}
									?>
									</span>
								<? } ?>							       
                                </div>

                            <div class="art-PostContent">

				<table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tbody>
					<tr>				
					<td>
					<div class="boxinfo">
					<table cellspacing="5">
					<tr>					
					<td valign="top">
					<b>Author:</b><br/>
					<table>
											  <tr>
												  <td align=center><img height=50 src="<?=$avatar?>"/><br/><?=anchor("user/view/$author_id", "$author_name")?></td>
											  </tr>
										  </table>
					</td>					
					<td valign="top">
					<b>Status:</b><br/>
						<?php if ($this->dx_auth->is_administrator() or $this->dx_auth->is_moderator()) {?>					
								<?php echo form_open("idea/change_status/".$idea_id); ?>
									<select name="current_status">
									<?php foreach($statuses->result() as $status):
										echo "<option value=\"".$status->id."\" ";
										if ($current_status == $status->id) {
											echo "SELECTED";
										}
										echo ">".$status->name."</option>";
									endforeach;?>
									</select>
									<input type="submit" value="ok"/>
								</form>
						<?php } else { ?>
							<?=$idea_status?>
						<?php } ?>
					</td>
					<td valign="top" width="90%">&nbsp;</td>
					<td valign="top">
						<b>Score:</b><br/>
						<?php 
							if ($score > 0) { 
								$color = "#8cc153";
							}
							elseif ($score < 0) {
								$color = "#c15353";
							}
							else {
								$color = "#c1ab53";
							}
						?>
						
							<table>
								<tr>
									<td align="center">
									<font color="<?=$color?>" size="10">&nbsp;<?=$score?></font><br/>
									<?php 
										if ($votes > 1) {
											echo $votes." votes";
										}
										elseif ($votes == 1) {
											echo "1 vote";
										}
										else {
											echo "no votes";
										}
									?>
									</td>
								</tr>
							</table>
						
						</td>
					</tr>
					</table>
					</div>
					</td>
					</tr>
					<tr><th>Idea:</th></tr>
					<tr><td><blockquote><p><?=$idea_body?></p></blockquote></td></tr>

					<?php if ($this->dx_auth->is_logged_in()) { ?>
						<tr><th>Your vote:</th></tr>
						<tr><td>
						
						<?php if ($mine == True) { ?>
						<p>
							<span class="art-button-wrapper">
								<span class="l"> </span>
								<span class="r"> </span>
								<?=anchor("idea/edit/$idea_id", "Edit", 'class="art-button"')?>
							</span>
									
							<span class="art-button-wrapper">
								<span class="l"> </span>
								<span class="r"> </span>
								<?=anchor("idea/delete/$idea_id", "Delete", 'class="art-button"')?>
							</span>
						</p>

						<?php } else { ?>
													   
							<?php
							if ($vote == 0) {
								$myvote = "You didn't vote";
							}
							else if ($vote == 1) {
								$myvote = "<font color=red>You are demoting this idea</font>";
							}
							else if ($vote == 2) {
								$myvote = "You don't care about this idea";
							}
							else if ($vote == 3) {
								$myvote = "<font color=green>You are promoting this idea</font>";
							}
							?>

							<p align=center>
								<?=$myvote?><br/>
								<span class="art-button-wrapper">
									<span class="l"> </span>
									<span class="r"> </span>
									<?=anchor("idea/vote/$idea_id/promote", "Promote", 'class="art-button"')?>
								</span>
								<span class="art-button-wrapper">
									<span class="l"> </span>
									<span class="r"> </span>
									<?=anchor("idea/vote/$idea_id/dontcare", "Don't care", 'class="art-button"')?>
								</span>
								<span class="art-button-wrapper">
									<span class="l"> </span>
									<span class="r"> </span>
									<?=anchor("idea/vote/$idea_id/demote", "Demote", 'class="art-button"')?>
								</span>
							</p>
						<?php } ?>
						</td></tr>
					<?php } ?>
					
					
					
					<tr><th>Comments:</th></tr>
					
					</table>
					</div>
					</td></tr>

				</tbody>
				</table>				





<?php if ($this->dx_auth->is_logged_in()) { ?>

	<p>
	<?= form_open("idea/comment/$idea_id") ?>
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
			<?=anchor("idea/delete_comment/$comment->id/$idea_id", "<img src=/img/delete.png>")?>
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
                          

<h3>Other ideas from <?=$author_name?></h3>
<ul>
<?php foreach($other_ideas->result() as $idea):?>
	<li><?=anchor("idea/view/$idea->id", "$idea->title")?></li>
<?php endforeach;?>
</ul>
<?php if($other_ideas->num_rows == 0):?>
	<p><i>No other ideas.</i></p>
<?php endif;?>
                                  
                                  
                                  
                                	
                                    
                            </div>
                            <div class="cleared"></div>
                        </div>
                        
                            </div>
                        </div>
                    </div>
                </div>

