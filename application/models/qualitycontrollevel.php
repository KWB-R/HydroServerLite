<?php

class QualityControlLevel extends MY_Model
{
	function __construct()
	{
		parent::__construct();
		
		$this->tableName = "qualitycontrollevels";	
	}
}
?>
