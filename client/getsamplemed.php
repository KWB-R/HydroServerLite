<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

require_once 'db_config.php';


// get data and store in a json array
$query = "SELECT * FROM samplemediumcv";
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$variables[] = array(
        //'smterm' => "Select...",
		'smterm' => $SelectEllipsis,
        'smdef' => "-1" );

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    
		$variables[] = array(
        'smterm' => utf8_encode($row['Term']),
        'smdef' => utf8_encode($row['Definition']));

}

$variables[] = array(
        //'smterm' => "Other/New",
		'smterm' => $OtherSlashNew ,
        'smdef' => "-10" );


echo json_encode($variables);
?>