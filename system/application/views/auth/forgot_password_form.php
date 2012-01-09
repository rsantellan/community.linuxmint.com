<?php

$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'maxlength'	=> 80,
	'size'	=> 30,
	'value' => set_value('login'),
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
        <div class="t">Forgotten Password</div>
    </div>
</div>
	<div class="art-BlockContent">
       <div class="art-BlockContent-body">

        <div>
	<?php echo form_open($this->uri->uri_string())?>

	<?php echo $this->dx_auth->get_auth_error(); ?>	

        <?php echo form_label('Enter your Username or Email Address', $login['id']);?><?php echo form_input($login); ?><?php echo form_error($login['name']); ?><br/>
        <span class="art-button-wrapper">
        	<span class="l"> </span>
        	<span class="r"> </span>
        	<input class="art-button" type="submit" name="reset" value="Reset Now"/>
        </span>
        </form>
	</div>
</div>
</div>
</div>
</div>
