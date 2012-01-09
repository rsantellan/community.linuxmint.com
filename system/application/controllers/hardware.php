<?php
class Hardware extends Controller{

	function Hardware()
	{
		parent::Controller();
		$this->load->helper('url');
		$this->load->helper('form');		
		$this->load->helper('date');
	}

	function index()
	{
		$this->welcome();
	}

	function view($device_id = 0)
	{
		$device_id = intval($device_id);
		
		//Update the number of views
		//$this->db->query("UPDATE hardware_devices SET views=views+1 WHERE id = $device_id");
		
		$query = $this->db->query("SELECT hardware_devices.created, hardware_devices.views, hardware_devices.last_edited, hardware_statuses.name AS device_status, hardware_devices.name AS device_name, 
						hardware_brands.name AS device_brand, hardware_categories.name AS device_category,
						distro_releases.name AS device_release, users.username AS owner_name, users.id AS owner_id,
						what_works, what_doesnt_work, what_i_did_to_make_it_work, additional_notes
						FROM hardware_devices, hardware_statuses, hardware_brands, hardware_categories, distro_releases, users
						WHERE hardware_statuses.id = hardware_devices.status 
						AND hardware_brands.id = hardware_devices.brand 
						AND hardware_categories.id = hardware_devices.category 
						AND users.id = hardware_devices.owner
						AND distro_releases.id = hardware_devices.distro_release
						AND hardware_devices.id = $device_id");
		if ($query->num_rows() > 0) {
			$row = $query->row_array();

			if ($this->dx_auth->is_logged_in()) {
				$user_id = $this->dx_auth->get_user_id();
			}
			else {
				$user_id = 0;
			}

			$data["device_id"] = $device_id;
			$data["created"] = $row["created"];
			$data["last_edited"] = $row["last_edited"];
			$data["views"] = $row["views"];
			$data["device_status"] = $row["device_status"];
			$data["device_name"] = $row["device_name"];	
			$data["page_title"] = $row["device_brand"]." ".$row["device_name"]."(".$row["device_category"].")";	
			$data["device_brand"] = $row["device_brand"];	
			$data["device_category"] = $row["device_category"];		
			$data["device_release"] = $row["device_release"];	
			$data["what_works"] = nl2br($row["what_works"]);
			$data["what_doesnt_work"] = nl2br($row["what_doesnt_work"]);
			$data["what_i_did_to_make_it_work"] = nl2br($row["what_i_did_to_make_it_work"]);
			$data["additional_notes"] = nl2br($row["additional_notes"]);
			$data["owner_id"] = $row["owner_id"];
			$data["owner_name"] = $row["owner_name"];			
			if ($data["owner_id"] == $user_id) {
				$data["mine"] = TRUE;
			}
			else {
				$data["mine"] = FALSE;
			}

			if (file_exists(FCPATH.'uploads/avatars/'.$data["owner_id"].".jpg")) {
        	                $data["avatar"] = '/uploads/avatars/'.$data["owner_id"].".jpg";
	                }
                	else {
                        	$data["avatar"] = '/img/default_avatar.jpg';
	                }

			$this->db->where("owner", $data["owner_id"]);
			$this->db->where("id <> $device_id");
			$data["other_devices"] = $this->db->get("hardware_devices");			
			
			$this->load->view("header", $data);
			$this->load->view("menu");
			$this->load->view('left');	
			$this->load->view('hardware', $data);
			$this->load->view("footer");
		}		
	}

	function from($owner_id = 0) {
		$owner_id = intval($owner_id);		
		if ($this->dx_auth->is_logged_in()) {
			$user_id = $this->dx_auth->get_user_id();
		}
		else {
			$user_id = 0;
		}		

		$this->db->select("hardware_devices.id as id, hardware_devices.name as name, hardware_categories.name as category, hardware_brands.name as brand, distro_releases.name as distro_release, hardware_statuses.name as status, hardware_statuses.id as status_id");		
		$this->db->join("hardware_brands", "hardware_devices.brand = hardware_brands.id");
		$this->db->join("hardware_categories", "hardware_devices.category = hardware_categories.id");
		$this->db->join("hardware_statuses", "hardware_devices.status = hardware_statuses.id");
		$this->db->join("distro_releases", "hardware_devices.distro_release = distro_releases.id");
		$this->db->where("hardware_devices.owner", $owner_id);		
		$this->db->order_by("category, name");
		$data['hardware_devices'] = $this->db->get("hardware_devices");

		$query = $this->db->query("SELECT username, email, last_login, country, edition, distro_release FROM users WHERE id = $owner_id");
		if ($query->num_rows() > 0) {
	                $row = $query->row_array();
	                $data["owner_name"] = $row["username"];
			$data['view_title'] = $row["username"]."'s hardware";
			$data['view_mine'] = False;
			$data['view_all'] = False;
			if ($owner_id == $user_id) {
				$data['view_title'] = "My Hardware";
				$data['view_mine'] = True;				
			}
			$data['page_title'] = $data['view_title'];
			$this->load->view("header", $data);
			$this->load->view("menu");			
			$this->load->view('left');
			$this->load->view("list_hardware", $data);			
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
        
        $this->db->select("hardware_devices.id as id, hardware_devices.name as name, hardware_categories.name as category, hardware_brands.name as brand, distro_releases.name as distro_release, hardware_statuses.name as status, hardware_statuses.id as status_id, hardware_devices.created");		
		$this->db->join("hardware_brands", "hardware_devices.brand = hardware_brands.id");
		$this->db->join("hardware_categories", "hardware_devices.category = hardware_categories.id");
		$this->db->join("hardware_statuses", "hardware_devices.status = hardware_statuses.id");
		$this->db->join("distro_releases", "hardware_devices.distro_release = distro_releases.id");			
		$this->db->order_by("created desc");
        $this->db->limit(5);
		$data['latest_hardware'] = $this->db->get("hardware_devices");
             
        
        $data["statuses_chart"] = $this->db->query('select hardware_statuses.name as status, count(*) as number from hardware_devices, hardware_statuses WHERE hardware_statuses.id = hardware_devices.status GROUP BY hardware_devices.status ORDER BY number DESC');                
        $data["brands_chart"] = $this->db->query('select hardware_brands.name as name, count(*) as number from hardware_devices, hardware_brands WHERE hardware_brands.id = hardware_devices.brand GROUP BY hardware_devices.brand ORDER BY number DESC');        
        $data["categories_chart"] = $this->db->query('select hardware_categories.name as name, count(*) as number from hardware_devices, hardware_categories WHERE hardware_categories.id = hardware_devices.category GROUP BY hardware_devices.category ORDER BY number DESC');                
        $data["releases_chart"] = $this->db->query('select distro_releases.name as name, count(*) as number from hardware_devices, distro_releases WHERE distro_releases.id = hardware_devices.distro_release GROUP BY hardware_devices.distro_release ORDER BY number DESC');        
        
        $data['statuses'] = $this->db->get("hardware_statuses");
        $this->db->order_by("name");
        $data['brands'] = $this->db->get("hardware_brands");
        $this->db->order_by("name");
        $data['categories'] = $this->db->get("hardware_categories");
        $this->db->order_by("name");
        $data['releases'] = $this->db->get("distro_releases");
        
        $this->load->view("header", $data);
        $this->load->view("menu");			
        $this->load->view('left');
        $this->load->view("welcome_hardware", $data);
        $this->load->view("footer");
    }

	function search($offset = 0, $search_get = "") {
        
        if ($search_get != "") {
            $search_get = str_replace("%20", " ", $search_get);
            $this->session->set_userdata('search_hardware_name', $search_get);
        }
        
		$search_hardware_name = $this->retreive_data('search_hardware_name', "");
		$search_hardware_category = intval($this->retreive_data('search_hardware_category', 0));
		$search_hardware_release = intval($this->retreive_data('search_hardware_release', 0));
		$search_hardware_brand = intval($this->retreive_data('search_hardware_brand', 0));
		$search_hardware_status = intval($this->retreive_data('search_hardware_status', 0));

		$this->db->start_cache();
		$this->db->select("hardware_devices.id as id, hardware_devices.name as name, hardware_categories.name as category, hardware_brands.name as brand, distro_releases.name as distro_release, hardware_statuses.name as status, hardware_statuses.id as status_id");		
		$this->db->from("hardware_devices");
		$this->db->join("hardware_brands", "hardware_devices.brand = hardware_brands.id");
		$this->db->join("hardware_categories", "hardware_devices.category = hardware_categories.id");
		$this->db->join("hardware_statuses", "hardware_devices.status = hardware_statuses.id");
		$this->db->join("distro_releases", "hardware_devices.distro_release = distro_releases.id");					
		
        if ($search_hardware_name != "") {
            $this->db->like("hardware_devices.name", $search_hardware_name);		
        }
		if ($search_hardware_category != -1) {
			$this->db->where("hardware_devices.category", $search_hardware_category);
		}
		if ($search_hardware_release != -1) {		
			$this->db->where("hardware_devices.distro_release", $search_hardware_release);
		}
		if ($search_hardware_brand != -1) {
			$this->db->where("hardware_devices.brand", $search_hardware_brand);
		}
		if ($search_hardware_status != -1) {
			$this->db->where("hardware_devices.status", $search_hardware_status);
		}
		$this->db->order_by("hardware_categories.name, hardware_brands.name, hardware_devices.name");
		$this->db->stop_cache();
		$num_devices = $this->db->count_all_results();		
		$this->db->limit(20);
		$this->db->offset($offset);
		$data['hardware_devices'] = $this->db->get();
		$this->db->flush_cache();
		$data['statuses'] = $this->db->get("hardware_statuses");
		$this->db->order_by("name");
		$data['brands'] = $this->db->get("hardware_brands");
		$this->db->order_by("name");
		$data['categories'] = $this->db->get("hardware_categories");
		$this->db->order_by("name");
		$data['releases'] = $this->db->get("distro_releases");
		$data["search_hardware_name"] = $search_hardware_name;
		$data["search_hardware_category"] = $search_hardware_category;
		$data["search_hardware_brand"] = $search_hardware_brand;
		$data["search_hardware_release"] = $search_hardware_release;
		$data["search_hardware_status"] = $search_hardware_status;
		$data['view_title'] = "Hardware devices";
		$data['view_mine'] = False;
		$data['view_all'] = True;

		$this->load->library('pagination');
		$config['base_url'] = base_url().'index.php/hardware/search/';
		$config['total_rows'] = $num_devices;
		$config['per_page'] = '20';
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$this->pagination->initialize($config);

		$data["page_title"] = "Hardware Database";
		$this->load->view("header", $data);
		$this->load->view("menu");
		$this->load->view('left');
		$this->load->view("list_hardware", $data);
		$this->load->view("footer");
	}

	function add()
	{
		$this->security->restrict_to_registered_users();
		$user_id = $this->dx_auth->get_user_id();		
		$data['statuses'] = $this->db->get("hardware_statuses");
		$this->db->order_by("name");
		$data['brands'] = $this->db->get("hardware_brands");
		$this->db->order_by("name");
		$data['categories'] = $this->db->get("hardware_categories");
		$this->db->order_by("name");
		$data['releases'] = $this->db->get("distro_releases");
		$this->load->view("header");
		$this->load->view("menu");
		$this->load->view('left');
		$this->load->view("add_hardware", $data);
		$this->load->view("footer");
	}

	function edit($hardware_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$hardware_id = intval($hardware_id);		

		$this->db->where("id", $hardware_id);
		$query = $this->db->get("hardware_devices");
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$data = $row;
			$data['statuses'] = $this->db->get("hardware_statuses");
			$this->db->order_by("name");
			$data['brands'] = $this->db->get("hardware_brands");
			$this->db->order_by("name");
			$data['categories'] = $this->db->get("hardware_categories");
			$this->db->order_by("name");
			$data['releases'] = $this->db->get("distro_releases");
			$this->load->view("header");
			$this->load->view("menu");
			$this->load->view('left');
			$this->load->view("edit_hardware", $data);
			$this->load->view("footer");
		}		
	}

	function review($hardware_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$hardware_id = intval($hardware_id);		
		$this->db->where("id", $hardware_id);
		$query = $this->db->get("hardware_devices");
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$data = $row;
            $this->db->order_by("name");
			$data['brands'] = $this->db->get("hardware_brands");
			$this->db->order_by("name");
			$data['categories'] = $this->db->get("hardware_categories");
			$this->load->view("header");
			$this->load->view("menu");
			$this->load->view('left');
			$this->load->view("review_hardware", $data);
			$this->load->view("footer");
		}		
	}

	function save_review()
	{
		$this->security->restrict_to_registered_users();
        if ($this->dx_auth->is_moderator() or $this->dx_auth->is_administrator()) {
            $data = array();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('category', '', '');
            $this->form_validation->set_rules('brand', '', '');
            $this->form_validation->set_rules('name', 'Model name', 'required');

            $data["brand"] = intval($_POST["brand"]);
            $data["category"] = intval($_POST["category"]);            
            $data["name"] = trim($_POST["name"]);
            $data["last_edited"] = now();

            $hardware_id = intval($_POST["id"]);
            if ($hardware_id > 0) {
                if ($this->form_validation->run() == FALSE) {
                    $this->review($hardware_id);
                }
                else {		                   
                    $this->db->where("id", $hardware_id);
                    $this->db->update("hardware_devices", $data);        
                    $this->delete_empty_records(); 
                }
            }
        }
        redirect("hardware/view/$hardware_id", "location");
	}

	function delete($hardware_id = 0)
	{
		$this->security->restrict_to_registered_users();
		$hardware_id = intval($hardware_id);
		$user_id = $this->dx_auth->get_user_id();
		//Check that is OUR hardware
		$this->db->where("id", $hardware_id);
		$query = $this->db->get("hardware_devices");
		if ($query->num_rows() == 1) {
			$row = $query->row();
			if  ($row->owner == $user_id) {
				$this->db->where("owner", $user_id);
				$this->db->where("id", $hardware_id);
				$this->db->delete("hardware_devices");
				redirect("hardware/from/$user_id", "location");
			}
		    	elseif ($this->dx_auth->is_administrator() or $this->dx_auth->is_moderator()) {                
				$this->db->where("id", $hardware_id);
				$this->db->delete("hardware_devices");
				$this->delete_empty_records();
				redirect("hardware/welcome", "location");
		    	}
		}
		else {		
		    redirect("hardware/welcome", "location");
		}
	}
    
    	function delete_empty_records() {
		$this->db->where("id NOT IN (SELECT DISTINCT brand FROM hardware_devices) AND name <> 'Other'");
		$this->db->delete("hardware_brands");
		
		$this->db->where("id NOT IN (SELECT DISTINCT category FROM hardware_devices) AND name <> 'Other'");
		$this->db->delete("hardware_categories");
    	}

	function save()
	{
		$this->security->restrict_to_registered_users();
		$data = array();


		$this->load->library('form_validation');
		$this->form_validation->set_rules('category', '', '');
		$this->form_validation->set_rules('otherCategory', '', '');
		$this->form_validation->set_rules('brand', '', '');
		$this->form_validation->set_rules('otherBrand', '', '');
		$this->form_validation->set_rules('name', 'Model name', 'required');
		$this->form_validation->set_rules('status', '', '');
		$this->form_validation->set_rules('release', '', '');
		$this->form_validation->set_rules('what_works', '', '');
		$this->form_validation->set_rules('what_doesnt_work', '', '');
		$this->form_validation->set_rules('what_i_did_to_make_it_work', '', '');
		$this->form_validation->set_rules('additional_notes', '', '');

		$brand_txt = trim($_POST["brand_txt"]);
		if ($brand_txt != "") {
			$this->db->where("name", $brand_txt);
			$query = $this->db->get("hardware_brands");
			if ($query->num_rows() > 0) {
				// Brand already exists so we just get the ID back
				$row = $query->row();
				$data["brand"] = $row->id;
			}
			else {
				// User specified a new brand, let's insert it
				$this->db->set('name', $brand_txt); 
				$this->db->insert("hardware_brands");
				$data["brand"] = $this->db->insert_id();
			}
		}
		else {
			$data["brand"] = intval($_POST["brand"]);
		}

		$category_txt = trim($_POST["category_txt"]);
		if ($category_txt != "") {
			$this->db->where("name", $category_txt);
			$query = $this->db->get("hardware_categories");
			if ($query->num_rows() > 0) {
				// Category already exists so we just get the ID back
				$row = $query->row();
				$data["category"] = $row->id;
			}
			else {
				// User specified a new category, let's insert it
				$this->db->set('name', $category_txt); 
                		$this->db->insert("hardware_categories");
				$data["category"] = $this->db->insert_id();
			}
		}
		else {
			$data["category"] = intval($_POST["category"]);
		}

		$user_id = $this->dx_auth->get_user_id();
		$data["owner"] = $user_id;
		$data["name"] = trim($_POST["name"]);
		$data["status"] = intval($_POST["status"]);
		$data["distro_release"] = intval($_POST["release"]);
		$data["what_works"] = trim($_POST["what_works"]);
		$data["what_doesnt_work"] = trim($_POST["what_doesnt_work"]);
		$data["what_i_did_to_make_it_work"] = trim($_POST["what_i_did_to_make_it_work"]);
		$data["additional_notes"] = trim($_POST["additional_notes"]);					

		if ($_POST["action"] == "add") {
			if ($this->form_validation->run() == FALSE) {
				$this->add();
			}
			else {
				$data["created"] = now();
				$data["last_edited"] = now();
				$this->db->insert("hardware_devices", $data);
				$new_id = $this->db->insert_id();
				redirect("hardware/view/$new_id", "location");
			}			
			
		}
		elseif ($_POST["action"] == "edit") {
			$hardware_id = intval($_POST["id"]);
			if ($hardware_id > 0) {
				if ($this->form_validation->run() == FALSE) {
					$this->edit($hardware_id);
				}
				else {		
					$data["last_edited"] = now();		
					$this->db->where("owner", $user_id);
					$this->db->where("id", $hardware_id);
					$this->db->update("hardware_devices", $data);
					redirect("hardware/view/$hardware_id", "location");
				}				
			}
                }
	}			
}
?>
