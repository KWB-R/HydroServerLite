<?php
class Method extends MY_Model
{
	function __construct()
	{
		parent::__construct();
		$this->tableName = "methods";	
	}
	
	function getMethodsByVar($varid)
	{
		
		$this->db->select("MethodID")
		->from("varmeth")
		->where('VariableID',$varid);
		$query = $this->db->get();
		
		if($query->num_rows()<1)
		{
			return;	
		}
		$result = $query->result_array();
		$result = $result[0];
		$methodstr=array_map('intval', explode(',', $result['MethodID']));

		$query = $this->db->select()
		->from($this->tableName)
		->where_in('MethodID', $methodstr)
		->get();
		return $this->tranResult($query->result_array());	
	}
	
}
?>