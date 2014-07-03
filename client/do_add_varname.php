<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//Part 1 for Adding variable

//Process a new variable name

$varname=$_GET['varname'];
$vardef=$_GET['vardef'];

//First check if the same entry exists in the table

//All queries go through a translator. 
require_once 'DBTranslator.php';

$sql="SELECT * FROM `variablenamecv` WHERE Term='$varname'";
$result = transQuery($sql,1,1);
if(count($result)>0)
{echo $VarNameExists;}

else
{	$sql1="INSERT INTO `variablenamecv`(`Term`, `Definition`) VALUES ('$varname','$vardef')	";
	$result1 = transQuery($sql1,1,-1);
	echo($result1);
	}
?>
