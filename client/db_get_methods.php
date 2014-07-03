<?php

//All queries go through a translator. 
require_once 'DBTranslator.php';

// get data and store in a json array
$query = "SELECT MethodID, MethodDescription FROM seriescatalog";
$siteid = $_GET['siteid'];
$varid = $_GET['varid'];
$query .= " WHERE SiteID=".$siteid." AND VariableID='".$varid."'";

$result = transQuery($query,0,1);

foreach ($result as $row) {
		$methods[] = array(
        'methodid' => $row['MethodID'],
        'methodname' => $row['MethodDescription']);
}

echo json_encode($methods);
mysql_close($connect);
?>