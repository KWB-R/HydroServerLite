<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';
//All queries go through a translator. 
require_once 'DBTranslator.php';
//value given from the page
$m=$_GET["m"];

$query = "SELECT MethodID FROM varmeth WHERE VariableID='".$m."'";
$result = transQuery($query,0,0);

//filter the Type results after Site is selected

$row2 = $result[0];
$num_m = count($result);

	if ($num_m < 1) {		
		$methods[] = array(
        'methodid' => "-1",
		'methodname' => $NoMethodsAvailable );
		} 

	else {

		$methods[] = array(
        'methodid' => "-1",
		'methodname' => $SelectMethodElipsis );

		$methodstr = explode(",", $row2['MethodID']);
	
		foreach($methodstr as &$value){
			$sql_m2 ="SELECT * FROM methods WHERE MethodID=".$value;
			$result_m2 = transQuery($sql_m2,0,1);
				foreach ($result_m2 as $row) {
					$methods[] = array(
					'methodid' => $row["MethodID"],
					'methodname' => $row["MethodDescription"] );
					}
				}
		}

echo json_encode($methods);
?>