<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/countries.png" align="absmiddle"/>&nbsp;Top Countries</span>
    </h2>
    <div class="art-PostContent">

<p></p>

<table border="0" cellspacing="3" cellpadding="3">
<tbody>
	<tr><th>Country</th><th>Number of users</th></tr>
	<?php foreach($countries->result() as $country):?>
		<tr class="<?= alternator('', 'even') ?>">
			<td align="left" valign="center">
			<?php if (file_exists(FCPATH.'img/flags/'.$country->code.'.gif')) {?>
				<img src="/img/flags/<?=$country->code?>.gif" style="float: left"/>&nbsp;
			<?php }?>
			<?=anchor("country/view/$country->id", "$country->name")?>
			</td>
			<td><?=$country->users?></td>
		</tr>
	<?php endforeach;?>
</tbody>
</table>

<?php if($countries->num_rows == 0):?>
	<p><i>No countries found.</i></p>
<?php endif;?>

</div>
    <div class="cleared"></div>
</div>

    </div>
</div>
</div>
</div>

