<?php
//All queries go through a translator. 
require_once 'DBTranslator.php';

$siteid=$_GET['siteid'];
$varid=$_GET['varid'];
$startdate=$_GET['startdate'];
$enddate=$_GET['enddate'];
$methodid=$_GET['meth'];

$query2 = "SELECT NoDataValue FROM variables";
$query2 .= " WHERE VariableID=".$varid;
$result2 = transQuery($query2,0,0);
$unitid = $result2[0];
$NoValue = $unitid['NoDataValue'];

$query = "SELECT ValueID, DataValue, LocalDateTime FROM datavalues";
$query .= " WHERE SiteID=".$siteid." and VariableID=".$varid." and MethodID='$methodid' and LocalDateTime between '".$startdate."' and '".$enddate."' ORDER BY LocalDateTime ASC";

$result = transQuery($query,0,0);

$num_rows = count($result);
$count=1;
foreach ($result as $row) 
  {
	  if (!($row['DataValue'] == $NoValue))
{
    echocsv( $row );
   if($count!=$num_rows)
	{echo "\r\n";}
  $count=$count+1;
  }
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