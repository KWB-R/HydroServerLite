<?php
class Variables extends MY_Model
{
	function __construct()
	{
		parent::__construct();
		$this->tableName = "variables";	
	}
}
?>