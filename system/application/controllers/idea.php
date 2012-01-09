<?php
class Idea extends Controller{

	function Idea()
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

	function view($idea_id = 0)
	{
		$idea_id = intval($idea_id);
		
		//Update the number of views
		//$this->db->query("UPDATE ideas SET views=views+1 WHERE id = $idea_id");
		
		$query = $this->db->query("SELECT ideas.created, ideas.views, ideas.last_edited, ideas.title as title, ideas.body as body, ideas.votes as votes, ideas.score as score, idea_statuses.name as status,
						users.id as author_id, users.username as author_name, idea_statuses.id as current_status, ideas.reviewer, ideas.last_status_changed
						FROM ideas, users, idea_statuses WHERE idea_statuses.id = ideas.status AND users.id = ideas.author AND ideas.id = $idea_id");
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$data["idea_id"] = $idea_id;
			$data["created"] = $row["created"];
			$data["last_edited"] = $row["last_edited"];
			//$data["views"] = $row["views"];
			$data["idea_status"] = $row["status"];
			$data["current_status"] = $row["current_status"];
			$data["idea_title"] = $row["title"];
			$data["page_title"] = $row["title"];
			$data["idea_body"] = str_replace("\n", "<br/>", $row["body"]);
			$data["idea_body"] = str_replace("\\n", "<br/>", $data["idea_body"]);
			$data["idea_body"] = str_replace("\'", "'", $data["idea_body"]);
            $data["last_status_changed"] = $row["last_status_changed"];
            $data["reviewer"] = $row["reviewer"];
            if ($data["reviewer"] > 0) {
                $this->db->where("id", $data['reviewer']);
                $reviewer = $this->db->get("users");
                $data["reviewer_name"] = $reviewer->row()->username;                            
            }
			$data["votes"] =  $row["votes"];
            $data["score"] =  $row["score"];
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
			$query = $this->db->query("SELECT vote from idea_votes WHERE idea = $idea_id AND voter = $user_id");
			$data["vote"] = 0; // 0 means we didn't vote, 1 for demotion, 2 for didn't care, 3 for promotion
			if ($query->num_rows() > 0) {
				$row = $query->row();
				$data["vote"] = $row->vote;								
			}

			$this->db->where("author", $data["author_id"]);
			$this->db->where("id <> $idea_id");
			$data["other_ideas"] = $this->db->get("ideas");

			$data["statuses"] = $this->db->get("idea_statuses");

			//Delete any notification we've got about this
			$this->db->where("user", $user_id);
			$this->db->where("element_type", 1);
			$this->db->where("element_id", $idea_id);
			$query = $this->db->delete("notifications");

			//Check whether we're subscribed to this idea
			$this->db->where("user", $user_id);
			$this->db->where("element_type", 1);
			$this->db->where("element_id", $idea_id);
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

			$this->db->select("users.id as author_id, users.username as author_name, idea_comments.body as body, idea_comments.id as id, idea_comments.timestamp as timestamp");			
			$this->db->join("users", "users.id = idea_comments.author");
            $this->db->where("idea", $idea_id);
			$this->db->order_by("timestamp DESC, id DESC");
            $data["comments"] = $this->db->get("idea_comments");	                       
            	
			$this->load->view("header", $data);
			$this->load->view("menu");
			$this->load->view('left');	
			$this->load->view('idea', $data);
			$this->load->view("footer");
		}
		else {
			$data["error"] = "Not found";
			$data["details"] = "This idea does not exist.";
			$this->load->view("header");
			$this->load->view("menu");
			$this->load->view('left');	
			$this->load->view('error', $data);
			$this->load->view("footer");
		}		
	}

	function from($author_id = 0) {
		$author_id = intval($author_id);

		if ($this->dx_auth->is_logged_in()) {
			$user_id = $this->dx_auth->get_user_id();
		}
		else {
			$user_id = 0;
		}

		$this->db->select("ideas.id as id, ideas.title as title, ideas.votes as votes, ideas.score as score, ideas.comments as comments");
		$this->db->where("ideas.author", $author_id);
		$this->db->order_by("score", "desc");
		$data['ideas'] = $this->db->get("ideas");

		$query = $this->db->query("SELECT username, email, last_login, country, edition, distro_release FROM users WHERE id = $author_id");
		if ($query->num_rows() > 0) {
	        $row = $query->row_array();
	        $data["author_name"] = $row["username"];
			$data['view_title'] = $row["username"]."'s Ideas";
			$data['view_mine'] = False;
			$data['view_all'] = False;
			if ($author_id == $this->dx_auth->get_user_id()) {
				$data['view_title'] = "My Ideas";
				$data['view_mine'] = True;
				$this->db->select("ideas.id as id, ideas.title as title, ideas.votes as votes, ideas.score as score, ideas.comments as comments");
				$this->db->join("idea_votes", "idea_votes.idea = ideas.id");
				$this->db->where("idea_votes.voter", $user_id);
				$this->db->where("idea_votes.vote", 3);
				$this->db->where("ideas.author !=", $user_id);
				$this->db->order_by("score", "desc");
				$data['ideas_promoted'] = $this->db->get("ideas");
			}
			$data['page_title'] = $data['view_title'];
			$this->load->view("header", $data);
			$this->load->view("menu");			
			$this->load->view('left');
			$this->load->view("list_ideas", $data);			
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
    
     function welcome() {
        $this->db->select("ideas.id as id, ideas.title as title, count(idea_votes.vote) as votes, sum(idea_votes.vote - 2) as score");
		$this->db->join("idea_votes", "idea_votes.idea = ideas.id", "left");
		$this->db->group_by("idea_votes.idea");
		$this->db->order_by("score", "desc");
		$this->db->limit(5);
		$data['ideas'] = $this->db->get("ideas");
        
        $this->db->select("ideas.id as id, ideas.title as title, count(idea_votes.vote) as votes, sum(idea_votes.vote - 2) as score, created");
		$this->db->join("idea_votes", "idea_votes.idea = ideas.id", "left");
		$this->db->group_by("idea_votes.idea");
		$this->db->order_by("created desc");
		$this->db->limit(5);
		$data['latest_ideas'] = $this->db->get("ideas");
        
        $this->db->select("ideas.id as id, ideas.title as title, count(idea_votes.vote) as votes, sum(idea_votes.vote - 2) as score, ideas.last_status_changed, idea_statuses.name as status");
        $this->db->where("ideas.status > 0");
		$this->db->join("idea_votes", "idea_votes.idea = ideas.id", "left");
        $this->db->join("idea_statuses", "idea_statuses.id = ideas.status");
		$this->db->group_by("idea_votes.idea");
		$this->db->order_by("ideas.last_status_changed DESC");
		$this->db->limit(5);
		$data['latest_developments'] = $this->db->get("ideas");
        
        $data["statuses_chart"] = $this->db->query('select idea_statuses.name as status, count(*) as number from ideas, idea_statuses WHERE idea_statuses.id = ideas.status GROUP BY ideas.status ORDER BY number DESC');        
        
        $data['statuses'] = $this->db->get("idea_statuses");
        
        $this->load->view("header", $data);
        $this->load->view("menu");			
        $this->load->view('left');
        $this->load->view("welcome_ideas", $data);
        $this->load->view("footer");
    }

	function search($offset = 0, $search_get = "") {
        
        if ($search_get != "") {
            $search_get = str_replace("%20", " ", $search_get);
            $this->session->set_userdata('search_title', $search_get);
        }
		$search_title = $this->retreive_data('search_title', "");
		$search_body = $this->retreive_data('search_body', "");
		$search_status = intval($this->retreive_data('search_status', 0));
		$search_sort = intval($this->retreive_data('search_sort', 0));
		$search_filter = intval($this->retreive_data('search_filter', 0));					

		$this->db->start_cache();
		$this->db->select("ideas.id as id, ideas.title as title, 
		   	ideas.votes as votes, 
			ideas.score as score, 
			ideas.comments as comments");
		$this->db->from("ideas");		
		switch ($search_filter) {
		    case 0:
			//All Ideas			
			break;
		    case 1:
			//Ideas I didn't vote
			$this->db->where("ideas.author != ". $this->dx_auth->get_user_id());
			$this->db->where("ideas.id NOT IN (SELECT idea FROM idea_votes WHERE voter =". $this->dx_auth->get_user_id(). ")");
			break;
		    case 2:
			//Ideas I am promoting
			$this->db->where("ideas.author != ". $this->dx_auth->get_user_id());
			$this->db->where("ideas.id IN (SELECT idea FROM idea_votes WHERE voter =". $this->dx_auth->get_user_id() ." AND vote = 3)");
			break;
		    case 3:
			//Ideas I am demoting
			$this->db->where("ideas.author != ". $this->dx_auth->get_user_id());
			$this->db->where("ideas.id IN (SELECT idea FROM idea_votes WHERE voter =". $this->dx_auth->get_user_id() ." AND vote = 1)");
			break;
		    case 4:
			//Ideas I don't care about
			$this->db->where("ideas.author != ". $this->dx_auth->get_user_id());
			$this->db->where("ideas.id IN (SELECT idea FROM idea_votes WHERE voter =". $this->dx_auth->get_user_id() ." AND vote = 2)");
			break;
		}	
        if ($search_title != "") {	
            $this->db->like("ideas.title", $search_title);
        }
        if ($search_body != "") {	
            $this->db->like("ideas.body", $search_body);
        }        
        $this->db->where("ideas.status", $search_status);
        
		$this->db->stop_cache();
		$num_ideas = $this->db->count_all_results();
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
		$data['ideas'] = $this->db->get();
		$this->db->flush_cache();
		$data['statuses'] = $this->db->get("idea_statuses");
		$data['view_title'] = "Ideas";
		$data['view_mine'] = False;
		$data['view_all'] = True;
		$data["search_title"] = $search_title;
		$data["search_body"] = $search_body;
		$data["search_status"] = $search_status;
		$data["search_sort"] = $search_sort;
		$data["search_filter"] = $search_filter;


		$this->load->library('pagination');
		$config['base_url'] = base_url().'index.php/idea/search/';
		$config['total_rows'] = $num_ideas;
		$config['per_page'] = '20';
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$this->pagination->initialize($config);

		$data["page_title"] = "Ideas";
		$this->load->view("header", $data);
		$this->load->view("menu");
		$this->load->view('left');
		$this->load->view("list_ideas", $data);
		$this->load->view("footer");
	}

	function subscribe($idea_id = 0)
	{
		$this->security->restrict_to_registered_users();

		$user_id = $this->dx_auth->get_user_id();
		$idea_id = intval($idea_id);

		//Check whether we're subscribed to this idea
		$this->db->where("user", $user_id);
		$this->db->where("element_type", 1);
		$this->db->where("element_id", $idea_id);
		$query = $this->db->get("subscriptions");
		if ($query->num_rows() <= 0) {
			//Insert a new subscription
			$this->db->set("user", $user_id);
			$this->db->set("element_type", 1);
			$this->db->set("element_id", $idea_id);
			$this->db->set("timestamp", now());
			$query = $this->db->insert("subscriptions");
		}
		
		$this->view($idea_id);
	}

	function unsubscribe($idea_id = 0)
	{
		$this->security->restrict_to_registered_users();

		$user_id = $this->dx_auth->get_user_id();
		$idea_id = intval($idea_id);

		//Check whether we're subscribed to this idea
		$this->db->where("user", $user_id);
		$this->db->where("element_type", 1);
		$this->db->where("element_id", $idea_id);
		$query = $this->db->get("subscriptions");
		if ($query->num_rows() > 0) {
			//Delete the subscription
			$row = $query->row();
			$this->db->where("id", $row->id);			
			$query = $this->db->delete("subscriptions");
		}
		
		$this->view($idea_id);
	}


	function add()
	{
		$this->security->restrict_to_registered_users();

		$user_id = $this->dx_auth->get_user_id();
		$this->load->view("header");
		$this->load->view("menu");
		$this->load->view('left');
		$this->load->view("add_idea");
		$this->load->view("footer");
	}

	function edit($idea_id = 0)
	{
		$this->security->restrict_to_registered_users();

		$idea_id = intval($idea_id);
		$query = $this->db->query("SELECT ideas.title as title, ideas.body as body, ideas.status as status, users.id as author_id, users.username as author_name FROM ideas, users WHERE users.id = ideas.author AND ideas.id = $idea_id");
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$data["idea_id"] = $idea_id;
			$data["idea_title"] = $row["title"];
			$data["idea_body"] = $row["body"];
			$this->load->view("header");
			$this->load->view("menu");
			$this->load->view('left');
			$this->load->view("edit_idea", $data);
			$this->load->view("footer");
		}		
	}

	function delete($idea_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$idea_id = intval($idea_id);
		$user_id = $this->dx_auth->get_user_id();
		$this->db->where("ideas.id", $idea_id);
		$this->db->from("ideas");
		$this->db->join("users", "users.id = ideas.author");
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			$row = $query->row();
			//Check that is our idea or that we're a moderator						
			if  ($this->dx_auth->is_moderator() || $row->author == $user_id) {
				if ($this->dx_auth->is_moderator() && $row->author != $user_id) {
					//Log this as a mod activity
					$data['timestamp'] = now();
        	       	$data['moderator'] = $user_id;
					$data['activity'] = "Deleted idea: '".$row->title."' from user '".$row->username."'";
					$this->db->insert("moderators_activity", $data);
				}
				$this->db->where("id", $idea_id);
				$this->db->delete("ideas");

				$this->db->where("idea", $idea_id);
				$this->db->delete("idea_votes");

				$this->db->where("idea", $idea_id);
				$this->db->delete("idea_comments");
				
				$this->db->where("element_type", 1);
				$this->db->where("element_id", $idea_id);
				$this->db->delete("notifications");
				
				$this->db->where("element_type", 1);
				$this->db->where("element_id", $idea_id);
				$this->db->delete("subscriptions");
			}
		}
			
		redirect("idea/from/$user_id", "location");
	}

	function save()
	{
		$this->security->restrict_to_registered_users();

		$user_id = $this->dx_auth->get_user_id();

                $data = array(
	       	        'title' => $_POST["title"],
        	       	'body' => $_POST["body"],
					'author' => $user_id
        	        );
		
		if ($_POST["action"] == "add") {
			if (trim($_POST["title"]) != "" and trim($_POST["body"]) != "") {
				$data["created"] = now();
				$data["last_edited"] = now();
				$this->db->insert("ideas", $data);
				$new_idea_id = $this->db->insert_id();
				$this->subscribe($new_idea_id);
			}
			else {
				redirect("idea/from/$user_id", "location");
			}
		}
		elseif ($_POST["action"] == "edit") {
			$idea_id = intval($_POST["id"]);
			if ($idea_id > 0) {
				if (trim($_POST["title"]) != "" and trim($_POST["body"]) != "") {
					$data["last_edited"] = now();
					$this->db->where("author", $user_id);
					$this->db->where("id", $idea_id);
        	        $this->db->update("ideas", $data);

					// Alert all subscribers of the edit					
					$query = $this->db->query("SELECT * FROM ideas WHERE ideas.id = $idea_id");
					if ($query->num_rows() > 0) {
						$row = $query->row();
						$title = $row->title;
						$this->_alert_subscribers($idea_id, "The idea '$title' was edited");
					}					
				}
				redirect("idea/view/$idea_id", "location");
			}
                }
	}

	function change_status($idea_id)
	{
		$this->security->restrict_to_registered_users();
		$idea_id = intval($idea_id);
		if ($this->dx_auth->is_administrator() or $this->dx_auth->is_moderator()) {	
			$data = array(
				'status' => intval($_POST["current_status"]),
                'last_status_changed' => now(),
                'reviewer' => $this->dx_auth->get_user_id()
				);		
			$this->db->where("id", $idea_id);
			$this->db->update("ideas", $data);

			// Alert all subscribers of the status change
			$query = $this->db->query("SELECT ideas.title as title, idea_statuses.name as status FROM ideas, idea_statuses WHERE ideas.status = idea_statuses.id AND ideas.id = $idea_id");
			if ($query->num_rows() > 0) {
				$row = $query->row();
				$status = $row->status;
				$title = $row->title;
				$this->_alert_subscribers($idea_id, "The status was changed to '$status' for the idea '$title'");
			}
		}
		redirect("idea/view/$idea_id", "location");
	}

	function vote($idea_id = 0, $action_id = "dontcare") 
	{
		$this->security->restrict_to_registered_users();
		$user_id = $this->dx_auth->get_user_id();
		$idea_id = intval($idea_id);

		//Check that is NOT our idea (not allowed on our own ideas)
		$this->db->where("id", $idea_id);
		$query = $this->db->get("ideas");
		if ($query->num_rows() == 1) {
			$row = $query->row();
			if  ($row->author != $user_id) {
				$data["idea"] = $idea_id;
				$data["voter"] = $user_id;

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
					redirect("idea/view/$idea_id", "location");
				}

				if ($data["voter"] > 0 && $data["idea"] > 0 && $data["vote"] > -1) {
					$this->db->where("voter", $data["voter"]);
					$this->db->where("idea", $data["idea"]);
					$this->db->delete("idea_votes");
					$this->db->insert("idea_votes", $data);	
					//Update stats					
					$this->db->query("UPDATE ideas SET votes = (SELECT count(*) FROM idea_votes WHERE idea = $idea_id), score = (SELECT SUM(vote - 2) FROM idea_votes WHERE idea = $idea_id) WHERE id = $idea_id");
				}
				
			}
		}

		redirect("idea/view/$idea_id", "location");
	}

	function comment($idea_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$user_id = $this->dx_auth->get_user_id();
		$idea_id = intval($idea_id);
		$data["idea"] = $idea_id;
	        $data["author"] = $user_id;
        	$data["body"] = $_POST["body"];
        	$data["timestamp"] = now();
		if (trim($data["body"]) != "") {
			$this->db->insert("idea_comments", $data);
			//Update stats			
			$this->db->query("UPDATE ideas SET comments = (SELECT count(*) FROM idea_comments WHERE idea = $idea_id) WHERE id = $idea_id");
			// Alert all subscribers of the comment
			$query = $this->db->query("SELECT * FROM ideas WHERE ideas.id = $idea_id");
			if ($query->num_rows() > 0) {
				$row = $query->row();
				$title = $row->title;
				$this->_alert_subscribers($idea_id, "A new comment was posted on the idea '$title'");
			}
		}
		redirect("idea/view/$idea_id", "location");
	}

	function delete_comment($comment_id = 0, $idea_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$comment_id = intval($comment_id);
		$user_id = $this->dx_auth->get_user_id();
		//Check that is OUR comment
		$this->db->where("id", $comment_id);
		$query = $this->db->get("idea_comments");
		if ($query->num_rows() == 1) {
			$row = $query->row();
			if  ($row->author == $user_id) {
				$this->db->where("author", $user_id);
				$this->db->where("id", $comment_id);
				$this->db->delete("idea_comments"); 
				//Update stats				
				$this->db->query("UPDATE ideas SET comments = (SELECT count(*) FROM idea_comments WHERE idea = $idea_id) WHERE id = $idea_id");
			}
		}
			
		redirect("idea/view/$idea_id", "location");
	}

	function _alert_subscribers($idea_id, $text)
	{		
		// Find subscribers
		$this->db->where("element_type", 1); //Idea
		$this->db->where("element_id", $idea_id);
		$subscriptions = $this->db->get("subscriptions");
		foreach($subscriptions->result() as $subscription) {			
			$subscriber = $subscription->user;
			// Create a new notification
			$data = array('element_id' => $idea_id, 'element_type' => 1, 'user' => $subscriber, 'timestamp' => now(), 'text' => $text);			
			$this->db->insert('notifications', $data);
		}
	}
}
?>
