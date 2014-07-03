<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//All queries go through a translator. 
require_once 'DBTranslator.php';


// get data and store in a json array
$query = "SELECT * FROM generalcategorycv";
$result = transQuery($query,0,1) ;
$variables[] = array(
		'dtterm' => $SelectEllipsis,
        'dtdef' => "-1" );

foreach ($result as $row) {
    
		$variables[] = array(
        'dtterm' => $row['Term'],
        'dtdef' => $row['Definition']);

}


echo json_encode($variables);
?>