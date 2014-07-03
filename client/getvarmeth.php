<?php 

//check authority to be here
require_once 'authorization_check.php';
require_once 'db_config.php';

$varid=$_GET['varid'];
$select = "SELECT * FROM varmeth WHERE VariableID='$varid'";
$result = mysql_query ( $select ) or die ( "Sql error : " . mysql_error( ) );
$row = mysql_fetch_array ($result);

echo $row['MethodID'];

?>
