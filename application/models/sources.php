<?php
class Sources extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->tableName = "sources";	
	}
	
	function addsource($id,$organization,$description,$link,$name,$phone,$email,$address,$city,$state,$zipcode,$citation,$metadata)
	{
	  $this->db->set('SourceID', $id);
	  $this->db->set('Organization', $organization);
	  $this->db->set('SourceDescription', $description);
	  $this->db->set('SourceLink', $link);
	  $this->db->set('ContactName', $name);
	  $this->db->set('Phone', $phone);
	  $this->db->set('Email', $email);
	  $this->db->set('Address', $address);
	  $this->db->set('City', $city);
	  $this->db->set('State', $state);
	  $this->db->set('Citation', $citation);
	  $this->db->set('MetadataID', $metadata);
	  $this->db->insert($this->tableName);
	  
	  $num_inserts = $this->db->affected_rows();
	  return $num_inserts;
	}
}
?>