<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//All queries go through a translator. 
require_once 'DBTranslator.php';


// get data and store in a json array
$query = "SELECT DISTINCT VariableName FROM seriescatalog";
$siteid = $_GET['siteid'];
$query .= " WHERE SiteID=".$siteid;

$result = transQuery($query,0,1);

$variables[] = array(
        'variableid' => "-1",
        'variablename' => "Please select a variable" );
	
$temp=1;

	foreach ($result as $row) {
    
if($row['VariableName']!=null){
		$variables[] = array(
        'variableid' => $temp,
        'variablename' => $row['VariableName']);
$temp=$temp+1;
}
}

echo json_encode($variables);
?>