<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//Part 4 for Adding variable

//Process a new sample medium

$varname=$_GET['varname'];
$vardef=$_GET['vardef'];

//All queries go through a translator. 
require_once 'DBTranslator.php';

$sql="SELECT * FROM `samplemediumcv` WHERE Term='$varname'";
$result = transQuery($sql,1,0);

if(count($result)>0)
//{echo("The Sample Medium already exists. Cannot Add again. Please select it from the drop down list");}
{echo $TheSampleExists;}

else

{	$sql1="INSERT INTO `samplemediumcv`(`Term`, `Definition`) VALUES ('$varname','$vardef')	";
	$result1 = transQuery($sql1,1,-1);
	echo($result1);
	}
?>
