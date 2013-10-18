<?php

//Editing exiting variable
require_once 'database_connection.php';

$varid=$_GET['varid'];
$newmlist=$_GET['vmeth'];

//Post the new result for the Method in the varmeth table
$sql4 ="UPDATE `varmeth` SET `MethodID`='$newmlist' WHERE `VariableID`='$varid'";
//UPDATE `varmeth` SET `MethodID`='17,23,29' WHERE `VariableID`='42'
$result4 = @mysql_query($sql4,$connection)or die(mysql_error());

echo($result4);

?>


