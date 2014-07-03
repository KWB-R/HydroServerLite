<?php
//Part 6 for Adding variable

//Process all to add a variable

//All queries go through a translator. 
require_once 'DBTranslator.php';

$vc=$_GET['varcode'];
$vn=$_GET['varname'];
$sp=$_GET['sp'];
$unit=$_GET['unit'];
$sm=$_GET['sm'];
$vt=$_GET['vt'];
$isr=$_GET['isreg'];

if($isr=="Regular"){
	$isr=1;	
}else{
	$isr=0;
}

$ts=$_GET['ts'];
$tid=$_GET['tid'];
$dt=$_GET['dt'];
$cat=$_GET['cat'];
$nod=$_GET['nodata'];
$mid=$_GET['mid'];


$qShowStatus = "SHOW TABLE STATUS LIKE 'variables'";
$qShowStatusResult = transQuery($qShowStatus,0,0);

$row1 = $qShowStatusResult[0];
$varid = $row1['Auto_increment'];


$sql1="INSERT INTO `variables`(`VariableCode`, `VariableName`, `Speciation`, `VariableunitsID`, `SampleMedium`, `ValueType`, `IsRegular`, `TimeSupport`, `TimeunitsID`, `DataType`, `GeneralCategory`, `NoDataValue`) VALUES ('$vc','$vn','$sp','$unit','$sm','$vt','$isr','$ts','$tid','$dt','$cat','$nod')";

$result1 = transQuery($sql1,1,-1);

//Update the Var Meth Table to have a new Variable

$sql2="INSERT INTO `varmeth`(`VariableID`, `VariableCode`, `VariableName`, `DataType`, `MethodID`) VALUES ('$varid','$vc','$vn','$dt','$mid')";
$result2 = transQuery($sql2,1,-1);

echo($result1);
?>