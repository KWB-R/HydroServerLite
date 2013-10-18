<?php
//check authority to be here
require_once 'authorization_check.php';

$SID = $_GET['SourceID'];
$MID = $_GET['MetadataID2'];

//connect to server and select database
require_once 'database_connection.php';

//Delete the SourceID # provided
$sql_del ="DELETE FROM sources WHERE SourceID='$SID'";

$result_del = @mysql_query($sql_del,$connection)or die(mysql_error());

	if($result_del){
		//Also delete the MetadataID # provided
		$sql_del2 ="DELETE FROM isometadata WHERE MetadataID='$MID'";

		$result_del2 = @mysql_query($sql_del2,$connection)or die(mysql_error());
	}
	
echo ($result_del);

?>