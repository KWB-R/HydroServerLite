<?php
//All queries go through a translator. 
require_once 'DBTranslator.php';

// get data and store in a json array
$query = "Select * FROM methods ORDER BY MethodDescription ASC";

$result = transQuery($query,0,1);

$methods[] = array(
        'methodid' => "-1",
        'methodname' => "Select...." );
	
	foreach ($result as $row) {
    
		$methods[] = array(
        'methodid' => $row['MethodID'],
        'methodname' => $row['MethodDescription']);
	}


echo json_encode($methods);
?>