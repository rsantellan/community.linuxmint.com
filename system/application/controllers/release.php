<?php
class Release extends Controller{

	function Release()
	{
		parent::Controller();
		$this->load->helper('url');
		$this->load->helper('form');		
	}
	
	function index()
	{
		$this->view(0);
	}

	function view($release_id = 0)
	{		
		$release_id = intval($release_id);
		$query = $this->db->query("SELECT name FROM distro_releases WHERE id = $release_id");
		$row = $query->row_array();
		if ($query->num_rows() > 0) {
			$data["release_name"] = $row["name"];			
			$this->db->where("distro_release", $release_id);
			$this->db->order_by("score DESC, lower(username) ASC");			
			$data["users"] = $this->db->get('users');
			$this->load->view('header');
			$this->load->view('menu');
			$this->load->view('left');
			$this->load->view('release', $data);
			$this->load->view('footer');
		}		
	}

}
?>
