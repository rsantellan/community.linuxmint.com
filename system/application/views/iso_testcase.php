<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/iso.png" align="absmiddle"/>&nbsp;<?=$testcase->name?></span>
    </h2>
    <div class="art-PostContent">
<a onClick="history.go(-1)">Back</a>
<table cellspacing="2" cellpadding="5">
	<tr>
		<td valign="top">
					<table cellspacing=5 cellpadding=5>																
					<tr><th nowrap>How to reproduce:</th><td><?=$testcase->reproducing?></td></tr>
					<tr><th nowrap>What to expect:</th><td><?=$testcase->expecting?></td></tr>					
					<tr><th nowrap>Additional notes:</th><td><?=$testcase->notes?></td></tr>					
					</table>
					
					<?php if ($this->dx_auth->is_administrator()) {?>
						<span class="art-button-wrapper" align="right">
						<span class="l"> </span>
						<span class="r"> </span>	
						<?=anchor("iso/edit_testcase/".$testcase->id, "Edit", 'class="art-button"')?>
						</span>
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

