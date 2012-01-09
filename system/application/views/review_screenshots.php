        <div class="art-content">             
              <div class="art-Post">
                            <div class="art-Post-body">
				<a onClick="history.go(-1)">Back</a>

                        <div class="art-Post-inner">
                            <h2 class="art-PostHeaderIcon-wrapper">
                                <span class="art-PostHeader">Review screenshots</span>				
                            </h2>

                            <div class="art-PostContent">

					<table>
						<?php foreach($screenshots as $screenshot):
							$screenshot_path = FCPATH."/uploads/screenshots/".$screenshot;
						?>
						<tr><td>
							<a href="/uploads/screenshots/<?=$screenshot?>">
							<img class="screenshot" src="<?php echo "/thumbnail.php?pic=$screenshot_path";?>">
							</a>
						</td>
						<td><?=$screenshot?></td>
						<td><?=anchor("software/reject_screenshot/$screenshot", "Reject", 'class="art-button"')?></td>
						<td><?=anchor("software/approve_screenshot/$screenshot", "Approve", 'class="art-button"')?></td>
						</tr>
	                                        <?php endforeach;?>
                                        <?php if(count($screenshots) == 0):?>
                                                <tr><td><i>No screenshots to review.</i></td></tr>				
                                        <?php endif;?>
					</table>
					
                                    
  </div>
    <div class="cleared"></div>
</div>

    </div>
</div>
</div>
</div>

