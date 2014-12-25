<?php
class Variables extends MY_Model
{
	function __construct()
	{
		parent::__construct();
		$this->tableName = "variables";	
	}
	
	function getSite($siteid)
	{
		//Gets vars by site. 
		$this->db->select('VariableName,VariableID')
			->distinct()
			->from('seriescatalog')
			->where('SiteID',$siteid)
			->where('VariableID !=','NULL');
		
		$query=$this->db->get();
		return $this->tranResult($query->result_array());		
	}
	function getTypes($siteid,$varname)
	{
		$this->db->select('DataType')
			->distinct()
			->from('seriescatalog')
			->where('SiteID',$siteid)
			->where('VariableName',$varname);
		
		$query=$this->db->get();
		return $this->tranResult($query->result_array());	
	}
	function getVarID($siteid,$varname,$type)
	{
		$this->db->select('VariableID')
			->distinct()
			->from('seriescatalog')
			->where('SiteID',$siteid)
			->where('VariableName',$varname)
			->where('DataType',$type);
		
		$query=$this->db->get();
		return $query->result_array();	
	}
}
?>