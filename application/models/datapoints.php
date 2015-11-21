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

		if ($this->oneRowAffected())
		{
			return $this->db->insert_id();
		}
		else
		{
			return false;	
		}
	}
	
	function addPoints($data)
	{
		$this->db->insert_batch($this->tableName, $data);

		//$num_inserts = $this->affectedRows();
		//Not returning this as batch processing doesn't return a true result here.
		return true;
	}
	
	function getQueryForGetData($site, $var, $method, $start, $end,
		$fieldList = 'ValueID, DataValue, LocalDateTime'
	)
	{
		$this->db->select($fieldList)
			->from($this->tableName)
			->where('SiteID', $site)
			->where('VariableID', $var)
			->where('MethodID', $method)
			->where("LocalDateTime between '".$start."' and '".$end."'")
			->order_by('LocalDateTime');

		return $this->db->get();
	}

	function getData($site, $var, $method, $start, $end,
		$fieldList = 'ValueID, DataValue, LocalDateTime'
	)
	{
		$query = $this->getQueryForGetData($site, $var, $method, $start, $end,
			$fieldList
		);

		return $query->result_array();	
	}
	
	function getResultData($site, $var, $method, $start, $end,
		$fieldList = 'ValueID, DataValue, LocalDateTime'
	)
	{
		return $this->getQueryForGetData(
			$site, $var, $method, $start, $end, $fieldList
		);
	}

	function delete($ValueID)
	{
		$this->db
			->where('ValueID', $ValueID)
			->delete($this->tableName);

		return $this->oneRowAffected();
	}

	function editPoint($valueid, $value, $LocalDateTime, $DateTimeUTC)
	{
		$this->db->set('LocalDateTime', $LocalDateTime)
		->set('DataValue', $value)
		->set('DateTimeUTC', $DateTimeUTC)
		->where('ValueID', $valueid);

		$this->db->update($this->tableName); 

		return $this->oneRowAffected();
	}
}

