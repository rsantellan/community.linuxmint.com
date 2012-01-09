<div class="art-content">
	<div class="art-Post">
	    <div class="art-Post-body">
		<div class="art-Post-inner">
		    <h2><?=$edition_name?></h2>
		    	<div class="art-PostContent">


<p>People running this edition:</p>
<table>
<th>Score</th><th colspan="2">User</th>
<?php
foreach($users->result() as $user):
	$avtr = '/img/default_avatar.jpg';
	if (file_exists(FCPATH.'uploads/avatars/'.$user->id.".jpg")) {
                        $avtr = '/uploads/avatars/'.$user->id.".jpg";
        }
?>

<tr>
<td><?=$user->score?>xp</td><td><?=anchor("user/view/$user->id", "<img height=30 src=$avtr>")?></td><td><?=anchor("user/view/$user->id", "$user->username")?></td>
</tr>

<?php
endforeach;
?>
</table>
			
</div>
			<div class="cleared"></div>
</div>
</div>
</div>
</div>

