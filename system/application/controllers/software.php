<?php
class Software extends Controller{

	function Software()
	{
		parent::Controller();
		$this->load->helper('url');
		$this->load->helper('form');		
		$this->load->helper('date');
		$this->load->library('email');
	}

	function index()
	{		
		$this->browse(0);
	}

	function view($package = 0)
	{
		$query = $this->db->query("SELECT * FROM software_packages WHERE pkg_name = '$package'");
		if ($query->num_rows() > 0) {
			$data = $query->row_array();
			$package_id = $data["id"];
			if ($this->dx_auth->is_logged_in()) {
				$user_id = $this->dx_auth->get_user_id();
			}
			else {
				$user_id = 0;
			}				

			if (file_exists(FCPATH.'img/screenshots/'.$package.".png")) {
        	                $data["screenshot"] = '/img/screenshots/'.$package.".png";
				$data["screenshot_path"] = FCPATH.'img/screenshots/'.$package.".png";
			}
			elseif (file_exists(FCPATH.'uploads/screenshots/'.$package.".png")) {
        	                $data["screenshot"] = "#";
				$data["screenshot_path"] = FCPATH."img/screenshot-under-review.png";				
			}
			elseif (!$this->dx_auth->is_logged_in()) {
				$data["screenshot"] = "#";
				$data["screenshot_path"] = FCPATH."img/no-screenshot-visitors.png";
			}			
			else {
		
				$data["screenshot"] = "/index.php/software/suggest_screenshot/".$package;
				$data["screenshot_path"] = FCPATH."img/no-screenshot.png";
			}

			$this->db->from("software_assoc_packages_categories");
			$this->db->join("software_categories", "software_categories.id = software_assoc_packages_categories.category");			
			$this->db->where("software_assoc_packages_categories.package", $package_id);
	                $data['categories'] = $this->db->get();

			$this->db->from("software_assoc_packages_releases");
			$this->db->join("distro_releases", "distro_releases.id = software_assoc_packages_releases.distro_release");
			$this->db->where("software_assoc_packages_releases.package", $package_id);
	                $data['releases'] = $this->db->get();

			$query_review = $this->db->query("SELECT * FROM software_reviews WHERE package = $package_id AND user = $user_id");
			if ($query_review->num_rows() > 0) {
				$review = $query_review->row();
				$data["review"] = $review;
				$data["reviewed"] = true;
			}
			else {
				$data["reviewed"] = false;
			}

			$this->db->select("software_reviews.*, users.username");
			$this->db->from("software_reviews");
			$this->db->where("package", $package_id);
			$this->db->join("users", "users.id = software_reviews.user");
			$this->db->order_by("timestamp DESC");
			$data["reviews"] = $this->db->get();

			$this->db->where("package", $package_id);
			$reviews_count = $this->db->get("software_reviews");			
			$data["num_reviews"] = $reviews_count->num_rows();

			if ($data["num_reviews"] > 0) {
				$query_score = $this->db->query("SELECT sum(score - 3) as score FROM software_reviews WHERE package = $package_id");
				$score_row = $query_score->row();
				$data["overall_score"] = $score_row->score;
			}
			else {
				$data["overall_score"] = 0;
			}

			$data["page_title"] = $data["pkg_name"];
			$this->load->view("header", $data);
			$this->load->view("menu");
			$this->load->view('left');	
			$this->load->view('software', $data);
			$this->load->view("footer");
		}		
	}

	function moderate_review($review_id = 0, $package= 0) {		
		$this->security->restrict_to_registered_users();
		if ($this->dx_auth->is_moderator()) {			
			$review_id = intval($review_id);		
			$user_id = $this->dx_auth->get_user_id();
			$this->db->where("software_reviews.id", $review_id);
			$this->db->from("software_reviews");
			$this->db->join("users", "users.id = software_reviews.user");
			$query = $this->db->get();
			if ($query->num_rows() == 1) {
				$row = $query->row();
			
				// Delete the review
				$this->db->where("id", $review_id);
				$this->db->delete("software_reviews");

				//Log this as a mod activity
				$data['timestamp'] = now();
				$data['moderator'] = $user_id;
				$data['activity'] = "Deleted software review: '".$row->comment."' from user '".$row->username."'";
				$this->db->insert("moderators_activity", $data);
			}
		}
		$this->view($package);
	}

	function review($package = 0) {
		$this->security->restrict_to_registered_users();
		$query = $this->db->query("SELECT * FROM software_packages WHERE pkg_name = '$package'");
                if ($query->num_rows() > 0) {
			$comment = trim($this->input->post('comment_given'));
			if ($comment != "") {
				$data = $query->row_array();
        	                $package_id = $data["id"];
                	        $user_id = $this->dx_auth->get_user_id();
				
				// Delete any review previously made
				$this->db->where("user", $user_id);
				$this->db->where("package", $package_id);
				$this->db->delete("software_reviews");

				//Insert the new review
				$new_data = array(
			               'package' => $package_id,
		        	       'user' => $user_id,
				       'score' => $this->input->post('score_given'),
				       'comment' => $this->input->post('comment_given'),
				       'timestamp' => now()
			        );
				$this->db->insert('software_reviews', $new_data); 

				//Notify by email																
				$this->email->from('no-reply@linuxmint.com', 'Linux Mint Community');
				$this->email->to("root@linuxmint.com"); 
				$this->email->subject("Web review - \"$package\"");
				$this->email->message("Hello clem,

A user reviewed ".$package.":

Score: ".$this->input->post('score_given')."
Comment: \"".$this->input->post('comment_given')."\"
--
"); 
				$this->email->send();

			}
			$this->view($package);
		}
	}

	function remote_review() {	
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$score = intval($this->input->post('score'));
		$comment = trim($this->input->post('comment'));
		$package = $this->input->post('package');

		$password = $this->dx_auth->encode_password(base64_decode($password));		

		$query_user = $this->db->query("SELECT * FROM users WHERE username = '$username'");
		if ($query_user->num_rows() > 0) {
			$stored_password = $query_user->row()->password;
			if (crypt($password, $stored_password) === $stored_password) {
				$user_id = $query_user->row()->id;
				$query_package = $this->db->query("SELECT * FROM software_packages WHERE pkg_name = '$package'");
        	        	if ($query_package->num_rows() > 0) {			
					if ($comment != "" and $comment != $username 
					and $comment != "1" and $comment != "2" and $comment != "3" and $comment != "4" and $comment != "5"
					and $score >= 1 and $score <= 5) {
						$package_id = $query_package->row()->id;
						
						// Delete any review previously made
						$this->db->where("user", $user_id);
						$this->db->where("package", $package_id);
						$this->db->delete("software_reviews");

						//Insert the new review
						$new_data = array(
					               'package' => $package_id,
				        	       'user' => $user_id,
						       'score' => $score,
						       'comment' => $comment,
						       'timestamp' => now()
					        );
						$this->db->insert('software_reviews', $new_data); 

						//Notify by email																
						$this->email->from('no-reply@linuxmint.com', 'Linux Mint Community');
						$this->email->to("root@linuxmint.com"); 
						$this->email->subject("Remote review - \"$package\"");
						$this->email->message("Hello clem,

".$username." remotely reviewed ".$package.":

Score: ".$score."
Comment: \"".$comment."\"
--
"); 
						$this->email->send();
					
						$this->load->view('remote_review_success');

						return;   
					}
					else {
						$data["error"] = "Invalid review";
						$data["details"] = "Make sure to write a comment and to select a score between 1 and 5.";
						$this->load->view('remote_review_failure', $data);
					}
				}
				else {
					$data["error"] = "Invalid package name";
					$data["details"] = "The package you're trying to review was not found in our database. Please contact the development team about this.";
					$this->load->view('remote_review_failure', $data);
				}
			}
			else {
				$data["error"] = "Invalid password";
				$data["details"] = "Your password is incorrect. To change it, click on the 'Edit' menu and select 'Account information'.";
				$this->load->view('remote_review_failure', $data);
			}
		}
		else {
			$data["error"] = "Invalid username";
			$data["details"] = "Your username is incorrect. To change it, click on the 'Edit' menu and select 'Account information'.";
			$this->load->view('remote_review_failure', $data);
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

	function browse($category=0, $offset=0)
	{
		$category = intval($category);

		$req_category = $this->db->query("SELECT * from software_categories WHERE id = $category");
		if ($req_category->num_rows() > 0) {
			$category_name = $req_category->row()->name;
			$data["show_stats"] = false;
		}
		else {
			$category_name = "Software packages";
			$data["show_stats"] = true;
			
			//Latest reviews
			$this->db->select("software_reviews.*, users.username, software_packages.pkg_name");
			$this->db->from("software_reviews");
			$this->db->join("users"," users.id = software_reviews.user");
			$this->db->join("software_packages", "software_packages.id = software_reviews.package");
			$this->db->limit(5);
			$this->db->order_by("timestamp desc");
			$data["latest_reviews"] = $this->db->get();

			//Top packages
			$this->db->select("software_packages.pkg_name, sum(score -3) as overall_score");    
			$this->db->from("software_packages");
			$this->db->join("software_reviews", "software_packages.id = software_reviews.package"); 
			$this->db->group_by("software_reviews.package");
			$this->db->order_by("overall_score desc, pkg_name asc");
            $this->db->where("commercial", 0);
			$this->db->limit(5);
			$data["top_packages"] = $this->db->get();

			//Stats
			$data["num_packages"] = $this->db->count_all("software_packages");
			$data["num_reviews"] = $this->db->count_all("software_reviews");
			$num_users = $this->db->count_all("users");
			$req_distinct_users = $this->db->query("SELECT count(DISTINCT user) as number FROM software_reviews");
			$data["percentage_users_reviewing"] = intval($req_distinct_users->row()->number / $num_users * 100)."%";
			$data["percentage_packages_reviewed"] = intval($data["num_reviews"] / $data["num_packages"] * 100)."%";
			$this->load->helper('directory');
			$screenshots = directory_map(FCPATH.'img/screenshots/');
			$data["num_screenshots"] = count($screenshots);
			$data["percentage_packages_with_screenshots"] = intval($data["num_screenshots"] / $data["num_packages"] * 100)."%";			
		}
				
		$data["view_all"] = false;
		$data["view_title"] = $category_name;

		$this->db->select("software_categories.*, (SELECT count(*) FROM software_assoc_packages_categories WHERE category = software_categories.id) as num_packages");
		$this->db->from("software_categories");
		$this->db->where("parent", $category);
		$data["categories"] = $this->db->get();

		$this->db->start_cache();
		$this->db->select("software_packages.*, (SELECT sum(software_reviews.score -3) FROM software_reviews WHERE package = software_packages.id) as score");
		$this->db->from("software_packages");
		$this->db->join("software_assoc_packages_categories", "software_assoc_packages_categories.package = software_packages.id");
		$this->db->where("category", $category);
        $this->db->where("commercial", 0);
		$this->db->stop_cache();
		$num_packages = $this->db->count_all_results();
		$this->db->order_by("score desc, pkg_name asc");
		$this->db->limit(20);
		$this->db->offset($offset);
		$data["packages"] = $this->db->get();
		$this->db->flush_cache();

		$this->load->library('pagination');
		$config['base_url'] = base_url()."index.php/software/browse/$category/";
		$config['uri_segment'] = 4;
		$config['num_links'] = 2;
		$config['total_rows'] = $num_packages;
		$config['per_page'] = '20';
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$this->pagination->initialize($config);

		$data["page_title"] = "Software Portal";
		$this->load->view("header", $data);
		$this->load->view("menu");
		$this->load->view('left');
		$this->load->view("list_software", $data);
		$this->load->view("footer");
	}

	function search($offset = 0, $search_get = "") {
        
        if ($search_get != "") {
            $search_get = str_replace("%20", " ", $search_get);
            $this->session->set_userdata('search_software_name', $search_get);
        }
		$search_software_name = $this->retreive_data('search_software_name', "");
		$search_software_body = $this->retreive_data('search_software_body', "");
		$search_software_category = intval($this->retreive_data('search_software_category', 0));
		$search_software_release = intval($this->retreive_data('search_software_release', 0));

		$this->db->start_cache();
		$this->db->select("software_packages.*, (SELECT sum(software_reviews.score -3) FROM software_reviews WHERE package = software_packages.id) as score");
		$this->db->from("software_packages");
		$this->db->like("software_packages.pkg_name", $search_software_name);		
		$this->db->like("CONCAT(software_packages.summary, software_packages.description)", $search_software_body);
		//if ($search_software_category != -1) {
		//	$this->db->where("category", $search_software_category);
		//}
		//if ($search_software_release != -1) {
		//	$this->db->where("software_packages.id IN (SELECT package FROM software_assoc_packages_releases WHERE distro_release = $search_software_release)");
		//}
		$this->db->stop_cache();
                $num_packages = $this->db->count_all_results();
		$this->db->order_by("score desc, pkg_name asc");
                $this->db->limit(20);
       	        $this->db->offset($offset);
               	$data["packages"] = $this->db->get();
                $this->db->flush_cache();

		$data['categories'] = $this->db->get("software_categories");
		$this->db->order_by("name");
		$data['releases'] = $this->db->get("distro_releases");

		$data["search_software_name"] = $search_software_name;
		$data["search_software_body"] = $search_software_body;
		$data["search_software_category"] = $search_software_category;
		$data["search_software_release"] = $search_software_release;
		$data['view_title'] = "Packages";
		$data['view_all'] = true;

		$this->load->library('pagination');
		$config['base_url'] = base_url().'index.php/software/search/';
		$config['total_rows'] = $num_packages;
		$config['per_page'] = '20';
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$this->pagination->initialize($config);

		$data["page_title"] = "Software Portal";
		$this->load->view("header", $data);
		$this->load->view("menu");
		$this->load->view('left');
		$this->load->view("list_software", $data);
		$this->load->view("footer");
	}

	function suggest_screenshot($pkg_name) {
		$this->security->restrict_to_registered_users();
		// Make sure pkg_name exists and that it doesn't have a screenshot already
		$query = $this->db->query("SELECT * from software_packages WHERE pkg_name = '$pkg_name'");
		if ($query->num_rows() > 0) {
			if (!file_exists(FCPATH.'img/screenshots/'.$pkg_name.".png")) {
				$data["pkg_name"] = $pkg_name;
				$this->load->view("header");
		                $this->load->view("menu");
        		        $this->load->view('left');
                		$this->load->view("screenshot_suggest", $data);
		                $this->load->view("footer");
			}
		}
	}

	function upload_screenshot($pkg_name) {
		$this->security->restrict_to_registered_users();
		// Make sure pkg_name exists and that it doesn't have a screenshot already
                $query = $this->db->query("SELECT * from software_packages WHERE pkg_name = '$pkg_name'");
                if ($query->num_rows() > 0) {
                        if (!file_exists(FCPATH.'img/screenshots/'.$pkg_name.".png")) {
				$config['upload_path'] = FCPATH.'uploads/screenshots/';
		                $config['file_name'] = $pkg_name;
		                $config['overwrite'] = TRUE;
		                $config['allowed_types'] = 'png';
		                $config['is_image'] = TRUE;
		                $config['max_size']     = '500';

		                $this->load->library('upload', $config);

				$data["pkg_name"] = $pkg_name;

                		if (!$this->upload->do_upload("screenshot"))
		                {
                		        $upload_data = $this->upload->data();
               		                $data["error"] = $this->upload->display_errors();
                               		$this->load->view('header');
	                                $this->load->view('menu');
               		                $this->load->view('left', $data);
	                                $this->load->view('screenshot_upload_failure', $data);
               		                $this->load->view('footer');
                               		return;				
				}
				else {					
					$this->load->view('header');
                                        $this->load->view('menu');
                                        $this->load->view('left', $data);
                                        $this->load->view('screenshot_upload_success', $data);
                                        $this->load->view('footer');
					//Notify by email																
					$this->email->from('no-reply@linuxmint.com', 'Linux Mint Community');
					$this->email->to("root@linuxmint.com"); 
					$this->email->subject("New screenshot uploaded - \"$pkg_name\"");
					$this->email->message("Hello clem,

A new screenshot was uploaded for the package ".$pkg_name.":

To review it, click on : http://community.linuxmint.com/index.php/software/review_screenshots
--
"); 
					$this->email->send();
					return;
				}
                        }
                }
	}

	function review_screenshots() {
		$this->security->restrict_to_registered_users();
		if ($this->dx_auth->is_administrator()) {
			$this->load->helper('directory');
			$data["screenshots"] = directory_map(FCPATH.'uploads/screenshots/');
			$this->load->view('header');
			$this->load->view('menu');
			$this->load->view('left');
			$this->load->view('review_screenshots', $data);
			$this->load->view('footer');
		}
	}

	function reject_screenshot($screenshot) {
		$this->security->restrict_to_registered_users();
		 if ($this->dx_auth->is_administrator()) {
			$file = FCPATH.'uploads/screenshots/'.$screenshot;
			unlink($file);
				$this->review_screenshots();
			}
	}

	function approve_screenshot($screenshot) {
		$this->security->restrict_to_registered_users();
		 if ($this->dx_auth->is_administrator()) {
			$file = FCPATH.'uploads/screenshots/'.$screenshot;
			$destination = FCPATH.'img/screenshots/'.$screenshot;
			rename($file, $destination);
				$this->review_screenshots();
			}
	}
}
?>
