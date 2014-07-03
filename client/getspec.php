<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//All queries go through a translator. 
require_once 'DBTranslator.php';


// get data and store in a json array
$query = "SELECT * FROM speciationcv";
$result = transQuery($query,0,1);
$variables[] = array(

		'specterm' => $SelectEllipsis,
        'specdef' => "-1" );

foreach ($result as $row) {
    
		$variables[] = array(
        'specterm' => $row['Term'],
        'specdef' => $row['Definition']);

}

$variables[] = array(
		'specterm' => $OtherSlashNew,
        'specdef' => "-10" );

echo json_encode($variables);
?>