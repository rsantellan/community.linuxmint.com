<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/iso.png" align="absmiddle"/>&nbsp;ISO Testing Team</span>
    </h2>
    <div class="art-PostContent">

<table cellspacing="2" cellpadding="5">
	<tr>
		<td valign="top">
			<h3>Maintainers</h3>
				<?php if ($this->dx_auth->is_administrator()) {?>
					<?php echo form_open("iso/add_maintainer"); ?>
					<table>						
					<tr><td valign=center><input type="text" size=10 name="username"/><input type="submit" name="search" value="New"/></td></tr>
					</form> 		
					</table>
				<?php } ?>
				<?php if($maintainers->num_rows > 0) {?>
					<table>	
					<?php foreach($maintainers->result() as $user):?>

					<?php
					$avatar = '/img/default_avatar.jpg';
					if (file_exists(FCPATH.'uploads/avatars/'.$user->id.".jpg")) {
						$avatar = '/uploads/avatars/'.$user->id.".jpg";
					}
					?>

						<tr>
							<td><img src="<?=$avatar?>" height="30" align="middle"/></td><td><?=anchor("user/view/$user->id", "$user->username")?></td>
							<?php if ($this->dx_auth->is_administrator()) {?>
								<td><?=anchor("iso/remove_maintainer/$user->id", "<img src=/img/delete.png>")?></td>
							<?php }?>
						</tr>
					<?php endforeach;?>
					</table>
				<?php } else { ?>
					<p><i>No maintainers found.</i></p>
				<?php } ?>
				
			<h3>Testers</h3>
				<?php if ($this->dx_auth->is_administrator()) {?>
					<?php echo form_open("iso/add_tester"); ?>
					<table>						
					<tr><td valign=center><input type="text" size=10 name="username"/><input type="submit" name="search" value="New"/></td></tr>
					</form> 		
					</table>
				<?php } ?>
				<?php if($testers->num_rows > 0) {?>
					<table>	
					<?php foreach($testers->result() as $user):?>

					<?php
					$avatar = '/img/default_avatar.jpg';
					if (file_exists(FCPATH.'uploads/avatars/'.$user->id.".jpg")) {
						$avatar = '/uploads/avatars/'.$user->id.".jpg";
					}
					?>

						<tr>
							<td><img src="<?=$avatar?>" height="30" align="middle"/></td><td><?=anchor("user/view/$user->id", "$user->username")?></td>
							<?php if ($this->dx_auth->is_administrator()) {?>
								<td><?=anchor("iso/remove_tester/$user->id", "<img src=/img/delete.png>")?></td>
							<?php }?>
						</tr>
					<?php endforeach;?>
					</table>
				<?php } else { ?>
					<p><i>No testers found.</i></p>
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

