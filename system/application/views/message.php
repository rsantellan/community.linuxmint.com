<?php
$array = preg_split("/,/", timespan($timestamp, time()));
$ago = strtolower($array[0]);			
if (file_exists(FCPATH.'uploads/avatars/'.$from.".jpg")) {
        $avatar = '/uploads/avatars/'.$from.".jpg";
}
else {
	$avatar = '/img/default_avatar.jpg';
}
?>


        <div class="art-content">
               
                        <div class="art-Post">
                            <div class="art-Post-body">
				<?=anchor("message", "Back")?>

                        <div class="art-Post-inner">
                            <h2 class="art-PostHeaderIcon-wrapper">
                                <span class="art-PostHeader"><?=$subject?></span>				
                            </h2>

			<center><small><?=$ago?> ago</small></center>
				
                            <div class="art-PostContent">

				<table border="0" cellspacing="0" cellpadding="0">
                                  <tbody>					
					<tr><td align="center"><?=anchor("user/view/$from", "<img src=$avatar height=50>")?><br/><small><font="grey"><?=anchor("user/view/$from", $username)?></font></small></th><td><blockquote><p><?=nl2br($body)?></p></blockquote></td></tr>
				
					<tr><th></th>
					<td><div class="boxinfo">						
						<?= form_open("message/reply/$id") ?>
						<textarea name="body" rows=10 cols=50></textarea>
						<span class="art-button-wrapper" align="right">
				                <span class="l"> </span>
				                <span class="r"> </span>
				                      <a href="#" onclick="document.forms[0].submit();" class="art-button">Reply</a>
				                </span>						
						</form>
						</div>
					</td></tr>					
					</table>
					</div>
					</td></tr>

				</tbody>
				</table>



                                
                                    
                            </div>
                            <div class="cleared"></div>
                        </div>
                        
                            </div>
                        </div>
                    </div>
                </div>


