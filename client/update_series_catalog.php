<?php

require_once 'database_connection.php';
require_once 'update_series_catalog_function.php';

echo 'Testing UpdateSeriesCatalog!';
db_UpdateSeriesCatalog_All();

function get_table_name($uppercase_table_name) {
    return '`'. strtolower($uppercase_table_name) .'`';
}

// This function updates all entries in the 
// SeriesCatalog by extracting the aggregate values
// from the dataValues table and from related tables.
function db_UpdateSeriesCatalog_All() {
  
  $result_status = array("inserted" => 0, "updated" => 0);
  
  $query = 'SELECT MAX(SiteID), MAX(VariableID), MAX(MethodID), MAX(SourceID), MAX(QualityControlLevelID)
            FROM ' . get_table_name('DataValues') .
            ' GROUP BY SiteID, VariableID, SourceID, MethodID, QualityControlLevelID';
  
  $result = mysql_query($query);
  
  if (!$result) {
    die("<p>Error in executing the SQL query " . $query . ": " . 
	  mysql_error() . "</p>");
  }
  
  $result_array = mysql_fetch_rowsarr($result, MYSQL_NUM);
  foreach($result_array as $r) {
    echo "<p>executing db_UpdateSeriesCatalog({$r[0]}, {$r[1]}, {$r[2]}, {$r[3]}, {$r[4]}</p>";
    $status = update_series_catalog($r[0], $r[1], $r[2], $r[3], $r[4]);
	$result_status["inserted"] += $status["inserted"];
	$result_status["updated"] += $status["updated"];
  }
  
  echo "<p>rows inserted: " . $result_status["inserted"] . "</p>";
  echo "<p>rows updated: " . $result_status["updated"] . "</p>";
}

function mysql_fetch_rowsarr($result, $numass=MYSQL_BOTH) {
  $i=0;
  $keys=array_keys(mysql_fetch_array($result, $numass));
  mysql_data_seek($result, 0);
    while ($row = mysql_fetch_array($result, $numass)) {
      foreach ($keys as $speckey) {
        $got[$i][$speckey]=$row[$speckey];
      }
    $i++;
    }
  return $got;
}
