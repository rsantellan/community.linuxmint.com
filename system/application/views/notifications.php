<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/notifications.png" align="absmiddle"/>&nbsp;Subscription alerts</span>
    </h2>
    <div class="art-PostContent">	

<p>&nbsp;</p>

<?php if($notifications->num_rows > 0) {?>
	<table border="0" cellspacing="3" cellpadding="3">
	  <tbody>		
	<?php foreach($notifications->result() as $notification):
		$array = preg_split("/,/", timespan($notification->timestamp, time()));
		$ago = strtolower($array[0]);
		
		$link = "#";
		if ($notification->element_type == 1) {
			# notification about an idea
			$link = "idea/view/".$notification->element_id;
		}
		elseif ($notification->element_type == 2) {
			# notification about a tutorial
			$link = "tutorial/view/".$notification->element_id;
		}

	?>
		<tr class="<?= alternator('', 'even') ?>"><td><?="$ago ago"?></td><td><?=anchor($link, $notification->text)?></td><td><?=anchor("notification/delete/$notification->id", "<img src=/img/delete.png>")?></td></tr>
	<?php endforeach;?>
	</tbody></table>
<?php } else { ?>
	<p><i>No notifications found.</i></p>
<?php } ?>

</div>
    <div class="cleared"></div>
</div>

    </div>
</div>
</div>
</div>

