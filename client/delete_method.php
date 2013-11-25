<?php
//check authority to be here
require_once 'authorization_check.php';

$MID = $_GET['MethodID'];

//connect to server and select database
require_once 'database_connection.php';

//Delete the MethodID # provided

$sql_d ="DELETE FROM methods WHERE MethodID='$MID'";

$result_d = @mysql_query($sql_d,$connection)or die(mysql_error());

//Update or delete method from varmeth table too!
require_once 'update_varmeth.php';

echo ($result_d);

?>