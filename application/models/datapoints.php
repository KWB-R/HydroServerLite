<?php
class Datapoints extends MY_Model
{
	function __construct()
	{
		parent::__construct();
		$this->tableName = "datavalues";
	
	}
	
	function addPoint($data)
	{
		$this->db->insert($this->tableName, $data);
		$num_inserts = $this->db->affected_rows();
		$id = $this->db->insert_id();
	  	if($num_inserts==1)
		{
			return $id;	
		}
		else
		{
			return false;	
		}
	}
	
	function addPoints($data)
	{
		$this->db->insert_batch($this->tableName, $data);
		$num_inserts = $this->db->affected_rows(); //Not returning this as batch processing doesn't return a true result here. 
	  	return true;
	}
	
	function getData($site,$var,$method,$start,$end)
	{
		$this->db->select('ValueID, DataValue, LocalDateTime')
			->from($this->tableName)
			->where('SiteID',$site)
			->where('VariableID',$var)
			->where('MethodID',$method)
			->where("LocalDateTime between '".$start."' and '".$end."'")
			->order_by('LocalDateTime');
		$query = $this->db->get();
		return $query->result_array();	
	}
	
	function getResultData($site,$var,$method,$start,$end)
	{
		$this->db->select('ValueID, DataValue, LocalDateTime')
			->from($this->tableName)
			->where('SiteID',$site)
			->where('VariableID',$var)
			->where('MethodID',$method)
			->where("LocalDateTime between '".$start."' and '".$end."'")
			->order_by('LocalDateTime');
		$query = $this->db->get();
		return $query;	
	}
	function delete($ValueID)
	{
		$this->db
			->where('ValueID',$ValueID)
			->delete($this->tableName);
		$num_del = $this->db->affected_rows();
		return $num_del==1;	
	}
	function editPoint($valueid,$value,$LocalDateTime,$DateTimeUTC)
	{
		$this->db->set('LocalDateTime', $LocalDateTime)
		->set('DataValue',$value)
		->set('DateTimeUTC',$DateTimeUTC)
		->where('ValueID',$valueid);
		$this->db->update($this->tableName); 
		$num = $this->db->affected_rows();
		return $num==1;	
	}
}
?>