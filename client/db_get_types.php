<?php

require_once 'DBTranslator.php';

// get data and store in a json array


$query = "Select VariableID,VariableName,DataType FROM variables ORDER BY VariableName ASC";
$data = transQuery($query,0,1);


$variables[] = array(
        'variableid' => "-1",
        'variablename' => "Select...." );


foreach ($data as $row) {

    $variables[] = array(
        'variableid' => $row['VariableID'],
        'variablename' => $row['VariableName']. ' ' ."(".$row["DataType"].")");
	
}

echo json_encode($variables);
?>