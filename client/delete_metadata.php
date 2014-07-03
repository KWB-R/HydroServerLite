<?php
//check authority to be here
require_once 'authorization_check.php';

$MetaID = $_GET['MetadataID'];

//All queries go through a translator. 
require_once 'DBTranslator.php';

//Delete the MetadataID # provided

$sql_del_m ="DELETE FROM isometadata WHERE MetadataID='$MetaID'";

$result_del_m = transQuery($sql_del_m,0,-1);

echo ($result_del_m);

?>