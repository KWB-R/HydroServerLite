<?php

//All queries go through a translator. 
require_once 'DBTranslator.php';

$varid=$_GET['varid'];
$newmlist=$_GET['vmeth'];

//Post the new result for the Method in the varmeth table
$sql4 ="UPDATE `varmeth` SET `MethodID`='$newmlist' WHERE `VariableID`='$varid'";
//UPDATE `varmeth` SET `MethodID`='17,23,29' WHERE `VariableID`='42'
$result4 = transQuery($sql4,0,-1);

echo($result4);

?>


