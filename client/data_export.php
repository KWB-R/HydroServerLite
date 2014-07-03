<?php
// Set the JSON header
header( 'Content-Type: text/csv' );
header('Content-Disposition: attachment; filename=data.csv');

//All queries go through a translator. 
require_once 'DBTranslator.php';

$siteid=$_GET['siteid'];
$varid=$_GET['varid'];
$startdate=$_GET['startdate'];
$enddate=$_GET['enddate'];
$methodid=$_GET['meth'];

$query = "SELECT ValueID, DataValue, LocalDateTime FROM datavalues";
$query .= " WHERE SiteID=".$siteid." and VariableID=".$varid." and MethodID='$methodid' and LocalDateTime between '".$startdate."' and '".$enddate."' ORDER BY LocalDateTime ASC";

$result = transQuery($query,0,0);

//Echo the details

echo("HYDROSERVER WEB - DATA EXPORT\r\n");

//Run a query to get the site details

$query2 = "SELECT SiteID, SiteName, SiteCode, Latitude, Longitude FROM sites";
$query2 .= " WHERE SiteID=".$siteid;

$result2 = transQuery($query2,0,0);
$row2 = $result2[0];


echo("Site: ".$row2['SiteName']."(".$row2['SiteCode'].") Latitude: ".$row2['Latitude']." Longitude: ".$row2['Longitude']."\r\n");

//Run A query to get Variable Details

$query2 = "SELECT VariableID, VariableName, DataType FROM variables";
$query2 .= " WHERE VariableID=".$varid;

$result2 = transQuery($query2,0,1);
$row2 = $result2[0];

$varname = str_replace(",", "", $row2['VariableName']);

echo("Variable: ".$varname."(".$row2['VariableID'].") Datatype: ".$row2['DataType']);
echo("\r\n");
//Echo Date Range

echo("The below data is from: ".$startdate." to ".$enddate."\r\n");

//Echo Column Names

echo("ValueID,DataValue,LocalDateTime\r\n");


$num_rows = count($result);
$count=1;
 foreach ($result as $row) 
  {
    echocsv( $row );
   if($count!=$num_rows)
	{echo "\r\n";}
  $count=$count+1;
  }


  function echocsv( $fields )
  {
    $separator = '';
    foreach ( $fields as $field )
    {
      if ( preg_match( '/\\r|\\n|,|"/', $field ) )
      {
        $field = '"' . str_replace( '"', '""', $field ) . '"';
      }
      echo $separator . $field;
      $separator = ',';
    }
    
  }

?>