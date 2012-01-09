<div class="art-content">
	<div class="art-Post">
	    <div class="art-Post-body">
		<div class="art-Post-inner">
		    <h2 class="art-PostHeaderIcon-wrapper"><span class="art-PostHeader"><?=$name?></span></h2>
		    	<div class="art-PostContent">

<p>Please edit your hardware device:</p>

<b><font color="red"><?php echo validation_errors(); ?></font></b>

<?php
echo form_open('hardware/save');
?>
<input type="hidden" name="action" value="edit"/>
<input type="hidden" name="id" value="<?=$id?>"/>
<table class="art-article" border="0" cellspacing="0" cellpadding="0">
  <tbody>
	<tr><th>Name of the device:</th><td><input type="text" name="name" value="<?php echo set_value('name', $name); ?>" size="70"/></td></tr>
	<tr><th>Type of hardware:</th><td>
		<select name="category">				
			<?php foreach($categories->result() as $cat) {	?>
				<option value="<?=$cat->id?>" <?php echo set_select('category', $cat->id, ($category == $cat->id)); ?>><?=$cat->name?></option>							
			<?php } ?>
		</select></td></tr>
		<tr><th>If the type isn't listed please specify it here:</th><td><input type="text" name="category_txt" value="<?php echo set_value('category_txt'); ?>" size="70"/></td></tr>
	<tr><th>Brand:</th><td>
		<select name="brand">				
			<?php foreach($brands->result() as $brnd) { ?>	
				<option value="<?=$brnd->id?>" <?php echo set_select('brand', $brnd->id, ($brand == $brnd->id)); ?>><?=$brnd->name?></option>	
			<?php } ?>							
		</select></td></tr>
		<tr><th>If the brand isn't listed please specify it here:</th><td><input type="text" name="brand_txt" value="<?php echo set_value('brand_txt'); ?>" size="70"/></td></tr>

	<tr><th>Overall status:</th><td>
		<select name="status">				
			<?php foreach($statuses->result() as $stt) { ?>
				<option value="<?=$stt->id?>" <?php echo set_select('status', $stt->id, ($status == $stt->id)); ?>><?=$stt->name?></option>
			<?php } ?>	
		</select></td></tr>
	<tr><th>Running on the following release:</th><td>
		<select name="release">				
			<?php foreach($releases->result() as $release) { ?>	
				<option value="<?=$release->id?>" <?php echo set_select('release', $release->id, ($distro_release == $release->id)); ?>><?=$release->name?></option>				
			<?php } ?>	
		</select></td></tr>

	<tr><th>Explain what works:</th><td><textarea name="what_works" rows="20" cols="70"><?php echo set_value('what_works', $what_works); ?></textarea></td></tr>
	<tr><th>Explain what doesn't work:</th><td><textarea name="what_doesnt_work" rows="20" cols="70"><?php echo set_value('what_doesnt_work', $what_doesnt_work); ?></textarea></td></tr>
	<tr><th>Explain what you did to make it work (if applicable):</th><td><textarea name="what_i_did_to_make_it_work" rows="20" cols="70"><?php echo set_value('what_i_did_to_make_it_work', $what_i_did_to_make_it_work); ?></textarea></td></tr>
	<tr><th>Additional notes:</th><td><textarea name="additional_notes" rows="20" cols="70"><?php echo set_value('additional_notes', $additional_notes); ?></textarea></td></tr>
	
	<tr><td colspan="2" align="center">
		 <span class="art-button-wrapper">
                	<span class="l"> </span>
                	<span class="r"> </span>
                	<input class="art-button" type="submit" name="submit" value="Save this hardware device"/>
                </span>
	</td></tr>
</form> 
</tbody>
</table>
			</div>
			<div class="cleared"></div>
</div>
</div>
</div>
</div>

