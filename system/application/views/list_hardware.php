<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/hardware.png" align="absmiddle"/>&nbsp;<?=$view_title?></span>
    </h2>
    <div class="art-PostContent">


<table>
<tr>
<?php if ($view_all):?>
<td>
	<?php echo form_open("hardware/search"); ?>
	<table>
	<tr><th>Type:</th><td>
		<select name="search_hardware_category">
				<option value="-1">Any</option>
			<?php foreach($categories->result() as $category):
				echo "<option value=\"".$category->id."\" ";
				if ($search_hardware_category == $category->id) {
					echo "SELECTED";
				}
				echo ">".$category->name."</option>";
			endforeach;?>
		</select></td></tr>
	<tr><th>Brand:</th><td>
		<select name="search_hardware_brand">
				<option value="-1">Any</option>
			<?php foreach($brands->result() as $brand):
				echo "<option value=\"".$brand->id."\" ";
				if ($search_hardware_brand == $brand->id) {
					echo "SELECTED";
				}
				echo ">".$brand->name."</option>";
			endforeach;?>
		</select></td></tr>
	<tr><th>Status:</th><td>
		<select name="search_hardware_status">
				<option value="-1">Any</option>
			<?php foreach($statuses->result() as $status):
				echo "<option value=\"".$status->id."\" ";
				if ($search_hardware_status == $status->id) {
					echo "SELECTED";
				}
				echo ">".$status->name."</option>";
			endforeach;?>
		</select></td></tr>
	<tr><th>Release:</th><td>
		<select name="search_hardware_release">
				<option value="-1">Any</option>
			<?php foreach($releases->result() as $release):
				echo "<option value=\"".$release->id."\" ";
				if ($search_hardware_release == $release->id) {
					echo "SELECTED";
				}
				echo ">".$release->name."</option>";
			endforeach;?>
		</select></td></tr>
	<tr><th>Name:</th><td><input type="text" name="search_hardware_name" value="<?=$search_hardware_name?>"/></td></tr>
	<td colspan="2" align="center"><span class="art-button-wrapper">
		<span class="l"> </span>
		<span class="r"> </span>
		<input class="art-button" type="submit" name="search" value="Search"/>
	</span></td></tr>
	</table>
	</form> 
</td>
<?php endif;?>

</tr></table>
<?php if($hardware_devices->num_rows > 0) {?>
	<table class="art-article" border="0" cellspacing="0" cellpadding="0">
	  <tbody>
		<tr><th>Type</th><th>Brand</th><th>Model name</th><th>Release</th><th>Status</th></tr>
	<?php foreach($hardware_devices->result() as $device):?>
		<tr 
		<?php 
			switch($device->status_id) {
				case 1:
					echo "style='background-color:#e5ffd7'";
					break;
				case 2:
					echo "style='background-color:#f2ffd7'";
					break;
				case 3:
					echo "style='background-color:#fffad7'";
					break;
				case 4:
					echo "style='background-color:#ffefd7'";
					break;
				case 5:
					echo "style='background-color:#ffddd7'";
					break;
			}
		?> class="<?= alternator('', 'even') ?>"><td><?=$device->category?></td><td><?=$device->brand?></td><td><?=anchor("hardware/view/$device->id", "$device->name")?></td><td><?=$device->distro_release?></td><td><?=$device->status?></td></tr>
	<?php endforeach;?>
	</tbody></table>
<?php } else { ?>
	<p><i>No hardware found.</i></p>
<?php } ?>

<?php if ($view_all):?>
<?php echo $this->pagination->create_links(); ?>
<?php endif;?>

<?php if($view_mine):?>
	<p>
		<span class="art-button-wrapper">
			<span class="l"> </span>
			<span class="r"> </span>
			<?=anchor("hardware/add", "Add a new hardware device", 'class="art-button"')?>
		</span>
	</p>
<?php endif;?>

</div>
    <div class="cleared"></div>
</div>

    </div>
</div>
</div>
</div>

