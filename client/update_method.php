<?php
//check authority to be here
//require_once 'authorization_check.php';

$MID = $_GET['MethodID2'];
$MethodD = $_GET['MethodDescription2'];
$MethodL = $_GET['MethodLink2'];

//All queries go through a translator. 
require_once 'DBTranslator.php';

//Update the fields for the MethodID # provided
if ($MethodL ==''){
	$sql_up ="UPDATE methods SET MethodDescription='$MethodD',MethodLink=NULL WHERE MethodID='$MID'";
}else{
	$sql_up ="UPDATE methods SET MethodDescription='$MethodD',MethodLink='$MethodL' WHERE MethodID='$MID'";
}

$result_up = transQuery($sql_up,1,-1);

echo ($result_up);

?>