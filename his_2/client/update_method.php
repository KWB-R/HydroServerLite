<?php
//check authority to be here
//require_once 'authorization_check.php';

$MID = $_GET['MethodID2'];
$MethodD = $_GET['MethodDescription2'];
$MethodL = $_GET['MethodLink2'];

//connect to server and select database
require_once 'database_connection.php';

//Update the fields for the MethodID # provided
if ($MethodL ==''){
	$sql_up ="UPDATE methods SET MethodDescription='$MethodD',MethodLink=NULL WHERE MethodID='$MID'";
}else{
	$sql_up ="UPDATE methods SET MethodDescription='$MethodD',MethodLink='$MethodL' WHERE MethodID='$MID'";
}

$result_up = @mysql_query($sql_up,$connection)or die(mysql_error());

echo ($result_up);

?>