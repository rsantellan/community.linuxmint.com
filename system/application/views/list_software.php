<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<a onClick="history.go(-1)">Back</a>
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/software.png" align="absmiddle"/>&nbsp;<?=$view_title?></span>
    </h2>
    <div class="art-PostContent">

<?php if ($view_all) {?>
	<?php echo form_open("software/search"); ?>
	<table>
	<tr><th>Name:</th><td><input type="text" name="search_software_name" value="<?=$search_software_name?>"/></td></tr>
	<tr><th>Content:</th><td><input type="text" name="search_software_body" value="<?=$search_software_body?>"/></td></tr>
<!--        <tr><th>Category:</th><td>
                <select name="search_software_category">
                                <option value="-1">Any</option>
                        <?php foreach($categories->result() as $category):
                                echo "<option value=\"".$category->id."\" ";
                                if ($search_software_category == $category->id) {
                                        echo "SELECTED";
                                }
                                echo ">".$category->name."</option>";
                        endforeach;?>
                </select></td>
        <th>Release:</th><td>
                <select name="search_software_release">
                                <option value="-1">Any</option>
                        <?php foreach($releases->result() as $release):
                                echo "<option value=\"".$release->id."\" ";
                                if ($search_software_release == $release->id) {
                                        echo "SELECTED";
                                }
                                echo ">".$release->name."</option>";
                        endforeach;?>
                </select></td></tr>
-->
	<td colspan="2" align="center"><span class="art-button-wrapper">
		<span class="l"> </span>
		<span class="r"> </span>
		<input class="art-button" type="submit" name="search" value="Search"/>
	</span></td></tr>
	</table>
	</form> 
<?php } ?>

<?php if (!$view_all) { ?>	

	<?php if ($show_stats) {?>
		<table cellpadding="5">
		<tr>
		<td valign="top" width="40%">
		<h3><img src="/img/icons/search.png" align="absmiddle"/>&nbsp;Search</h3>
		<?=anchor("/software/search", "Search packages", 'class="art-button"')?>

		<?php if($categories->num_rows > 0) {?>
			<h3><img src="/img/icons/browse.png" align="absmiddle"/>&nbsp;Categories</h3>
			<ul>
			<?php foreach($categories->result() as $category):
				if ($category->num_packages == "") {
		        	        $numb_packages = 0;
				}
				else {
					$numb_packages = $category->num_packages;
				}
		?>		
				<li><?=anchor("software/browse/$category->id", "$category->name")?> <small><i><font color="grey">(<?=$numb_packages?> packages)</font></i></small></li>
			<?php endforeach;?>
			</ul>
		<?php } ?>
		</td>
		<td valign="top">
		<h3><img src="/img/icons/stats.png" align="absmiddle"/>&nbsp;Stats</h3>
		Packages: <?=$num_packages?><br/>
		Reviews: <?=$num_reviews?><br/>
		Screenshots: <?=$num_screenshots?><br/>
		Packages reviewed: <?=$percentage_packages_reviewed?><br/>
		Packages with a screenshot: <?=$percentage_packages_with_screenshots?><br/>
		Users participating in reviews: <?=$percentage_users_reviewing?><br/>

		<h3><img src="/img/icons/top.png" align="absmiddle"/>&nbsp;Top packages</h3>
		<ul>
		<?php foreach($top_packages->result() as $package):
		 ?>
		        <li><b><?=anchor("software/view/$package->pkg_name", $package->pkg_name)?></b> (score: <font color=green><b><?=$package->overall_score?></b></font>)</li>
		<?php endforeach;?>
		</ul>

		<h3><img src="/img/icons/latest.png" align="absmiddle"/>&nbsp;Latest reviews</h3>
		<ul>

		<?php foreach($latest_reviews->result() as $review):
			$array = preg_split("/,/", timespan($review->timestamp, time()));
		        $ago = strtolower($array[0]);
			if ($review->score > 3) {
				$color = "#8cc153";
			}
			elseif ($review->score < 3) {
				$color = "#c15353";
			}
			else {
				$color = "#c1ab53";
			}
			?>		
			<li><font color="orange"><?=$ago?> ago</font>, <?=anchor("user/view/$review->user", $review->username)?> reviewed <b><?=anchor("software/view/$review->pkg_name", $review->pkg_name)?></b>: "<?=$review->comment?>" (score: <font color=<?=$color?><b><?=$review->score?></b></font>)</li>
		<?php endforeach;?>
		</ul>		
		</td>		
		</tr>
		</table>
	<?php } else { ?>
		<?php if($categories->num_rows > 0) {?>
			<h3>Subcategories</h3>
			<ul>
			<?php foreach($categories->result() as $category):
				if ($category->num_packages == "") {
		        	        $num_packages = 0;
				}
				else {
					$num_packages = $category->num_packages;
				}
		?>		
				<li><?=anchor("software/browse/$category->id", "$category->name")?> <small><i><font color="grey">(<?=$num_packages?> packages)</font></i></small></li>
			<?php endforeach;?>
			</ul>
		<?php } ?>

	<?php } ?>
		
<?php } ?>


<?php if($packages->num_rows > 0) {?>
	<h3>Packages</h3>
	<table class="art-article" border="0" cellspacing="0" cellpadding="0">
	  <tbody>
		<tr><th>Score</th><th>Package</th></tr>
	<?php foreach($packages->result() as $package):
		if ($package->score == "") {
			$score = 0;
			$color = "#c1ab53";
		}
		else {
			$score = $package->score;
			if ($score > 0) {
				$color = "#8cc153";
			}
			else {
				$color = "#c15353";
			}
		}
	?>		
		<tr class="<?= alternator('', 'even') ?>"><td><font size="3" color="<?=$color?>"><?=$score?></font></td><td><b><?=anchor("software/view/$package->pkg_name", $package->pkg_name)?></b><br/><small><font color="grey"><?=$package->summary?></font></small></td></tr>
	<?php endforeach;?>
	</tbody></table>
<?php } ?>

<?php echo $this->pagination->create_links(); ?>

</div>
    <div class="cleared"></div>
</div>

    </div>
</div>
</div>
</div>

