<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/iso.png" align="absmiddle"/>&nbsp;ISO Images</span>
    </h2>
    <div class="art-PostContent">

<table cellspacing="2" cellpadding="5">
	<tr>
		<td valign="top">			
			<h3>ISO files</h3>
				<?php if ($this->dx_auth->is_maintainer()) {?>
					<span class="art-button-wrapper" align="right">
					<span class="l"> </span>
					<span class="r"> </span>	
					<?=anchor("iso/add", "Add new ISO", 'class="art-button"')?>
					</span>
				<?php } ?>
				<?php if($isos->num_rows > 0) {?>
					<table cellpadding=5 cellspacing=5>	
					<th></th><th>Maintainer</th><th>ISO</th><th>Status</th>
					<?php foreach($isos->result() as $iso):
							
							$array = preg_split("/,/", timespan($iso->created, time()));
							$ago = $array[0];
							$avatar = '/img/default_avatar.jpg';
							if (file_exists(FCPATH.'uploads/avatars/'.$iso->maintainer.".jpg")) {
								$avatar = '/uploads/avatars/'.$iso->maintainer.".jpg";			
							}
							$progress = round($iso->num_tests / $num_cases * 100, 2);
							if ($iso->status == 1) { // "Being tested"
								$status = $progress."% tested";
							}
							else {
								$status = $iso->status_name;
							}
							
					?>						
						<tr>
						    <td nowrap><font color="orange"><?=$ago?> ago</font></td> 
							<td>
								<center>
								<?=anchor("user/view/$iso->maintainer", "<img height=30 src=$avatar>")?><br/>
								<?=anchor("user/view/$iso->maintainer", "$iso->username")?>
								</center>
							</td>
							<td><?=anchor("iso/view/$iso->id", "$iso->release_name $iso->edition_name $iso->architecture (build #$iso->build)")?></td>
							<td><?=$status?></td>
						</tr>
					<?php endforeach;?>
					</table>
				<?php } else { ?>
					<p><i>No iso files found.</i></p>
				<?php } ?>	
			
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

