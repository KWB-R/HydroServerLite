<?php
//check authority to be here
//require_once 'authorization_check.php';
//connect to server and select database
require_once 'database_connection.php';
$SiteID = $_GET['sid'];
$VariableID = $_GET['varid'];
$MethodID = $_GET['mid'];
$DataValue = $_GET['val'];

//Run a query to fetch Source id

$query12 = "Select SourceID FROM seriescatalog WHERE `MethodID`='$MethodID' and `VariableID`='$VariableID' and `SiteID`='$SiteID'";

$result12 = mysql_query($query12,$connection) or die("SQL Error 1: " . mysql_error());
$row = mysql_fetch_array($result12, MYSQL_ASSOC);

$SourceID = $row['SourceID'];

require_once 'AL_hidden_values.php';

//Create Local and UTC DateTimes
$LocalDate = $_GET['dt'];
$LocalTime = $_GET['time'];

$LocalDateTime = $LocalDate . " " . $LocalTime . ":00";
$localtimestamp = strtotime($LocalDateTime);
$ms = $UTCOffset * 3600;
$utctimestamp = $localtimestamp - ($ms);
$DateTimeUTC = date("Y-m-d H:i:s", $utctimestamp);	

//add the all variables to the datavalues table
$sql7 ="INSERT INTO `datavalues`(`ValueID`, `DataValue`, `ValueAccuracy`, `LocalDateTime`, `UTCOffset`, `DateTimeUTC`, `SiteID`, `VariableID`, `OffsetValue`, `OffsetTypeID`, `CensorCode`, `QualifierID`, `MethodID`, `SourceID`, `SampleID`, `DerivedFromID`, `QualityControlLevelID`) VALUES ('$ValueID', '$DataValue', '$ValueAccuracy', '$LocalDateTime', '$UTCOffset', '$DateTimeUTC', '$SiteID', '$VariableID', '$OffsetValue', $OffsetTypeID, '$CensorCode', '$QualifierID', '$MethodID', '$SourceID', $SampleID, '$DerivedFromID', '$QualityControlLevelID')";

$result7 = @mysql_query($sql7,$connection)or die(mysql_error());

require_once 'update_series_catalog_function.php';

update_series_catalog($SiteID, $VariableID, $MethodID, $SourceID, $QualityControlLevelID);

if($result7==1)
{

echo ($ValueID);
	
	
}

?>
