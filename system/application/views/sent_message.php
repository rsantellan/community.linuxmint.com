<?php
$array = preg_split("/,/", timespan($timestamp, time()));
$ago = strtolower($array[0]);			
if (file_exists(FCPATH.'uploads/avatars/'.$to.".jpg")) {
        $avatar = '/uploads/avatars/'.$to.".jpg";
}
else {
	$avatar = '/img/default_avatar.jpg';
}
?>


        <div class="art-content">
               
                        <div class="art-Post">
                            <div class="art-Post-body">
				<?=anchor("message/outbox", "Back")?>

                        <div class="art-Post-inner">
                            <h2 class="art-PostHeaderIcon-wrapper">
                                <span class="art-PostHeader"><?=$subject?></span>				
                            </h2>

			<center><small><?=$ago?> ago</small></center>
				
                            <div class="art-PostContent">

				<table border="0" cellspacing="0" cellpadding="0">
                                  <tbody>					
					<tr><td align="center"><?=anchor("user/view/$to", "<img src=$avatar height=50>")?><br/><small><font="grey"><?=anchor("user/view/$to", $username)?></font></small></th><td><blockquote><p><?=nl2br($body)?></p></blockquote></td></tr>												
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


