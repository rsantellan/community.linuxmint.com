<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myself {
    
    function Myself()
    {
	$this->ci =& get_instance();
	$this->ci->load->database();
    }

    function get_details($user_id) {

		$result["user_id"] = $user_id;

		//Get the Avatar
		$result["avatar"] = '/img/default_avatar.jpg';
		if (file_exists(FCPATH.'uploads/avatars/'.$user_id.".jpg")) {
			$result["avatar"] = '/uploads/avatars/'.$user_id.".jpg";
		}
		
		$query = $this->ci->db->query("SELECT username, email, last_login, country, edition, distro_release, ismoderator, score, signature, biography FROM users WHERE id = $user_id");
		$row = $query->row_array();

		$result["ismoderator"] = $row["ismoderator"];
		$result["score"] = $row["score"];
		$result["user_name"] = $row["username"];
		$result["email"] = $row["email"];
		$result["last_login"] = $row["last_login"];
        
        $result["signature"] = $row["signature"];
        $result["biography"] = $row["biography"];

		$country_id = $row["country"];
		$result["country_id"] = $row["country"];
		$result["country_name"] = "None";
		$result["country_code"] = "None";
		if ($result["country_id"] > 0) {
			$query2 = $this->ci->db->query("SELECT name, code FROM countries WHERE id = $country_id");
			$row2 = $query2->row_array();
			$result["country_name"] = $row2["name"];
			$result["country_code"] = $row2["code"];
		}

		$release_id = $row["distro_release"];
		$result["release_id"] = $row["distro_release"];
		$result["release_name"] = "None";
		if ($result["release_id"] > 0) {
			$query2 = $this->ci->db->query("SELECT name FROM distro_releases WHERE id = $release_id");
			$row2 = $query2->row_array();
			$result["release_name"] = $row2["name"];
		}

		$edition_id = $row["edition"];
		$result["edition_id"] = $row["edition"];
		$result["edition_name"] = "None";
		if ($result["edition_id"] > 0) {
			$query2 = $this->ci->db->query("SELECT name FROM editions WHERE id = $edition_id");
			$row2 = $query2->row_array();
			$result["edition_name"] = $row2["name"];
		}	

		return $result;
    }
}

?>
