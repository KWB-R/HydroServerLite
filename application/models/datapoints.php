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
	
	function getQueryForGetData($siteID = -1, $variableID = -1, $methodID = -1,
		$start = '', $end = '',	$fieldList = 'ValueID, DataValue, LocalDateTime'
	)
	{
		$ids = array(
			'SiteID' => $siteID,
			'VariableID' => $variableID,
			'MethodID' => $methodID
		);

		// keep only IDs that are not -1 in the array
		$ids = array_filter($ids, function($id) {
			return ($id != -1);
		});

		// start the SQL query
		$this->db->select($fieldList)->from($this->tableName);

		// append a condition to the WHERE clause for the remaining IDs
		$this->db->where($ids);

		$timeCondition = $this->sqlTimeCondition($start, $end);

		if ($timeCondition !== '')
		{
			$this->db->where($timeCondition);
		}

		$this->db->order_by('DateTimeUTC');

		return $this->db->get();
	}

	private function sqlTimeCondition($start = '', $end = '',
		$field = 'LocalDateTime'
	)
	{
		$conditions = array();

		if ($start !== '')
		{
			$conditions[] = "$field >= '$start'";
		}

		if ($end !== '')
		{
			$conditions[] = "$field <= '$end'";
		}

		return implode(' AND ', $conditions);
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

