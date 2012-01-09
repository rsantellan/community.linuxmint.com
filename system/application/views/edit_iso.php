<div class="art-content">
	<div class="art-Post">
	    <div class="art-Post-body">
		<div class="art-Post-inner">
		    <h2 class="art-PostHeaderIcon-wrapper"><span class="art-PostHeader"><?=$iso_name?></span></h2>
		    	<div class="art-PostContent">

<p>Please edit your ISO:</p>

<?php
echo form_open('iso/save');
?>
<input type="hidden" name="action" value="edit"/>
<input type="hidden" name="id" value="<?=$iso->id?>"/>
<table class="art-article" border="0" cellspacing="0" cellpadding="0">
  <tbody>
	<tr><td>Release:</td><td><select name="distro_release">
	<?php foreach($releases->result() as $release):
		echo "<option value=\"".$release->id."\" ";	
		if ($release->id == $iso->distro_release) { 
			echo "SELECTED";
		}
		echo ">".$release->name."</option>";
	endforeach;?>
	</select></td></tr>

	<tr><td>Edition:</td><td><select name="edition">
	<?php foreach($editions->result() as $edition):
		echo "<option value=\"".$edition->id."\" ";	
		if ($edition->id == $iso->edition) { 
			echo "SELECTED";
		}					
		echo ">".$edition->name."</option>";
	endforeach;?>
	</select></td></tr>					
	
	<tr><td>Architecture:</td><td><input type="text" name="architecture" value="<?=$iso->architecture?>"/></td></tr>
	<tr><td>Build #:</td><td><input type="text" name="build" value="<?=$iso->build?>"/></td></tr>
	<tr><td>URL:</td><td><input type="text" name="url" size=70 value="<?=$iso->url?>"/></td></tr>					
	<tr><td>MD5:</td><td><input type="text" name="md5" size=40 value="<?=$iso->md5?>"/></td></tr>
	
	<tr><td colspan="2" align="center">
		 <span class="art-button-wrapper">
                	<span class="l"> </span>
                	<span class="r"> </span>
                	<input class="art-button" type="submit" name="submit" value="Save"/>
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

