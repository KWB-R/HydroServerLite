<?php
require_once 'db_config.php';


// get data and store in a json array
$query = "SELECT DISTINCT unitsType FROM units ORDER BY `unitsType` ASC";
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$variables[] = array(
        'unitype' => "Select...",
        'unitid' => "-1" );

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    
		$variables[] = array(
        'unitype' => $row['unitsType'],
        'unitid' => "1");

}

$variables[] = array(
        'unitype' => "Other/New",
        'unitid' => "-10" );


echo json_encode($variables);
?>