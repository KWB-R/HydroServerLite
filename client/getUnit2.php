<?php

//All queries go through a translator. 
require_once 'DBTranslator.php';

$varid=$_GET['varid'];

$query1="SELECT u.`unitsAbbreviation` FROM `variables` v INNER JOIN units u ON v.VariableunitsID=u.unitsID WHERE v.`VariableID` = ".$varid;

$export = transQuery($query1,1,1);
if ($row = $export[0])
{
	$data = $row[0];
	if ($data == "None")
	{
		$data="Unit:None";	
	}
	else
	{
	$data="".$data;
	}
}
else
{
	$data="Unit:None";
}
echo $data;

?>