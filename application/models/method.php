<?php
class Method extends MY_Model
{
	function __construct()
	{
		parent::__construct();
		$this->tableName = "methods";
	}
	
	function add($methodname,$methodlink,$varmeth)
	{
		$this->db
			->set('MethodDescription', $methodname)
			->set('MethodLink',$methodlink);

		$this->db->insert($this->tableName);

		if($this->db->affected_rows() != 1) {
			return false;
		}

		$MethodID = $this->db->insert_id();
		$methodstr = explode(",", $varmeth);

		foreach($methodstr as $value) {

			$varmeth = $this->getVarMeth($value);

			if (count($varmeth) > 0) {
				$newmethodstr = $varmeth['MethodID'] . "," . $MethodID;

				//Post the new result for the Method in the varmeth table
				$this->updateVarMeth($newmethodstr,$value);
			}
		}

		return true;
	}

	function getVarMeth($varid)
	{
		$this->db->select("MethodID")
			->from("varmeth")
			->where('VariableID', $varid);

		$query = $this->db->get();

		if($query->num_rows() < 1) {
			return;
		}

		return $query->result_array()[0];
	}	
	
	function updateVarMeth($varmeth, $varid)
	{
		$this->db->set("MethodID", $varmeth)
			->where("VariableID", $varid)
			->update('varmeth');
	}

	function getMethodsByVar($varid)
	{
		$result = $this->getVarMeth($varid);
		$methodstr = array_map('intval', explode(',', $result['MethodID']));

		$query = $this->db->select()
			->from($this->tableName)
			->where_in('MethodID', $methodstr)
			->get();
			
		return $this->tranResult($query->result_array());
	}

	function getByVarSite($var, $site)
	{
		$this->db
			->distinct()
			->select('MethodID, MethodDescription')
			->from('seriescatalog')
			->where('SiteID', $site)
			->where('VariableID', $var);

		return $this->db->get()->result_array();
	}

	function getEditable()
	{
		$this->db->select()
			->from($this->tableName)
			->where('MethodID >1');

		return $this->db->get()->result_array();
	}

	function getByID($id)
	{
		$this->db->select()
			->from($this->tableName)
			->where('MethodID', $id);

		return $this->db->get()->result_array();
	}

	function delete($ValueID)
	{
		$this->db
			->where('MethodID', $ValueID)
			->delete($this->tableName);

		return $this->db->affected_rows() == 1;
	}

	function updateVarMeth2($methodID)
	{
		$tableName = "varmeth";

		$this->db->select()->from($tableName);

		foreach ($this->db->get()->result_array() as $row) {

			$variableID = $row['VariableID'];

			$methodIDs = explode(',', $row['MethodID']);

			if (in_array($methodID, $methodIDs)) {

				// Remove $MethodID from $methodIDs
				$methodIDs = array_diff($methodIDs, array($methodID));

				// Update column MethodID with the new string of comma separated 
				// MethodIDs
				$this->db
					->set('MethodID', implode(',', $methodIDs))
					->where('VariableID', $variableID)
					->update($tableName);

				return ($this->db->affected_rows() >= 0);
			}
		}
	}

	function update($MethodID, $methodname, $methodlink)
	{
		$this->db
			->set('MethodDescription', $methodname)
			->set('MethodLink', $methodlink)
			->where('MethodID', $MethodID)
			->update($this->tableName);

		return ($this->db->affected_rows() >= 0);
	}
}
?>
