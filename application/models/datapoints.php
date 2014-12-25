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
	  	return $num_inserts==1;
	}
	
}
?>