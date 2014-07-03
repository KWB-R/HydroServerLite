<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';
//All queries go through a translator. 
require_once 'DBTranslator.php';


// get data and store in a json array
$query = "SELECT Term,Definition FROM valuetypecv";

$data = transQuery($query,0,1);

$variables[] = array(
        //'vtterm' => "Select...",
		'vtterm' => $SelectEllipsis,
        'vtdef' => "-1" );

foreach ($data as $row) {
    
		$variables[] = array(
        'vtterm' => $row['Term'],
        'vtdef' => $row['Definition']);

}

$variables[] = array(
        //'vtterm' => "Other/New",
		'vtterm' => $OtherSlashNew,
        'vtdef' => "-10" );


echo json_encode($variables);
?>