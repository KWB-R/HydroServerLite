<?php
$SourceID = $_GET["SourceID"];
$SiteID = $_GET["SiteID"];

if(!isset($_GET['special']))
{
	//Special new case. This one doesnt need these as it reads from the file itself. 
$VariableID = $_GET["VariableID"];
$MethodID = $_GET["MethodID"];
}

$updater = array(); //Added to update the series catalog.
$name="uploads/";
$name .=$_GET['filename'];


require_once 'AL_hidden_values.php';

//All queries go through a translator. 
require_once 'DBTranslator.php';

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




if(isset($_GET['special']))
{
$VariableID = $data[6];
$MethodID = $data[5];
$p_datetime=$data[3];
$p_value=$data[4];
}
else
{
$p_datetime=$data[0];
$p_value=$data[1];
}

$DataValue=$p_value;
	
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
	$result7 = transQuery($sql7,0,-1);
	
	$sql7 ="INSERT INTO `datavalues`(`DataValue`, `ValueAccuracy`, `LocalDateTime`, `UTCOffset`, `DateTimeUTC`, `SiteID`, `VariableID`, `OffsetValue`, `OffsetTypeID`, `CensorCode`, `QualifierID`, `MethodID`, `SourceID`, `SampleID`, `DerivedFromID`, `QualityControlLevelID`) VALUES ";
}

}
$row++;
}


//Check for no values!

if ((strlen($sql7)>300 )) 
{
$sql7=substr($sql7,0,(strlen($sql7)-2));

$result7 = transQuery($sql7,0,-1);
}

if(isset($_GET['special']))
{
require_once 'update_series_catalog.php';
	
}
else
{
require_once 'update_series_catalog_function.php';
update_series_catalog($SiteID, $VariableID, $MethodID, $SourceID, $QualityControlLevelID);
}

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
