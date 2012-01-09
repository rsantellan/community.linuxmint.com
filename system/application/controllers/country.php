<?php
class Country extends Controller{

	function Country()
	{
		parent::Controller();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('DX_Auth');
		$this->load->library('Myself');					
	}
	
	function index()
	{
		$this->view(0);
	}

	function view($country_id = 0)
	{
		$country_id = intval($country_id);		
		$query = $this->db->query("SELECT name, code FROM countries WHERE id = $country_id");
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$data["country_name"] = $row["name"];
			$data["country_code"] = $row["code"];			
			$this->db->where("country", $country_id);
			$this->db->order_by("score DESC, lower(username) ASC");			
			$data["users"] = $this->db->get('users');
			$data["page_title"] = $data["country_name"];
			$this->load->view("header", $data);
			$this->load->view("menu");
			$this->load->view('left');
			$this->load->view('country', $data);
			$this->load->view("footer");
		}
	}

	function all() 
	{			
		$this->db->select("countries.id as id, countries.name as name, countries.code as code, count(*) as users");
		$this->db->from("countries");
		$this->db->join("users", "users.country = countries.id");
		$this->db->group_by("users.country");
		$this->db->order_by("count(*) desc, name asc");
		$data["countries"] = $this->db->get();
		$data["page_title"] = "Countries";
		$this->load->view("header", $data);
		$this->load->view("menu");
		$this->load->view('left');
		$this->load->view("list_countries", $data);
		$this->load->view("footer");
	}

}
?>
