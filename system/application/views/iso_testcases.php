<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/iso.png" align="absmiddle"/>&nbsp;ISO Test Cases</span>
    </h2>
    <div class="art-PostContent">

<table cellspacing="2" cellpadding="5">
	<tr>
		<td valign="top">
			<h3>Test Cases</h3>
				<?php if ($this->dx_auth->is_administrator()) {?>
					<span class="art-button-wrapper" align="right">
					<span class="l"> </span>
					<span class="r"> </span>	
					<?=anchor("iso/add_testcase", "Add a new test case", 'class="art-button"')?>
					</span>
				<?php } ?>
				<?php if($iso_testcases->num_rows > 0) {?>
					<table>	
					<?php foreach($iso_testcases->result() as $iso_testcase):?>				
						<tr>
							<td><?=anchor("iso/testcase/$iso_testcase->id", "$iso_testcase->name")?></td>
							<?php if ($this->dx_auth->is_administrator()) {?>
								<td><?=anchor("iso/delete_testcase/$iso_testcase->id", "<img src=/img/delete.png>")?></td>
							<?php }?>
						</tr>
					<?php endforeach;?>
					</table>
				<?php } else { ?>
					<p><i>No test cases found.</i></p>
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

