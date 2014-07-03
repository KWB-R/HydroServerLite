<?php
require_once 'db_config.php';

// get data and store in a json array
$query = "Select * FROM methods ORDER BY MethodDescription ASC";

$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());

$methods[] = array(
        'methodid' => "-1",
        'methodname' => "Select...." );
	
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    
		$methods[] = array(
        'methodid' => $row['MethodID'],
        'methodname' => $row['MethodDescription']);
	}


echo json_encode($methods);
?>