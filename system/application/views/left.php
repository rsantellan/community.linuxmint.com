 <div class="art-contentLayout">
                    <div class="art-sidebar1">
                                                   
			<?php if (!$this->dx_auth->is_logged_in()) { ?>

				<?php
					$username = array(
						'name'	=> 'username',
						'id'	=> 'username',
						'size'	=> 30,
						'value' => set_value('username'),
						'style' => 'width: 95%;'
					);

					$password = array(
						'name'	=> 'password',
						'id'	=> 'password',
						'size'	=> 30,
						'style' => 'width: 95%;'
					);

					$remember = array(
						'name'	=> 'remember',
						'id'	=> 'remember',
						'value'	=> 1,
						'checked'	=> set_value('remember'),
						'style' => 'width: 95%;'
					);

					$confirmation_code = array(
						'name'	=> 'captcha',
						'id'	=> 'captcha',
						'maxlength'	=> 8,
						'style' => 'width: 95%;'
					);

					?> 
						
			 <div class="art-Block">
                            <div class="art-Block-tl"></div>
                            <div class="art-Block-tr"></div>
                            <div class="art-Block-bl"></div>
                            <div class="art-Block-br"></div>
                            <div class="art-Block-tc"></div>
                            <div class="art-Block-bc"></div>
                            <div class="art-Block-cl"></div>
                            <div class="art-Block-cr"></div>
                            <div class="art-Block-cc"></div>
				<div class="art-Block-body">
                                <div class="art-BlockHeader">
                                    <div class="l"></div>
                                    <div class="r"></div>
                                    <div class="art-header-tag-icon">
                                        <div class="t">Login</div>
                                    </div>
                                </div>
					<div class="art-BlockContent">
                                       <div class="art-BlockContent-body">

                                        <div>
					<?php echo form_open("/auth/login")?>

					<?php echo $this->dx_auth->get_auth_error(); ?>

                                        <?php echo form_label('Username', $username['id']);?><?php echo form_input($username)?><?php echo form_error($username['name']); ?>
                                        <?php echo form_label('Password', $password['id']);?><?php echo form_password($password)?><?php echo form_error($password['name']); ?>


					<?php if (isset($show_captcha) && ($show_captcha)): ?>

						<small>Enter the code exactly as it appears. There is no zero.</small><br/>
						<?php echo $this->dx_auth->get_captcha_image(); ?>

						<?php echo form_label('Confirmation Code', $confirmation_code['id']);?>
						<?php echo form_input($confirmation_code);?>
						<?php echo form_error($confirmation_code['name']); ?>
						
	
					<?php endif; ?>

					<?php echo form_checkbox($remember);?> <?php echo form_label('Remember me', $remember['id']);?><br/>
					<?php echo anchor($this->dx_auth->forgot_password_uri, 'Forgot password');?> <br/>

                                        <span class="art-button-wrapper">
                                        	<span class="l"> </span>
                                        	<span class="r"> </span>
                                        	<input class="art-button" type="submit" name="login" value="Login"/>
                                        </span>
					<span class="art-button-wrapper">
						<span class="l"> </span>
						<span class="r"> </span>
						<?=anchor($this->dx_auth->register_uri, "Register", 'class="art-button"')?>
					</span>
                                        </form>
					</div>
				</div>
                               </div>
 				</div>
				 </div>
			<?php 
				}
				else {
					$user_id = $this->dx_auth->get_user_id();
					$username = $this->dx_auth->get_username();
					$avatar = '/img/default_avatar.jpg';
					if (file_exists(FCPATH.'uploads/avatars/'.$user_id.".jpg")) {
							$avatar = '/uploads/avatars/'.$user_id.".jpg";
					}
					
					$this->db->from("messages");
					$this->db->where("status", 0); //unread
					$this->db->where("to", $user_id);
					$num_messages = $this->db->count_all_results();	

					$this->db->from("notifications");
					$this->db->where("user", $user_id);
					$num_notifications = $this->db->count_all_results();
					
					$this->db->select("score");
					$this->db->from("users");
					$this->db->where("id", $user_id);
					$res = $this->db->get();
					if ($res->num_rows() > 0) {
						$score = "(".$res->row()->score."xp)";
					}
					else {
						$score = "";
					}
					
			?>

 <div class="art-Block">
                            <div class="art-Block-tl"></div>
                            <div class="art-Block-tr"></div>
                            <div class="art-Block-bl"></div>
                            <div class="art-Block-br"></div>
                            <div class="art-Block-tc"></div>
                            <div class="art-Block-bc"></div>
                            <div class="art-Block-cl"></div>
                            <div class="art-Block-cr"></div>
                            <div class="art-Block-cc"></div>
				<div class="art-Block-body">
                                <div class="art-BlockHeader">
                                    <div class="l"></div>
                                    <div class="r"></div>
                                    <div class="art-header-tag-icon">
                                        <div class="t"><?=$username?> <small><?=$score?></small></div>
                                    </div>
                                </div>
					<div class="art-BlockContent">
                                       <div class="art-BlockContent-body">

                                        <div>
					<table border=0>
					<tr><td>
                        <?=anchor("user/view/", "<img src='$avatar' align='left' height='50'>")?></td>
					<td>	
								
					<?=anchor("message", "<img src='/img/message.png' align=absmiddle>")?>&nbsp;
					<?=anchor("message", $num_messages)?>				
					<br/>					
					<?=anchor("notification", "<img src='/img/alert.png' align=absmiddle>")?>&nbsp;
					<?=anchor("notification", $num_notifications)?>
					</td>
					</tr>
					<tr></tr>					
					<tr><td colspan=2><?=anchor("auth/logout", "Logout")?></td></tr>
					</table>
					</div>
				</div>
                               </div>
 				</div>
				 </div>





			<?php } ?>

                                    
                           
                       

                        <div class="art-Block">
                            <div class="art-Block-tl"></div>
                            <div class="art-Block-tr"></div>
                            <div class="art-Block-bl"></div>
                            <div class="art-Block-br"></div>
                            <div class="art-Block-tc"></div>
                            <div class="art-Block-bc"></div>
                            <div class="art-Block-cl"></div>
                            <div class="art-Block-cr"></div>
                            <div class="art-Block-cc"></div>
                            <div class="art-Block-body">
                                <div class="art-BlockHeader">
                                    <div class="l"></div>
                                    <div class="r"></div>
                                    <div class="art-header-tag-icon">
                                        <div class="t">Changelog</div>
                                    </div>
                                </div><div class="art-BlockContent">
                                    <div class="art-BlockContent-body">
                                        <div>

                            <p><b>25 Aug 2011</b><br/>
	  						  New signature and biography for users.<br/>
                              Improved statistics.<br/>
                              Moderators can now process ideas and tutorials.</p>						

							<p><b>22 Jul 2010</b><br/>
	  						  Only mutual friends appear in profile. Friends page now show mutual and non-mutual friends.<br/></p>
							
							<p><b>28 Jun 2010</b><br/>
	  						  Moderators can now delete software reviews.<br/></p>
							
							<p><b>23 Jun 2010</b><br/>
	  						  Performance improvements, faster listing of users, editions, countries and releases.<br/></p>
							
							<p><b>18 Jun 2010</b><br/>
	  						  New ISO testing module<br/></p>
								  						  	  						  													
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="art-Block">
                            <div class="art-Block-tl"></div>
                            <div class="art-Block-tr"></div>
                            <div class="art-Block-bl"></div>
                            <div class="art-Block-br"></div>
                            <div class="art-Block-tc"></div>
                            <div class="art-Block-bc"></div>
                            <div class="art-Block-cl"></div>
                            <div class="art-Block-cr"></div>
                            <div class="art-Block-cc"></div>
                            <div class="art-Block-body">
                                <div class="art-BlockHeader">
                                    <div class="l"></div>
                                    <div class="r"></div>
                                    <div class="art-header-tag-icon">
                                        <div class="t">Improve this website</div>
                                    </div>
                                </div><div class="art-BlockContent">
                                    <div class="art-BlockContent-body">
                                        <div>					
						<ul>
                                              		<li>Report bugs <?=anchor("https://bugs.launchpad.net/community.linuxmint.com", "here")?>.</li>
							<li>Register ideas for improvements using the idea module on this website.</li>
						</ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
