<?php
class Message extends Controller{

	function Message()
	{
		parent::Controller();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->library('email');
	}
	
	function index()
	{
		$this->inbox();
	}

	function view($message_id)
	{
		$this->security->restrict_to_registered_users();

		$message_id = intval($message_id);
		$user_id = $this->dx_auth->get_user_id();

		//Check that is OUR message	
		$this->db->select("messages.*, users.username as username");
		$this->db->from("messages");
		$this->db->join("users", "users.id = messages.from");
		$this->db->where("to", $user_id);
		$this->db->where("messages.id", $message_id);
		$this->db->order_by("timestamp desc");
		$query = $this->db->get();		
		if ($query->num_rows() == 1) {
			$message = $query->row();
			if  ($message->to == $user_id) {
				//Mark the message as read
				$this->db->where("id", $message_id);
				$this->db->set("status", 1);
				$this->db->update("messages");

				//Show the message
				$data["page_title"] = $message->subject;
				$this->load->view("header", $data);
				$this->load->view("menu");
				$this->load->view('left');	
				$this->load->view('message', $message);
				$this->load->view("footer");          	     
			}
		}
	}

	function view_sent($message_id)
	{
		$this->security->restrict_to_registered_users();

		$message_id = intval($message_id);
		$user_id = $this->dx_auth->get_user_id();

		//Check that is OUR message	
		$this->db->select("messages.*, users.username as username");
		$this->db->from("messages");
		$this->db->join("users", "users.id = messages.to");
		$this->db->where("from", $user_id);
		$this->db->where("messages.id", $message_id);
		$this->db->order_by("timestamp desc");
		$query = $this->db->get();		
		if ($query->num_rows() == 1) {
			$message = $query->row();
			if  ($message->from == $user_id) {				
				//Show the message
				$data["page_title"] = $message->subject;
				$this->load->view("header", $data);
				$this->load->view("menu");
				$this->load->view('left');	
				$this->load->view('sent_message', $message);
				$this->load->view("footer");          	     
			}
		}
	}

	function inbox()
	{
		$this->security->restrict_to_registered_users();
		$user_id = $this->dx_auth->get_user_id();
		$this->db->select("messages.*, users.username as username");
		$this->db->from("messages");
		$this->db->join("users", "users.id = messages.from");
		$this->db->where("to", $user_id);
		$this->db->order_by("timestamp desc");
		$data["messages"] = $this->db->get();
		$data["page_title"] = "Inbox";
		$this->load->view("header", $data);
		$this->load->view("menu");
		$this->load->view('left');	
		$this->load->view('inbox', $data);
		$this->load->view("footer");
	}

	function outbox()
	{
		$this->security->restrict_to_registered_users();
		$user_id = $this->dx_auth->get_user_id();
		$this->db->select("messages.*, users.username as username");
		$this->db->from("messages");
		$this->db->join("users", "users.id = messages.to");
		$this->db->where("from", $user_id);
		$this->db->order_by("timestamp desc");
		$data["messages"] = $this->db->get();
		$data["page_title"] = "Outbox";
		$this->load->view("header", $data);
		$this->load->view("menu");
		$this->load->view('left');	
		$this->load->view('outbox', $data);
		$this->load->view("footer");
	}

	function compose($to = 0)
	{
		$this->security->restrict_to_registered_users();
		$to = intval($to);		
		$this->db->where("id", $to);
		$query = $this->db->get("users");
		if ($query->num_rows() > 0) {
			$data = $query->row();
			$page_title["page_title"] = "New message";
			$this->load->view("header", $page_title);
			$this->load->view("menu");
			$this->load->view('left');	
			$this->load->view('compose_message', $data);
			$this->load->view("footer");
		}
	}

	function send($to = 0)
	{
		$this->security->restrict_to_registered_users();
		$user_id = $this->dx_auth->get_user_id();
		$to = intval($to);		
		$this->db->where("id", $to);
		$query = $this->db->get("users");
		if ($query->num_rows() > 0) {

			$myself = $this->myself->get_details($user_id);
			if ($myself['score'] > 0) {

				//Create message
				$subject = trim($this->input->post('subject'));
				if ($subject == "") {
					$subject = "No subject";
				}
				$body = $this->input->post('body');
				$data = array('from' => $user_id, 'to' => $to, 'subject' => $subject, 'body' => $body, 'timestamp' => now());
				$this->db->insert('messages', $data);

				//Notify by email
				$myself = $this->myself->get_details($user_id);
				$recipient = $this->myself->get_details($to);
				$this->email->from('no-reply@linuxmint.com', 'Linux Mint Community');
				$this->email->to($recipient['email']); 
				$this->email->subject("Message notification - \"$subject\"");
				$this->email->message("Hello ".$recipient['user_name'].",

".$myself['user_name']." sent you a message.

To check your messages, click the following link:
http://community.linuxmint.com/index.php/message

--
"); 
				$this->email->send();
			}

			$this->load->view("header");
			$this->load->view("menu");
			$this->load->view('left');	
			$this->load->view('message_sent');
			$this->load->view("footer");		
		}
	}

	function reply($message_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$message_id = intval($message_id);
		$user_id = $this->dx_auth->get_user_id();
		//Check that is OUR message
		$this->db->where("id", $message_id);
		$query = $this->db->get("messages");
		if ($query->num_rows() == 1) {			
			$row = $query->row();
			if  ($row->to == $user_id) {
				if (substr($row->subject, 0, 4) == "Re: ") {
					$subject = $row->subject;
				}
				else {
					$subject = "Re: ".$row->subject;
				}
				$body = $this->input->post('body');
				$data = array('from' => $user_id, 'to' => $row->from, 'subject' => $subject, 'body' => $body, 'timestamp' => now());			
				$this->db->insert('messages', $data);

				//Notify by email
				$myself = $this->myself->get_details($user_id);
				$recipient = $this->myself->get_details($row->from);						
				$this->email->from('no-reply@linuxmint.com', 'Linux Mint Community');
				$this->email->to($recipient['email']); 
				$this->email->subject("Reply notification - \"$subject\"");
				$this->email->message("Hello ".$recipient['user_name'].",

".$myself['user_name']." replied to your message \"".$subject."\".

To check your messages, click the following link:
http://community.linuxmint.com/index.php/message

--
"); 
				$this->email->send();

				$this->load->view("header");
				$this->load->view("menu");
				$this->load->view('left');	
				$this->load->view('message_sent');
				$this->load->view("footer");
			}
		}
	}	

	function delete($message_id = 0)
	{
		$this->security->restrict_to_registered_users();

		$message_id = intval($message_id);
		$user_id = $this->dx_auth->get_user_id();

		//Check that is OUR message
		$this->db->where("id", $message_id);
		$query = $this->db->get("messages");
		if ($query->num_rows() == 1) {			
			$row = $query->row();
			if  ($row->to == $user_id) {				
				$this->db->where("to", $user_id);
                	        $this->db->where("id", $message_id);
       		                $this->db->delete("messages");                	     
			}
		}
			
		redirect("message", "location");
	}		
}
?>
