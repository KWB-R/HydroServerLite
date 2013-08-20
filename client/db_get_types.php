<?php

require_once 'db_config.php';

$urlExtraName = "_common.php";
require_once 'internationalize.php';



// get data and store in a json array
$query = "Select * FROM variables ORDER BY VariableName ASC";

$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());




$variables[] = array(
        'variableid' => "-1",
        'variablename' => $SelectEllipsis );
	


while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    

		$variables[] = array(
        'variableid' => $row['VariableID'],
        'variablename' => utf8_encode($row['VariableName']). ' ' ."(".utf8_encode($row["DataType"]).")");

}


echo json_encode($variables);
?>