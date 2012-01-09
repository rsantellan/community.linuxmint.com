<?php
class Chat extends Controller{

	function Chat()
	{
		parent::Controller();
		$this->load->helper('url');
		$this->load->helper('form');		
	}
	
	function index()
	{
		$this->view(0);
	}

	function view()
	{
		if ($this->dx_auth->is_logged_in()) {
			$data["user_name"] = $this->dx_auth->get_username;		
		}
		else {
			$data["user_name"] = "guest";
		}
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('left');
		$this->load->view('chat', $data);
		$this->load->view('footer');
	}

}
?>
