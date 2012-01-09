        <div class="art-content">
               
                        <div class="art-Post">
                            <div class="art-Post-body">
				<a onClick="history.go(-1)">Back</a>

                        <div class="art-Post-inner">
                            <h2 class="art-PostHeaderIcon-wrapper">
                                <span class="art-PostHeader"><img src="/img/icons/software.png" align="absmiddle"/>&nbsp;<?=$pkg_name?></span>				
                            </h2>
				<center><?=$summary?><br/><?=$size?><br/><i><a href="<?=$homepage?>"><?=$homepage?></a></i></center>
							       <div align="right">
                                <span class="art-button-wrapper" align="right">
                                <span class="l"> </span>
                                <span class="r"> </span>
                                      <?php echo anchor("apt://$pkg_name", "Install", 'class="art-button"');?>
                                </span>
                                </div>

                            <div class="art-PostContent">

				<table border="0" cellspacing="0" cellpadding="0">
                                  <tbody>
					<tr>				
					<td>
					<div class="boxinfo">
					<table cellspacing="5">
					<tr>
					<td valign="top" align="left">
					<?php if (isset($screenshot)) {?>
					    <a href="<?=$screenshot?>"><img class="screenshot" src="<?php echo "/thumbnail.php?pic=$screenshot_path";?>"></a>
					<?php } ?>
					</td>
					<td valign="top" align="left">
					<b>Categories:</b>
					<ul>
					<?php foreach($categories->result() as $category):?>
					        <li><?=anchor("software/browse/$category->id", "$category->name")?></li>
					<?php endforeach;?>
					</ul>
					<?php if($categories->num_rows == 0):?>
					        <p><i>No categories.</i></p>
					<?php endif;?>
					</td>
					<td valign="top">
					<b>Releases:</b>
					<ul>
					<?php foreach($releases->result() as $release):?>
					        <li><?=anchor("release/view/$release->id", "$release->name ($release->version)")?></li>
					<?php endforeach;?>
					</ul>
					<?php if($releases->num_rows == 0):?>
					        <p><i>No releases.</i></p>
					<?php endif;?>
					</td>
					<td valign="top">
					<b>Score:</b><br/>
					<?php 
					 	if ($overall_score > 0) { 
							$color = "#8cc153";
						}
						elseif ($overall_score < 0) {
							$color = "#c15353";
						}
						else {
							$color = "#c1ab53";
						}
					?>
					<font color="<?=$color?>" size="10">&nbsp;<?=$overall_score?></font><br/>
					<?php 
						if ($num_reviews > 1) {
							echo $num_reviews." reviews";
						}
						elseif ($num_reviews == 1) {
							echo "1 review";
						}
						else {
							echo "no reviews";
						}
					?>
					</td>
					</tr>
					</table>
					</div>
					</td>
					</tr>
					<tr><th>Description:</th></tr>
					<tr><td><blockquote><p><?=$description?></p></blockquote></td></tr>

					<?php if ($this->dx_auth->is_logged_in()) { ?>
						<tr><th>Your review:</th></tr>
						<tr><td><div class="boxinfo">
						<?php 
						if ($reviewed) {
							$score = $review->score;
							$comment = $review->comment;
							$submit_label = "Update review";
						} else {
							$score = 3;
							$comment = "";
							$submit_label = "Add review";
						}
						?>
						<?= form_open("software/review/$pkg_name") ?>
						<select name="score_given">
							<?php
							$descriptions = array("", "Hate it", "Not a fan", "So so", "Like it", "Awesome!");
							foreach (range(1, 5) as $i) {
								echo "<option value=\"$i\"";
								if ($i == $score) {
									echo " selected=\"selected\"";
								}
								echo ">$i ($descriptions[$i])</option>";
							}
							?>
						</select>
						<input type="text" size="40" name="comment_given" value="<?=$comment?>">
						<input type="submit" name="mysubmit" value="<?=$submit_label?>"/>
						</form>
						</div>
						</td></tr>
					<?php } ?>
					<tr><th>User reviews:</th></tr>
					<tr><td>
					<div class="boxinfo">
					<table summary="Comments" class="commentsTable" cellspacing="0">
					<tr><td colspan="2"></td><th align="center">Score</th><th align="center">Comment</th><th></th></tr>
						<?php foreach($reviews->result() as $review):
							$avtr = '/img/default_avatar.jpg';
							if (file_exists(FCPATH.'uploads/avatars/'.$review->user.".jpg")) {
									$avtr = '/uploads/avatars/'.$review->user.".jpg";
							}
							if ($review->score > 3) { 
	                                                        $color = "#8cc153";
        	                                        }
                	                                elseif ($review->score < 3) {
                        	                                $color = "#c15353";
                                	                }
                                        	        else {
                                                	        $color = "#c1ab53";
	                                                }
						?>
						<tr>
						<td nowrap>
							<font color="orange"><?php
					                $array = preg_split("/,/", timespan($review->timestamp, time()));
					                echo strtolower($array[0]);						
					                ?> ago</font>
							</td>
                                                	<td>
								<center>
								<?=anchor("user/view/$review->user", "<img height=30 src=$avtr>")?><br/>
								<?=anchor("user/view/$review->user", "$review->username")?>
								</center>
							</td>
							<td><b><font size="3" color="<?=$color?>"><?=$review->score?></font></b></td>
							<td><?=$review->comment?></td>
							<td>
							<?php if ($this->dx_auth->is_moderator()) { ?>
									<?=anchor("software/moderate_review/$review->id/$pkg_name", "<img src=/img/delete.png>")?>								
							<?php } ?>
							</td>
						</tr>
	                                        <?php endforeach;?>
                                        <?php if($reviews->num_rows == 0):?>
                                                <tr><td><i>No reviews.</i></td></tr>
                                        <?php endif;?>
					</table>
					</div>
					</td></tr>

				</tbody>
				</table>



                                
                                    
                            </div>
                            <div class="cleared"></div>
                        </div>
                        
                            </div>
                        </div>
                    </div>
                </div>


