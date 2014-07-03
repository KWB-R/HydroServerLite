<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//value given from the page
$x=$_GET["x"];

//All queries go through a translator. 
require_once 'DBTranslator.php';

//filter the Type results after Site is selected
$sql3 ="SELECT DISTINCT VariableID, VariableName, DataType FROM seriescatalog WHERE SiteID='".$x."' ORDER BY VariableName ASC";

$result3 = transQuery($sql3,0,1);
	if (count($result3) < 1) {
	echo "<P><em2> $NoTypesForSite </em></p>";
	} else {
$option_block3 = "<select name='VariableID' id='VariableID' onChange='showMethods(this.value)'><option value=''> $SelectEllipsis </option>";
	foreach ($result3 as $row3) {

		$typeid = $row3["VariableID"];
		$typename = $row3["VariableName"];
		$datatype = $row3["DataType"];

		$option_block3 .= "<option value=$typeid>$typename ($datatype)</option>";

		}
	}
$option_block3 .= "</select>";
echo $option_block3;
mysql_close($connection);
?>