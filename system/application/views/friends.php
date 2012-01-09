<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/friends.png" align="absmiddle"/>&nbsp;Friends</span>
    </h2>
    <div class="art-PostContent">	

<p>&nbsp;</p>

			<h4>Mutual friends</h4>
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
						  echo alternator('</tr><tr>', '', '', '', '', '');
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


			<h4>People I added as a friend</h4>
				<div class="boxinfo">
				<?php if($friends_invited->num_rows > 0) {?>
					<table border="0" cellspacing="3" cellpadding="3">
					  <tbody><tr>		
					<?php foreach($friends_invited->result() as $friend):
						if (file_exists(FCPATH.'uploads/avatars/'.$friend->friend_id.".jpg")) {
							$avatar = '/uploads/avatars/'.$friend->friend_id.".jpg";
						}
						else {
							$avatar = '/img/default_avatar.jpg';
						}		
						 echo alternator('</tr><tr>', '', '', '', '', '');
					?>						
						<td align=center nowrap>
							<?=anchor("user/view/$friend->friend_id", "<img src=$avatar height=50>")?>
							<br/>													
							<small><?=anchor("user/view/$friend->friend_id", $friend->friend_name)?>&nbsp;<font color=grey>(<?=$friend->score?>xp)</font></small>
							</td>				
					<?php endforeach;?>
					</tr></tbody></table>
				<?php } else { ?>
					<p><i>Nobody</i></p>
				<?php } ?>
				</div>
				
			<h4>People who added me as a friend</h4>
				<div class="boxinfo">
				<?php if($friends_invitations->num_rows > 0) {?>
					<table border="0" cellspacing="3" cellpadding="3">
					  <tbody><tr>		
					<?php foreach($friends_invitations->result() as $friend):
						if (file_exists(FCPATH.'uploads/avatars/'.$friend->friend_id.".jpg")) {
							$avatar = '/uploads/avatars/'.$friend->friend_id.".jpg";
						}
						else {
							$avatar = '/img/default_avatar.jpg';
						}		
						 echo alternator('</tr><tr>', '', '', '', '', '');
					?>						
						<td align=center nowrap>
							<?=anchor("user/view/$friend->friend_id", "<img src=$avatar height=50>")?>
							<br/>													
							<small><?=anchor("user/view/$friend->friend_id", $friend->friend_name)?>&nbsp;<font color=grey>(<?=$friend->score?>xp)</font></small>
							</td>				
					<?php endforeach;?>
					</tr></tbody></table>
				<?php } else { ?>
					<p><i>Nobody</i></p>
				<?php } ?>
				</div>

</div>
    <div class="cleared"></div>
</div>

    </div>
</div>
</div>
</div>

