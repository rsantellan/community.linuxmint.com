<?php
$old_password = array(
	'name'	=> 'old_password',
	'id'		=> 'old_password',
	'size' 	=> 30,
	'value' => set_value('old_password')
);

$new_password = array(
	'name'	=> 'new_password',
	'id'		=> 'new_password',
	'size'	=> 30
);

$confirm_new_password = array(
	'name'	=> 'confirm_new_password',
	'id'		=> 'confirm_new_password',
	'size' 	=> 30
);

?>

<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader">Change password</span>
    </h2>
    <div class="art-PostContent">
        <p>Change your password:</p>

<?php echo form_open($this->uri->uri_string()); ?>

<?php echo $this->dx_auth->get_auth_error(); ?>

<table>
<tr><th><?php echo form_label('Old Password', $old_password['id']); ?></th><td><?php echo form_password($old_password); ?><?php echo form_error($old_password['name']); ?></td></tr>

<tr><th><?php echo form_label('New Password', $new_password['id']); ?></th><td><?php echo form_password($new_password); ?><?php echo form_error($new_password['name']); ?></td></tr>

<tr><th><?php echo form_label('Confirm New Password', $confirm_new_password['id']); ?></th><td><?php echo form_password($confirm_new_password); ?><?php echo form_error($confirm_new_password['name']); ?></td></tr>

<td colspan="2" align="center"><span class="art-button-wrapper">
                	<span class="l"> </span>
                	<span class="r"> </span>
                	<input class="art-button" type="submit" name="change" value="Change Password"/>
                </span></td></tr>
</table>
</form> 
      
            
    </div>
    <div class="cleared"></div>
</div>

    </div>
</div>
</div>
</div>
