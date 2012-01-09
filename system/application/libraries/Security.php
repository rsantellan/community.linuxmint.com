<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Security {
    
    function Security()
    {
	$this->ci =& get_instance();
	$this->ci->load->library('DX_Auth');
    }

    function restrict_to_registered_users() {
	if (!$this->ci->dx_auth->is_logged_in()) {		
		exit(0);		
	}
    }
}

?>
