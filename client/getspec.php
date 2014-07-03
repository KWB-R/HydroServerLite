<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

require_once 'db_config.php';


// get data and store in a json array
$query = "SELECT * FROM speciationcv";
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$variables[] = array(
        //'specterm' => "Select...",
		'specterm' => $SelectEllipsis,
        'specdef' => "-1" );

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    
		$variables[] = array(
        'specterm' => utf8_encode($row['Term']),
        'specdef' => utf8_encode($row['Definition']));

}

$variables[] = array(
        //'specterm' => "Other/New",
		'specterm' => $OtherSlashNew,
        'specdef' => "-10" );

echo json_encode($variables);
?>