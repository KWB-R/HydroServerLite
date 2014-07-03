<?php
Header("content-type: application/x-javascript");

//All queries go through a translator. 
require_once 'DBTranslator.php';

$siteid=$_GET['siteid'];
$varid=$_GET['varid'];
$startdate=$_GET['startdate'];
$enddate=$_GET['enddate'];
$methodid=$_GET['meth'];


$query = "SELECT ValueID, DataValue, LocalDateTime FROM datavalues";
$query .= " WHERE SiteID='$siteid' and VariableID='$varid' and MethodID='$methodid' and LocalDateTime between '".$startdate."' and '".$enddate."' ORDER BY LocalDateTime ASC";

$result = transQuery($query,0,0);
$query2 = "SELECT VariableunitsID, NoDataValue FROM variables";
$query2 .= " WHERE VariableID=".$varid;

$result2 = transQuery($query2,0,0);

$unitid = $result2[0];
$unitid = $unitid['VariableunitsID'];
$NoValue = $unitid['NoDataValue'];
$query3 = "SELECT * FROM units";
$query3 .= " WHERE unitsID=".$unitid;
$result3 = transQuery($query3,0,1);
$result3 = $result3[0];

$unit=$result3['unitsType'];

echo("var data_test = [\r\n");

$num_rows = count($result);
$count=1;

//To echo Data in javascript format


foreach ($result as $row) 
{
$pieces = explode("-", $row['LocalDateTime']);
$pieces2 = explode(" ", $pieces[2]);
$pieces3 = explode(":", $pieces2[1]);
$pieces[1]=$pieces[1]-1;

$output="[Date.UTC(".$pieces[0].",".$pieces[1].",".$pieces2[0].",".$pieces3[0].",".$pieces3[1].",".$pieces3[2]."),".$row['DataValue']."]";

//Check for NoDataValue (Default is -9999))

if (!($row['DataValue'] == $NoValue))
{
echo $output;
 if($count!=$num_rows)
	{echo ",";}
  $count=$count+1;
  
  echo ("\r\n");

}

}

echo("];");

?>
