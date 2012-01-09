<div class="art-content">
	<div class="art-Post">
	    <div class="art-Post-body">
		<div class="art-Post-inner">
		    <h2 class="art-PostHeaderIcon-wrapper"><span class="art-PostHeader"><img src="/img/icons/hardware.png" align="absmiddle"/>&nbsp;New hardware device</span></h2>
		    	<div class="art-PostContent">

<p>Please describe your device:</p>


<script> 
function checkForOther(combo, inputfield) { 
	if (!document.layers) { 		
		if (combo.value == "other") { 
			inputfield.style.display = "inline"; 
			// gives the text field the name of the drop-down, for easy processing 
			//txt.name = "selTitle"; 
			//obj.name = ""; 
		} else { 
			inputfield.style.display = "none"; 
			//txt.name = ""; 
			//obj.name = "selTitle"; 
		} 
	} 
} 
</script>  

<b><font color="red"><?php echo validation_errors(); ?></font></b>

<?php
echo form_open('hardware/save');
?>
<input type="hidden" name="action" value="add"/>
<table class="art-article" border="0" cellspacing="0" cellpadding="0">
  <tbody>	
	<tr><th>Category:</th><td>
		<select name="category" onchange="checkForOther(this, document.getElementById('otherCategory'))">				
			<?php foreach($categories->result() as $category):?>
				<option value="<?=$category->id?>" <?php echo set_select('category', $category->id); ?>><?=$category->name?></option>
			<?php endforeach;?>
			<optgroup></optgroup>
			<!--<option value="other">Other, please specify:</option>-->
		</select><input style="display:none" id="otherCategory" type="text" name="category_txt" value="<?php echo set_value('otherCategory'); ?>"/></td></tr>		
	<tr><th>Brand:</th><td>
		<select name="brand" onchange="checkForOther(this, document.getElementById('otherBrand'))">				
			<?php foreach($brands->result() as $brand):?>
				<option value="<?=$brand->id?>" <?php echo set_select('brand', $brand->id); ?>><?=$brand->name?></option>
			<?php endforeach;?>
			<optgroup></optgroup>
			<!--<option value="other">Other, please specify:</option>-->
		</select><input style="display:none" id="otherBrand" type="text" name="brand_txt" value="<?php echo set_value('otherBrand'); ?>"/></td></tr>
	<tr><th>Model name:</font></th><td><input type="text" name="name" value="<?php echo set_value('name'); ?>"/> <font color="red" size="1"><i>Do not include the brand</i></td></tr>
	<tr><th>Overall status:</th><td>
		<select name="status">				
			<?php foreach($statuses->result() as $status):?>
				<option value="<?=$status->id?>" <?php echo set_select('status', $status->id); ?>><?=$status->name?></option>
			<?php endforeach;?>
		</select></td></tr>
	<tr><th>Running on the following release:</th><td>
		<select name="release">				
			<?php foreach($releases->result() as $release):?>
				<option value="<?=$release->id?>" <?php echo set_select('release', $release->id); ?>><?=$release->name?></option>
			<?php endforeach;?>
		</select></td></tr>

	<tr><th>Explain what works:</th><td><textarea name="what_works" rows="20" cols="70"><?php echo set_value('what_works'); ?></textarea></td></tr>
	<tr><th>Explain what doesn't work:</th><td><textarea name="what_doesnt_work" rows="20" cols="70"><?php echo set_value('what_doesnt_work'); ?></textarea></td></tr>
	<tr><th>Explain what you did to make it work (if applicable):</th><td><textarea name="what_i_did_to_make_it_work" rows="20" cols="70"><?php echo set_value('what_i_did_to_make_it_work'); ?></textarea></td></tr>
	<tr><th>Additional notes:</th><td><textarea name="additional_notes" rows="20" cols="70"><?php echo set_value('additional_notes'); ?></textarea></td></tr>
	
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

