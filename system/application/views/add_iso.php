<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/iso.png" align="absmiddle"/>&nbsp;New ISO Image</span>
    </h2>
    <div class="art-PostContent">

<table cellspacing="2" cellpadding="5">
	<tr>
		<td valign="top">			
					<?php echo form_open("iso/save"); ?>
					<input type="hidden" name="action" value="add"/>
					<table>						
					<tr><td>Release:</td><td><select name="distro_release">
					<?php foreach($releases->result() as $release):
						echo "<option value=\"".$release->id."\" ";						
						echo ">".$release->name."</option>";
					endforeach;?>
					</select></td></tr>

					<tr><td>Edition:</td><td><select name="edition">
					<?php foreach($editions->result() as $edition):
						echo "<option value=\"".$edition->id."\" ";						
						echo ">".$edition->name."</option>";
					endforeach;?>
					</select></td></tr>					
					<tr><td>Architecture:</td><td><input type="text" name="architecture"/></td></tr>
					<tr><td>Build #:</td><td><input type="text" name="build"/></td></tr>
					<tr><td>URL:</td><td><input type="text" name="url"/></td></tr>					
					<tr><td>MD5:</td><td><input type="text" name="md5"/></td></tr>
					<tr><td colspan="2"><input type="submit" name="search" value="New"/></td></tr>
					</form> 		
					</table>
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

