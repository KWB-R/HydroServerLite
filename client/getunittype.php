<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//All queries go through a translator. 
require_once 'DBTranslator.php';


// get data and store in a json array
$query = "SELECT DISTINCT unitsType FROM units";
$result = transQuery($query,0,1);
$variables[] = array(
		'unitype' => $SelectEllipsis,
        'unitid' => "-1" );

foreach ($result as $row) {
    
		$variables[] = array(
        'unitype' => $row['unitsType'],
        'unitid' => "1");

}

$variables[] = array(
		'unitype' => $OtherSlashNew,
        'unitid' => "-10" );


echo json_encode($variables);
?>