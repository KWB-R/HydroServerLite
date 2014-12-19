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


//These slices pick off the parts of the array that I want up in the front and then sorts the bulk of the elements and then places them back together.		
$slicedBodyArray = array_slice($variables,1,-1);
$rowOneSlice = array_slice($variables,0,1);
$lastRowSlice = array_slice ($variables,-1,1);

sort($slicedBodyArray); 
$newArray = array_merge ($rowOneSlice, $slicedBodyArray, $lastRowSlice);

echo json_encode($newArray);
?>