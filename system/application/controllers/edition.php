<?php
class Edition extends Controller{

	function Edition()
	{
		parent::Controller();
		$this->load->helper('url');
		$this->load->helper('form');		
	}
	
	function index()
	{
		$this->view(0);
	}

	function view($edition_id = 0)
	{
		$edition_id = intval($edition_id);		
		$query = $this->db->query("SELECT name FROM editions WHERE id = $edition_id");
		$row = $query->row_array();
		if ($query->num_rows() > 0) {
			$data["edition_name"] = $row["name"];
			$this->db->where("edition", $edition_id);
			$this->db->order_by("score DESC, lower(username) ASC");
			$data["users"] = $this->db->get("users");
			$this->load->view('header');
			$this->load->view('menu');
			$this->load->view('left');
			$this->load->view('edition', $data);
			$this->load->view('footer');
		}		
	}

}
?>
