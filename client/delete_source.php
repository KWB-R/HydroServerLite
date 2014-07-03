<?php
//check authority to be here
require_once 'authorization_check.php';
//All queries go through a translator. 
require_once 'DBTranslator.php';

$SID = $_GET['SourceID'];
$MID = $_GET['MetadataID2'];

//Delete the SourceID # provided
$sql_del ="DELETE FROM sources WHERE SourceID='$SID'";

$result_del = transQuery($sql_del,0,-1);

	if($result_del){
		//Also delete the MetadataID # provided
		$sql_del2 ="DELETE FROM isometadata WHERE MetadataID='$MID'";
		$result_del2 = transQuery($sql_del2,0,0);
	}
	
echo ($result_del);

?>