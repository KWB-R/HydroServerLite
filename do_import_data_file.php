<?php
$SourceID = $_GET["SourceID"];
$SiteID = $_GET["SiteID"];
$VariableID = $_GET["VariableID"];
$MethodID = $_GET["MethodID"];
$name="uploads/";
$name .=$_GET['filename'];

require_once 'AL_hidden_values.php';



$handle = fopen($name, "r");
$flag=0;
$row=0;
$row_success=1;

$sql7 ="INSERT INTO `datavalues`(`DataValue`, `ValueAccuracy`, `LocalDateTime`, `UTCOffset`, `DateTimeUTC`, `SiteID`, `VariableID`, `OffsetValue`, `OffsetTypeID`, `CensorCode`, `QualifierID`, `MethodID`, `SourceID`, `SampleID`, `DerivedFromID`, `QualityControlLevelID`) VALUES ";

while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {


//Checking for Header and preventing further processing if it is a header

if($flag==0)
{
//First Run
$flag=1;
}

else
{

$p_datetime=$data[0];
$p_value=$data[1];
$DataValue=$p_value;

$pieces = explode("-", $data[0]);
$pieces2 = explode(" ", $pieces[2]);
$pieces3 = explode(":", $pieces2[1]);

$timepieces[0]=$pieces3[0]; // piece1 (hours)
$timepieces[1]=$pieces3[1]; // piece2 (minutes)

// the UTC Offset time is 7 hours in this location
$newUTCpiece = ($timepieces[0] + $UTCOffset2); 
	
$LocalDateTime = $p_datetime;
$localtimestamp = strtotime($LocalDateTime);
$ms = $UTCOffset * 3600;
$utctimestamp = $localtimestamp - ($ms);
$DateTimeUTC = date("Y-m-d H:i:s", $utctimestamp);

//add the all variables to the datavalues table
$sql7.="('$DataValue', '$ValueAccuracy', '$LocalDateTime', '$UTCOffset', '$DateTimeUTC', '$SiteID', '$VariableID', '$OffsetValue', $OffsetTypeID, '$CensorCode', '$QualifierID', '$MethodID', '$SourceID', $SampleID, '$DerivedFromID', '$QualityControlLevelID'), ";

if ($row%5000 == 0 )
{
	$sql7=substr($sql7,0,(strlen($sql7)-2));
	$result7 = @mysql_query($sql7,$connection)or die(mysql_error());
	$sql7 ="INSERT INTO `datavalues`(`DataValue`, `ValueAccuracy`, `LocalDateTime`, `UTCOffset`, `DateTimeUTC`, `SiteID`, `VariableID`, `OffsetValue`, `OffsetTypeID`, `CensorCode`, `QualifierID`, `MethodID`, `SourceID`, `SampleID`, `DerivedFromID`, `QualityControlLevelID`) VALUES ";
}

}
$row++;
}


//Check for no values!

if ((strlen($sql7)>300 )) 
{
$sql7=substr($sql7,0,(strlen($sql7)-2));
$result7 = @mysql_query($sql7,$connection)or die(mysql_error());	
}

require_once 'update_series_catalog_function.php';
update_series_catalog($SiteID, $VariableID, $MethodID, $SourceID, $QualityControlLevelID);

echo $result7;

/*
if($row_success==$row)
{
echo(1);	
}
else
echo(-1);
*/
?>
