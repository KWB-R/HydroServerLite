<?php
class Sources extends MY_Model
{
	function __construct()
	{
		parent::__construct();
		$this->tableName = "sources";	
	}
	
	//Generate the MetadataID
	function genMD($data)
	{
		$this->db->insert('isometadata',$data);
		return $this->db->insert_id();	
	}
	function addsource($organization,$description,$link,$name,$phone,$email,$address,$city,$state,$zipcode,$citation,$metadata)
	{
	  $this->db->set('Organization', $organization);
	  $this->db->set('SourceDescription', $description);
	  $this->db->set('SourceLink', $link);
	  $this->db->set('ContactName', $name);
	  $this->db->set('Phone', $phone);
	  $this->db->set('Email', $email);
	  $this->db->set('Address', $address);
	  $this->db->set('City', $city);
	  $this->db->set('State', $state);
	  $this->db->set('ZipCode', $zipcode);
	  $this->db->set('Citation', $citation);
	  $this->db->set('MetadataID', $metadata);
	  $this->db->insert($this->tableName);
	  
	  $num_inserts = $this->db->affected_rows();
	  return $num_inserts;
	}
	function delete($source)
	{
		$this->db
			->where('SourceID',$source)
			->delete($this->tableName);
		$num_del = $this->db->affected_rows();
		return $num_del==1;	
	}
	function getTC()
	{
		
		$query=$this->db->get('topiccategorycv');
		return $this->tranResult($query->result_array());		
	}
}
?>