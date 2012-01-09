<?php	
if (file_exists(FCPATH.'uploads/avatars/'.$id.".jpg")) {
        $avatar = '/uploads/avatars/'.$id.".jpg";
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
                                <span class="art-PostHeader"><img src="/img/icons/messages.png" align="absmiddle"/>&nbsp;New message</span>				
                            </h2>			
				
                            <div class="art-PostContent">

				<table border="0" cellspacing="0" cellpadding="0">
                                  <tbody>					
					<?= form_open("message/send/$id") ?>
					<tr><th>To:</th><td align=center><div class="boxinfo"><?=anchor("user/view/$id", "<img src=$avatar height=50>")?><br/><small><font="grey"><?=anchor("user/view/$id", $username)?></font></small></div></td>
					<tr><th>Subject:</th><td><div class="boxinfo"><input type="text" size="50" name="subject"/></div></td>
					<tr><th>Message:</th>
					<td><div class="boxinfo">						
						<textarea name="body" rows=10 cols=50></textarea>
						<span class="art-button-wrapper" align="right">
				                <span class="l"></span>
				                <span class="r"></span>
				                      <a href="#" onclick="document.forms[0].submit();" class="art-button">Send</a>
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


