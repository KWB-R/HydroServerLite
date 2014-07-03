<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//Part 3 for Adding variable

//Process a new unit

$varname=$_GET['varname'];
$vardef=$_GET['vardef'];
$vartype=$_GET['vartype'];


//First check if the same entry exists in the table
//All queries go through a translator. 
require_once 'DBTranslator.php';

$sql="SELECT * FROM `units` WHERE unitsName='$varname'";
$result = transQuery($sql,1,1);
if(count($result)>0)
{echo "false|" . $UnitExists;}

else
{	$sql1="INSERT INTO `units`(`unitsName`, `unitsType`, `unitsAbbreviation`) VALUES ('$varname','$vartype','$vardef')";
	$result1 = transQuery($sql1,1,-1);
	$sql2="SELECT `unitsID` FROM `units` WHERE `unitsAbbreviation`='$vardef' and `unitsType`='$vartype' and `unitsName`='$varname'";
	$result2 = transQuery($sql2,1,0);
	$row2=$result2[0];
	echo("true|" . $row2['unitsID']);
	}
?>
