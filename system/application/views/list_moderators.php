<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/moderation.png" align="absmiddle"/>&nbsp;Moderation</span>
    </h2>
    <div class="art-PostContent">
    
    <?php
    if ($this->dx_auth->is_administrator()) {
	?>
		<?php echo form_open("user/add_moderator"); ?>
		<table>
		<tr><th>Username:</th><td><input type="text" name="username" /></td></tr>
		<td colspan="2" align="center"><span class="art-button-wrapper">
			<span class="l"> </span>
			<span class="r"> </span>
			<input class="art-button" type="submit" name="search" value="Make moderator"/>
		</span></td></tr>
		</table>
		</form> 
	<?php } ?>

<table cellspacing="2" cellpadding="5">
	<tr>
		<td width="50%" valign="top">
		<h3>General rules</h3>
			<p>Moderators can delete content if they consider it:</p>
			<ul>
				<li>spam</li>
				<li>off-topic</li>
				<li>offensive</li>
				<li>controversial</li>
			</ul>
			<p>Moderators should not delete other kinds of content. In particular, moderators are not entitled to assess the quality or lack of quality of content on the website.</p>
			<p>Moderators should be careful not to delete content which they cannot read due to different character encodings. In particular, some content can appear with '?' characters to some users/moderatoris, and this content should not be deleted.</p>
			
		<h3>Moderators rights</h3>
			<p>Moderators currently have the following superpowers:</p>
			<ul>
				<li>Appear on this page</li>
				<li>Can delete ideas</li>
				<li>Can delete tutorials</li>
				<li>Can delete software reviews</li>
				<li>Can delete users</li>
			</ul>
			<p>Note to moderators: A "moderate (delete)" button appears on ideas and tutorials which score is less than 5. A negative score or the bad quality of some content is not reason enough for its deletion. Deletions should be motivated by violations of one or many of general rules, as described on this page. Per the user's request, or if in presence of a spammer or someone that needs to be banned, you can click the "delete this user" link on the user's profile if his/her score is below 20xp.</p>
				
		</td>
		<td valign="top">
			<h3>Current moderators</h3>
				<?php if($moderators->num_rows > 0) {?>
					<table>	
					<?php foreach($moderators->result() as $user):?>

					<?php
					$avatar = '/img/default_avatar.jpg';
					if (file_exists(FCPATH.'uploads/avatars/'.$user->id.".jpg")) {
						$avatar = '/uploads/avatars/'.$user->id.".jpg";
					}
					?>

						<tr>
							<td><img src="<?=$avatar?>" height="30" align="middle"/></td><td><?=anchor("user/view/$user->id", "$user->username")?></td>
							<?php if ($this->dx_auth->is_administrator()) {?>
								<td><?=anchor("user/remove_moderator/$user->id", "<img src=/img/delete.png>")?></td>
							<?php }?>
						</tr>
					<?php endforeach;?>
					</table>
				<?php } else { ?>
					<p><i>No moderators found.</i></p>
				<?php } ?>	
	
				<h3>Latest activity</h3>
				<?php if($moderators_activity->num_rows > 0) {?>
					<table>	
					<?php foreach($moderators_activity->result() as $activity):?>

					<?php
					$avatar = '/img/default_avatar.jpg';
					if (file_exists(FCPATH.'uploads/avatars/'.$activity->moderator.".jpg")) {
						$avatar = '/uploads/avatars/'.$activity->moderator.".jpg";
					}
					$array = preg_split("/,/", timespan($activity->timestamp, time()));
					$ago = strtolower($array[0]);
					?>

						<tr>
							<td><?=$ago?> ago</td>
							<td><img src="<?=$avatar?>" height="30" align="middle"/></td><td><?=anchor("user/view/$activity->moderator", "$activity->username")?></td>
							<td><?=$activity->activity?></td>
						</tr>
					<?php endforeach;?>
					</table>
				<?php } else { ?>
					<p><i>No moderation activity found.</i></p>
				<?php } ?>	
		</td>
	</tr>
</table>

</div>
    <div class="cleared"></div>
</div>

    </div>
</div>
</div>
</div>

