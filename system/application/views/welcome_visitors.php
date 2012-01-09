<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader">Welcome to the Linux Mint Community</span>
    </h2>
    <div class="art-PostContent">
        
        <img class="art-article" src="/images/logo.png" alt="Linux Mint logo" style="float: right" />
        
        <p>Welcome to the Community. We hope you'll have a wonderful time using Linux Mint and interacting with the other users. Don't hesitate to ask questions, to register your hardware specifications, to submit new ideas or to vote and comment the ones that are already there.</p>

	<p>There are <b><?=$num_users - 1?></b> registered users and here is the list of the top 10 countries:</p>         
            
		       <ol>
			<?php
			foreach($countries->result() as $country):
			?>
			<li>
			<?php
			if (file_exists(FCPATH.'img/flags/'.$country->code.'.gif')) {
			?>
			       <img src="/img/flags/<?=$country->code?>.gif"/>
			<?
			}
			?>
			<?=$country->name?> (<?=$country->users?> people)</li>
			<?php
			endforeach;
			?>
			</ol>
                  	

        <p>Here are the main modules on this website:</p>
        
        <table class="table" width="100%">
        	<tr>
        		<td width="33%" valign="top">
        		<div class="art-Block">
        			<div class="art-Block-body">
        				<div class="art-BlockHeader">
                  <div class="l"></div>
        				  <div class="r"></div>
        				  <div class="t"><center>Ideas</center></div>
        			  </div>
        				<div class="art-BlockContent">
        					<div class="art-PostContent">
        						<img src="/images/02.png" width="55px" height="55px" alt="an image" style="margin: 0 auto; display: block; border: 0" />
        						<p>You can register your ideas to improve the distribution. Other users can comment them, promote or demote them. The best rated ideas get to the top of the list and eventually get implemented by the development team.</p>
        					</div>
        				</div>
        			</div>
        		</div>
        		</td>
        		<td width="33%" valign="top">
        		<div class="art-Block">
        			<div class="art-Block-body">
        				<div class="art-BlockHeader">
                  <div class="l"></div>
        				  <div class="r"></div>
        				  <div class="t"><center>Groups</center></div>
        			  </div>
        				<div class="art-BlockContent">
        					<div class="art-PostContent">
        						<img src="/images/01.png" width="55px" height="55px" alt="an image" style="margin: 0 auto; display: block; border: 0" />
        						<p>Form mini-communities by creating and joining groups, based on your location or your interests. <i>Not implemented yet.</i></p>
        					</div>
        				</div>
        			</div>
        		</div>
        		</td>
        		<td width="33%" valign="top">
        		<div class="art-Block">
        			<div class="art-Block-body">
                <div class="art-BlockHeader">
                  <div class="l"></div>
        				  <div class="r"></div>
        				  <div class="t"><center>Support</center></div>
        			  </div>
        				<div class="art-BlockContent">
        					<div class="art-PostContent">
        						<img src="/images/03.png" width="55px" height="55px" alt="an image" style="margin: 0 auto; display: block; border: 0" />
        						<p>Find users with the same hardware as you and see how they got it to work with Linux Mint. Ask questions, thank those who helped the best. </i>Not implemented yet.</i></p>
        					</div>
        				</div>
        			</div>
        		</div>
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
