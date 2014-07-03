<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';
//All queries go through a translator. 
require_once 'DBTranslator.php';
//value given from the page
$m=$_GET["m"];


//filter the Site results after Source is selected
$sql4 ="SELECT MethodID FROM varmeth WHERE VariableID='".$m."'";
$result4 = transQuery($sql4,0,0);


$num4 = count($result4);
	if ($num4 < 1) {

    //$msg4 = "<P><em2>No Methods for this Variable.</em></p>";
	$msg4 = "<P><em2>".$NoMethodsVariable."</em></p>";

	} else {
		$result4 = $result4 [0]; //To get the first row from the results
	//$option_block4 = "<select name='MethodID' id='MethodID'><option value='-1'>Select....</option>";
	$option_block4 = "<select name='MethodID' id='MethodID'><option value='-1'>".$SelectEllipsis."</option>";

// works to here	

	
	$methodstr=explode(",", $result4['MethodID']);
	
		foreach($methodstr as &$value){

			$final_sql ="SELECT * FROM methods WHERE MethodID=".$value;
			$f_result = transQuery($final_sql,0,1);

				foreach ($f_result as $finalarray) {
        		$MethodID = $finalarray["MethodID"];
	        	$MethodDescription = $finalarray["MethodDescription"];

				$option_block4 .= "<option value='".$MethodID."'>".$MethodDescription."</option>";
				}
		}
	}


$option_block4 .= "</select>*&nbsp;<a href='#' onClick='show_answer()' border='0'><img src='images/questionmark.png' border='0'></a>";

echo $option_block4;

mysql_close($connect);

?>
