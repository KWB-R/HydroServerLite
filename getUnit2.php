<?php

require_once 'db_config.php';

$varid=$_GET['varid'];

$query1="SELECT u.`unitsAbbreviation` FROM `variables` v INNER JOIN units u ON v.VariableunitsID=u.unitsID WHERE v.`VariableID` = ".$varid;

$export = mysql_query ( $query1 ) or die ( "Sql error : " . mysql_error( ) );




if ($row = mysql_fetch_row( $export ))
{
	$data = $row[0];
	if ($data == "None")
	{
		$data="Unit:None";	
	}
	else
	{
	$data="Unit:".$data;
	}
}
else
{
	$data="Unit:None";
}
echo $data;

?>