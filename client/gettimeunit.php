<?php
require_once 'db_config.php';

$type="Time";

// get data and store in a json array
$query = "SELECT * FROM units WHERE unitsType='$type' ORDER BY `unitsName` ASC";
// get data and store in a json array
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$variables[] = array(
        'unit' => "Select...",
        'id' => "-1" );

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    
		$variables[] = array(
        'unit' => $row['unitsName'],
        'id' => $row['unitsID']);

}


echo json_encode($variables);
?>