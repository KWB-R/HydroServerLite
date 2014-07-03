<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//All queries go through a translator. 
require_once 'DBTranslator.php';


// get data and store in a json array
$query = "SELECT * FROM samplemediumcv";
$result = transQuery($query,0,1);
$variables[] = array(
		'smterm' => $SelectEllipsis,
        'smdef' => "-1" );

foreach ($result as $row) {
    
		$variables[] = array(
        'smterm' => $row['Term'],
        'smdef' => $row['Definition']);

}

$variables[] = array(
		'smterm' => $OtherSlashNew ,
        'smdef' => "-10" );


echo json_encode($variables);
?>