<?php

require_once 'database_connection.php';

function get_table_name($uppercase_table_name) {
    return '`'. strtolower($uppercase_table_name) .'`';
}

function to_xml($xml_tag, $value) {
   return "<$xml_tag>$value</$xml_tag>";
}

function to_attribute($attribute_name, $value) {
   return "$attribute_name=\"$value\"";
}

function db_GetSeriesCatalog($shortSiteCode)
{
   //get the table names
   $variables_table = get_table_name('Variables');
   $seriescatalog_table = get_table_name('SeriesCatalog');
   $units_table = get_table_name('Units');
   $qc_table = get_table_name('QualityControlLevels');
   $methods_table = get_table_name('Methods');
   
   //run SQL query
    $query_text = "SELECT s.VariableID, s.VariableCode, s.VariableName, s.ValueType, s.DataType, s.GeneralCategory, s.SampleMedium,
   s.VariableUnitsName, u.UnitsType AS \"VariableUnitsType\", u.UnitsAbbreviation AS \"VariableUnitsAbbreviation\", s.VariableUnitsID, 
   v.NoDataValue, v.IsRegular, 
   s.TimeUnitsName, tu.UnitsType AS \"TimeUnitsType\", tu.UnitsAbbreviation AS \"TimeUnitsAbbreviation\", s.TimeUnitsID, 
   s.TimeSupport, s.Speciation, 
   s.ValueCount, s.BeginDateTime, s.EndDateTime, s.BeginDateTimeUTC, s.EndDateTimeUTC, 
   s.SourceID, s.Organization, s.SourceDescription, s.Citation, 
   s.QualityControlLevelID, s.QualityControlLevelCode, qc.Definition, 
   s.MethodID, s.MethodDescription, m.MethodLink
   FROM $seriescatalog_table s 
   LEFT JOIN $variables_table v ON s.VariableID = v.VariableID 
   LEFT JOIN $units_table u ON s.VariableUnitsID = u.UnitsID 
   LEFT JOIN $units_table tu ON s.TimeUnitsID = tu.UnitsID 
   LEFT JOIN $qc_table qc ON s.QualityControlLevelID = qc.QualityControlLevelID 
   LEFT JOIN $methods_table m ON m.MethodID = s.MethodID
   WHERE SiteCode = \"$shortSiteCode\"";

    $result = mysql_query($query_text);

    if (!$result) {
        die("<p>Error in executing the SQL query " . $query_text . ": " .
            mysql_error() . "</p>");
    }

    $retVal = '<seriesCatalog>';

    while ($row = mysql_fetch_assoc($result)) {
		$serviceCode = SERVICE_CODE;
		$variableID = $row["VariableID"];
        $variableName = $row["VariableName"];
		$variableCode = $row["VariableCode"];
		$valueType = $row["ValueType"];
		$dataType = $row["DataType"];
		$generalCategory = $row["GeneralCategory"];
		$sampleMedium = $row["SampleMedium"];
		$isRegular = $row["IsRegular"] ? "true" : "false";
		$beginTime = str_replace(" ", "T", $row["BeginDateTime"]); //1995-01-02T06:00:00
        $endTime = str_replace(" ", "T", $row["EndDateTime"]); //2011-10-01T07:00:00
        $beginTimeUTC = str_replace(" ", "T", $row["BeginDateTimeUTC"]); //1995-01-02T12:00:00
        $endTimeUTC = str_replace(" ", "T", $row["EndDateTimeUTC"]); //2011-10-01T12:00:00
		$methodID = $row["MethodID"];
		
		$retVal .= "<series>";
		$retVal .= variableFromDataRow($row);
        $retVal .= to_xml("valueCount", $row["ValueCount"]);
        $retVal .= "<variableTimeInterval xsi:type=\"TimeIntervalType\">";     
        $retVal .= to_xml("beginDateTime", $beginTime);
        $retVal .= to_xml("endDateTime", $endTime);
        $retVal .= to_xml("beginDateTimeUTC", $beginTimeUTC);
        $retVal .= to_xml("endDateTimeUTC", $endTimeUTC);
        $retVal .= "</variableTimeInterval>";
        $retVal .= "<method " . to_attribute("methodID", $methodID) . ">";
        $retVal .= to_xml("methodCode", $methodID);
        $retVal .= to_xml("methodDescription", $row["MethodDescription"]);
        $retVal .= to_xml("methodLink", $row["MethodLink"]);
        $retVal .= "</method>";
        $retVal .= "<source " . to_attribute("sourceID", $row["SourceID"]) . ">";
        $retVal .= to_xml("organization", $row["Organization"]);
        $retVal .= to_xml("sourceDescription", $row["SourceDescription"]);
        $retVal .= to_xml("citation", $row["Citation"]);
        $retVal .= "</source>";
        $retVal .= "<qualityControlLevel " . to_attribute("qualityControlLevelID", $row["QualityControlLevelID"]) . ">";
        $retVal .= to_xml("qualityControlLevelCode", $row["QualityControlLevelCode"]);
        $retVal .= to_xml("definition", $row["Definition"]);
        $retVal .= "</qualityControlLevel>";
        $retVal .= "</series>";
    }
    $retVal .= '</seriesCatalog>';
    return $retVal;
}

function db_GetSitesByQuery($query_text, $siteTag = "siteInfo", $siteTagType = "") {

	$siteArray[0] = '';
    $result = mysql_query($query_text);

    if (!$result) {
        die("<p>Error in executing the SQL query " . $query_text . ": " .
            mysql_error() . "</p>");
    }
    $siteIndex = 0;

    $fullSiteTag = $siteTag;
    if ($siteTagType != "") {
        $fullSiteTag = $siteTag . ' xsi:type="' . $siteTagType . '"';
    }

    while ($row = mysql_fetch_assoc($result)) {
        $retVal = '';
        $retVal .= "<" . $fullSiteTag . ">";
        $retVal .= to_xml("siteName", $row["SiteName"]);
        $retVal .= '<siteCode network="' . SERVICE_CODE . '">' . $row["SiteCode"] . "</siteCode>";
        $retVal .= "<geoLocation>";
		$retVal .="<geogLocation xsi:type=\"LatLonPointType\">";
        $retVal .= to_xml("latitude", $row["Latitude"]);
		$retVal .= to_xml("longitude", $row["Longitude"]);
		$retVal .= "</geogLocation>";

        // local projection info (optional)
        $localProjectionID = $row["LocalProjectionID"];
        $localX = $row["LocalX"];
        $localY = $row["LocalY"];
        if ($localProjectionID != '' and $localX != '' and $localY != '') {
            $retVal .= '<localSiteXY projectionInformation="' . $localProjectionID . '" >';
            $retVal .= '<X>' . $localX . '</X><Y>' . $localY . '</Y></localSiteXY>';
        }

        $retVal .= "</geoLocation>";

        $elevation_m = $row["Elevation_m"];
        if ($elevation_m != '') {
            $retVal .= to_xml("elevation_m", $elevation_m);
        }
        $verticalDatum = $row["VerticalDatum"];
        if ($verticalDatum != '') {
            $retVal .= to_xml("verticalDatum", $verticalDatum);
        }
        $retVal .= "</" . $siteTag . ">";       
		$siteArray[$siteIndex] = $retVal;
		$siteIndex++;
    }
    return $siteArray;
}

function createQuery_GetAllSites()
{
    $sr_table = get_table_name('SpatialReferences');
	$sites_table = get_table_name('Sites');
	$series_catalog_table = get_table_name('SeriesCatalog');
	
	$query_text =
        'SELECT s.SiteName, s.SiteID, s.SiteCode, s.Latitude, s.Longitude, sr.SRSID, s.LocalProjectionID, s.LocalX, s.LocalY,
        s.Elevation_m, s.VerticalDatum, s.State, s.County, s.Comments
        FROM ' . $sites_table . ' s LEFT JOIN ' . $sr_table . ' sr ON s.LocalProjectionID = sr.SpatialReferenceID';
        $query_text = $query_text. " WHERE s.SiteID in (SELECT SiteID FROM " . $series_catalog_table . ")";
    return $query_text;
}

function createQuery_GetValidSites()
{
    
}

function createQuery_GetSitesByBox($west, $south, $east, $north)
{
    $where = ' AND Longitude >= "' . $west . '" AND Longitude <= "' . $east . '" AND Latitude >= "' . $south . '" AND Latitude <= "' . $north . '"';
    return createQuery_GetAllSites() . $where;
}

function createQuery_GetSiteByCode($shortCode)
{
    $where = ' AND SiteCode = "' . $shortCode . '"';
    return createQuery_GetAllSites() . $where;
}

function createQuery_GetSitesByCodes($fullSiteCodeArray)
{
    //split array of site codes
    $where = ' AND SiteCode IN (';
    foreach ($fullSiteCodeArray as $fullCode) {
        $split = explode(":", $fullCode);
        $shortCode = $split[1];
        $where .= '"' . $shortCode . '",';
    }
    $whereStr = substr($where, 0, strlen($where) - 1);
    $whereStr .= ")";

    //run SQL query
    $query_text = createQuery_GetAllSites() . $whereStr;
    return $query_text;
}

function db_GetSiteByCode($shortCode, $siteTag, $siteTagType)
{
    $query_text = createQuery_GetSiteByCode($shortCode);
    $sitesArray = db_GetSitesByQuery($query_text, $siteTag, $siteTagType);
    return $sitesArray[0]; //what if no site is found?
}

function db_GetSiteByID($siteID, $siteTag = "siteInfo", $siteTagType = "")
{
    $query_text = createQuery_GetSiteByID($siteID);
    $sitesArray = db_GetSitesByQuery($query_text, $siteTag, $siteTagType);
    return $sitesArray[0]; //what if no site is found?
}

function db_GetSites()
{
    $query_text = createQuery_GetAllSites();
    $sitesArray = db_GetSitesByQuery($query_text);
    $retVal = '';

    foreach ($sitesArray as $site) {
        $retVal .= "<site>";
        $retVal .= $site;
        $retVal .= "</site>";
    }
    return $retVal;
}

function db_GetSitesByCodes($fullSiteCodeArray)
{
    $query_text = createQuery_GetSitesByCodes($fullSiteCodeArray);
    $sitesArray = db_GetSitesByQuery($query_text);
    $retVal = '';
    foreach ($sitesArray as $site) {
        $retVal .= "<site>";
        $retVal .= $site;
        $retVal .= "</site>";
    }
    return $retVal;
}

function db_GetSitesByBox($west, $south, $east, $north)
{
    $query_text = createQuery_GetSitesByBox($west, $south, $east, $north);
    $sitesArray = db_GetSitesByQuery($query_text);
    $retVal = '';
    foreach ($sitesArray as $site) {
        $retVal .= "<site>";
        $retVal .= $site;
        $retVal .= "</site>";
    }
    return $retVal;
}

function db_GetVariableCodesBySite($shortSiteCode) {
    $query_text =
        'SELECT VariableCode FROM ' . get_table_name('SeriesCatalog') . ' WHERE SiteCode = "' . $shortSiteCode . '"';
    $result = mysql_query($query_text);

    if (!$result) {
        die("<p>Error in executing the SQL query " . $query_text . ": " .
            mysql_error() . "</p>");
    }
    $retVal = array();
    $nr = 0;
    while ($ret = mysql_fetch_array($result)) {
        $retVal[$nr] = $ret[0];
        $nr++;
    }
    return $retVal;
}

function variableFromDataRow($row) {
		
    $variableID = $row["VariableID"];
    $variableName = $row["VariableName"];
    $variableCode = $row["VariableCode"];
	$valueType = $row["ValueType"];
	$dataType = $row["DataType"];
	$generalCategory = $row["GeneralCategory"];
	$sampleMedium = $row["SampleMedium"];
	$isRegular = $row["IsRegular"] ? "true" : "false";
		
	$retVal = "<variable>";
	$retVal .= "<variableCode vocabulary=\"" . SERVICE_CODE . "\" default=\"true\" variableID=\"" . $variableID . "\" >" . $variableCode . "</variableCode>";
	$retVal .= to_xml("variableName",$variableName);
    $retVal .= to_xml("valueType", $valueType);
    $retVal .= to_xml("dataType", $dataType);
    $retVal .= to_xml("generalCategory", $generalCategory);
    $retVal .= to_xml("sampleMedium", $sampleMedium);
    $retVal .= "<unit>";
    $retVal .= to_xml("unitName",$row["VariableUnitsName"]);
	$retVal .= to_xml("unitType", $row["VariableUnitsType"]);
    $retVal .= to_xml("unitAbbreviation", $row["VariableUnitsAbbreviation"]);
    $retVal .= to_xml("unitCode", $row["VariableUnitsID"]);
	$retVal .= "</unit>";
    $retVal .= to_xml("noDataValue", $row["NoDataValue"]);
    $retVal .= "<timeScale " . to_attribute("isRegular", $isRegular) . ">";
    $retVal .= "<unit>";
    $retVal .= to_xml("unitName", $row["TimeUnitsName"]);
	$retVal .= to_xml("unitType", $row["TimeUnitsType"]);
    $retVal .= to_xml("unitAbbreviation", $row["TimeUnitsAbbreviation"]);
    $retVal .= to_xml("unitCode", $row["TimeUnitsID"]);
	$retVal .= "</unit>";
    $retVal .= to_xml("timeSupport",$row["TimeSupport"]);
    $retVal .= "</timeScale>";
    $retVal .= to_xml("speciation", $row["Speciation"]);
    $retVal .= "</variable>";	
	return $retVal;
}

function db_GetVariableByCode($shortvariablecode = NULL)
{
    $variables_table = get_table_name('Variables');
	$units_table = get_table_name('Units');
    //run SQL query
    $query_text =
        'SELECT VariableID, VariableCode, VariableName, ValueType, DataType, GeneralCategory, SampleMedium,
   u1.UnitsName AS "VariableUnitsName", u1.UnitsType AS "VariableUnitsType", u1.UnitsAbbreviation AS "VariableUnitsAbbreviation", 
   VariableUnitsID, NoDataValue, IsRegular, 
   u2.UnitsName AS "TimeUnitsName", u2.UnitsType AS "TimeUnitsType", u2.UnitsAbbreviation AS "TimeUnitsAbbreviation", 
   TimeUnitsID, TimeSupport, Speciation
   FROM ' . $variables_table . 'v LEFT JOIN ' .
   $units_table . ' u1 ON v.VariableUnitsID = u1.UnitsID LEFT JOIN ' .
   $units_table . ' u2 ON v.TimeUnitsID = u2.UnitsID';

    if (!is_null($shortvariablecode)) {
        $query_text .= ' WHERE VariableCode = "' . $shortvariablecode . '"';
    }

    $result = mysql_query($query_text);

    if (!$result) {
        die("<p>Error in executing the SQL query " . $query_text . ": " .
            mysql_error() . "</p>");
    }

    $retVal = '';

    while ($row = mysql_fetch_assoc($result)) {
	    $retVal .= variableFromDataRow($row);
    }
    return $retVal;
}

function createQuery_TimeRange($startTime, $endTime)
{
    //time range query..
    $query = "( (BeginDateTime <= '" . $startTime . "' AND EndDateTime >= '" . $endTime . "' )";
    $query .= " OR (BeginDateTime >= '" . $startTime . "' AND BeginDateTime <= '" . $endTime . "' )";
    $query .= " OR (EndDateTime >= '" . $startTime . "' AND EndDateTime <= '" . $endTime . "') )";
    return $query;
}

function db_GetValues($siteCode, $variableCode, $beginTime, $endTime)
{
    //first get the metadata
    $querymeta = 'SELECT SiteID, VariableID, MethodID, SourceID, QualityControlLevelID FROM ' . get_table_name('SeriesCatalog');
    $querymeta .= ' WHERE SiteCode = "' . $siteCode . '" AND VariableCode = "' . $variableCode . '" AND ';
    $querymeta .= createQuery_TimeRange($beginTime, $endTime);
	
    $result = mysql_query($querymeta);

    if (!$result) {
        die("<p>Error in executing the SQL query " . $querymeta . ": " .
            mysql_error() . "</p>");
    }

    $numSeries = mysql_num_rows($result);

    if ($numSeries == 0) {
        return "<values />";
    }
    else if ($numSeries == 1) {

        $row = mysql_fetch_assoc($result);

        return db_GetValues_OneSeries($row["SiteID"], $row["VariableID"], $row["MethodID"], $row["SourceID"], $row["QualityControlLevelID"], $beginTime, $endTime);
    }
    else {
		$row = mysql_fetch_assoc($result);
		$siteID = $row["SiteID"];
		$variableID = $row["VariableID"];
		
		$method_array[0] = $row["MethodID"];
		$source_array[0] = $row["SourceID"];
		$qc_array[0] = $row["QualityControlLevelID"];
		$method_index = 0;
		$source_index = 0;
		$qc_index = 0;
		
        while($row = mysql_fetch_assoc($result)) {
		  $last_methodID = $method_array[$method_index];
		  if ($row["MethodID"] != $last_methodID) {
		    $method_index++;
			$method_array[$method_index] = $row["MethodID"];
		  }
		  $last_sourceID = $source_array[$source_index];
		  if ($row["SourceID"] != $last_sourceID) {
		    $source_index++;
			$source_array[$source_index] = $row["SourceID"];
		  }
		  $last_qcID = $qc_array[$qc_index];
		  if ($row["QualityControlLevelID"] != $last_qcID) {
		    $qc_index++;
			$qc_array[$qc_index] = $row["QualityControlLevelID"];
		  }
		}
        return db_GetValues_MultipleSeries($siteID, $variableID, $method_array, $source_array, $qc_array, $beginTime, $endTime);
    }
}

function db_GetValues_OneSeries($siteID, $variableID, $methodID, $sourceID, $qcID, $beginTime, $endTime)
{
    $data_values_table = get_table_name('DataValues');
	$queryval = 'SELECT LocalDateTime, UTCOffset, DateTimeUTC, DataValue FROM ' . $data_values_table . ' WHERE ';
    $queryval .= "SiteID={$siteID} AND VariableID={$variableID} AND MethodID={$methodID} AND SourceID={$sourceID} AND QualityControlLevelID={$qcID}";
    $queryval .= " AND LocalDateTime >= '" . $beginTime . "' AND LocalDateTime <= '" . $endTime . "'";
	$queryval .= " ORDER BY LocalDateTime";

    $result = mysql_query($queryval);
    if (!$result) {
        die("<p>Error in executing the SQL query " . $queryval . ": " .
            mysql_error() . "</p>");
    }
    $retVal = "<values>";
    $metadata = 'methodCode="' . $methodID . '" sourceCode="' . $sourceID . '" qualityControlLevelCode="' . $qcID . '"';
    while ($row = mysql_fetch_row($result)) {
        $retVal .= '<value censorCode="nc" dateTime="' . $row[0] . '"';
        $retVal .= ' timeOffset="' . $row[1] . '" dateTimeUTC="' . $row[2] . '" ';
        $retVal .= $metadata;
        $retVal .= ">{$row[3]}</value>";
    }
    $retVal .= db_GetQualityControlLevelByID($qcID);
    $retVal .= db_GetMethodByID($methodID);
    $retVal .= db_GetSourceByID($sourceID);

    $retVal .= "<censorCode><censorCode>nc</censorCode><censorCodeDescription>not censored</censorCodeDescription></censorCode>";

    $retVal .= "</values>";

    return $retVal;
}

function db_GetValues_MultipleSeries($siteID, $variableID, $method_array, $source_array, $qc_array, $beginTime, $endTime)
{
    $queryval = "SELECT LocalDateTime, UTCOffset, DateTimeUTC, MethodID, SourceID, QualityControlLevelID, DataValue FROM " . get_table_name('DataValues') . ' WHERE ';
    $queryval .= "SiteID={$siteID} AND VariableID={$variableID}";
    $queryval .= " AND LocalDateTime >= '" . $beginTime . "' AND LocalDateTime <= '" . $endTime . "'";

    $result = mysql_query($queryval);
    if (!$result) {
        die("<p>Error in executing the SQL query " . $queryval . ": " .
            mysql_error() . "</p>");
    }
    $retVal = "<values>";
    //$metadata = 'methodCode="' . $methodID . '" sourceCode="' . $sourceID . '" qualityControlLevelCode="' . $qcID . '"';
    while ($row = mysql_fetch_row($result)) {
        $retVal .= '<value censorCode="nc" dateTime="' . $row[0] . '"';
        $retVal .= ' timeOffset="' . $row[1] . '" dateTimeUTC="' . $row[2] . '"';
        $retVal .= ' methodCode="' . $row[3] . '" ';
        $retVal .= ' sourceCode="' . $row[4] . '" ';
        $retVal .= ' qualityControlLevelCode="' . $row[5] . '" ';
        $retVal .= ">{$row[6]}</value>";
    }
	
	foreach ($qc_array as $qcID) {
        $retVal .= db_GetQualityControlLevelByID($qcID);
    }
	foreach ($method_array as $methodID) {
        $retVal .= db_GetMethodByID($methodID);
    }
	foreach ($source_array as $sourceID) {
        $retVal .= db_GetSourceByID($sourceID);
    }

    $retVal .= "<censorCode><censorCode>nc</censorCode><censorCodeDescription>not censored</censorCodeDescription></censorCode>";
    
	//add more vals!
	
	
    $retVal .= "</values>";
    return $retVal;
}

function db_GetQualityControlLevelByID($qcID)
{
    $qc_table = get_table_name("QualityControlLevels");
	$query = "SELECT QualityControlLevelCode, Definition, Explanation FROM " . $qc_table . " WHERE QualityControlLevelID = " . $qcID;
    $result = mysql_query($query);
    if (!$result) {
        die("<p>Error in executing the SQL query " . $query . ": " .
            mysql_error() . "</p>");
    }

    $row = mysql_fetch_assoc($result);
    $retVal = '<qualityControlLevel qualityControlLevelID="' . $qcID . '">';
    $retVal .= "<qualityControlLevelCode>" . $row["QualityControlLevelCode"] . "</qualityControlLevelCode>";
    $retVal .= "<definition>" . $row["Definition"] . "</definition>";
    $retVal .= "<explanation>" . $row["Explanation"] . "</explanation>";
    $retVal .= "</qualityControlLevel>";
    return $retVal;
}

function db_GetMethodByID($methodID)
{
    $method_table = get_table_name("Methods");
	$query = "SELECT MethodDescription, MethodLink FROM " . $method_table . " WHERE MethodID = " . $methodID;
    $result = mysql_query($query);
    if (!$result) {
        die("<p>Error in executing the SQL query " . $query . ": " .
            mysql_error() . "</p>");
    }

    $row = mysql_fetch_assoc($result);
    $retVal = '<method methodID="' . $methodID . '"><methodCode>' . $methodID . "</methodCode>";
    $retVal .= "<methodDescription>" . $row["MethodDescription"] . "</methodDescription>";
    $retVal .= "<methodLink>" . $row["MethodLink"] . "</methodLink>";
    $retVal .= "</method>";
    return $retVal;
}

function db_GetSourceByID($sourceID)
{
    $sources_table = get_table_name('Sources');
	$query = "SELECT Organization, SourceDescription, ContactName, Phone, Email, Address, City, State, ZipCode, SourceLink, ";
    $query .= "Citation FROM " . $sources_table . " WHERE SourceID = " . $sourceID;
    $result = mysql_query($query);
    if (!$result) {
        die("<p>Error in executing the SQL query " . $query . ": " .
            mysql_error() . "</p>");
    }
    $row = mysql_fetch_assoc($result);

    $retVal = '<source sourceID="' . $sourceID . '">';
    $retVal .= "<sourceCode>" . $sourceID . "</sourceCode>";
    $retVal .= "<organization>" . $row["Organization"] . "</organization>";
    $retVal .= "<sourceDescription>" . $row["SourceDescription"] . "</sourceDescription>";
    $retVal .= "<contactInformation>";
    $retVal .= "<contactName>" . $row["ContactName"] . "</contactName>";
    $retVal .= "<typeOfContact>main</typeOfContact>";
    $retVal .= "<email>" . $row["Email"] . "</email>";
    $retVal .= "<phone>" . $row["Phone"] . "</phone>";
    $retVal .= '<address xsi:type="xsd:string">' . $row["Address"] . ", " . $row["City"] . ", " . $row["State"] . ", " . $row["ZipCode"];
    $retVal .= "</address></contactInformation>";
    $retVal .= "<sourceLink>" . $row["SourceLink"] . "</sourceLink>";
    $retVal .= "<citation>" . $row["Citation"] . "</citation>";
    $retVal .= "</source>";
    return $retVal;
}