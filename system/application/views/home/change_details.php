<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader">Account details</span>
    </h2>
    <div class="art-PostContent">
        <p>Change your account details:</p>
<?php
echo form_open_multipart('user/save_details');
?>

<img src="<?=$avatar?>" style="float: left"/>
<?php if(substr_count($avatar, 'default_avatar') == 0): ?>
  <a href="<?php echo site_url('user/deleteAvatar');?>" onclick="return confirm('Are you sure do you want to delete avatar?')">
    <img src="/images/delete.png" style="float: left"/>
  </a>
<?php endif;?>
<table>
<tr><th>Avatar</th><td><input type="file" name="avatar" size="20"> (.jpg, 100x100px, 100KB max) </td></tr>

<tr><th>Signature</th><td><input type=text size=25 name="signature" value="<?=$signature?>" /></td></tr>

<tr><th>Biography</th><td><textarea name="biography" rows=10 cols=50><?=$biography?></textarea></td></tr>

<tr><th>Country</th><td><select name="country"><option value="0">None</option>
<?php foreach($countries->result() as $country):
	echo "<option value=\"".$country->id."\" ";
	if ($country_id == $country->id) {
		echo "SELECTED";
	}
	echo ">".$country->name."</option>";
endforeach;?>
</select></td></tr>

<tr><th>Release</th><td><select name="release"><option value="0">None</option>
<?php foreach($releases->result() as $release):
	echo "<option value=\"".$release->id."\" ";
	if ($release_id == $release->id) {
		echo "SELECTED";
	}
	echo ">".$release->name."</option>";
endforeach;?>
</select></td></tr>

<tr><th>Edition</th><td><select name="edition"><option value="0">None</option>
<?php foreach($editions->result() as $edition):
	echo "<option value=\"".$edition->id."\" ";
	if ($edition_id == $edition->id) {
		echo "SELECTED";
	}
	echo ">".$edition->name."</option>";
endforeach;?>
</select></td></tr>

<tr><th></th><td><input type="checkbox" name="disable_emails" value="1" <?php if ($disable_emails == 1) { echo "CHECKED"; } ?> />Disable email notifications</td></tr>

<td colspan="2" align="center"><span class="art-button-wrapper">
                	<span class="l"> </span>
                	<span class="r"> </span>
                	<input class="art-button" type="submit" name="submit" value="Save"/>
</span></td></tr>
</table>
</form> 

      
            
    </div>
    <div class="cleared"></div>
</div>

    </div>
</div>
</div>
</div>


<script type="text/javascript">
	CKEDITOR.replace( 'biography' );
</script>
