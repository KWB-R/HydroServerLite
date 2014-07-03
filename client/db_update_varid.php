<?php

//All queries go through a translator. 
require_once 'DBTranslator.php';

// get data and store in a json array
$query = "SELECT DISTINCT VariableID FROM seriescatalog";
$siteid = $_GET['siteid'];
$varname = $_GET['varname'];
$datatype = $_GET['type'];
$query .= " WHERE SiteID=".$siteid." AND VariableName='".$varname."'"." AND DataType='".$datatype."'";

$result = transQuery($query,1,0);

$row = $result[0];
$output = $row['VariableID'];

echo $output;
mysql_close($connect);
?>