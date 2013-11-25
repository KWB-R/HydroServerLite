<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

require_once 'db_config.php';


// get data and store in a json array
$query = "SELECT DISTINCT unitsType FROM units";
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$variables[] = array(
        //'unitype' => "Select...",
		'unitype' => $SelectEllipsis,
        'unitid' => "-1" );

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    
		$variables[] = array(
        'unitype' => utf8_encode($row['unitsType']),
        'unitid' => "1");

}

$variables[] = array(
        //'unitype' => "Other/New",
		'unitype' => $OtherSlashNew,
        'unitid' => "-10" );


echo json_encode($variables);
?>