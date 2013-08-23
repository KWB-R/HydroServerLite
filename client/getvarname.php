<?php
require_once 'db_config.php';


// get data and store in a json array
$query = "SELECT * FROM variablenamecv ORDER BY `Term` ASC";
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$variables[] = array(
        'specterm' => "Select...",
        'specdef' => "-1" );

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    
		$variables[] = array(
        'specterm' => $row['Term'],
        'specdef' => $row['Definition']);

}

$variables[] = array(
        'specterm' => "Other/New",
        'specdef' => "-10" );


echo json_encode($variables);
?>