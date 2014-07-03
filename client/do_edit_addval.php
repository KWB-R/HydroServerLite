<?php
//check authority to be here
//require_once 'authorization_check.php';
//All queries go through a translator. 
require_once 'DBTranslator.php';
$SiteID = $_GET['sid'];
$VariableID = $_GET['varid'];
$MethodID = $_GET['mid'];
$DataValue = $_GET['val'];

//Run a query to fetch Source id

$query12 = "Select SourceID FROM seriescatalog WHERE `MethodID`='$MethodID' and `VariableID`='$VariableID' and `SiteID`='$SiteID'";

$result12 = transQuery($query12,0,0);
$row = $result12[0];

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

$result7 = transQuery($sql7,0,-1);

require_once 'update_series_catalog_function.php';

update_series_catalog($SiteID, $VariableID, $MethodID, $SourceID, $QualityControlLevelID);

if($result7==1)
{

echo ($ValueID);
	
	
}

?>
