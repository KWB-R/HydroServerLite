<?php
class MY_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
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
?>