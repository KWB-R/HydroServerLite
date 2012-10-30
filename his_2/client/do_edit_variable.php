<?php

//Editing exiting variable
require_once 'db_config.php';

if(isset($_GET['del'])){
	
	//Perform Delete
	$varid=$_GET['varid'];

	$sql1="DELETE FROM `variables` WHERE `VariableID`='$varid'";
	$result1 = @mysql_query($sql1,$connect)or die(mysql_error());

	if($result1){
		$sql_vm="DELETE FROM `varmeth` WHERE `VariableID`='$varid'";
		$result_vm = @mysql_query($sql_vm,$connect)or die(mysql_error());
	}

	echo($result1);

}else{

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
	$varid=$_GET['varid'];


	$sql1="UPDATE `variables` SET `VariableCode`='$vc',`VariableName`='$vn',`Speciation`='$sp',`VariableunitsID`='$unit',`SampleMedium`='$sm',`ValueType`='$vt',`IsRegular`='$isr',`TimeSupport`='$ts',`TimeunitsID`='$tid',`DataType`='$dt',`GeneralCategory`='$cat',`NoDataValue`='$nod' WHERE `VariableID`='$varid'";

	$result1 = @mysql_query($sql1,$connect)or die(mysql_error());

	echo($result1);

}

?>