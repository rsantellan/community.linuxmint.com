<div class="art-nav" style="z-index:4;">
                	<div class="l"></div>
                	<div class="r"></div>
                	<ul class="art-menu">
                		<li><a href="<?php echo base_url();?>" background="images/header.gif"><span class="l"></span><span class="r"></span><span class="t">Home</span></a>				
				</li>
				<?php if ($this->dx_auth->is_logged_in()) { 

					$username = $this->dx_auth->get_username();
				?>
					<li><a href="#"><span class="l"></span><span class="r"></span><span class="t">My places</span></a>
						<ul class="dropmenu">
							<li><?=anchor("user/view/", "<img src='".base_url()."img/menu/profile.png' align='absmiddle'>&nbsp;My Profile")?></li>												
							<li><?=anchor("idea/from/".$this->dx_auth->get_user_id(), "<img src='".base_url()."img/menu/ideas.png' align='absmiddle'>&nbsp;My Ideas")?></li>
							<li><?=anchor("tutorial/from/".$this->dx_auth->get_user_id(), "<img src='".base_url()."img/menu/tutorials.png' align='absmiddle'>&nbsp;My Tutorials")?></li>
							<li><?=anchor("hardware/from/".$this->dx_auth->get_user_id(), "<img src='".base_url()."img/menu/hardware.png' align='absmiddle'>&nbsp;My Hardware")?></li>
							<li><?=anchor("friend", "<img src='".base_url()."img/menu/friends.png' align='absmiddle'>&nbsp;My Friends")?></li>
						</ul>
					</li>
		        		<li><a href="#"><span class="l"></span><span class="r"></span><span class="t">Community</span></a>
						<ul class="dropmenu">
							<li><?=anchor("idea/welcome", "<img src='".base_url()."img/menu/ideas.png' align='absmiddle'>&nbsp;Ideas")?></li>		
							<li><?=anchor("tutorial/welcome", "<img src='".base_url()."img/menu/tutorials.png' align='absmiddle'>&nbsp;Tutorials")?></li>						
							<li><?=anchor("hardware/welcome", "<img src='".base_url()."img/menu/hardware.png' align='absmiddle'>&nbsp;Hardware")?></li>
							<li><?=anchor("software/browse", "<img src='".base_url()."img/menu/software.png' align='absmiddle'>&nbsp;Software")?></li>
							<li><?=anchor("country/all", "<img src='".base_url()."img/menu/countries.png' align='absmiddle'>&nbsp;Countries")?></li>
							<li><?=anchor("user/search", "<img src='".base_url()."img/menu/users.png' align='absmiddle'>&nbsp;Users")?></li>
							<li><?=anchor("user/moderators", "<img src='".base_url()."img/menu/moderation.png' align='absmiddle'>&nbsp;Moderation")?></li>
							<li><a href="#" onClick="window.open('http://widget.mibbit.com/?settings=afafcb2fef3d61ee26d44fe55394b54b&server=irc.spotchat.org&channel=%23linuxmint-chat&autoConnect=1&nick=<?=$username?>_web', 'Chat room', 'left=20,top=100,width=800,height=600,toolbar=0,resizable=0');"><img src='<?php echo base_url();?>img/menu/chat.png' align='absmiddle'>&nbsp;Chat room</a></li>
						</ul>
		        		</li>
		        	
						<li><a href="#"><span class="l"></span><span class="r"></span><span class="t">Testing</span></a>
							<ul class="dropmenu">                                                        
								<li><?=anchor("iso", "<img src='".base_url()."img/menu/iso.png' align='absmiddle'>&nbsp;ISO Images")?></li>
								<li><?=anchor("iso/team", "<img src='".base_url()."img/menu/iso.png' align='absmiddle'>&nbsp;Teams")?></li>
							</ul>
						</li>
						
					<?php if ($this->dx_auth->is_administrator()) { 
						$this->load->helper('directory');
						$screenshots = directory_map(FCPATH."uploads/screenshots/");
						$num_screenshots_to_review = count($screenshots);
					?>
						<li><a href="#"><span class="l"></span><span class="r"></span><span class="t">Admin</span></a>
                                                <ul class="dropmenu">
                                                        <li><?=anchor("software/review_screenshots", "Review screenshots ($num_screenshots_to_review)")?></li>   
                                                        <li><?=anchor("iso/testcases", "<img src='/img/menu/iso.png' align='absmiddle'>&nbsp;Test Cases")?></li>                                                     
                                                </ul>
                                        </li>

					<?php }?>
				<?php } else {?>					
		        		<li><a href="#"><span class="l"></span><span class="r"></span><span class="t">Community</span></a>
						<ul class="dropmenu">
							<li><?=anchor("idea/search", "<img src='".base_url()."img/menu/ideas.png' align='absmiddle'>&nbsp;Ideas")?></li>	
							<li><?=anchor("tutorial/search", "<img src='".base_url()."img/menu/tutorials.png' align='absmiddle'>&nbsp;Tutorials")?></li>							
							<li><?=anchor("hardware/search", "<img src='".base_url()."img/menu/hardware.png' align='absmiddle'>&nbsp;Hardware")?></li>
							<li><?=anchor("software/browse", "<img src='".base_url()."img/menu/software.png' align='absmiddle'>&nbsp;Software")?></li>
							<li><?=anchor("country/all", "<img src='".base_url()."img/menu/countries.png' align='absmiddle'>&nbsp;Countries")?></li>
							<li><?=anchor("user/search", "<img src='".base_url()."img/menu/users.png' align='absmiddle'>&nbsp;Users")?></li>			
							<li><?=anchor("user/moderators", "<img src='".base_url()."img/menu/moderation.png' align='absmiddle'>&nbsp;Moderation")?></li>				
							<li><a href="#" onClick="window.open('http://widget.mibbit.com/?settings=afafcb2fef3d61ee26d44fe55394b54b&server=irc.spotchat.org&channel=%23linuxmint-chat&autoConnect=1&nick=guest%3F%3F%3F%3F_web', 'Chat room', 'left=20,top=100,width=800,height=600,toolbar=0,resizable=0');"><img src='<?php echo base_url();?>img/menu/chat.png' align='absmiddle'>&nbsp;Chat room</a></li>
						</ul>
		        		</li>
		        		
		        		<li><a href="#"><span class="l"></span><span class="r"></span><span class="t">Testing</span></a>
							<ul class="dropmenu">                                                        
								<li><?=anchor("iso", "<img src='".base_url()."img/menu/iso.png' align='absmiddle'>&nbsp;ISO Images")?></li>
								<li><?=anchor("iso/team", "<img src='".base_url()."img/menu/iso.png' align='absmiddle'>&nbsp;Teams")?></li>
							</ul>
						</li>
						

				<?php }?>                		                	
                	</ul>
                </div>

