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
		$start = '', $end = '',	$fieldList = 'ValueID, DataValue, LocalDateTime',
		$extended = FALSE
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

		if ($extended)
		{
			$fields = preg_split("/\s*,\s*/", $fieldList);

			$fields = array_merge(
				$this->prefixed($fields, 'A.'),
				array(
					'B.SiteCode', 
					'C.VariableCode', 
					'D.Organization', 
					'E.MethodDescription',
					'F.QualifierCode'
				)
			);
			
			$fieldList = implode(', ', $fields);
			
			// Rewrite the array $ids by prefixing its keys with 'A.'
			$ids = array_combine(
				$this->prefixed(array_keys($ids), 'A.'), 
				array_values($ids)
			);
		}

		// start the SQL query
		$this->db->select($fieldList)->from($this->tableName . ' AS A');

		// if required, extend query source by joining tables Sites, Variables,
		// Sources, Methods
		if ($extended)
		{
			$this->db->join('sites AS B', 'A.SiteID = B.SiteID', 'INNER');
			$this->db->join('variables AS C', 'A.VariableID = C.VariableID', 'INNER');
			$this->db->join('sources AS D', 'A.SourceID = D.SourceID', 'INNER');
			$this->db->join('methods AS E', 'A.MethodID = E.MethodID', 'LEFT');
			$this->db->join('qualifiers AS F', 'A.QualifierID = F.QualifierID', 'LEFT');
		}

		// append a condition to the WHERE clause for the remaining IDs
		$this->db->where($ids);

		$timeCondition = $this->sqlTimeCondition($start, $end);

		if ($timeCondition !== '')
		{
			$this->db->where($timeCondition);
		}

		$this->db->order_by('DateTimeUTC');
		
		$result = $this->db->get();
		
		log_message("debug", "Last Query: " . $this->db->last_query());
		
		return $result;
	}

	private function prefixed($values, $prefix = "") 
	{
		foreach ($values as &$value) {		
	    $value = $prefix . $value;
	  }
	  
		return $values;
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
		$fieldList = 'ValueID, DataValue, LocalDateTime', $extended = FALSE
	)
	{
		$query = $this->getQueryForGetData($site, $var, $method, $start, $end,
			$fieldList, $extended
		);

		return $query->result_array();	
	}
	
	function getResultData($site, $var, $method, $start, $end,
		$fieldList = 'ValueID, DataValue, LocalDateTime', $extended = FALSE
	)
	{
		log_message('debug', "getResultData(site=$site,var=$var,method=$method," .
			"start=$start,end=$end)...");
		
		return $this->getQueryForGetData(
			$site, $var, $method, $start, $end, $fieldList, $extended
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

