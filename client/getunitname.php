<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

require_once 'db_config.php';

$type=$_GET['type'];

// get data and store in a json array
$query = "SELECT * FROM units WHERE unitsType='$type'";
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$variables[] = array(
        //'unit' => "Select...",
		'unit' => $SelectEllipsis,
        'unitid' => "-1" );

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    
		$variables[] = array(
        'unit' => utf8_encode($row['unitsName']),
        'unitid' => $row['unitsID']);

}

$variables[] = array(
        //'unit' => "Other/New",
		'unit' => $OtherSlashNew,
        'unitid' => "-10" );


echo json_encode($variables);
?>