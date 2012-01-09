<?php
class Iso extends Controller{

	function Iso()
	{
		parent::Controller();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('date');	
		$this->load->library('email');	
	}
	
	function index()
	{
		$this->browse();
	}
	
	function browse()
	{		
		$this->db->select('iso_files.*, editions.name as edition_name, distro_releases.name as release_name, users.username, iso_statuses.name as status_name, (SELECT COUNT(DISTINCT test_case) FROM iso_results WHERE iso_file = iso_files.id) as num_tests');
		$this->db->from('iso_files');
		$this->db->join('editions', 'editions.id = iso_files.edition');
		$this->db->join('distro_releases', 'distro_releases.id = iso_files.distro_release');
		$this->db->join('users', 'users.id = iso_files.maintainer');
		$this->db->join('iso_statuses', 'iso_statuses.id = iso_files.status');
		$this->db->order_by('iso_files.created DESC');
		$data['isos'] = $this->db->get();	
		
		$data['num_cases'] = $this->db->count_all('iso_testcases');			
			
		$data['page_title'] = 'Iso images';
		$this->load->view('header', $data);
		$this->load->view('menu');
		$this->load->view('left');
		$this->load->view('list_isos', $data);
		$this->load->view('footer');
	}	
	
	function view($iso_id) {
		$iso_id = intval($iso_id);
		$this->db->select('iso_files.*, editions.name as edition_name, distro_releases.name as release_name, users.username, iso_statuses.name as status_name');
		$this->db->from('iso_files');
		$this->db->join('editions', 'editions.id = iso_files.edition');
		$this->db->join('distro_releases', 'distro_releases.id = iso_files.distro_release');
		$this->db->join('users', 'users.id = iso_files.maintainer');
		$this->db->join('iso_statuses', 'iso_statuses.id = iso_files.status');
		$this->db->where('iso_files.id', $iso_id);
		$isos = $this->db->get();		
		if ($isos->num_rows() > 0) {
			$iso = $isos->row();
			$data['iso']= $iso;
			$data['iso_name'] = $iso->release_name." ".$iso->edition_name." ".$iso->architecture." (build #".$iso->build.")";
			$data['page_title'] = $data['iso_name'];
			
			$data['statuses'] = $this->db->get('iso_statuses');
			
			$data['testcases'] = $this->db->query("SELECT 
													UU.*, 
													(SELECT COUNT(*) FROM iso_results WHERE iso_file = $iso_id AND test_case = UU.id) AS tests, 
													(SELECT COUNT(*) FROM iso_results WHERE iso_file = $iso_id AND test_case = UU.id AND result = 1) AS success, 
													(SELECT COUNT(*) FROM iso_results WHERE iso_file = $iso_id AND test_case = UU.id AND result = 0) AS warnings,
													(SELECT COUNT(*) FROM iso_results WHERE iso_file = $iso_id AND test_case = UU.id AND result = -1) AS failures
													FROM iso_testcases AS UU ORDER BY UU.name");
			if ($this->dx_auth->is_tester()) {							
				$data['myremainingtestcases'] = $this->db->query("SELECT 
													UU.*, 
													(SELECT COUNT(*) FROM iso_results WHERE iso_file = $iso_id AND test_case = UU.id) AS tests, 
													(SELECT COUNT(*) FROM iso_results WHERE iso_file = $iso_id AND test_case = UU.id AND result = 1) AS success, 
													(SELECT COUNT(*) FROM iso_results WHERE iso_file = $iso_id AND test_case = UU.id AND result = 0) AS warnings,
													(SELECT COUNT(*) FROM iso_results WHERE iso_file = $iso_id AND test_case = UU.id AND result = -1) AS failures
													FROM iso_testcases AS UU WHERE ".$this->dx_auth->get_user_id()." NOT IN (SELECT DISTINCT tester FROM iso_results WHERE iso_file = ".$iso_id." AND test_case = UU.id) ORDER BY UU.name");																						
			}
			
			$this->db->select("users.id as author_id, users.username as author_name, iso_comments.body as body, iso_comments.id as id, iso_comments.timestamp as timestamp");			
			$this->db->join("users", "users.id = iso_comments.author");
            $this->db->where("iso_file", $iso_id);
			$this->db->order_by("timestamp DESC");
            $data["comments"] = $this->db->get("iso_comments");
			
			$this->load->view('header', $data);
			$this->load->view('menu');
			$this->load->view('left');
			$this->load->view('iso', $data);
			$this->load->view('footer');
		}
	}	
	
	function add() {
		$this->db->order_by('name DESC');
		$data['releases'] = $this->db->get('distro_releases');
		$this->db->order_by('name');
		$data['editions'] = $this->db->get('editions');
		$data['page_title'] = 'New ISO image';
		$this->load->view('header', $data);
		$this->load->view('menu');
		$this->load->view('left');
		$this->load->view('add_iso', $data);
		$this->load->view('footer');
	}
	
	function edit($iso_id) {		
		$iso_id = intval($iso_id);
		$this->db->select('iso_files.*, editions.name as edition_name, distro_releases.name as release_name, users.username, iso_statuses.name as status_name');
		$this->db->from('iso_files');
		$this->db->join('editions', 'editions.id = iso_files.edition');
		$this->db->join('distro_releases', 'distro_releases.id = iso_files.distro_release');
		$this->db->join('users', 'users.id = iso_files.maintainer');
		$this->db->join('iso_statuses', 'iso_statuses.id = iso_files.status');
		$this->db->where('iso_files.id', $iso_id);
		$isos = $this->db->get();		
		if ($isos->num_rows() > 0) {
			$iso = $isos->row();
			$data['iso']= $iso;
			$data['iso_name'] = $iso->release_name." ".$iso->edition_name." ".$iso->architecture." (build #".$iso->build.")";
			$data['page_title'] = $data['iso_name'];					
				
			$this->db->order_by('name DESC');
			$data['releases'] = $this->db->get('distro_releases');
		
			$this->db->order_by('name');
			$data['editions'] = $this->db->get('editions');
			
			$this->load->view('header', $data);
			$this->load->view('menu');
			$this->load->view('left');
			$this->load->view('edit_iso', $data);
			$this->load->view('footer');
		}
	}
	
	function save()
	{
		$this->security->restrict_to_registered_users();
		$user_id = $this->dx_auth->get_user_id();
		if ($this->dx_auth->is_maintainer()){
			if ($_POST["action"] == "add") {
					$data = array(
						'distro_release' => $_POST["distro_release"],
						'edition' => $_POST["edition"],
						'architecture' => $_POST["architecture"],
						'build' => $_POST["build"],
						'url' => $_POST["url"],
						'md5' => $_POST["md5"],
						'maintainer' => $user_id,
						'status' => 1,
						'created' => now()
						);
					$this->db->insert("iso_files", $data);
					$new_id = $this->db->insert_id();
					
					$this->db->where('id', $_POST["edition"]);
					$editions = $this->db->get('editions');
					$edition_name = $editions->row()->name;
					
					$this->db->where('id', $_POST["distro_release"]);
					$releases = $this->db->get('distro_releases');
					$release_name = $releases->row()->name;
					
					//Notify the testers by email
					$this->db->where("istester", 1);
					$testers = $this->db->get("users");
					foreach($testers->result() as $tester) {			
						$this->email->from('no-reply@linuxmint.com', 'Linux Mint Community');
						$this->email->to($tester->email); 
						$this->email->subject($release_name." ".$edition_name." ".$_POST['architecture']."(build #".$_POST['build'].") ready for testing");
						$this->email->message("Hello ".$tester->username.",

".$release_name." ".$edition_name." ".$_POST['architecture']."(build #".$_POST['build'].") is ready for testing:

http://community.linuxmint.com/iso/view/".$new_id."

--
"); 
						$this->email->send();
					}
					
					redirect("iso", "location");
			}
			elseif ($_POST["action"] == "edit") {				
				$iso_id = intval($_POST["id"]);
				$this->db->where('id', $iso_id);
				$isos = $this->db->get('iso_files');
				if ($isos->num_rows() > 0) {
					$iso = $isos->row();
					if ($iso->maintainer == $this->dx_auth->get_user_id()) {
						$data = array(
						'distro_release' => $_POST["distro_release"],
						'edition' => $_POST["edition"],
						'architecture' => $_POST["architecture"],
						'build' => $_POST["build"],
						'url' => $_POST["url"],
						'md5' => $_POST["md5"]																
						);
						$this->db->where('id', $iso_id);
						$this->db->update('iso_files', $data);
					}
				}
				
				redirect("iso/view/".$iso_id, "location");				
			}
		}
	}
	
	function delete($iso_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$iso_id = intval($iso_id);
		$user_id = $this->dx_auth->get_user_id();
		$this->db->where('id', $iso_id);
		$query = $this->db->get('iso_files');
		if ($query->num_rows() == 1) {
			$row = $query->row();
			//Check that is our iso						
			if  ($row->maintainer == $user_id) {
				
				$this->db->where('id', $iso_id);
				$this->db->delete('iso_files');

				$this->db->where('iso_file', $iso_id);
				$this->db->delete('iso_results');

				$this->db->where('iso_file', $iso_id);
				$this->db->delete('iso_comments');
			}
		}
			
		redirect("iso", "location");
	}
	
	function team() {				
		$this->db->where('istester', 1);
		$this->db->order_by('lower(username) ASC');
		$data['testers'] = $this->db->get('users');		
		$this->db->where('ismaintainer', 1);
		$this->db->order_by('lower(username) ASC');
		$data['maintainers'] = $this->db->get('users');		
		
		$data['page_title'] = 'Testers';
		$this->load->view('header', $data);
		$this->load->view('menu');
		$this->load->view('left');
		$this->load->view('iso_team', $data);
		$this->load->view('footer');
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
	
	function add_tester() {
		$this->security->restrict_to_registered_users();
		if ($this->dx_auth->is_administrator()) {	
			$username = $this->input->post('username');
			$this->db->where('lower(username)', strtolower($username));
			$users = $this->db->get('users');
			if ($users->num_rows() > 0) {
				$id = $users->row()->id;
				$this->make_tester($id);
			}
			else {
				redirect("iso/team", "location");
			}
		}
	}
	
	function make_tester($user_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$user_id = intval($user_id);
		if ($this->dx_auth->is_administrator()) {	
			$this->db->set('istester', 1);
			$this->db->where('id', $user_id);
            $this->db->update('users');

			//Notify by email
			$user = $this->myself->get_details($user_id);					
			$this->email->from('no-reply@linuxmint.com', 'Linux Mint Community');
			$this->email->to($user['email']); 
			$this->email->subject("Welcome to the ISO testing team");
			$this->email->message("Hello ".$user['user_name'].",

Your are now part of the ISO testing team. As such you are given access to Linux Mint ISO images before they are released to the public.

Each ISO needs to pass a series of tests before being approved for a public release. You can help and monitor the testing of ISO images by visiting:

http://community.linuxmint.com/iso

Congratulations and many thanks for being part of the team.

--
"); 
			$this->email->send();			
		}
		redirect("iso/team", "location");
	}
	
	function remove_tester($user_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$user_id = intval($user_id);
		if ($this->dx_auth->is_administrator()) {	
			$this->db->set('istester', 0);
			$this->db->where('id', $user_id);
            $this->db->update('users');

			//Notify by email
			$user = $this->myself->get_details($user_id);					
			$this->email->from('no-reply@linuxmint.com', 'Linux Mint Community');
			$this->email->to($user['email']); 
			$this->email->subject("ISO testing team status notification");
			$this->email->message("Hello ".$user['user_name'].",

You are no longer a member of the ISO Testing team on the Linux Mint Community website.

--
"); 
			$this->email->send();			
		}
		redirect("iso/team", "location");
	}
	
	function add_maintainer() {
		$this->security->restrict_to_registered_users();
		if ($this->dx_auth->is_administrator()) {	
			$username = $this->input->post('username');
			$this->db->where('lower(username)', strtolower($username));
			$users = $this->db->get('users');
			if ($users->num_rows() > 0) {
				$id = $users->row()->id;
				$this->make_maintainer($id);
			}
			else {
				redirect("iso/team", "location");
			}
		}
	}
	
	function make_maintainer($user_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$user_id = intval($user_id);
		if ($this->dx_auth->is_administrator()) {	
			$this->db->set('ismaintainer', 1);
			$this->db->where('id', $user_id);
            $this->db->update('users');

			//Notify by email
			$user = $this->myself->get_details($user_id);					
			$this->email->from('no-reply@linuxmint.com', 'Linux Mint Community');
			$this->email->to($user['email']); 
			$this->email->subject("Welcome to the Linux Mint maintainers team");
			$this->email->message("Hello ".$user['user_name'].",

Your are now part of the Maintainers team. As such you are able to register new ISO images for them to be tested on the Community Website.

Each ISO needs to pass a series of tests before being approved for a public release. You can help and monitor the testing of ISO images by visiting:

http://community.linuxmint.com/iso

Congratulations and many thanks for being part of the team.

--
"); 
			$this->email->send();			
		}
		redirect("iso/team", "location");
	}
	
	function remove_maintainer($user_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$user_id = intval($user_id);
		if ($this->dx_auth->is_administrator()) {	
			$this->db->set('ismaintainer', 0);
			$this->db->where('id', $user_id);
            $this->db->update('users');

			//Notify by email
			$user = $this->myself->get_details($user_id);					
			$this->email->from('no-reply@linuxmint.com', 'Linux Mint Community');
			$this->email->to($user['email']); 
			$this->email->subject("ISO maintainers team status notification");
			$this->email->message("Hello ".$user['user_name'].",

You are no longer a member of the maintainers team on the Linux Mint Community website.

--
"); 
			$this->email->send();			
		}
		redirect("iso/team", "location");
	}
	
	function change_status($iso_id)
	{
		$this->security->restrict_to_registered_users();
		$iso_id = intval($iso_id);
		if ($this->dx_auth->is_administrator()) {	
			$data = array(
				'status' => intval($_POST["current_status"])
				);		
			$this->db->where("id", $iso_id);
			$this->db->update("iso_files", $data);			
		}
		redirect("iso/view/$iso_id", "location");
	}
	
	function comment($iso_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$iso_id = intval($iso_id);
		$user_id = $this->dx_auth->get_user_id();
		if ($this->dx_auth->is_tester()) {			
			$data["iso_file"] = $iso_id;
			$data["author"] = $user_id;
			$data["body"] = $this->input->post('body');
			$data["timestamp"] = now();
			if (trim($data["body"]) != "") {
				$this->db->insert("iso_comments", $data);		
                //Notify the testers by email
                $this->db->where("istester", 1);
                $this->db->where("disable_emails", 0);
                $testers = $this->db->get("users");
                foreach($testers->result() as $tester) {			
                    $this->email->from('no-reply@linuxmint.com', 'Linux Mint Community');
                    $this->email->to($tester->email); 
                    $this->email->subject("Linux Mint ISO Testing - Comment");                    
                    $this->email->message("Hello ".$tester->username.",

A new comment was posted by ".$this->dx_auth->get_username()." on the following ISO: http://community.linuxmint.com/iso/view/".$iso_id."

".$data["body"]."

--

"); 
						$this->email->send();
					}		
			}
		}
		redirect("iso/view/".$iso_id, "location");
	}

	function delete_comment($comment_id = 0, $iso_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$comment_id = intval($comment_id);
		$user_id = $this->dx_auth->get_user_id();
		//Check that is OUR comment
		$this->db->where("id", $comment_id);
		$query = $this->db->get("iso_comments");
		if ($query->num_rows() == 1) {
			$row = $query->row();
			if  ($row->author == $user_id) {
				$this->db->where("author", $user_id);
				$this->db->where("id", $comment_id);
				$this->db->delete("iso_comments"); 				
			}
		}
			
		redirect("iso/view/".$iso_id, "location");
	}
	
	function testcases() {
		$this->db->order_by('name');
		$data['iso_testcases'] = $this->db->get('iso_testcases');
		$data['page_title'] = 'ISO Test Cases';
		$this->load->view('header', $data);
		$this->load->view('menu');
		$this->load->view('left');
		$this->load->view('iso_testcases', $data);
		$this->load->view('footer');
	}
	
	function testcase($id) {
		$id = intval($id);
		$this->db->where('id', $id);
		$testcases = $this->db->get('iso_testcases');
		if ($testcases->num_rows() > 0) {
			$data['testcase'] = $testcases->row();
			$data['page_title'] = $data['testcase']->name;
			$this->load->view('header', $data);
			$this->load->view('menu');
			$this->load->view('left');
			$this->load->view('iso_testcase', $data);
			$this->load->view('footer');
		}		
	}
	
	function add_testcase() {
		$data['page_title'] = 'New Test Case';
		$this->load->view('header', $data);
		$this->load->view('menu');
		$this->load->view('left');
		$this->load->view('add_testcase', $data);
		$this->load->view('footer');
	}
	
	function edit_testcase($id) {
		$this->security->restrict_to_registered_users();
		$id = intval($id);
		if ($this->dx_auth->is_administrator()) {	
			$this->db->where('id', $id);
			$tests = $this->db->get('iso_testcases');
			if ($tests->num_rows() > 0) {
				$test = $tests->row();
				$data['testcase'] = $test;
				$data['page_title'] = $test->name;
				$this->load->view('header', $data);
				$this->load->view('menu');
				$this->load->view('left');
				$this->load->view('edit_testcase', $data);
				$this->load->view('footer');
			}	
		}	
	}
	
	function save_testcase() {
		$this->security->restrict_to_registered_users();		
		if ($this->dx_auth->is_administrator()) {	
			if ($_POST["action"] == "add") {
				$data = array(
					'name' => $_POST["name"],
					'reproducing' => $_POST["reproducing"],
					'expecting' => $_POST["expecting"],
					'notes' => $_POST["notes"]				
					);
				$this->db->insert("iso_testcases", $data);			
				redirect("iso/testcases", "location");
			}
			elseif ($_POST["action"] == "edit") {
				$id = $_POST['id'];
				$data = array(
					'name' => $_POST["name"],
					'reproducing' => $_POST["reproducing"],
					'expecting' => $_POST["expecting"],
					'notes' => $_POST["notes"]				
					);
				$this->db->where('id', $id);
				$this->db->update("iso_testcases", $data);
				redirect("iso/testcase/".$id, "location");
			}
		}
		
	}
	
	function delete_testcase($id) {
		$this->security->restrict_to_registered_users();
		$id = intval($id);
		if ($this->dx_auth->is_administrator()) {		
			$this->db->where('id', $id);
            $this->db->delete('iso_testcases');
            
            $this->db->where('test_case', $id);
            $this->db->delete('iso_results');
		}
		redirect("iso/testcases", "location");
	}
	
	function reports($iso_id =0, $test_id = 0) {
		$iso_id = intval($iso_id);
		$test_id = intval($test_id);
		$this->db->where('id', $test_id);		
		$testcases = $this->db->get('iso_testcases');
		if ($testcases->num_rows() > 0) {
			$data['iso_id'] = $iso_id;
			$data['testcase'] = $testcases->row();
			$this->db->select('iso_results.*, users.username');
			$this->db->where('iso_file', $iso_id);
			$this->db->where('test_case', $test_id);
			$this->db->order_by('timestamp');
			$this->db->join('users', 'users.id = iso_results.tester');
			$data['results'] = $this->db->get('iso_results');
			
			if ($this->dx_auth->is_tester()) {
				$this->db->where('tester', $this->dx_auth->get_user_id());
				$this->db->where('iso_file', $iso_id);
				$this->db->where('test_case', $test_id);
				$results = $this->db->get('iso_results');
				if ($results->num_rows() > 0) {
					$data['vote'] = $results->row()->result;
				}
				else {
					$data['vote'] = -2;
				}				
			}
			
			$data['page_title'] = $data['testcase']->name;
			$this->load->view('header', $data);
			$this->load->view('menu');
			$this->load->view('left');
			$this->load->view('iso_reports', $data);
			$this->load->view('footer');
		}		
	}
	
	function report($iso_id = 0, $test_id = 0, $action_id = 'success') 
	{
		$this->security->restrict_to_registered_users();
		$iso_id = intval($iso_id);
		$test_id = intval($test_id);
		
		if ($action_id == 'success') {
			$data['result'] = 1;			
		}
		else if ($action_id == 'warning') {
			$data['result'] = 0;			
		}
		else if ($action_id == 'failure') {
			$data['result'] = -1;
		}
		else {
			redirect("iso/reports/$iso_id/$test_id", 'location');
		}
		
		if ($this->dx_auth->is_tester()) {		
			$user_id = $this->dx_auth->get_user_id();
						
			$this->db->where('tester', $user_id);
			$this->db->where('iso_file', $iso_id);
			$this->db->where('test_case', $test_id);		
			$this->db->delete('iso_results');
			
			$data['tester'] = $user_id;
			$data['iso_file'] = $iso_id;
			$data['test_case'] = $test_id;
			$data['timestamp'] = now();
			$this->db->insert('iso_results', $data);						
		}

		redirect("iso/reports/$iso_id/$test_id", 'location');
	}
}
?>
