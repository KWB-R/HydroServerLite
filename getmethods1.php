<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//value given from the page
$m=$_GET["m"];

//connect to server and select database
require_once 'database_connection.php';

//filter the Site results after Source is selected
$sql4 ="SELECT MethodID FROM varmeth WHERE VariableID='".$m."'";

$result4 = @mysql_query($sql4,$connection)or die(mysql_error());

$num4 = @mysql_num_rows($result4);
	if ($num4 < 1) {

    //$msg4 = "<P><em2>No Methods for this Variable.</em></p>";
	$msg4 = "<P><em2>".$NoMethodsVariable."</em></p>";

	} else {
		
	//$option_block4 = "<select name='MethodID' id='MethodID'><option value='-1'>Select....</option>";
	$option_block4 = "<select name='MethodID' id='MethodID'><option value='-1'>".$SelectEllipsis."</option>";

// works to here	

	$array = mysql_fetch_array($result4);
	
	$methodstr=explode(",", $array['MethodID']);
	
		foreach($methodstr as &$value){
		
			$final_sql ="SELECT * FROM methods WHERE MethodID=".$value;
	
			$f_result = @mysql_query($final_sql,$connection)or die(mysql_error());

				while ($finalarray = mysql_fetch_array($f_result)){
			
        		$MethodID = $finalarray["MethodID"];
	        	$MethodDescription = $finalarray["MethodDescription"];

				$option_block4 .= "<option value='".$MethodID."'>".$MethodDescription."</option>";
				}
		}
	}


$option_block4 .= "</select>*&nbsp;<a href='#' onClick='show_answer()' border='0'><img src='images/questionmark.png' border='0'></a>";

echo $option_block4;

mysql_close($connection);

?>
