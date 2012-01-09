<?php
class Friend extends Controller{

	function Friend()
	{
		parent::Controller();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('email');		
	}
	
	function index()
	{
		$this->view();
	}

	function view()
	{
		$this->security->restrict_to_registered_users();
		$user_id = $this->dx_auth->get_user_id();
		$this->db->select("users.username as friend_name, users.id as friend_id, users.score");
		$this->db->from("friends");
		$this->db->join("users", "friends.friend = users.id");		
		$this->db->where("friends.user", $user_id);
		$this->db->where("users.id IN (SELECT user FROM friends WHERE friend = ".$user_id.")");
		$this->db->order_by("users.score DESC, friend_name");
		$data["friends"] = $this->db->get();
		
		$this->db->select("users.username as friend_name, users.id as friend_id, users.score");
		$this->db->from("friends");
		$this->db->join("users", "friends.friend = users.id");
		$this->db->where("friends.user", $user_id);
		$this->db->where("users.id NOT IN (SELECT user FROM friends WHERE friend = ".$user_id.")");
		$this->db->order_by("users.score DESC, friend_name");
		$data["friends_invited"] = $this->db->get();
		
		$this->db->select("DISTINCT(users.username) as friend_name, users.id as friend_id, users.score");
		$this->db->from("friends");
		$this->db->join("users", "friends.user = users.id");	
		$this->db->where("friends.user IN (SELECT user FROM friends WHERE friend = ".$user_id.")");
		$this->db->where("friends.user NOT IN (SELECT friend FROM friends WHERE user = ".$user_id.")");
		$this->db->order_by("users.score DESC, friend_name");
		$data["friends_invitations"] = $this->db->get();
		
		$data["page_title"] = "My Friends";
		$this->load->view("header", $data);
		$this->load->view("menu");
		$this->load->view('left');	
		$this->load->view('friends', $data);
		$this->load->view("footer");
	}	

	function delete($friend_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$friend_id = intval($friend_id);
		$user_id = $this->dx_auth->get_user_id();

		$this->db->where("user", $user_id);
		$this->db->where("friend", $friend_id);
       		$this->db->delete("friends");			
			
		redirect("user/view/$friend_id", "location");
	}

	function add($friend_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$friend_id = intval($friend_id);
		$user_id = $this->dx_auth->get_user_id();

		if ($friend_id != $user_id) {	
			$query = $this->db->query("SELECT * FROM friends WHERE user = $user_id AND friend = $friend_id");
			if ($query->num_rows() == 0) {
				$this->db->set("user", $user_id);
				$this->db->set("friend", $friend_id);
	       			$this->db->insert("friends");

				//Notify by email
				$myself = $this->myself->get_details($user_id);
				$friend = $this->myself->get_details($friend_id);					
				$this->email->from('no-reply@linuxmint.com', 'Linux Mint Community');
				$this->email->to($friend['email']); 
				$this->email->subject("Friendship notification - ".$myself['user_name']);
				$this->email->message("Hello ".$friend['user_name'].",

".$myself['user_name']." added you to his/her friend list.

To see ".$myself['user_name']."'s profile, click the following link:
http://community.linuxmint.com/index.php/user/view/".$user_id."

--
"); 
				$this->email->send();

			}
		}	
		redirect("user/view/$friend_id", "location");
	}		
}
?>
