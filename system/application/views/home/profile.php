        <div class="art-content">
               
                        <div class="art-Post">
                            <div class="art-Post-body">
				<a onClick="history.go(-1)">Back</a>

<?php if ($user_id == $this->dx_auth->get_user_id()) { ?>
                <div align=right>
                    <span class="art-button-wrapper">
                        <span class="l"> </span>
                        <span class="r"> </span>
                        <?=anchor("auth/change_password", "Change password", 'class="art-button"')?>
                    </span>

                    <span class="art-button-wrapper">
                        <span class="l"> </span>
                        <span class="r"> </span>
                        <?=anchor("user/change_details", "Change account details", 'class="art-button"')?>
                    </span>
                </div>
			<?php }?>

                        <div class="art-Post-inner">
                            <h2 class="art-PostHeaderIcon-wrapper">
                                <span class="art-PostHeader"><img src="/img/icons/profile.png" align="absmiddle"/>&nbsp;<?=$user_name?><br/><small><font color=grey size="2"><?=$score?>xp</font></small></span>				
                            </h2>
				<center>Last login: <?=$last_login?></center>



   			 <div class="art-PostContent">
			
			<table border="0" cellspacing="0" cellpadding="0">
                          <tbody>
				<tr>
				<th>&nbsp;</th>
				<td>
				<div class="boxinfo">
				<table cellspacing="5">
				<tr>
				<td valign="top" align="center">
  			    	<img src="<?=$avatar?>"><br/><br/>
                    <i><font size="1"><?=$signature?></font></i>
				</td>
				<td valign="top" align="left">	
				<?php if ($country_name != "None") {?>
					<img src="/img/flags/<?=$country_code?>.gif"/>&nbsp;<?=anchor("/country/view/$country_id", "$country_name")?><br/>
				<?php }?>
				<?php if ($release_name != "None") {?>
					<?=$release_name?><br/>
				<?php }?>
				<?php if ($edition_name != "None") {?>
					<?=$edition_name?>
				<?php }?>
				</td>
				<td valign="top">
				<?php 
				if ($this->dx_auth->is_logged_in()) {
					if ($user_id != $this->dx_auth->get_user_id()) { 
						
						$myself = $this->dx_auth->get_user_id();

						if ($myself["score"] > 0) {
							echo anchor("message/compose/$user_id", "<img src='/img/message.png' align=absmiddle>")."&nbsp;".anchor("message/compose/$user_id", "Send message");
						}

						$this->db->where("user", $myself);
						$this->db->where("friend", $user_id);
						$query_friends = $this->db->get("friends");
						if ($query_friends->num_rows() > 0) {
							echo "<br/>".anchor("friend/delete/$user_id", "<img src='/img/friend.png' align=absmiddle>")."&nbsp;".anchor("friend/delete/$user_id", "Remove from friends");
						}
						else {
							echo "<br/>".anchor("friend/add/$user_id", "<img src='/img/friend.png' align=absmiddle>")."&nbsp;".anchor("friend/add/$user_id", "Add as friend");
						}
					}
				 } 
				?>	
				
				 <?php if (($this->dx_auth->is_moderator() && intval($score) < 20) || $this->dx_auth->is_administrator()) { ?>
					
						<br/>						
						<img src='/img/delete.png' align=absmiddle>&nbsp;<?=anchor("user/delete/$user_id", "<font color='#660000'>Delete this user</font>", "onclick=\"return confirm('Are you sure you want to delete this user and all his/her activity on this website?');\"")?>						
				<?php } ?>
							
				</td>
				</tr>
				</table>
				</div>
				</td>
				</tr>
                
                <?php if ($biography != "") {?>
                    <tr><th>Biography:</th><td>
                    <div class="boxinfo">
                        <?=$biography?>
                    </div>
                    </td></tr>
                <?php } ?>
                
				<tr><th>Friends:</th><td>
				<div class="boxinfo">
				<?php if($friends->num_rows > 0) {?>
					<table border="0" cellspacing="3" cellpadding="3">
					  <tbody><tr>		
					<?php foreach($friends->result() as $friend):
						if (file_exists(FCPATH.'uploads/avatars/'.$friend->friend_id.".jpg")) {
							$avatar = '/uploads/avatars/'.$friend->friend_id.".jpg";
						}
						else {
							$avatar = '/img/default_avatar.jpg';
						}		
						 echo alternator('</tr><tr>', '', '', '', '');
					?>						
						<td align=center nowrap>
							<?=anchor("user/view/$friend->friend_id", "<img src=$avatar height=50>")?>
							<br/>													
							<small><?=anchor("user/view/$friend->friend_id", $friend->friend_name)?>&nbsp;<font color=grey>(<?=$friend->score?>xp)</font></small>
							</td>	
					<?php endforeach;?>
					</tr></tbody></table>
				<?php } else { ?>
					<p><i>No friends found.</i></p>
				<?php } ?>
				</div>
				</td></tr>
				<tr><th>Ideas:</th><td>
				<div class="boxinfo">
				<?php if($ideas->num_rows > 0) {?>
					<table border="0" cellspacing="3" cellpadding="3">
					  <tbody>						
					<?php foreach($ideas->result() as $idea):?>
						<tr class="<?= alternator('', 'even') ?>"><td><td><?=anchor("idea/view/$idea->id", "$idea->status - $idea->title<br/><small><font color=grey>score: $idea->score, $idea->votes votes and $idea->comments comments</font></small>")?></td></tr>
					<?php endforeach;?>
					</tbody></table>
				<?php } else { ?>
					<p><i>No ideas found.</i></p>
				<?php } ?>
				</div>
				</td></tr>
				<tr><th>Tutorials:</th><td>
				<div class="boxinfo">
				<?php if($tutorials->num_rows > 0) {?>
					<table border="0" cellspacing="3" cellpadding="3">
					  <tbody>						
					<?php foreach($tutorials->result() as $tutorial):?>
						<tr class="<?= alternator('', 'even') ?>"><td><td><?=anchor("tutorial/view/$tutorial->id", "$tutorial->title<br/><small><font color=grey>score: $tutorial->score, $tutorial->votes votes and $tutorial->comments comments</font></small>")?></td></tr>
					<?php endforeach;?>
					</tbody></table>
				<?php } else { ?>
					<p><i>No tutorials found.</i></p>
				<?php } ?>
				</div>
				</td></tr>
				<tr><th>Hardware:</th><td>
				<div class="boxinfo">
				<?php if($hardware_devices->num_rows > 0) {?>
					<table border="0" cellspacing="3" cellpadding="3">
					  <tbody>						
					<?php foreach($hardware_devices->result() as $device):?>
						<tr><td><?=anchor("hardware/view/$device->id", "$device->name")?><br/><small><font color=grey><?=$device->brand?> <?=$device->category?>, <?=$device->status?> on <?=$device->distro_release?></font></small></td></tr>
					<?php endforeach;?>
					</tbody></table>
				<?php } else { ?>
					<p><i>No hardware found.</i></p>
				<?php } ?>
				</div>
				</td></tr>
				<tr><th>Favorite software:</th><td>
				<div class="boxinfo">
				<?php if($reviews->num_rows > 0) {?>
					<table border="0" cellspacing="3" cellpadding="3">
					  <tbody>						
					<?php foreach($reviews->result() as $review):?>
						<tr><td><?=anchor("software/view/$review->pkg_name", "$review->pkg_name")?><br/><small><font color=grey>"<?=$review->comment?>"</font></small></td></tr>
					<?php endforeach;?>
					</tbody></table>
				<?php } else { ?>
					<p><i>No reviews found.</i></p>
				<?php } ?>
				</div>
				</td></tr>				
			</tbody>
			</table>

</p>

			



 </div>
    <div class="cleared"></div>
</div>

    </div>
</div>
</div>

