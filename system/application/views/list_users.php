<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/users.png" align="absmiddle"/>&nbsp;Users</span>
    </h2>
    <div class="art-PostContent">

	<?php echo form_open("user/search"); ?>
	<table>
	<tr><th>Username:</th><td><input type="text" name="search_user_username" value="<?=$search_user_username?>"/></td></tr>
	<tr><th>Email:</th><td><input type="text" name="search_user_email" value="<?=$search_user_email?>"/></td></tr>
	<tr><th>Country:</th><td>
		<select name="search_user_country">
			<option value="-1">Any</option>
			<option value="0" <?php if ($search_user_country == "any") {?>SELECTED<?php }?>>None</option>
			<?php foreach($countries->result() as $country):
				echo "<option value=\"".$country->id."\" ";
				if ($search_user_country == $country->id) {
					echo "SELECTED";
				}
				echo ">".$country->name."</option>";
			endforeach;?>
		</select></td></tr>
	<tr><th>Release:</th><td>
		<select name="search_user_release">
			<option value="-1">Any</option>
			<option value="0" <?php if ($search_user_release == "any") {?>SELECTED<?php }?>>None</option>
			<?php foreach($releases->result() as $release):
				echo "<option value=\"".$release->id."\" ";
				if ($search_user_release == $release->id) {
					echo "SELECTED";
				}
				echo ">".$release->name."</option>";
			endforeach;?>
		</select></td></tr>
	<tr><th>Edition:</th><td>
		<select name="search_user_edition">
			<option value="-1">Any</option>
			<option value="0" <?php if ($search_user_edition == "any") {?>SELECTED<?php }?>>None</option>
			<?php foreach($editions->result() as $edition):
				echo "<option value=\"".$edition->id."\" ";
				if ($search_user_edition == $edition->id) {
					echo "SELECTED";
				}
				echo ">".$edition->name."</option>";
			endforeach;?>
		</select></td></tr>
	
	<td colspan="2" align="center"><span class="art-button-wrapper">
		<span class="l"> </span>
		<span class="r"> </span>
		<input class="art-button" type="submit" name="search" value="Search"/>
	</span></td></tr>
	</table>
	</form> 

<?php if($users->num_rows > 0) {?>
	<table>	
	<th>Score</th><th colspan="2">User</th>
	<?php foreach($users->result() as $user):?>

	<?php
	$avatar = '/img/default_avatar.jpg';
	if (file_exists(FCPATH.'uploads/avatars/'.$user->id.".jpg")) {
                        $avatar = '/uploads/avatars/'.$user->id.".jpg";
        }
	?>

		<tr>
			<td><?=$user->score?>xp</td><td><img src="<?=$avatar?>" height="30" align="middle"/></td><td><?=anchor("user/view/$user->id", "$user->username")?></td>
		</tr>
	<?php endforeach;?>
	</table>
<?php } else { ?>
	<p><i>No users found.</i></p>
<?php } ?>

<?php echo $this->pagination->create_links(); ?>

</div>
    <div class="cleared"></div>
</div>

    </div>
</div>
</div>
</div>

