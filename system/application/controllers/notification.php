<?php
class Notification extends Controller{

	function Notification()
	{
		parent::Controller();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('date');
	}
	
	function index()
	{
		$this->view();
	}

	function view()
	{
		$this->security->restrict_to_registered_users();
		$user_id = $this->dx_auth->get_user_id();
		$this->db->where("user", $user_id);
		$this->db->order_by("timestamp desc");
		$data["notifications"] = $this->db->get("notifications");
		$data["page_title"] = "Notifications";
		$this->load->view("header", $data);
		$this->load->view("menu");
		$this->load->view('left');	
		$this->load->view('notifications', $data);
		$this->load->view("footer");
	}	

	function delete($notification_id = 0)
	{
		$this->security->restrict_to_registered_users();

		$notification_id = intval($notification_id);
		$user_id = $this->dx_auth->get_user_id();

		//Check that is OUR notification
		$this->db->where("id", $notification_id);
		$query = $this->db->get("notifications");
		if ($query->num_rows() == 1) {
			$row = $query->row();
			if  ($row->user == $user_id) {
				$this->db->where("user", $user_id);
                	        $this->db->where("id", $notification_id);
       		                $this->db->delete("notifications");                	     
			}
		}
			
		redirect("notification", "location");
	}		
}
?>
