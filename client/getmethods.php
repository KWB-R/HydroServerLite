<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//value given from the page
$m=$_GET["m"];

//connect to server and select database
require_once 'db_config.php';

$query = "SELECT MethodID FROM varmeth WHERE VariableID='".$m."'";

$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());


//filter the Type results after Site is selected

$row2 = mysql_fetch_array ($result);
$num_m = @mysql_num_rows($result);

	if ($num_m < 1) {		
		$methods[] = array(
        'methodid' => "-1",
        //'methodname' => "No Methods Available" );
		'methodname' => $NoMethodsAvailable );
		} 

	else {

				$methods[] = array(
        'methodid' => "-1",
        //'methodname' => "Select Method..." );
		'methodname' => $SelectMethodElipsis );

	$methodstr = explode(",", $row2['MethodID']);
	
	foreach($methodstr as &$value){
		
		$sql_m2 ="SELECT * FROM methods WHERE MethodID=".$value;
	
		$result_m2 = @mysql_query($sql_m2,$connect)or die(mysql_error());

			while ($rows = mysql_fetch_array ($result_m2)) {
			

	$methods[] = array(
        'methodid' => utf8_encode($rows["MethodID"]),
        'methodname' => utf8_encode($rows["MethodDescription"]) );


			}
		}
	}


echo json_encode($methods);



?>