<?php

//Editing exiting variable
require_once 'database_connection.php';

$varid=$_GET['varid'];
$newmlist=$_GET['vmeth'];

//First Search if the variable id exists in the database


$query_Recordset1 ="SELECT * FROM `varmeth` WHERE `VariableID`='$varid'";
$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
$row1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

if($totalRows_Recordset1)
{
	//Post the new result for the Method in the varmeth table
$sql4 ="UPDATE `varmeth` SET `MethodID`='$newmlist' WHERE `VariableID`='$varid'";
$result4 = @mysql_query($sql4,$connection)or die(mysql_error());
}

else
{
//Add the New Entry to the VarMeth Table

//Fetch the Variable Details from the Variable Table

$query_Recordset2 ="SELECT * FROM `variables` WHERE `VariableID`='$varid'";
$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_error());
$row2 = mysql_fetch_assoc($Recordset2);

$vc=$row2['VariableCode'];
$vn=$row2['VariableName'];
$dt=$row2['DataType'];


$sql4="INSERT INTO `varmeth`(`VariableID`, `VariableCode`, `VariableName`, `DataType`, `MethodID`) VALUES ('$varid','$vc','$vn','$dt','$newmlist')	";
$result4 = @mysql_query($sql4,$connection)or die(mysql_error());
	
}



echo($result4);

?>


