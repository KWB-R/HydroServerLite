<?php
require_once 'db_config.php';

$siteid=$_GET['siteid'];
$varid=$_GET['varid'];
$startdate=$_GET['startdate'];
$enddate=$_GET['enddate'];
$methodid=$_GET['meth'];

$query2 = "SELECT NoDataValue FROM variables";
$query2 .= " WHERE VariableID=".$varid;
$result2 = mysql_query($query2) or die("SQL Error 1: " . mysql_error());
$unitid = mysql_fetch_array($result2, MYSQL_ASSOC);
$NoValue = $unitid['NoDataValue'];

$query = "SELECT ValueID, DataValue, LocalDateTime FROM datavalues";
$query .= " WHERE SiteID=".$siteid." and VariableID=".$varid." and MethodID='$methodid' and LocalDateTime between '".$startdate."' and '".$enddate."' ORDER BY LocalDateTime ASC";

$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());

$num_rows = mysql_num_rows($result);
$count=1;
 while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
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