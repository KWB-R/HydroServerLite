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
	
	function updateMD($data,$id)
	{
		$this->db->where('MetadataID',$id)
		->update('isometadata',$data);
		return $this->db->affected_rows()>=0;	
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
	
	function updateSource($organization,$description,$link,$name,$phone,$email,$address,$city,$state,$zipcode,$citation,$metadata,$ID)
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
	  $this->db->where('SourceID', $ID);
	  $this->db->update($this->tableName);
	  
	  return $this->db->affected_rows()>=0;
	}
	
	function delete($source)
	{
		$this->db->select('MetadataID')
		->from($this->tableName)
		->where('SourceID',$source);
		$query=$this->db->get();
		$metadata = $query->result_array();
		if(count($metadata)<1)
		{
			return false;	
		}
		$metid = $metadata[0]['MetadataID'];
		
		$this->db
			->where('SourceID',$source)
			->delete($this->tableName);
		$num_del = $this->db->affected_rows();
		
		//Delete metadata
		
		$this->db
			->where('MetadataID',$metid)
			->delete('isometadata');
		
		
		return $num_del==1 && $this->db->affected_rows()==1;	
	}
	function getTC()
	{
		
		$query=$this->db->get('topiccategorycv');
		return $this->tranResult($query->result_array());		
	}
	
	function get($sourceid)
	{
		$this->db->select()
		->from($this->tableName)
		->join('isometadata',$this->tableName.'.MetadataID=isometadata.MetadataID')
		->where('SourceID',$sourceid);
		$query=$this->db->get();
		return $this->tranResult($query->result_array());	
	}
}
?>