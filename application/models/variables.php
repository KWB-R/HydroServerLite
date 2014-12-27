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
	function getUnit($varID)
	{
		$this->db->select('unitsAbbreviation as unitA')
			->from($this->tableName)
			->join('units', $this->tableName.'.VariableunitsID=units.unitsID', 'inner')
			->where('VariableID',$varID);
		
		$query=$this->db->get();
		return $this->tranResult($query->result_array());	
	}
	function getVariableWithUnit($var)
	{
		$this->db->select()
			->from($this->tableName)
			->join('units', $this->tableName.'.VariableunitsID=units.unitsID', 'inner')
			->where('VariableID',$var);
		
		$query=$this->db->get();
		return $this->tranResult($query->result_array());		
	}
	
	function getByTable($tableName)
	{
		$query = $this->db->get($tableName);
		return $this->tranResult($query->result_array());		
	}
	
	function getUnitTs()
	{
		$this->db->distinct()->select('unitsType')
		->from('units')->order_by('unitsType');
		$query = $this->db->get();
		return $this->tranResult($query->result_array());	
	}
	
	function getUnitsByType($type)
	{
		$this->db->select()->where('unitsType',$type)
		->from('units')->order_by('unitsName');
		$query = $this->db->get();
		return $this->tranResult($query->result_array());	
	}
}
?>