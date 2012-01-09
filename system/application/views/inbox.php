<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/messages.png" align="absmiddle"/>&nbsp;Inbox</span>
    </h2>
    <div class="art-PostContent">	

<p>&nbsp;</p>

<div align=right>
<span class="art-button-wrapper" align="right">
<span class="l"> </span>
<span class="r"> </span>
<?=anchor("message/outbox", "View Outbox", "class='art-button'")?>
</span>
</div>

<?php if($messages->num_rows > 0) {?>
	<table border="0" cellspacing="3" cellpadding="3">
	  <tbody>		
	<?php foreach($messages->result() as $message):
		$array = preg_split("/,/", timespan($message->timestamp, time()));
		$ago = strtolower($array[0]);			
		if (file_exists(FCPATH.'uploads/avatars/'.$message->from.".jpg")) {
	                $avatar = '/uploads/avatars/'.$message->from.".jpg";
                }
        	else {
                	$avatar = '/img/default_avatar.jpg';
                }
		if (strlen($message->body) > 50) {
			$body = substr($message->body, 0, 50)."...";
		}
		else {
			$body = $message->body;
		}
		if ($message->status == 0) {
			$subject = "<b>".$message->subject."</b>";
		}
		else {
			$subject = $message->subject;
		}
	?>
		<tr class="<?= alternator('', 'even') ?>"><td align=center><?=anchor("user/view/$message->from", "<img src=$avatar height=50>")?><br/><small><font="grey"><?=anchor("user/view/$message->from", $message->username)?></font></small></td><td><?=anchor("message/view/$message->id", $subject)?><br/><small><font color="grey"><?=$body?></font></small></td><td><?="$ago ago"?></td><td><?=anchor("message/delete/$message->id", "<img src=/img/delete.png>")?></td></tr>
	<?php endforeach;?>
	</tbody></table>
<?php } else { ?>
	<p><i>No messages found.</i></p>
<?php } ?>

</div>
    <div class="cleared"></div>
</div>

    </div>
</div>
</div>
</div>

