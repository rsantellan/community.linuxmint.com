<?php
class User extends Controller{

	function User()
	{
		parent::Controller();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('date');	
		$this->load->library('email');	
	}
	
	function index()
	{
		$this->welcome();
	}
	
	function welcome()
	{		
		$data["num_users"] = $this->db->count_all('users');
		$data["num_ideas"] = $this->db->count_all('ideas');
		$data["num_tutorials"] = $this->db->count_all('tutorials');
		$data["num_hardware"] = $this->db->count_all('hardware_devices');
		$data["num_software"] = $this->db->count_all('software_reviews');

		$this->db->select("countries.id as id, countries.name as name, countries.code as code, count(*) as users");
		$this->db->from("countries");
		$this->db->join("users", "users.country = countries.id");
		$this->db->group_by("users.country");
		$this->db->order_by("count(*)", "desc");
		$data["countries"] = $this->db->get();

		$this->db->select("distro_releases.id as id, distro_releases.name as name, count(*) as users");
		$this->db->from("distro_releases");
		$this->db->join("users", "users.distro_release = distro_releases.id");
		$this->db->group_by("users.distro_release");
		$this->db->order_by("count(*)", "desc");
		$data["releases"] = $this->db->get();

		$this->db->select("editions.id as id, editions.name as name, count(*) as users");
		$this->db->from("editions");
		$this->db->join("users", "users.edition = editions.id");
		$this->db->group_by("users.edition");
		$this->db->order_by("count(*)", "desc");
		$data["editions"] = $this->db->get();
	
		//$this->db->select("tutorials.id as id, tutorials.title as title, count(tutorial_votes.vote) as votes, sum(tutorial_votes.vote - 2) as score");
		//$this->db->join("tutorial_votes", "tutorial_votes.tutorial = tutorials.id", "left");
		//$this->db->group_by("tutorial_votes.tutorial");
		//$this->db->order_by("score", "desc");
		//$this->db->limit(5);
		//$data['tutorials'] = $this->db->get("tutorials");	
		
		//Latest events
		//$query = $this->db->query('(SELECT 
		//							software_reviews.timestamp as timestamp, 
		//							"software review" as element_type, 
		//							software_packages.pkg_name as element_id, 
		//							software_packages.pkg_name as element_name, 
		//							users.id as user_id, 
		//							users.username as user_name,
		//							software_reviews.comment as additional_info,
		//							software_reviews.score as additional_info2
		//							FROM software_reviews, software_packages, users WHERE software_packages.id = software_reviews.package AND users.id = software_reviews.user)
		//							UNION
		//							(SELECT 
		//								idea_comments.timestamp AS timestamp, 
		//								"idea comment" as element_type, 
		//								ideas.id as element_id, 
		//								ideas.title as element_name, 
		//								users.id as user_id, 
		//								users.username as user_name,
		//								idea_comments.body as additional_info,
		//								"" as additional_info2
		//								FROM idea_comments, ideas, users WHERE idea_comments.idea = ideas.id and idea_comments.author = users.id)
		//							UNION
		//							(SELECT 
		//								ideas.created AS timestamp,
		//								"new idea" as element_type,
		//								ideas.id as element_id,
		//								ideas.title as element_name,
		//								users.id as user_id,
		//								users.username as user_name,
		//								"" as additional_info,
		//								"" as additional_info2
		//								FROM ideas, users WHERE ideas.author = users.id)
		//							UNION
		//							(SELECT 
		//								ideas.last_edited AS timestamp,
		//								"edited idea" as element_type,
		//								ideas.id as element_id,
		//								ideas.title as element_name,
		//								users.id as user_id,
		//								users.username as user_name,
		//								"" as additional_info,
		//								"" as additional_info2
		//								FROM ideas, users WHERE ideas.author = users.id AND ideas.last_edited > ideas.created)
		//							UNION
		//							(SELECT 
		//								hardware_devices.created AS timestamp,
		//								"new hardware" as element_type,
		//								hardware_devices.id as element_id,
		//								hardware_devices.name as element_name,
		//								users.id as user_id,
		//								users.username as user_name,
		//								hardware_brands.name as additional_info,
		//								hardware_categories.name as additional_info2
		//								FROM hardware_devices, users, hardware_brands, hardware_categories WHERE hardware_devices.owner = users.id AND hardware_devices.brand = hardware_brands.id AND hardware_devices.category = hardware_categories.id)
		//							UNION
		//							(SELECT 
		//								hardware_devices.last_edited AS timestamp,
		//								"edited hardware" as element_type,
		//								hardware_devices.id as element_id,
		//								hardware_devices.name as element_name,
		//								users.id as user_id,
		//								users.username as user_name,
		//								hardware_brands.name as additional_info,
		//								hardware_categories.name as additional_info2
		//								FROM hardware_devices, users, hardware_brands, hardware_categories  WHERE hardware_devices.owner = users.id AND hardware_devices.brand = hardware_brands.id AND hardware_devices.category = hardware_categories.id AND hardware_devices.last_edited > hardware_devices.created)
		//							UNION
		//							(SELECT 
		//								tutorials.created AS timestamp,
		//								"new tutorial" as element_type,
		//								tutorials.id as element_id,
		//								tutorials.title as element_name,
		//								users.id as user_id,
		//								users.username as user_name,
		//								"" as additional_info,
		//								"" as additional_info2
		//								FROM tutorials, users WHERE tutorials.author = users.id)
		//							UNION
		//							(SELECT 
		//								tutorials.last_edited AS timestamp,
		//								"edited tutorial" as element_type,
		//								tutorials.id as element_id,
		//								tutorials.title as element_name,
		//								users.id as user_id,
		//								users.username as user_name,
		//								"" as additional_info,
		//								"" as additional_info2
		//								FROM tutorials, users WHERE tutorials.author = users.id AND tutorials.last_edited > tutorials.created)
		//							UNION
		//							(SELECT 
		//								tutorial_comments.timestamp AS timestamp, 
		//								"tutorial comment" as element_type, 
		//								tutorials.id as element_id, 
		//								tutorials.title as element_name, 
		//								users.id as user_id, 
		//								users.username as user_name,
		//								tutorial_comments.body as additional_info,
		//								"" as additional_info2
		//								FROM tutorial_comments, tutorials, users WHERE tutorial_comments.tutorial = tutorials.id and tutorial_comments.author = users.id)
		//							ORDER BY timestamp DESC
		//							LIMIT 5');
		//$data["latest_events"] = $query;

		//Top packages
		//$this->db->select("software_packages.pkg_name, sum(score -3) as overall_score");    
        //        $this->db->from("software_packages");
        //        $this->db->join("software_reviews", "software_packages.id = software_reviews.package"); 
		//$this->db->group_by("software_reviews.package");
		//$this->db->order_by("overall_score desc, pkg_name asc");
        //        $this->db->limit(5);
        //       $data["top_packages"] = $this->db->get();

		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('left');
		$this->load->view('home/welcome', $data);
		$this->load->view('footer');
	}
	
	function delete($id = 0)
	{
		$this->security->restrict_to_registered_users();		
		$user_id = $this->dx_auth->get_user_id();
		$id = intval($id);
		$this->db->where("id", $id);
		$query = $this->db->get('users');
		if ($query->num_rows() == 1) {
			$row = $query->row();
			//Check that we're a moderator						
			if  ($this->dx_auth->is_moderator() or $this->dx_auth->is_administrator()) {
				//Log this as a mod activity
				$data['timestamp'] = now();
        	    $data['moderator'] = $user_id;
				$data['activity'] = "Deleted user: ".$row->username;
				$this->db->insert("moderators_activity", $data);							

				$this->db->where("user", $id);
				$this->db->delete("friends");
				
				$this->db->where("friend", $id);
				$this->db->delete("friends");

				$this->db->where("owner", $id);
				$this->db->delete("hardware_devices");
								
				$this->db->where("author", $id);
				$this->db->delete("ideas");
				
				$this->db->where("author", $id);
				$this->db->delete("idea_comments");
				
				$this->db->where("voter", $id);
				$this->db->delete("idea_votes");
				
				$this->db->where("author", $id);
				$this->db->delete("iso_comments");
				
				$this->db->where("maintainer", $id);
				$this->db->delete("iso_files");
				
				$this->db->where("tester", $id);
				$this->db->delete("iso_results");
				
				$this->db->where("to", $id);
				$this->db->delete("messages");
				
				$this->db->where("from", $id);
				$this->db->delete("messages");
				
				$this->db->where("moderator", $id);
				$this->db->delete("moderators_activity");
				
				$this->db->where("user", $id);
				$this->db->delete("notifications");
				
				$this->db->where("user", $id);
				$this->db->delete("software_reviews");
				
				$this->db->where("user", $id);
				$this->db->delete("subscriptions");
				
				$this->db->where("author", $id);
				$this->db->delete("tutorials");
				
				$this->db->where("author", $id);
				$this->db->delete("tutorial_comments");
				
				$this->db->where("voter", $id);
				$this->db->delete("tutorial_votes");
				
				$this->db->where("id", $id);
				$this->db->delete("users");
			}
		}
			
		redirect("user/moderators", "location");
	}

	function profile($username = "")
	{		
		$username = mysql_real_escape_string($username);	
		$this->db->where("LOWER(username)", strtolower($username));
		$user = $this->db->get("users");
		if ($user->num_rows() > 0) {
			$id = $user->row()->id;
			$this->view($id);
		}
		else {
			$data["error"] = "Not found";
			$data["details"] = "This user does not exist.";
			$this->load->view("header");
			$this->load->view("menu");
			$this->load->view('left');
			$this->load->view('error', $data);
			$this->load->view("footer");
		}
	}	

	function view($user_id = 0)
	{
		$user_id = intval($user_id);
		if ($user_id ==  0) {
			if ($this->dx_auth->is_logged_in()) {
				$user_id = $this->dx_auth->get_user_id();
			}
		}
		
		$this->db->where("id", $user_id);
		$records = $this->db->get("users");
		if ($records->num_rows() > 0) {

			$data = $this->myself->get_details($user_id);				

			$this->db->select("users.username as friend_name, users.id as friend_id, users.score");
			$this->db->from("friends");
			$this->db->join("users", "friends.friend = users.id");
			$this->db->where("friends.user", $user_id);
			$this->db->where("users.id IN (SELECT user FROM friends WHERE friend = ".$user_id.")");
			$this->db->order_by("users.score DESC, friend_name");
			$data['friends'] = $this->db->get();

			$this->db->select("ideas.id as id, ideas.title as title, count(idea_votes.vote) as votes, sum(idea_votes.vote - 2) as score, (SELECT count(*) FROM idea_comments WHERE idea_comments.idea = ideas.id) as comments, idea_statuses.name as status");
			$this->db->where("ideas.author", $user_id);
			$this->db->join("idea_votes", "idea_votes.idea = ideas.id", "left");
            $this->db->join("idea_statuses", "idea_statuses.id = ideas.status", "left");
			$this->db->group_by("idea_votes.idea");
			$this->db->order_by("ideas.status desc, score desc");
			$data['ideas'] = $this->db->get("ideas");

			$this->db->select("tutorials.id as id, tutorials.title as title, count(tutorial_votes.vote) as votes, sum(tutorial_votes.vote - 2) as score, (SELECT count(*) FROM tutorial_comments WHERE tutorial_comments.tutorial = tutorials.id) as comments");
			$this->db->where("tutorials.author", $user_id);
			$this->db->join("tutorial_votes", "tutorial_votes.tutorial = tutorials.id", "left");
			$this->db->group_by("tutorial_votes.tutorial");
			$this->db->order_by("score", "desc");
			$data['tutorials'] = $this->db->get("tutorials");

			$this->db->select("hardware_devices.id as id, hardware_devices.name as name, hardware_categories.name as category, hardware_brands.name as brand, distro_releases.name as distro_release, hardware_statuses.name as status, hardware_statuses.id as status_id");		
			$this->db->join("hardware_brands", "hardware_devices.brand = hardware_brands.id");
			$this->db->join("hardware_categories", "hardware_devices.category = hardware_categories.id");
			$this->db->join("hardware_statuses", "hardware_devices.status = hardware_statuses.id");
			$this->db->join("distro_releases", "hardware_devices.distro_release = distro_releases.id");
			$this->db->where("hardware_devices.owner", $user_id);		
			$this->db->order_by("category, name");
			$data['hardware_devices'] = $this->db->get("hardware_devices");

			$this->db->select("software_reviews.comment as comment, software_packages.pkg_name as pkg_name");
			$this->db->where("software_reviews.user", $user_id);
			$this->db->where("software_reviews.score", 5);
			$this->db->join("software_packages", "software_packages.id = software_reviews.package");
			$this->db->order_by("software_reviews.timestamp", "desc");
			$data['reviews'] = $this->db->get("software_reviews");			

			$data["page_title"] = $data["user_name"];
			$this->load->view('header', $data);
			$this->load->view('menu');
			$this->load->view('left');
			$this->load->view('home/profile', $data);
			$this->load->view('footer');
		}
		else {
			$data["error"] = "Not found";
			$data["details"] = "This user does not exist.";
			$this->load->view("header");
			$this->load->view("menu");
			$this->load->view('left');	
			$this->load->view('error', $data);
			$this->load->view("footer");
		}
	}

	function change_details()
	{
		$this->security->restrict_to_registered_users();

		$user_id = $this->dx_auth->get_user_id();
		$data = $this->myself->get_details($user_id);

		$this->db->order_by("name");
		$data["countries"] = $this->db->get('countries');

		$this->db->order_by("name");
		$data["releases"] = $this->db->get('distro_releases');

		$this->db->order_by("name");
		$data["editions"] = $this->db->get('editions');

		$query = $this->db->query("SELECT country, distro_release, edition, disable_emails FROM users WHERE id = $user_id");
		$row = $query->row_array();
		if ($query->num_rows() > 0) {
			$data["country_id"] = $row["country"];
			$data["release_id"] = $row["distro_release"];
			$data["edition_id"] = $row["edition"];
			$data["disable_emails"] = $row["disable_emails"];
			
			$this->load->view('header');
			$this->load->view('menu');
			$this->load->view('left');
			$this->load->view('home/change_details', $data);
			$this->load->view('footer');
		}
	}

  function deleteAvatar()
  {
    $this->security->restrict_to_registered_users();

		$user_id = $this->dx_auth->get_user_id();
    if (file_exists(FCPATH.'uploads/avatars/'.$user_id.".jpg"))
    {
      @unlink(FCPATH.'uploads/avatars/'.$user_id.".jpg");
    }
		redirect('user/change_details');
  }

	function save_details()
	{
		$this->security->restrict_to_registered_users();

		$user_id = $this->dx_auth->get_user_id();
		
		if (isset($_POST["disable_emails"])) {
			$disable_emails = 1;
		}
		else {
			$disable_emails = 0;
		}
		
        
		$data = array(
                'biography' => $_POST["biography"],
                'signature' => $_POST["signature"],
                'country' => intval($_POST["country"]),
				'distro_release' => intval($_POST["release"]),
				'edition' => intval($_POST["edition"]),
				'disable_emails' => $disable_emails
                );
    
    /*
     * 
     * Change the order of saving the data 
     * to improve the errors of uploading files
     * 
     */ 
    $this->db->where('id', $user_id);
    $this->db->update('users', $data);
		
    //Check if the files exists
    if(isset($_FILES['avatar']) && $_FILES['avatar']["error"] != 4)
    {
      $config['upload_path'] = FCPATH.'uploads/avatars/';
      $config['file_name'] = "$user_id";
      $config['overwrite'] = TRUE;
      $config['allowed_types'] = 'jpg';
      $config['is_image'] = TRUE;
      $config['max_size']	= '100';
      $config['max_width']  = '100';
      $config['max_height']  = '100';
      
      $this->load->library('upload', $config);

      if (!$this->upload->do_upload("avatar"))
      {
        $upload_data = $this->upload->data();
        if ($upload_data['orig_name'] != "") 
        {
        }
        $data["error"] = $this->upload->display_errors();
        $this->load->view('header');
        $this->load->view('menu');
        $this->load->view('left');
        $this->load->view('home/upload_failure', $data);
        $this->load->view('footer');
        return;
      }  
    }
		$this->view(0);		
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
            $this->session->set_userdata('search_user_username', $search_get);
        }
		$search_user_username = $this->retreive_data('search_user_username', "");
		$search_user_email = $this->retreive_data('search_user_email', "");
		$search_user_country = intval($this->retreive_data('search_user_country', -1));
		$search_user_release = intval($this->retreive_data('search_user_release', -1));
		$search_user_edition = intval($this->retreive_data('search_user_edition', -1));
		$this->db->start_cache();
		$this->db->select("id, username, country, distro_release, edition, score");
		$this->db->from("users");		
		$this->db->like("lower(username)", strtolower($search_user_username));
		$this->db->like("lower(email)", strtolower($search_user_email));
		if ($search_user_country != -1) {
			$this->db->where("country", $search_user_country);
		}
		if ($search_user_release != -1) {		
			$this->db->where("distro_release", $search_user_release);
		}
		if ($search_user_edition != -1) {
			$this->db->where("edition", $search_user_edition);
		}
		$this->db->order_by("score DESC, lower(username) ASC");
		$this->db->stop_cache();
		$num_users = $this->db->count_all_results();		
		$this->db->limit(20);
		$this->db->offset($offset);
		$data['users'] = $this->db->get();
		$this->db->flush_cache();
		$data['countries'] = $this->db->get("countries");
		$data['releases'] = $this->db->get("distro_releases");
		$data['editions'] = $this->db->get("editions");
		$data["search_user_username"] = $search_user_username;
		$data["search_user_email"] = $search_user_email;
		$data["search_user_country"] = $search_user_country;
		$data["search_user_release"] = $search_user_release;
		$data["search_user_edition"] = $search_user_edition;


		$this->load->library('pagination');
		$config['base_url'] = base_url().'index.php/user/search/';
		$config['total_rows'] = $num_users;
		$config['per_page'] = '20';
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$this->pagination->initialize($config);

		$data["page_title"] = "Users";
		$this->load->view("header", $data);
		$this->load->view("menu");
		$this->load->view('left');
		$this->load->view("list_users", $data);
		$this->load->view("footer");
	}
	
	function add_moderator() {
		$this->security->restrict_to_registered_users();
		if ($this->dx_auth->is_administrator()) {	
			$username = $this->input->post('username');
			$this->db->where('lower(username)', strtolower($username));
			$users = $this->db->get('users');
			if ($users->num_rows() > 0) {
				$id = $users->row()->id;
				$this->make_moderator($id);
			}
			else {
				redirect("user/moderators", "location");
			}
		}
	}
	
	function make_moderator($user_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$user_id = intval($user_id);
		if ($this->dx_auth->is_administrator()) {	
			$this->db->set('ismoderator', 1);
			$this->db->where('id', $user_id);
            $this->db->update('users');

			//Notify by email
			$user = $this->myself->get_details($user_id);					
			$this->email->from('no-reply@linuxmint.com', 'Linux Mint Community');
			$this->email->to($user['email']); 
			$this->email->subject("Welcome to the moderation team");
			$this->email->message("Hello ".$user['user_name'].",

Your status on the Community Website changed to moderator.

Congratulations and many thanks for being part of the team.

--
"); 
			$this->email->send();			
		}
		redirect("user/moderators", "location");
	}
	
	function remove_moderator($user_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$user_id = intval($user_id);
		if ($this->dx_auth->is_administrator()) {	
			$this->db->set('ismoderator', 0);
			$this->db->where('id', $user_id);
            $this->db->update('users');

			//Notify by email
			$user = $this->myself->get_details($user_id);					
			$this->email->from('no-reply@linuxmint.com', 'Linux Mint Community');
			$this->email->to($user['email']); 
			$this->email->subject("Moderation status notification");
			$this->email->message("Hello ".$user['user_name'].",

Your status on the Community Website changed to normal user.

Congratulations for the time you spent within the moderation team and for the help you brought to the project.

--
"); 
			$this->email->send();			
		}
		redirect("user/moderators", "location");
	}
	
	function moderators() {
		$this->db->where('ismoderator', 1);
		$this->db->order_by('lower(username) ASC');
		$data['moderators'] = $this->db->get('users');
		$this->db->order_by('timestamp DESC');
		$this->db->limit(10);
		$this->db->join('users', 'users.id = moderators_activity.moderator');
		$data['moderators_activity'] = $this->db->get('moderators_activity');
		$data['page_title'] = 'Moderation';
		$this->load->view('header', $data);
		$this->load->view('menu');
		$this->load->view('left');
		$this->load->view('list_moderators', $data);
		$this->load->view('footer');
	}	
	
}
?>
