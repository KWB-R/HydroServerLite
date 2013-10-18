<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

require_once 'db_config.php';


// get data and store in a json array
$query = "SELECT * FROM datatypecv";
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$variables[] = array(
        //'dtterm' => "Select...",
		'dtterm' => $SelectEllipsis,
        'dtdef' => "-1" );

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    
		$variables[] = array(
        'dtterm' => utf8_encode($row['Term']),
        'dtdef' => utf8_encode($row['Definition']));

}


echo json_encode($variables);
?>