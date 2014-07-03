<?php
//check authority to be here
require_once 'authorization_check.php';

$MID = $_GET['MethodID'];

//All queries go through a translator. 
require_once 'DBTranslator.php';

//Delete the MethodID # provided

$sql_d ="DELETE FROM methods WHERE MethodID='$MID'";

$result_d =  transQuery($sql_d,0,-1);

//Update or delete method from varmeth table too!
require_once 'update_varmeth.php';

echo ($result_d);

?>