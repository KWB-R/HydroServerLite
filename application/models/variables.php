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
	
	function getAllWithUnits()
	{
		$this->db->select()
			->from('variables')
			->join('units', 'variables.VariableunitsID=units.unitsID', 'left')
			->join('variablenamecv', 'variables.VariableName=variablenamecv.Term','left');
		
		$query=$this->db->get();
		return $this->tranResult($query->result_array());
	}
	
	function getAllWithUnits2()
	{
		$this->db->select('VariableID','VariableCode','VariableName','variablenamecv.Definition','Speciation','VariableunitsID','SampleMedium','ValueType','IsRegular','TimeSupport','TimeUnitsID','DataType','GeneralCategory','NoDataValue','units.unitsType','units.unitsName')
			->from('variables')
			->join('units', 'variables.VariableunitsID=units.unitsID', 'left')
			->join('variablenamecv', 'variables.VariableName=variablenamecv.Term','left');
		
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
	
	function addTDef($table,$term,$def)
	{
		$this->db->set('Term',$term)
		->set('Definition',$def)
		->insert($table);	
	}
	
	function addUnit($type,$name,$abb)
	{
		$this->db->set('unitsName',$name)
		->set('unitsType',$type)
		->set('unitsAbbreviation',$abb)
		->insert('units');
		return $this->db->insert_id();
	}
	
	function add($var)
	{
		$this->db->insert('variables',$var);
		$num_inserts = $this->db->affected_rows();
	  	return $this->db->insert_id();
	}
	
	function addVM($varMeth)
	{
		$this->db->insert('varmeth',$varMeth);
		$num_inserts = $this->db->affected_rows();
	  	return $num_inserts==1;
	}
	function getByID($id)
	{
		$this->db->select()
		->from($this->tableName)
		->where('VariableID',$id);
		$query = $this->db->get();
		return $this->tranResult($query->result_array());	
	}
	
	function delete($Var)
	{
		$this->db->where('VariableID',$Var)
		->delete($this->tableName);
		$affected = $this->db->affected_rows();
		$this->db->where('VariableID',$Var)
		->delete('varmeth');
		return ($affected==1 && $this->db->affected_rows()==1);
	
	}
	
	function update($Var,$id)
	{
		$this->db->where('VariableID', $id);
		$this->db->update($this->tableName, $Var); 
		$num_inserts = $this->db->affected_rows();
	  	return $num_inserts>=0;
	}
	function updateVM($VM,$id)
	{
		$this->db->where('VariableID', $id);
		$this->db->update('varmeth', $VM);
		$num_inserts = $this->db->affected_rows();
	  	return $num_inserts>=0;
	}
	function getUnitName($id)
	{
		$this->db->where('unitsID',$id);
		$query=$this->db->get('units');
		return $query->result_array();
	}
}
?>