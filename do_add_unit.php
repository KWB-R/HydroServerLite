<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//Part 3 for Adding variable

//Process a new unit

$varname=$_GET['varname'];
$vardef=$_GET['vardef'];
$vartype=$_GET['vartype'];


//First check if the same entry exists in the table
require_once 'db_config.php';

$sql="SELECT * FROM `units` WHERE unitsName='$varname'";
$result = @mysql_query($sql,$connect)or die(mysql_error());
$row=mysql_num_rows($result);
if($row>0)
//{echo("The unit already exists. Cannot Add again. Please select it from the drop down list");}
{echo "false|" . $UnitExists;}

else
{	$sql1="INSERT INTO `units`(`unitsName`, `unitsType`, `unitsAbbreviation`) VALUES ('$varname','$vartype','$vardef')";
	$result1 = @mysql_query($sql1,$connect)or die(mysql_error());
	$sql2="SELECT `unitsID` FROM `units` WHERE `unitsAbbreviation`='$vardef' and `unitsType`='$vartype' and `unitsName`='$varname'";
	$result2 = @mysql_query($sql2,$connect)or die(mysql_error());
	$row2=mysql_fetch_array ($result2);
	echo("true|" . $row2['unitsID']);
	}
?>
