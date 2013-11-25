<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

require_once 'db_config.php';


// get data and store in a json array
$query = "SELECT * FROM valuetypecv";
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$variables[] = array(
        //'vtterm' => "Select...",
		'vtterm' => $SelectEllipsis,
        'vtdef' => "-1" );

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    
		$variables[] = array(
        'vtterm' => utf8_encode($row['Term']),
        'vtdef' => utf8_encode($row['Definition']));

}

$variables[] = array(
        //'vtterm' => "Other/New",
		'vtterm' => $OtherSlashNew,
        'vtdef' => "-10" );


echo json_encode($variables);
?>