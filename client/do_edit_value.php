<?php
//Editing exiting variable

//All queries go through a translator. 
require_once 'DBTranslator.php';

$vid=$_GET['vid'];

if(isset($_GET['del']))
	{
	
	//Perform Delete

$sql1="DELETE FROM `datavalues` WHERE `ValueID`='$vid'";
$result1 = transQuery($sql1,0,-1);
echo($result1);
}
else
{

$val=$_GET['val'];
$dt=$_GET['dt'];
$time=$_GET['time'];


//Create Local and UTC DateTimes
$LocalDate = $dt;
$LocalTime = $time;

$LocalDateTime = $LocalDate . " " . $LocalTime . ":00";
$localtimestamp = strtotime($LocalDateTime);
$ms = $UTCOffset * 3600;
$utctimestamp = $localtimestamp - ($ms);
$DateTimeUTC = date("Y-m-d H:i:s", $utctimestamp);


$sql1="UPDATE `datavalues` SET `DataValue`='$val',`LocalDateTime`='$LocalDateTime',`DateTimeUTC`='$DateTimeUTC' WHERE `ValueID`='$vid'";

	$result1 = transQuery($sql1,0,-1);
	echo($result1);}
?>
