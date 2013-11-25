<?php
//check authority to be here
require_once 'authorization_check.php';

$MetaID = $_GET['MetadataID'];

//connect to server and select database
require_once 'database_connection.php';

//Delete the MetadataID # provided

$sql_del_m ="DELETE FROM isometadata WHERE MetadataID='$MetaID'";

$result_del_m = @mysql_query($sql_del_m,$connection)or die(mysql_error());

echo ($result_del_m);

?>