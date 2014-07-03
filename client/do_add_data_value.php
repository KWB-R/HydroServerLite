<?php

//check authority to be here
//require_once 'authorization_check.php';

$SourceID = $_POST["SourceID"];
$SiteID = $_POST["SiteID"];
$VariableID = $_POST["VariableID"];
$MethodID = $_POST["MethodID"];
$DataValue = $_POST["value"];

require_once 'AL_hidden_values.php';

//Create Local and UTC DateTimes
$LocalDate = $_POST["datepicker"];
$LocalTime = $_POST["timepicker"];

$LocalDateTime = $LocalDate . " " . $LocalTime . ":00";
$localtimestamp = strtotime($LocalDateTime);
$ms = $UTCOffset * 3600;
$utctimestamp = $localtimestamp - ($ms);
$DateTimeUTC = date("Y-m-d H:i:s", $utctimestamp);
	
//All queries go through a translator. 
require_once 'DBTranslator.php';

//add the all variables to the datavalues table
$sql7 ="INSERT INTO `datavalues`(`ValueID`, `DataValue`, `ValueAccuracy`, `LocalDateTime`, `UTCOffset`, `DateTimeUTC`, `SiteID`, `VariableID`, `OffsetValue`, `OffsetTypeID`, `CensorCode`, `QualifierID`, `MethodID`, `SourceID`, `SampleID`, `DerivedFromID`, `QualityControlLevelID`) VALUES ('$ValueID', '$DataValue', '$ValueAccuracy', '$LocalDateTime', '$UTCOffset', '$DateTimeUTC', '$SiteID', '$VariableID', '$OffsetValue', $OffsetTypeID, '$CensorCode', '$QualifierID', '$MethodID', '$SourceID', $SampleID, '$DerivedFromID', '$QualityControlLevelID')";

$result7 = transQuery($sql7,0,-1);

require_once 'update_series_catalog_function.php';

update_series_catalog($SiteID, $VariableID, $MethodID, $SourceID, $QualityControlLevelID);

echo ($result7);

?>
