<?php 

//check authority to be here
require_once 'authorization_check.php';
//All queries go through a translator. 
require_once 'DBTranslator.php';

$varid=$_GET['varid'];
$select = "SELECT * FROM varmeth WHERE VariableID='$varid'";
$result = transQuery ( $select,0,1 );
$row = $result[0];

echo $row['MethodID'];

?>
