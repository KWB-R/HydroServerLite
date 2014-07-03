<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//All queries go through a translator. 
require_once 'DBTranslator.php';

$type=$_GET['type'];

// get data and store in a json array
$query = "SELECT * FROM units WHERE unitsType='$type'";
$result = transQuery($query,1,1);
$variables[] = array(
		'unit' => $SelectEllipsis,
        'unitid' => "-1" );

foreach ($result as $row) {
    
		$variables[] = array(
        'unit' => $row['unitsName'],
        'unitid' => $row['unitsID']);

}

$variables[] = array(
		'unit' => $OtherSlashNew,
        'unitid' => "-10" );


echo json_encode($variables);
?>