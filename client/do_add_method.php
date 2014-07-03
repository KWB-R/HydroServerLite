<?php
//check authority to be here
//require_once 'authorization_check.php';

$MethodD = $_POST["MethodDescription"];
$MethodL = $_POST["MethodLink"];
$Variable = $_GET["varmeth"];


//All queries go through a translator. 
require_once 'DBTranslator.php';

//add the new MethodID #

$sql ="SHOW TABLE STATUS LIKE 'methods'";

$result = transQuery($sql,0,0);
$row = $result[0];
$MethodID = $row['Auto_increment'];

//add all the values to the methods table

if ($MethodL ==''){
	$sql2 ="INSERT INTO `methods`(`MethodID`, `MethodDescription`)  VALUES ('$MethodID', '$MethodD')";
}else{
	$sql2 ="INSERT INTO `methods`(`MethodID`, `MethodDescription`, `MethodLink`)  VALUES ('$MethodID', '$MethodD', '$MethodL')";
}

$result2 = transQuery($sql2,0,-1);

echo($result2);


$methodstr=explode(",", $Variable);
	
foreach($methodstr as &$value){

//Go get the current value for the Method in the varmeth table
$sql3 ="SELECT MethodID FROM varmeth WHERE VariableID='$value'";

$result3 = transQuery($sql3,0,0);

	if (count($result3) > 0) {

	$array = $result3[0];
	$newmethodstr = $array['MethodID'] . "," . $MethodID;
	
	//Post the new result for the Method in the varmeth table
	$sql4 ="UPDATE `varmeth` SET MethodID='$newmethodstr' WHERE VariableID='$value'";
	$result4 = transQuery($sql4,0,-1);
	
	}
}
?>
