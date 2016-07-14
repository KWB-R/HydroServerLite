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

	function getResultData(
		$conditions = array('SiteID' => -1, 'VariableID' => -1, 'MethodID' => -1),
		$start = '', $end = '',
		$fieldList = 'ValueID, DataValue, LocalDateTime',
		$extended = FALSE
	)
	{
		// In the following we will work with an array of arrays each of which
		// represents a combination of IDs for which the records are to be filtered.

		if (! is_array($conditions) || count($conditions) == 0) {
			log_message("error",
				"Non-empty array expected in argument 'conditions' of " .
				"Datapoints::getResultData()."
			);
			return FALSE;
		}

		if ($extended) {
			// Prefix field names with A (DataValues), B (Sites), C (Variables),
			// D (Sources) , E (Methods), and F (Qualifiers)
			$fieldList = $this->extendedFieldList($fieldList);
		}

		// start the SQL query
		$this->db->select($fieldList)->from($this->tableName . ' AS A');

		// if required, extend query source by joining tables Sites, Variables,
		// Sources, Methods
		if ($extended) {
			$this->db->join('sites AS B', 'A.SiteID = B.SiteID', 'INNER');
			$this->db->join('variables AS C', 'A.VariableID = C.VariableID', 'INNER');
			$this->db->join('sources AS D', 'A.SourceID = D.SourceID', 'INNER');
			$this->db->join('methods AS E', 'A.MethodID = E.MethodID', 'LEFT');
			$this->db->join('qualifiers AS F', 'A.QualifierID = F.QualifierID', 'LEFT');
		}

		// Generate the part of the WHERE clause that represents the time interval 
		// restriction (if any)
		$timeCondition = $this->sqlTimeCondition($start, $end);

		// Guarantee that we continue with an array of arrays
		if (! is_array(array_values($conditions)[0])) {
			$conditions = array($conditions);
		}

		// Biuld the WHERE clause incrementally
		for ($i = 0; $i < count($conditions); $i++) {

			// keep only IDs that are not -1 in the array
			$condition = array_filter($conditions[$i], function($id) {
				return ($id != -1);
			});

			// Rewrite the array $condition by prefixing its keys with 'A.'
			$condition = array_combine(
				$this->prefixed(array_keys($condition), 'A.'),
				array_values($condition)
			);

			// If this is not the first condition, start a new block of the
			// WHERE clause with OR and set the first element of this block to the
			// expression "ValueID > -1" which is always TRUE
			if ($i > 0) {
				$this->db->or_where('ValueID >', -1);
			}

			// We have to repeat the time restriction in each OR block
			if ($timeCondition !== '') {
				$this->db->where($timeCondition);
			}

			$this->db->where($condition);
		}

		$this->db->order_by('DateTimeUTC');

		$result = $this->db->get();
		
		log_message("debug", "Last Query: " . $this->db->last_query());
//echo $this->db->last_query();
		return $result;
	}

	private function extendedFieldList($fieldList)
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

		return implode(', ', $fields);
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

