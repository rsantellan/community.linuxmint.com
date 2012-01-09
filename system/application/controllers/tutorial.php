<?php
class Tutorial extends Controller{

	function Tutorial()
	{
		parent::Controller();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('DX_Auth');
		$this->load->helper('date');
	}
	
	function index()
	{
		$this->welcome();
	}

	function view($tutorial_id = 0)
	{
		$tutorial_id = intval($tutorial_id);

		//Update the number of views
		//$this->db->query("UPDATE tutorials SET views=views+1 WHERE id = $tutorial_id");

		$query = $this->db->query("SELECT tutorials.created, tutorials.views, tutorials.last_edited, tutorials.tags, tutorials.votes, tutorials.score, tutorials.title as title, tutorials.body as body, tutorial_statuses.name as status, 
						users.id as author_id, users.username as author_name, tutorial_statuses.id as current_status, tutorials.reviewer, tutorials.last_status_changed
						FROM tutorials, users, tutorial_statuses WHERE tutorial_statuses.id = tutorials.status AND users.id = tutorials.author AND tutorials.id = $tutorial_id");
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$data["tutorial_id"] = $tutorial_id;
			$data["tutorial_status"] = $row["status"];
			$data["current_status"] = $row["current_status"];
			$data["tutorial_title"] = $row["title"];
			$data["page_title"] = $row["title"];
			$data["created"] = $row["created"];
			$data["last_edited"] = $row["last_edited"];
			$data["views"] = $row["views"];
			$data["tags"] = $row["tags"];
			$data["tutorial_body"] = $row["body"];
            $data["last_status_changed"] = $row["last_status_changed"];
            $data["reviewer"] = $row["reviewer"];
            if ($data["reviewer"] > 0) {
                $this->db->where("id", $data['reviewer']);
                $reviewer = $this->db->get("users");
                $data["reviewer_name"] = $reviewer->row()->username;                            
            }
			#$data["tutorial_body"] = str_replace("\n", "<br/>", $row["body"]);
			#$data["tutorial_body"] = str_replace("\\n", "<br/>", $data["tutorial_body"]);
			#$data["tutorial_body"] = str_replace("\'", "'", $data["tutorial_body"]);
			$data["votes"] = $row["votes"];
			$data["score"] = $row["score"];
			$data["author_name"] = $row["author_name"];
			$data["author_id"] = $row["author_id"];
			$data["author_name"] = $row["author_name"];
			$data["author_id"] = $row["author_id"];
			if ($data["author_id"] == $this->dx_auth->get_user_id()) {
				$data["mine"] = TRUE;
			}
			else {
				$data["mine"] = FALSE;
			}

			if (file_exists(FCPATH.'uploads/avatars/'.$data["author_id"].".jpg")) {
        	                $data["avatar"] = '/uploads/avatars/'.$data["author_id"].".jpg";
	                }
                	else {
                        	$data["avatar"] = '/img/default_avatar.jpg';
	                }

			if ($this->dx_auth->is_logged_in()) {
				$user_id = $this->dx_auth->get_user_id();				
			}
			else {
				$user_id = 0;				
			}
			$query = $this->db->query("SELECT vote from tutorial_votes WHERE tutorial = $tutorial_id AND voter = $user_id");
			$data["vote"] = 0; // 0 means we didn't vote, 1 for demotion, 2 for didn't care, 3 for promotion
			if ($query->num_rows() > 0) {
				$row = $query->row();
				$data["vote"] = $row->vote;								
			}

			$this->db->where("author", $data["author_id"]);
			$this->db->where("id <> $tutorial_id");
			$data["other_tutorials"] = $this->db->get("tutorials");

			$data["statuses"] = $this->db->get("tutorial_statuses");

			//Delete any notification we've got about this
			$this->db->where("user", $user_id);
			$this->db->where("element_type", 2);
			$this->db->where("element_id", $tutorial_id);
			$query = $this->db->delete("notifications");

			//Check whether we're subscribed to this tutorial
			$this->db->where("user", $user_id);
			$this->db->where("element_type", 2);
			$this->db->where("element_id", $tutorial_id);
			$query = $this->db->get("subscriptions");
			if ($query->num_rows() > 0) {
				$row = $query->row();
				$data["subscribed"] = True;	
				$data["previous_subscription_timestamp"] = $row->timestamp;	
				//Update the timestamp of the subscription
				$this->db->where("id", $row->id);
				$this->db->set("timestamp", now());
				$query = $this->db->update("subscriptions");
			}
			else {
				$data["subscribed"] = False;
			}			

			$this->db->select("users.id as author_id, users.username as author_name, tutorial_comments.body as body, tutorial_comments.id as id, tutorial_comments.timestamp as timestamp");			
			$this->db->join("users", "users.id = tutorial_comments.author");
			$this->db->where("tutorial", $tutorial_id);
			$this->db->order_by("timestamp DESC, id DESC");
			$data["comments"] = $this->db->get("tutorial_comments");
                        
            
            	
			$this->load->view("header", $data);
			$this->load->view("menu");
			$this->load->view('left');	
			$this->load->view('tutorial', $data);
			$this->load->view("footer");
		}
		else {
			$data["error"] = "Not found";
			$data["details"] = "This tutorial does not exist.";
			$this->load->view("header");
			$this->load->view("menu");
			$this->load->view('left');	
			$this->load->view('error', $data);
			$this->load->view("footer");
		}		
	}
    
    function welcome() {
        $this->db->select("tutorials.id as id, tutorials.title as title, count(tutorial_votes.vote) as votes, sum(tutorial_votes.vote - 2) as score");
		$this->db->join("tutorial_votes", "tutorial_votes.tutorial = tutorials.id", "left");
		$this->db->group_by("tutorial_votes.tutorial");
		$this->db->order_by("score", "desc");
		$this->db->limit(5);
		$data['tutorials'] = $this->db->get("tutorials");
        
        $this->db->select("tutorials.id as id, tutorials.title as title, count(tutorial_votes.vote) as votes, sum(tutorial_votes.vote - 2) as score, created");
		$this->db->join("tutorial_votes", "tutorial_votes.tutorial = tutorials.id", "left");
		$this->db->group_by("tutorial_votes.tutorial");
		$this->db->order_by("created desc");
		$this->db->limit(5);
		$data['latest_tutorials'] = $this->db->get("tutorials");
        
        $this->db->select("tutorials.id as id, tutorials.title as title, count(tutorial_votes.vote) as votes, sum(tutorial_votes.vote - 2) as score, tutorials.last_status_changed, tutorial_statuses.name as status");
        $this->db->where("tutorials.status > 0");
		$this->db->join("tutorial_votes", "tutorial_votes.tutorial = tutorials.id", "left");
        $this->db->join("tutorial_statuses", "tutorial_statuses.id = tutorials.status");
		$this->db->group_by("tutorial_votes.tutorial");
		$this->db->order_by("tutorials.last_status_changed DESC");
		$this->db->limit(5);
		$data['latest_developments'] = $this->db->get("tutorials");
        
        $data["statuses_chart"] = $this->db->query('select tutorial_statuses.name as status, count(*) as number from tutorials, tutorial_statuses WHERE tutorial_statuses.id = tutorials.status GROUP BY tutorials.status ORDER BY number DESC');        
        
        $data['statuses'] = $this->db->get("tutorial_statuses");
        
        $this->load->view("header", $data);
        $this->load->view("menu");			
        $this->load->view('left');
        $this->load->view("welcome_tutorials", $data);
        $this->load->view("footer");
    }

	function from($author_id = 0) {
		$author_id = intval($author_id);

		if ($this->dx_auth->is_logged_in()) {
			$user_id = $this->dx_auth->get_user_id();
		}
		else {
			$user_id = 0;
		}

		$this->db->select("tutorials.id as id, tutorials.title as title, tutorials.votes as votes, tutorials.score as score, tutorials.comments as comments");
		$this->db->where("tutorials.author", $author_id);
		$this->db->order_by("score", "desc");
		$data['tutorials'] = $this->db->get("tutorials");

		$query = $this->db->query("SELECT username, email, last_login, country, edition, distro_release FROM users WHERE id = $author_id");
		if ($query->num_rows() > 0) {
	                $row = $query->row_array();
	                $data["author_name"] = $row["username"];
			$data['view_title'] = $row["username"]."'s tutorials";
			$data['view_mine'] = False;
			$data['view_all'] = False;
			if ($author_id == $this->dx_auth->get_user_id()) {
				$data['view_title'] = "My Tutorials";
				$data['view_mine'] = True;
				$this->db->select("tutorials.id as id, tutorials.title as title, tutorials.votes as votes, tutorials.score as score, tutorials.comments as comments");
				$this->db->join("tutorial_votes", "tutorial_votes.tutorial = tutorials.id");
				$this->db->where("tutorial_votes.voter", $user_id);
				$this->db->where("tutorial_votes.vote", 3);
				$this->db->where("tutorials.author !=", $user_id);
				$this->db->order_by("score", "desc");
				$data['tutorials_promoted'] = $this->db->get("tutorials");
			}
			$data['page_title'] = $data['view_title'];
			$this->load->view("header", $data);
			$this->load->view("menu");			
			$this->load->view('left');
			$this->load->view("list_tutorials", $data);			
			$this->load->view("footer");
		}		
	}	

	function retreive_data($variable, $default_value) {
		if (isset($_POST[$variable])) {
			$value = $_POST[$variable];
		}
		else {
			if ($this->session->userdata($variable)) {		
				$value = $this->session->userdata($variable);
			}
			else {
				$value = $default_value;
			}
		}
		$this->session->set_userdata($variable, $value);
		return $value;
	}

	function search($offset = 0, $search_get = "") {
        
        if ($search_get != "") {
            $search_get = str_replace("%20", " ", $search_get);
            $this->session->set_userdata('search_title', $search_get);
        }
        
        $search_title = $this->retreive_data('search_title', "");
		$search_body = $this->retreive_data('search_body', "");
		$search_tags = $this->retreive_data('search_tags', "");
		$search_status = intval($this->retreive_data('search_status', -1));
		$search_sort = intval($this->retreive_data('search_sort', 0));
		$search_filter = intval($this->retreive_data('search_filter', 0));					

		$this->db->start_cache();
		$this->db->select("tutorials.id as id, tutorials.title as title, tutorials.votes as votes, tutorials.score as score, tutorials.comments as comments");
		$this->db->from("tutorials");		
		switch ($search_filter) {
		    case 0:
			//All tutorials			
			break;
		    case 1:
			//tutorials I didn't vote
			$this->db->where("tutorials.author != ". $this->dx_auth->get_user_id());
			$this->db->where("tutorials.id NOT IN (SELECT tutorial FROM tutorial_votes WHERE voter =". $this->dx_auth->get_user_id(). ")");
			break;
		    case 2:
			//tutorials I am promoting
			$this->db->where("tutorials.author != ". $this->dx_auth->get_user_id());
			$this->db->where("tutorials.id IN (SELECT tutorial FROM tutorial_votes WHERE voter =". $this->dx_auth->get_user_id() ." AND vote = 3)");
			break;
		    case 3:
			//tutorials I am demoting
			$this->db->where("tutorials.author != ". $this->dx_auth->get_user_id());
			$this->db->where("tutorials.id IN (SELECT tutorial FROM tutorial_votes WHERE voter =". $this->dx_auth->get_user_id() ." AND vote = 1)");
			break;
		    case 4:
			//tutorials I don't care about
			$this->db->where("tutorials.author != ". $this->dx_auth->get_user_id());
			$this->db->where("tutorials.id IN (SELECT tutorial FROM tutorial_votes WHERE voter =". $this->dx_auth->get_user_id() ." AND vote = 2)");
			break;
		}	
        if ($search_title != "") {		
            $this->db->like("tutorials.title", $search_title);
        }
        if ($search_body != "") {	
            $this->db->like("tutorials.body", $search_body);
        }
        if ($search_tags != "") {	
            $this->db->like("tutorials.tags", $search_tags);
        }
		if ($search_status > -1) {
			$this->db->where("tutorials.status", $search_status);
		}
		$this->db->stop_cache();
		$num_tutorials = $this->db->count_all_results();
		switch ($search_sort) {
		    case 0:
			//Top Rated
			$this->db->order_by("score", "desc");
			break;
		    case 1:
			//Latest
			$this->db->order_by("id", "desc");
			break;
		    case 2:
			//Most Voted
			$this->db->order_by("votes", "desc");
			break;
		    case 3:
			//Most Commented
			$this->db->order_by("comments", "desc");
			break;
		}				
		$this->db->limit(20);
		$this->db->offset($offset);
		$data['tutorials'] = $this->db->get();
		$this->db->flush_cache();
		$data['statuses'] = $this->db->get("tutorial_statuses");
		$data['view_title'] = "Tutorials";
		$data['view_mine'] = False;
		$data['view_all'] = True;
		$data["search_title"] = $search_title;
		$data["search_body"] = $search_body;
		$data["search_tags"] = $search_tags;
		$data["search_status"] = $search_status;
		$data["search_sort"] = $search_sort;
		$data["search_filter"] = $search_filter;


		$this->load->library('pagination');
		$config['base_url'] = base_url().'index.php/tutorial/search/';
		$config['total_rows'] = $num_tutorials;
		$config['per_page'] = '20';
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$this->pagination->initialize($config);

		$data["page_title"] = "Tutorials";
		$this->load->view("header", $data);
		$this->load->view("menu");
		$this->load->view('left');
		$this->load->view("list_tutorials", $data);
		$this->load->view("footer");
	}

	function subscribe($tutorial_id = 0)
	{
		$this->security->restrict_to_registered_users();

		$user_id = $this->dx_auth->get_user_id();
		$tutorial_id = intval($tutorial_id);

		//Check whether we're subscribed to this tutorial
		$this->db->where("user", $user_id);
		$this->db->where("element_type", 2);
		$this->db->where("element_id", $tutorial_id);
		$query = $this->db->get("subscriptions");
		if ($query->num_rows() <= 0) {
			//Insert a new subscription
			$this->db->set("user", $user_id);
			$this->db->set("element_type", 2);
			$this->db->set("element_id", $tutorial_id);
			$this->db->set("timestamp", now());
			$query = $this->db->insert("subscriptions");
		}
		
		$this->view($tutorial_id);
	}

	function unsubscribe($tutorial_id = 0)
	{
		$this->security->restrict_to_registered_users();

		$user_id = $this->dx_auth->get_user_id();
		$tutorial_id = intval($tutorial_id);

		//Check whether we're subscribed to this tutorial
		$this->db->where("user", $user_id);
		$this->db->where("element_type", 2);
		$this->db->where("element_id", $tutorial_id);
		$query = $this->db->get("subscriptions");
		if ($query->num_rows() > 0) {
			//Delete the subscription
			$row = $query->row();
			$this->db->where("id", $row->id);			
			$query = $this->db->delete("subscriptions");
		}
		
		$this->view($tutorial_id);
	}


	function add()
	{
		$this->security->restrict_to_registered_users();

		$user_id = $this->dx_auth->get_user_id();
		$this->load->view("header");
		$this->load->view("menu");
		$this->load->view('left');
		$this->load->view("add_tutorial");
		$this->load->view("footer");
	}

	function edit($tutorial_id = 0)
	{
		$this->security->restrict_to_registered_users();

		$tutorial_id = intval($tutorial_id);
		$query = $this->db->query("SELECT tutorials.title as title, tutorials.body as body, tutorials.tags as tags, tutorials.status as status, users.id as author_id, users.username as author_name FROM tutorials, users WHERE users.id = tutorials.author AND tutorials.id = $tutorial_id");
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$data["tutorial_id"] = $tutorial_id;
			$data["tutorial_title"] = $row["title"];
			$data["tutorial_body"] = $row["body"];
			$data["tutorial_tags"] = $row["tags"];
			$this->load->view("header");
			$this->load->view("menu");
			$this->load->view('left');
			$this->load->view("edit_tutorial", $data);
			$this->load->view("footer");
		}		
	}

	function delete($tutorial_id = 0)
	{
		$this->security->restrict_to_registered_users();

		$tutorial_id = intval($tutorial_id);
		$user_id = $this->dx_auth->get_user_id();		
		$this->db->where("tutorials.id", $tutorial_id);
		$this->db->from("tutorials");
		$this->db->join("users", "users.id = tutorials.author");
		$query = $this->db->get();		
		if ($query->num_rows() == 1) {
			$row = $query->row();
			//Check that is our tutorial or that we're a moderator				
			if  ($this->dx_auth->is_moderator() || $row->author == $user_id) {
				if ($this->dx_auth->is_moderator() && $row->author != $user_id) {
					//Log this as a mod activity
					$data['timestamp'] = now();
        	       	$data['moderator'] = $user_id;
					$data['activity'] = "Deleted tutorial: '".$row->title."' from user '".$row->username."'";
					$this->db->insert("moderators_activity", $data);
				}				
				$this->db->where("id", $tutorial_id);
				$this->db->delete("tutorials");

				$this->db->where("tutorial", $tutorial_id);
				$this->db->delete("tutorial_votes");

				$this->db->where("tutorial", $tutorial_id);
				$this->db->delete("tutorial_comments");
				
				$this->db->where("element_type", 2);
				$this->db->where("element_id", $tutorial_id);
				$this->db->delete("notifications");

				$this->db->where("element_type", 2);
				$this->db->where("element_id", $tutorial_id);
				$this->db->delete("subscriptions");
			}
		}
			
		redirect("tutorial/from/$user_id", "location");
	}

	function save()
	{
		$this->security->restrict_to_registered_users();


		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('body', 'Content of the tutorial', 'required');
		$this->form_validation->set_rules('tags', 'Tags', 'required');

		$user_id = $this->dx_auth->get_user_id();

		

                $data = array(
	       	        'title' => $_POST["title"],
        	       	'body' => $_POST["body"],
        	       	'tags' => $_POST["tags"],
			'author' => $user_id
        	        );
		
		if ($_POST["action"] == "add") {
			if ($this->form_validation->run() == FALSE) {
				$this->add();
			}
			else {
				$data["created"] = now();
				$data["last_edited"] = now();
				$this->db->insert("tutorials", $data);
				$new_tutorial_id = $this->db->insert_id();
				$this->subscribe($new_tutorial_id);
			}
		}
		elseif ($_POST["action"] == "edit") {
			$tutorial_id = intval($_POST["id"]);
			if ($tutorial_id > 0) {
				if ($this->form_validation->run() == FALSE) {
					$this->edit($tutorial_id);
				}
				else {
					$data["last_edited"] = now();
					$this->db->where("author", $user_id);
					$this->db->where("id", $tutorial_id);
        	        	        $this->db->update("tutorials", $data);

					// Alert all subscribers of the edit					
					$query = $this->db->query("SELECT * FROM tutorials WHERE tutorials.id = $tutorial_id");
					if ($query->num_rows() > 0) {
						$row = $query->row();
						$title = $row->title;
						$this->_alert_subscribers($tutorial_id, "The tutorial '$title' was edited");
					}
					redirect("tutorial/view/$tutorial_id", "location");					
				}				
			}
                }
	}

	function change_status($tutorial_id)
	{
		$this->security->restrict_to_registered_users();
		$tutorial_id = intval($tutorial_id);
		if ($this->dx_auth->is_administrator() or $this->dx_auth->is_moderator()) {	
			$data = array(
	       	        'status' => intval($_POST["current_status"]),
                    'last_status_changed' => now(),
                    'reviewer' => $this->dx_auth->get_user_id()
        	        );		
			$this->db->where("id", $tutorial_id);
                	$this->db->update("tutorials", $data);

			// Alert all subscribers of the status change
			$query = $this->db->query("SELECT tutorials.title as title, tutorial_statuses.name as status FROM tutorials, tutorial_statuses WHERE tutorials.status = tutorial_statuses.id AND tutorials.id = $tutorial_id");
			if ($query->num_rows() > 0) {
				$row = $query->row();
				$status = $row->status;
				$title = $row->title;
				$this->_alert_subscribers($tutorial_id, "The format was changed to '$status' for the tutorial '$title'");
			}
		}
		redirect("tutorial/view/$tutorial_id", "location");
	}

	function vote($tutorial_id = 0, $action_id = "dontcare") 
	{
		$this->security->restrict_to_registered_users();

		$tutorial_id = intval($tutorial_id);

		//Check that is NOT our tutorial (not allowed on our own tutorials)
		$this->db->where("id", $tutorial_id);
		$query = $this->db->get("tutorials");
		if ($query->num_rows() == 1) {
			$row = $query->row();
			if  ($row->author != $this->dx_auth->get_user_id()) {
				$data["tutorial"] = $tutorial_id;
				$data["voter"] = $this->dx_auth->get_user_id();

				if ($action_id == "demote") {
					$data["vote"] = 1;			
				}
				else if ($action_id == "dontcare") {
				        $data["vote"] = 2;			
				}
				else if ($action_id == "promote") {
				        $data["vote"] = 3;
				}
				else {
					redirect("tutorial/view/$tutorial_id", "location");
				}

				if ($data["voter"] > 0 && $data["tutorial"] > 0 && $data["vote"] > -1) {
					$this->db->where("voter", $data["voter"]);
					$this->db->where("tutorial", $data["tutorial"]);
					$this->db->delete("tutorial_votes");
					$this->db->insert("tutorial_votes", $data);	
					//Update stats					
					$this->db->query("UPDATE tutorials SET votes = (SELECT count(*) FROM tutorial_votes WHERE tutorial = $tutorial_id), score = (SELECT SUM(vote - 2) FROM tutorial_votes WHERE tutorial = $tutorial_id) WHERE id = $tutorial_id");
				}
				
			}
		}

		redirect("tutorial/view/$tutorial_id", "location");
	}

	function comment($tutorial_id = 0)
	{
		$this->security->restrict_to_registered_users();

		$tutorial_id = intval($tutorial_id);
		$data["tutorial"] = $tutorial_id;
	        $data["author"] = $this->dx_auth->get_user_id();
        	$data["body"] = $_POST["body"];
        	$data["timestamp"] = now();
		if (trim($data["body"]) != "") {
			$this->db->insert("tutorial_comments", $data);
			//Update stats			
			$this->db->query("UPDATE tutorials SET comments = (SELECT count(*) FROM tutorial_comments WHERE tutorial = $tutorial_id) WHERE id = $tutorial_id");
			// Alert all subscribers of the comment
			$query = $this->db->query("SELECT * FROM tutorials WHERE tutorials.id = $tutorial_id");
			if ($query->num_rows() > 0) {
				$row = $query->row();
				$title = $row->title;
				$this->_alert_subscribers($tutorial_id, "A new comment was posted on the tutorial '$title'");
			}
		}
		redirect("tutorial/view/$tutorial_id", "location");
	}

	function delete_comment($comment_id = 0, $tutorial_id = 0)
	{
		$this->security->restrict_to_registered_users();

		$comment_id = intval($comment_id);
		$user_id = $this->dx_auth->get_user_id();
		//Check that is OUR comment
		$this->db->where("id", $comment_id);
		$query = $this->db->get("tutorial_comments");
		if ($query->num_rows() == 1) {
			$row = $query->row();
			if  ($row->author == $user_id) {
				$this->db->where("author", $user_id);
                	        $this->db->where("id", $comment_id);
       		                $this->db->delete("tutorial_comments");  
				//Update stats					
				$this->db->query("UPDATE tutorials SET comments = (SELECT count(*) FROM tutorial_comments WHERE tutorial = $tutorial_id) WHERE id = $tutorial_id");
			}
		}
			
		redirect("tutorial/view/$tutorial_id", "location");
	}

	function _alert_subscribers($tutorial_id, $text)
	{		
		// Find subscribers
		$this->db->where("element_type", 2); //tutorial
		$this->db->where("element_id", $tutorial_id);
		$subscriptions = $this->db->get("subscriptions");
		foreach($subscriptions->result() as $subscription) {			
			$subscriber = $subscription->user;
			// Create a new notification
			$data = array('element_id' => $tutorial_id, 'element_type' => 2, 'user' => $subscriber, 'timestamp' => now(), 'text' => $text);			
			$this->db->insert('notifications', $data);
		}
	}
}
?>
