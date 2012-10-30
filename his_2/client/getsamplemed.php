<?php
require_once 'db_config.php';


// get data and store in a json array
$query = "SELECT * FROM samplemediumcv";
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$variables[] = array(
        'smterm' => "Select...",
        'smdef' => "-1" );

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    
		$variables[] = array(
        'smterm' => $row['Term'],
        'smdef' => $row['Definition']);

}

$variables[] = array(
        'smterm' => "Other/New",
        'smdef' => "-10" );


echo json_encode($variables);
?>