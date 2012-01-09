<div class="art-content">
	<div class="art-Post">
	    <div class="art-Post-body">
		<div class="art-Post-inner">
		    <h2 class="art-PostHeaderIcon-wrapper"><span class="art-PostHeader"><?=$tutorial_title?></span></h2>
		    	<div class="art-PostContent">

<p>Please edit your tutorial:</p>

<b><font color="red"><?php echo validation_errors(); ?></font></b>

<?php
echo form_open('tutorial/save');
?>
<input type="hidden" name="action" value="edit"/>
<input type="hidden" name="id" value="<?=$tutorial_id?>"/>
<p><b>Title:&nbsp;</b><input type="text" name="title" value="<?php echo set_value('title', $tutorial_title); ?>"  size="70"/></p>
<p><b>Tags:&nbsp;</b><input type="text" name="tags" value="<?php echo set_value('tags', $tutorial_tags); ?>"  size="70"/></p>
<p><textarea name="body"><?php echo set_value('body', $tutorial_body); ?></textarea></p>
<p>		 <span class="art-button-wrapper">
                	<span class="l"> </span>
                	<span class="r"> </span>
                	<input class="art-button" type="submit" name="submit" value="Save this tutorial"/>
                </span>
</p>
</form> 
			</div>
			<div class="cleared"></div>
</div>
</div>
</div>
</div>


<script type="text/javascript">
	CKEDITOR.replace( 'body' );
</script>
