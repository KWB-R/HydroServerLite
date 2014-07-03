<?php
//All queries go through a translator. 
require_once 'DBTranslator.php';

// get data and store in a json array
$query = "SELECT DISTINCT DataType FROM seriescatalog";
$siteid = $_GET['siteid'];
$varname = $_GET['varname'];
$query .= " WHERE SiteID=".$siteid." AND VariableName='".$varname."'";

$result = transQuery($query,1,1);

foreach ($result as $row) {
	if($row['DataType']=="Average")
	{	
	$dataid=1;}
	else
	{
	$dataid=2;
	}
		$variables[] = array(
        'dataid' => $dataid,
        'dataname' => $row['DataType']);
}

echo json_encode($variables);
mysql_close($connect);
?>