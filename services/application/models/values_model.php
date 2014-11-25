<?php

// model for working with the series catalog
class Values_Model extends CI_Model
{
	function validate_password($user, $password) {
		$ci = &get_instance();
		$sql = "SELECT authority FROM moss_users WHERE username='$user' AND password =password('$password')";
		$query = $ci->db->query($sql);
	    if (!$query) {
	        return 0;
	    } else if ($query->num_rows() === 0) {
			return 0;
		} else {
			return 1;
		}
	}
	
	function validate_sitecode($sitecode) {
		$ci = &get_instance();
		$ci->db->select("SiteID");
		$ci->db->where("SiteCode", $sitecode);
		$query = $ci->db->get("sites");
		if (!$query) {
			return 0;
		} else if ($query->num_rows() === 0) {
			return 0;
		} else {
			return $query->row()->SiteID;
		}
	}
	
	function validate_variablecode($variablecode) {
		$ci = &get_instance();
		$ci->db->select("VariableID");
		$ci->db->where("VariableCode", $variablecode);
		$query = $ci->db->get("variables");
		if (!$query) {
			return 0;
		} else if ($query->num_rows() === 0) {
			return 0;
		} else {
			return $query->row()->VariableID;
		}
	}
	
	function validate_methodid($methodid) {
		$ci = &get_instance();
		$ci->db->select("MethodID");
		$ci->db->where("MethodID", $methodid);
		$query = $ci->db->get("methods");
		if (!$query) {
			return 0;
		} else if ($query->num_rows() === 0) {
			return 0;
		} else {
			return $methodid;
		}
	}
	
	function validate_sourceid($sourceid) {
		$ci = &get_instance();
		$ci->db->select("SourceID");
		$ci->db->where("SourceID", $sourceid);
		$query = $ci->db->get("sources");
		if (!$query) {
			return 0;
		} else if ($query->num_rows() === 0) {
			return 0;
		} else {
			return $sourceid;
		}
	}
	
	
	// inserts the data values to the database
	// returns -1 in case of error, 0 if there's nothing to insert, 
	// or else returns the no of inserted rows
	function insert_values($data) {
		$ci = &get_instance();
		if (count($data) === 0) {
			return 0; 
		} else {
			if($ci->db->insert_batch('datavalues', $data)) {
				return $ci->db->affected_rows();
			} else {
				return -1;
			}
		}
	}
}