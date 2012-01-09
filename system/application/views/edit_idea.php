<div class="art-content">
	<div class="art-Post">
	    <div class="art-Post-body">
		<div class="art-Post-inner">
		    <h2 class="art-PostHeaderIcon-wrapper"><span class="art-PostHeader"><?=$idea_title?></span></h2>
		    	<div class="art-PostContent">

<p>Please edit your idea:</p>

<?php
echo form_open('idea/save');
?>
<input type="hidden" name="action" value="edit"/>
<input type="hidden" name="id" value="<?=$idea_id?>"/>
<table class="art-article" border="0" cellspacing="0" cellpadding="0">
  <tbody>
	<tr><th>Title</th><td><input type="text" name="title" value="<?=$idea_title?>" size="70"/></td></tr>
	<tr><th>Idea</th><td><textarea name="body" rows="20" cols="70"><?=$idea_body?></textarea></td></tr>
	<tr><td colspan="2" align="center">
		 <span class="art-button-wrapper">
                	<span class="l"> </span>
                	<span class="r"> </span>
                	<input class="art-button" type="submit" name="submit" value="Save this idea"/>
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

