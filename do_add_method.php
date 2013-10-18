<?php
//check authority to be here
//require_once 'authorization_check.php';

$MethodD = $_POST["MethodDescription"];
$MethodL = $_POST["MethodLink"];
$Variable = $_GET["varmeth"];



//connect to server and select database
require_once 'database_connection.php';

//add the new MethodID #

$sql ="SHOW TABLE STATUS LIKE 'methods'";

$result = @mysql_query($sql,$connection)or die(mysql_error());

$row = mysql_fetch_assoc($result);

$MethodID = $row['Auto_increment'];

//add all the values to the methods table

if ($MethodL ==''){
	$sql2 ="INSERT INTO `methods`(`MethodID`, `MethodDescription`)  VALUES ('$MethodID', '$MethodD')";
}else{
	$sql2 ="INSERT INTO `methods`(`MethodID`, `MethodDescription`, `MethodLink`)  VALUES ('$MethodID', '$MethodD', '$MethodL')";
}

$result2 = @mysql_query($sql2,$connection)or die(mysql_error());

echo($result2);


$methodstr=explode(",", $Variable);
	
foreach($methodstr as &$value){

//Go get the current value for the Method in the varmeth table
$sql3 ="SELECT MethodID FROM varmeth WHERE VariableID='$value'";

$result3 = @mysql_query($sql3,$connection)or die(mysql_error());

$num3 = @mysql_num_rows($result3);
	if ($num3 > 0) {

	$array = mysql_fetch_array($result3);

	
	$newmethodstr = $array['MethodID'] . "," . $MethodID;
	
	//Post the new result for the Method in the varmeth table
$sql4 ="UPDATE `varmeth` SET MethodID='$newmethodstr' WHERE VariableID='$value'";

	$result4 = @mysql_query($sql4,$connection)or die(mysql_error());
	
	}
}
?>
