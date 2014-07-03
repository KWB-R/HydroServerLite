<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//All queries go through a translator. 
require_once 'DBTranslator.php';

$type="Time";

// get data and store in a json array
$query = "SELECT * FROM units WHERE unitsType='$type'";
// get data and store in a json array
$result = transQuery($query,0,1);
$variables[] = array(
		'unit' => $SelectEllipsis,
        'id' => "-1" );

foreach ($result as $row) {
    
		$variables[] = array(
        'unit' => $row['unitsName'],
        'id' => $row['unitsID']);

}


echo json_encode($variables);
?>