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


//These slices pick off the parts of the array that I want up in the front and then sorts the bulk of the elements and then places them back together.		
$slicedBodyArray = array_slice($variables,1,-1);
$rowOneSlice = array_slice($variables,0,1);
$lastRowSlice = array_slice ($variables,-1,1);

sort($slicedBodyArray); 
$newArray = array_merge ($rowOneSlice, $slicedBodyArray, $lastRowSlice);

echo json_encode($newArray);
?>