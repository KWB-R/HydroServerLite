<?php
require_once 'database_connection.php';

function update_series_catalog($siteID, $variableID, $methodID, $sourceID, $qcID) {
  
  $status = "error";
  $series_id_exists = 0; //0: not found, 1: incomplete, 2: complete
  
  //check for an existing seriesID
  $series_id = db_find_seriesid($siteID, $variableID, $methodID, $sourceID, $qcID);
  if ($series_id > 0) {
    $series_id_exists = 2; //complete series exists
  }
  if ($series_id == 0) {
    //special case: find the existing row with SiteID, SourceID
	$series_id  = db_find_seriesid2($siteID, $sourceID);
  }
  if ($series_id == 0) {
    $series_id_exists = 0; //incomplete series exists
  } else {
    $series_id_exists = 1;
  }  
  
  if ($series_id_exists == 0) { // INSERT FULL ENTRY to SERIESCATALOG
  	
  	//this query is used for collecting information for inserting a new seriesCatalog entry
  	$long_query =
  	"SELECT dv.SiteID, s.SiteCode, s.SiteName, s.SiteType,
  	dv.VariableID, v.VariableCode, v.VariableName, v.Speciation,
  	v.VariableUnitsID, vu.UnitsName AS \"VariableUnitsName\",
  	v.SampleMedium, v.ValueType, v.TimeSupport,
  	v.TimeUnitsID, tu.UnitsName AS \"TimeUnitsName\",
  	v.DataType, v.GeneralCategory,
  	m.MethodID, m.MethodDescription,
  	sou.SourceID, sou.Organization, sou.SourceDescription, sou.Citation,
  	qc.QualityControlLevelID, qc.QualityControlLevelCode,
  	MIN( dv.LocalDateTime ) AS \"BeginDateTime\", MAX( dv.LocalDateTime ) AS \"EndDateTime\",
  	MIN( dv.DateTimeUTC )  AS \"BeginDateTimeUTC\", MAX( dv.DateTimeUTC )  AS \"EndDateTimeUTC\",
  	COUNT( dv.ValueID ) AS \"ValueCount\" FROM datavalues dv
  	INNER JOIN sites s ON dv.SiteID = s.SiteID
  	INNER JOIN variables v ON dv.VariableID = v.VariableID
  	INNER JOIN units vu ON v.VariableunitsID = vu.UnitsID
  	INNER JOIN units tu ON v.TimeunitsID = tu.UnitsID
  	INNER JOIN methods m ON dv.MethodID = m.MethodID
  	INNER JOIN sources sou ON dv.SourceID = sou.SourceID
  	INNER JOIN qualitycontrollevels qc ON dv.QualityControlLevelID = qc.QualityControlLevelID
  	WHERE dv.SiteID = $siteID
  	AND dv.VariableID = $variableID
  	AND dv.MethodID = $methodID
  	AND dv.SourceID = $sourceID
  	AND dv.QualityControlLevelID = $qcID
  	GROUP BY dv.SiteID, s.SiteCode, s.SiteName, s.SiteType,
  	dv.VariableID, v.VariableCode, v.VariableName, v.Speciation,
  	v.VariableUnitsID, vu.UnitsName,
  	v.SampleMedium, v.ValueType, v.TimeSupport,
  	v.TimeUnitsID, tu.UnitsName,
  	v.DataType, v.GeneralCategory,
  	m.MethodID, m.MethodDescription,
  	sou.SourceID, sou.Organization, sou.SourceDescription, sou.Citation,
  	qc.QualityControlLevelID, qc.QualityControlLevelCode";
    
  	// run query
  	$valuesresult = mysql_query($long_query);  	 
  	if (!$valuesresult) {
  		die("<p>Error in executing the SQL query " . $long_query . ": " .
  		  mysql_error() . "</p>");
  	}
  	$num_values_rows = mysql_num_rows($valuesresult); 	
  	if ($num_values_rows == 0) {
  		return $status;
  	}
  	
  	// find entries to SeriesCatalog from joining DataValues and other tables
  	$row = mysql_fetch_assoc($valuesresult);
  	$siteID = $row['SiteID'];
  	$siteCode = $row['SiteCode'];
  	$siteName = $row['SiteName'];
  	$siteType = $row['SiteType'];
  	$variableID = $row['VariableID'];
  	$variableCode = $row['VariableCode'];
  	$variableName = $row['VariableName'];
  	$speciation = $row['Speciation'];
  	$variableUnitsID = $row['VariableUnitsID'];
  	$variableUnitsName = $row['VariableUnitsName'];
  	$sampleMedium = $row['SampleMedium'];
  	$valueType = $row['ValueType'];
  	$timeSupport = $row['TimeSupport'];
  	$timeUnitsID = $row['TimeUnitsID'];
  	$timeUnitsName = $row['TimeUnitsName'];
  	$dataType = $row['DataType'];
  	$generalCategory = $row['GeneralCategory'];
  	$methodID = $row['MethodID'];
  	$methodDescription = $row['MethodDescription'];
  	$sourceID = $row['SourceID'];
  	$organization = $row['Organization'];
  	$sourceDescription = $row['SourceDescription'];
  	$citation = $row['Citation'];
  	$qualityControlLevelID = $row['QualityControlLevelID'];
  	$qualityControlLevelCode = $row['QualityControlLevelCode'];
  	$beginDateTime = $row['BeginDateTime'];
  	$endDateTime = $row['EndDateTime'];
  	$beginDateTimeUTC = $row['BeginDateTimeUTC'];
  	$endDateTimeUTC = $row['EndDateTimeUTC'];
  	$valueCount = $row['ValueCount'];
  	
  	$siteName2 = mysql_real_escape_string($siteName);
  	$methodDescription2 = mysql_real_escape_string($methodDescription);
  	$sourceDescription2 = mysql_real_escape_string($sourceDescription);
  	$citation2 = mysql_real_escape_string($citation);
  	
  	// run insert
  	$insert = "INSERT INTO seriescatalog (SiteID, SiteCode, SiteName, SiteType,
  	VariableID, VariableCode, VariableName, Speciation, VariableunitsID, VariableunitsName,
  	SampleMedium, ValueType, TimeSupport, TimeunitsID, TimeunitsName, DataType, GeneralCategory,
  	MethodID, MethodDescription,
  	SourceID, Organization, SourceDescription, Citation,
  	QualityControlLevelID, QualityControlLevelCode,
  	BeginDateTime, EndDateTime, BeginDateTimeUTC, EndDateTimeUTC, ValueCount)
  	VALUES
  	('$siteID', '$siteCode', '$siteName2', '$siteType', '$variableID', '$variableCode', '$variableName',
  	'$speciation', '$variableUnitsID', '$variableUnitsName', '$sampleMedium', '$valueType', '$timeSupport',
  	'$timeUnitsID', '$timeUnitsName', '$dataType', '$generalCategory', '$methodID', '$methodDescription2',
  	'$sourceID', '$organization', '$sourceDescription2', '$citation2',
  	'$qualityControlLevelID', '$qualityControlLevelCode', '$beginDateTime', '$endDateTime',
  	'$beginDateTimeUTC', '$endDateTimeUTC', '$valueCount')";
  	
  	$insertresult = mysql_query($insert);
  	if (!$insertresult) {
  		die("<p>Error in executing the SQL query " . $insert . ": " .
  				mysql_error() . "</p>");
  	}
  	$status = "1 row inserted";
  	
  } elseif ($series_id_exists == 1) { // UPDATE ALL INFO EXCEPT Site and Source info in the SeriesCatalog
  	//this query is used for collecting information for inserting a new seriesCatalog entry
  	$mid_query =
  	"SELECT dv.SiteID,
  	 dv.VariableID, v.VariableCode, v.VariableName, v.Speciation,
  	 v.VariableUnitsID, vu.UnitsName AS \"VariableUnitsName\",
  	 v.SampleMedium, v.ValueType, v.TimeSupport,
  	 v.TimeUnitsID, tu.UnitsName AS \"TimeUnitsName\",
  	 v.DataType, v.GeneralCategory,
  	 m.MethodID, m.MethodDescription,
  	 qc.QualityControlLevelID, qc.QualityControlLevelCode,
  	 MIN( dv.LocalDateTime ) AS \"BeginDateTime\", MAX( dv.LocalDateTime ) AS \"EndDateTime\",
  	 MIN( dv.DateTimeUTC )  AS \"BeginDateTimeUTC\", MAX( dv.DateTimeUTC )  AS \"EndDateTimeUTC\",
  	 COUNT( dv.ValueID ) AS \"ValueCount\" FROM datavalues dv
  	 INNER JOIN variables v ON dv.VariableID = v.VariableID
  	 INNER JOIN units vu ON v.VariableunitsID = vu.UnitsID
  	 INNER JOIN units tu ON v.TimeunitsID = tu.UnitsID
  	 INNER JOIN methods m ON dv.MethodID = m.MethodID
  	 INNER JOIN qualitycontrollevels qc ON dv.QualityControlLevelID = qc.QualityControlLevelID
  	 WHERE dv.SiteID = $siteID
  	 AND dv.VariableID = $variableID
  	 AND dv.MethodID = $methodID
  	 AND dv.SourceID = $sourceID
  	 AND dv.QualityControlLevelID = $qcID
  	 GROUP BY dv.SiteID,
  	 dv.VariableID, v.VariableCode, v.VariableName, v.Speciation,
  	 v.VariableUnitsID, vu.UnitsName,
  	 v.SampleMedium, v.ValueType, v.TimeSupport,
  	 v.TimeUnitsID, tu.UnitsName,
  	 v.DataType, v.GeneralCategory,
  	 m.MethodID, m.MethodDescription,
  	 qc.QualityControlLevelID, qc.QualityControlLevelCode";
  	
  	// run query
  	$valuesresult = mysql_query($mid_query);
  	if (!$valuesresult) {
  	  die("<p>Error in executing the SQL query " . $mid_query . ": " .
  	  mysql_error() . "</p>");
  	}
  	$num_values_rows = mysql_num_rows($valuesresult);
  	if ($num_values_rows == 0) {
  	  return $status;
  	}
  	 
  	// find entries to SeriesCatalog from joining DataValues and other tables
  	$row = mysql_fetch_assoc($valuesresult);
  	$siteID = $row['SiteID'];
  	$variableID = $row['VariableID'];
  	$variableCode = $row['VariableCode'];
  	$variableName = $row['VariableName'];
  	$speciation = $row['Speciation'];
  	$variableUnitsID = $row['VariableUnitsID'];
  	$variableUnitsName = $row['VariableUnitsName'];
  	$sampleMedium = $row['SampleMedium'];
  	$valueType = $row['ValueType'];
  	$timeSupport = $row['TimeSupport'];
  	$timeUnitsID = $row['TimeUnitsID'];
  	$timeUnitsName = $row['TimeUnitsName'];
  	$dataType = $row['DataType'];
  	$generalCategory = $row['GeneralCategory'];
  	$methodID = $row['MethodID'];
  	$methodDescription = $row['MethodDescription'];
  	$qualityControlLevelID = $row['QualityControlLevelID'];
  	$qualityControlLevelCode = $row['QualityControlLevelCode'];
  	$beginDateTime = $row['BeginDateTime'];
  	$endDateTime = $row['EndDateTime'];
  	$beginDateTimeUTC = $row['BeginDateTimeUTC'];
  	$endDateTimeUTC = $row['EndDateTimeUTC'];
  	$valueCount = $row['ValueCount'];
  	 
  	$methodDescription2 = mysql_real_escape_string($methodDescription);
  	
  	// run update
  	$update = "UPDATE seriescatalog SET
  	BeginDateTime = '$beginDateTime', EndDateTime = '$endDateTime',
  	BeginDateTimeUTC = '$beginDateTimeUTC', EndDateTimeUTC = '$endDateTimeUTC', ValueCount = '$valueCount',
  	VariableID = '$variableID', VariableCode = '$variableCode', VariableName = '$variableName', 
  	Speciation = '$speciation', VariableUnitsID = '$variableUnitsID', VariableUnitsName = '$variableUnitsName', 
  	SampleMedium = '$sampleMedium', ValueType = '$valueType', TimeSupport = '$timeSupport', 
  	TimeUnitsID = '$timeUnitsID', TimeUnitsName = '$timeUnitsName', DataType = '$dataType', 
  	GeneralCategory = '$generalCategory',
  	MethodID = '$methodID', MethodDescription = '$methodDescription2', 
  	QualityControlLevelID = '$qualityControlLevelID', QualityControlLevelCode = '$qualityControlLevelCode'
  	 WHERE SeriesID = '$series_id';";
  	 
  	$updateresult = mysql_query($update);
  	if (!$updateresult) {
  		die("<p>Error in executing the SQL query " . $update . ": " .
  				mysql_error() . "</p>");
  	}
  	$status = "1 row updated";
  	return $status;
  	  	
  } else { // UPDATE (ONLY CHANGING ENTRIES ARE UPDATED)
  	
  	//this query is used for updating an existing seriesCatalog entry
  	$short_query =
  	"SELECT MIN( dv.LocalDateTime ) AS \"BeginDateTime\", MAX( dv.LocalDateTime ) AS \"EndDateTime\",
  	MIN( dv.DateTimeUTC )  AS \"BeginDateTimeUTC\", MAX( dv.DateTimeUTC )  AS \"EndDateTimeUTC\",
  	COUNT( dv.ValueID ) AS \"ValueCount\" FROM datavalues dv
  	WHERE
  	dv.SiteID = $siteID AND dv.VariableID = $variableID AND dv.MethodID = $methodID AND dv.SourceID = $sourceID
  	AND dv.QualityControlLevelID = $qcID";
  	
  	// run query
  	$valuesresult = mysql_query($short_query);  	 
  	if (!$valuesresult) {
  		die("<p>Error in executing the SQL query " . $short_query . ": " .
  		  mysql_error() . "</p>");
  	}
  	$num_values_rows = mysql_num_rows($valuesresult); 	
  	if ($num_values_rows == 0) {
  		return $status;
  	}
  	
  	// get values for update
  	$row = mysql_fetch_assoc($valuesresult);
  	$beginDateTime = $row['BeginDateTime'];
  	$endDateTime = $row['EndDateTime'];
  	$beginDateTimeUTC = $row['BeginDateTimeUTC'];
  	$endDateTimeUTC = $row['EndDateTimeUTC'];
  	$valueCount = $row['ValueCount'];
  	
  	// run update
  	$update = "UPDATE seriescatalog SET
  	 BeginDateTime = '$beginDateTime', EndDateTime = '$endDateTime',
  	 BeginDateTimeUTC = '$beginDateTimeUTC', EndDateTimeUTC = '$endDateTimeUTC', ValueCount = '$valueCount'
  	 WHERE SeriesID = '$series_id';";
  	
  	$updateresult = mysql_query($update);
  	if (!$updateresult) {
  		die("<p>Error in executing the SQL query " . $update . ": " .
  				mysql_error() . "</p>");
  	}
  	$status = "1 row updated";
  	return $status; 	
  }
}

function db_find_seriesid($siteID, $variableID, $methodID, $sourceID, $qcID) {
  $query_text = "SELECT SeriesID FROM seriescatalog WHERE 
  SiteID=$siteID AND VariableID=$variableID AND MethodID=$methodID AND SourceID=$sourceID AND QualityControlLevelID=$qcID"; 
  
  $result = mysql_query($query_text);
   
  if (!$result) {
    die("<p>Error in executing the SQL query " . $query_text . ": " . 
	  mysql_error() . "</p>");
  }
  $num_rows = mysql_num_rows($result);
  if ($num_rows == 0) {
    return 0;
  } else {
    $val = mysql_fetch_assoc($result);
	return $val['SeriesID'];
  }
}

// find the SeriesID if the siteID and sourceID are known
function db_find_seriesid2($siteID, $sourceID) {
	$query_text = "SELECT SeriesID FROM seriescatalog WHERE
	SiteID=$siteID AND SourceID=$sourceID AND ValueCount=0";

	$result = mysql_query($query_text);
	 
	if (!$result) {
	  die("<p>Error in executing the SQL query " . $query_text . ": " .
	  mysql_error() . "</p>");
	}
	$num_rows = mysql_num_rows($result);
	if ($num_rows == 1){
		return 0;
	} else {
		$val = mysql_fetch_assoc($result);
		return $val['SeriesID'];
	}
}
