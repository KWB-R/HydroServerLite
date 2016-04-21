<?php
class MY_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getAll($translate = true)
	{
		$query = $this->db->get($this->tableName);
		$result = $query->result_array();

		if ($translate === true) {
			$result = $this->tranResult($result);
		}

		return $result;
	}

	function affectedRows()
	{
		$result = $this->db->affected_rows();

		log_message('debug', "MY_Model::affectedRows: ".$result);

		return $result;
	}

	function oneRowAffected()
	{
		$result = ($this->affectedRows() == 1);

		log_message('debug', "MY_Model::oneRowAffected: ".
			(($result)? "true" : "false"));

		return $result;
	}

	function oneOrNoRowAffected()
	{
		return $this->affectedRows() >= 0;
	}

	function getResultArray()
	{
		return $this->db->get()->result_array();
	}
	
	function tranResult($result)
	{
		//Put in the logic to translate the results before showing it to the user. 
		//Possibly to do that new terms needed to be added to the lanugage files and database. 
		//Once that is done, some sort of linkng needs to be done to be able to translate both ways as there is no specific variable here. 
		
		$outputData =array();
		
		foreach($result as $row)
		{
			$row1=array();
			foreach ($row as $key => $value):
				$row1[$key]=$this->translateWord($value);	
			endforeach;
			$outputData[]=$row1;
		}
		return $outputData;		
	}
	
	function translateWord($value,$rev=0)
	{
		return $value; //@TODO
	}
}

