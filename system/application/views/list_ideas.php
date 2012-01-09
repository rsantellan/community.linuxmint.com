<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/ideas.png" align="absmiddle"/>&nbsp;<?=$view_title?></span>
    </h2>
    <div class="art-PostContent">

<?php if ($view_all):?>
	<?php echo form_open("idea/search"); ?>
	<table>
	<tr><th>Title:</th><td><input type="text" name="search_title" value="<?=$search_title?>"/></td><th>Content:</th><td><input type="text" name="search_body" value="<?=$search_body?>"/></td></tr>
	<tr><th>Status:</th><td>
		<select name="search_status">
			<?php foreach($statuses->result() as $status):
				echo "<option value=\"".$status->id."\" ";
				if ($search_status == $status->id) {
					echo "SELECTED";
				}
				echo ">".$status->name."</option>";
			endforeach;?>
		</select></td></tr>
	<tr><th>Sort:</th><td>
		<select name="search_sort">
			<option value="0" <?php if ($search_sort == 0):?>SELECTED<?php endif;?>>Top Rated</option>
			<option value="1" <?php if ($search_sort == 1):?>SELECTED<?php endif;?>>Latest</option>
			<option value="2" <?php if ($search_sort == 2):?>SELECTED<?php endif;?>>Most Voted</option>
			<option value="3" <?php if ($search_sort == 3):?>SELECTED<?php endif;?>>Most Commented</option>
		</select></td></tr>
	<tr><th>Filter:</th><td>
		<select name="search_filter">
			<option value="0" <?php if ($search_filter == 0):?>SELECTED<?php endif;?>>All ideas</option>
			<option value="1" <?php if ($search_filter == 1):?>SELECTED<?php endif;?>>Ideas I didn't vote</option>
			<option value="2" <?php if ($search_filter == 2):?>SELECTED<?php endif;?>>Ideas I am promoting</option>
			<option value="3" <?php if ($search_filter == 3):?>SELECTED<?php endif;?>>Ideas I am demoting</option>
			<option value="4" <?php if ($search_filter == 4):?>SELECTED<?php endif;?>>Ideas I don't care about</option>
		</select></td></tr>
	<td colspan="2" align="center"><span class="art-button-wrapper">
		<span class="l"> </span>
		<span class="r"> </span>
		<input class="art-button" type="submit" name="search" value="Search"/>
	</span></td></tr>
	</table>
	</form> 
<?php endif;?>

<?php if($ideas->num_rows > 0) {?>
	<table class="art-article" border="0" cellspacing="0" cellpadding="0">
	  <tbody>
		<tr><th>Score</th><th>Votes</th><th>Comments</th><th>Title</th></tr>
	<?php foreach($ideas->result() as $idea):?>
		<tr class="<?= alternator('', 'even') ?>"><td><?=$idea->score?></td><td><?=$idea->votes?></td><td><?=$idea->comments?></td><td><?=anchor("idea/view/$idea->id", "$idea->title")?></td></tr>
	<?php endforeach;?>
	</tbody></table>
<?php } else { ?>
	<p><i>No ideas found.</i></p>
<?php } ?>

<?php if($view_mine):?>
	<p>
		<span class="art-button-wrapper">
			<span class="l"> </span>
			<span class="r"> </span>
			<?=anchor("idea/add", "Add a new idea", 'class="art-button"')?>
		</span>
	</p>

	<p>You are also promoting the following ideas:</p>

	<?php if($ideas_promoted->num_rows > 0) {?>
		<table class="art-article" border="0" cellspacing="0" cellpadding="0">
		  <tbody>
			<tr><th>Score</th><th>Title</th></tr>
		<?php foreach($ideas_promoted->result() as $idea):?>
			<tr class="<?= alternator('', 'even') ?>"><td><?=$idea->score?></td><td><?=anchor("idea/view/$idea->id", "$idea->title")?></td></tr>
		<?php endforeach;?>
		</tbody></table>
	<?php } else { ?>
		<p><i>No ideas promoted.</i></p>
	<?php } ?>

<?php endif;?>

<?php if ($view_all):?>
<?php echo $this->pagination->create_links(); ?>
<?php endif;?>

</div>
    <div class="cleared"></div>
</div>

    </div>
</div>
</div>
</div>

