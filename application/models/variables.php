<?php
class Variables extends MY_Model
{
	function __construct()
	{
		parent::__construct();
		$this->tableName = "variables";
	}
	
	private function translatedResult()
	{
		return $this->tranResult($this->db->get()->result_array());
	}

	function getSite($siteid)
	{
		//Gets vars by site. 
		$this->db->select('VariableName, VariableID')
			->distinct()
			->from('seriescatalog')
			->where('SiteID', $siteid)
			->where('VariableID !=', 'NULL');

		return $this->translatedResult();
	}

	function getTypes($siteid,$varname)
	{
		$this->db->select('DataType')
			->distinct()
			->from('seriescatalog')
			->where('SiteID', $siteid)
			->where('VariableName', $varname);

		return $this->translatedResult();
	}

	function getVarID($siteid, $varname, $type)
	{
		$this->db->select('VariableID')
			->distinct()
			->from('seriescatalog')
			->where('SiteID', $siteid)
			->where('VariableName', $varname)
			->where('DataType', $type);
		
		return $this->db->get()->result_array();
	}

	function getUnit($varID)
	{
		$this->db->select('unitsAbbreviation as unitA')
			->from($this->tableName)
			->join('units', $this->tableName.'.VariableunitsID=units.unitsID', 'inner')
			->where('VariableID', $varID);
		
		return $this->translatedResult();
	}

	function getVariableWithUnit($var)
	{
		$table = $this->tableName;

		$this->db->select()
			->from($table)
			->join('units', $table . '.VariableunitsID = units.unitsID', 'inner')
			->where('VariableID', $var);

		return $this->translatedResult();
	}
	
	function getAllWithUnits()
	{
		$this->db->select()
			->from('variables')
			->join('units', 'variables.VariableunitsID = units.unitsID', 'left')
			->join('variablenamecv', 'variables.VariableName = variablenamecv.Term',
							'left');

		return $this->translatedResult();
	}
	
	function getAllWithUnits2()
	{
		$this->db->select('VariableID', 'VariableCode', 'VariableName',
			'variablenamecv.Definition', 'Speciation', 'VariableunitsID',
			'SampleMedium', 'ValueType', 'IsRegular', 'TimeSupport', 'TimeUnitsID',
			'DataType', 'GeneralCategory', 'NoDataValue', 'units.unitsType',
			'units.unitsName')
			->from('variables')
			->join('units', 'variables.VariableunitsID=units.unitsID', 'left')
			->join('variablenamecv', 'variables.VariableName=variablenamecv.Term','left');

		return $this->translatedResult();
	}
	
	function getByTable($tableName)
	{
		return $this->tranResult($this->db->get($tableName)->result_array());
	}
	
	function getUnitTs()
	{
		$this->db->distinct()->select('unitsType')->from('units')
			->order_by('unitsType');

		return $this->translatedResult();
	}
	
	function getUnitsByType($type)
	{
		$this->db->select()->where('unitsType', $type)
			->from('units')->order_by('unitsName');

		return $this->translatedResult();
	}
	
	function addTDef($table, $term, $def)
	{
		$this->db->set('Term', $term)->set('Definition', $def)->insert($table);
	}
	
	function addUnit($type,$name,$abb)
	{
		$this->db->set('unitsName', $name)
			->set('unitsType', $type)
			->set('unitsAbbreviation', $abb)
			->insert('units');

		return $this->db->insert_id();
	}
	
	function add($var)
	{
		$this->db->insert('variables', $var);

		return $this->db->insert_id();
	}
	
	function addVM($varMeth)
	{
		$this->db->insert('varmeth', $varMeth);

		return $this->db->affected_rows() == 1;
	}

	function getByID($id)
	{
		$this->db->select()->from($this->tableName)->where('VariableID', $id);

		return $this->translatedResult();
	}
	
	function delete($Var)
	{
		$this->db->where('VariableID', $Var)->delete($this->tableName);

		$affected = $this->db->affected_rows();

		$this->db->where('VariableID', $Var)->delete('varmeth');

		return ($affected == 1 && $this->db->affected_rows() ==1);
	}
	
	function update($Var, $id)
	{
		$this->db->where('VariableID', $id);
		$this->db->update($this->tableName, $Var);

		return $this->db->affected_rows() >= 0;
	}

	function updateVM($VM, $id)
	{
		$this->db->where('VariableID', $id);
		$this->db->update('varmeth', $VM);

		return $this->db->affected_rows() >= 0;
	}

	function getUnitName($id)
	{
		$this->db->where('unitsID',$id);

		return $this->db->get('units')->result_array();
	}
}

?>
