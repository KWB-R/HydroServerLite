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

    while ($row = mysql_fetch_row($result)) {
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
		
		$retval .= "<series>";
		$retval .= variableFromDataRow($row);
		$retval .= "<variable>";
		$retval .= "<variableCode vocabulary=" . to_attribute($serviceCode) . "default=\"true\"" . to_attribute($variableID) . ">";
		$retval .= to_xml("variableName",$variableName);
        $retVal .= to_xml("valueType", $valueType);
        $retVal .= to_xml("dataType", $row["DataType"]);
        $retVal .= to_xml("generalCategory", $row["GeneralCategory"]);
        $retVal .= to_xml("sampleMedium", $row["SampleMedium"]);
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
        $retVal .= to_xml("valueCount", $row["ValueCount"]);
        $retVal .= "<variableTimeInterval xsi:type=\"TimeIntervalType\">";     
        $retVal .= to_xml("beginDateTime", $beginTime);
        $retVal .= to_xml("endDateTime", $endTime);
        $retVal .= to_xml("beginDateTimeUTC", $beginTimeUTC);
        $retVal .= to_xml("endDateTimeUTC", $endTimeUTC);
        $retVal .= "</variableTimeInterval>";
        $retVal .= "<method " . to_attribute(methodID, $row["MethodID"]) . ">";
        $retVal .= to_xml("methodCode", $row["methodCode"]);
        $retVal .= to_xml("methodDescription", $row["methodDescription"]);
        $retVal .= to_xml("methodLink", $row["methodLink"]);
        $retVal .= "</method>";
        $retVal .= "<source " . to_attribute("sourceID", $row["SourceID"]) . ">";
        $retVal .= to_xml("organization", $row["Organization"]);
        $retVal .= to_xml("sourceDescription", $row["SourceDescription"]);
        $retVal .= to_xml("citation", $row["Citation"]);
        $retVal .= "</source>";
        $retVal .= "<qualityControlLevel " . to_attribute("qualityControlLevelID=", $row["QualityControlLevelID"]) . ">";
        $retVal .= to_xml("qualityControlLevelCode", $row["QualityControlLevelCode"]);
        $retVal .= to_xml("definition", $row["Definition"]);
        $retVal .= "</qualityControlLevel>";
        $retVal .= "</series>";
    }
    $retVal .= '</seriesCatalog>';
    return $retVal;
}

function db_GetSitesByQuery($query_text, $siteTag = "siteInfo", $siteTagType = "")
{
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

    while ($row = mysql_fetch_row($result)) {
        $retVal = '';
        $retVal .= "<" . $fullSiteTag . ">";
        $retVal .= to_xml("siteName", $row["SiteName"]);
        $retVal .= '<siteCode network="' . SERVICE_CODE . '">' . $row["SiteCode"] . "</siteCode>";
        $retVal .= "<geoLocation>";
		$retVal .="<geogLocation xsi:type=\"LatLonPointType\">";
        $retVal .= to_xml("latitude", $row["Latitude"]);
		$retVal .= to_xml("longitude", $row["Longitude"]);
		$retVal .= "</geogLocation>";

        //local projection info (optional)
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
        $siteIndex++;
    }
    return $siteArray;
}

function createQuery_GetAllSites()
{
    $query_text =
        'SELECT s.SiteName, s.SiteID, s.SiteCode, s.Latitude, s.Longitude, sr.SRSID, s.LocalX, s.LocalY,
        s.Elevation_m, s.VerticalDatum, s.State, s.County, s.Comments
        FROM ' . get_table_name('Sites') . 's LEFT JOIN ' . get_table_name('SpatialReferences') . 'sr ON s.LocalProjectionID = sr.SpatialReferenceID';
        $query_text = $query_text. " WHERE s.SiteID in (SELECT SiteID FROM " . get_table_name('SeriesCatalog') . ")";
    return $query_text;
}

function createQuery_GetValidSites()
{
    
}

function createQuery_GetSitesByBox($west, $south, $east, $north)
{
    $where = 'AND Longitude >= "' . $west . '" AND Longitude <= "' . $east . '" AND Latitude >= "' . $south . '" AND Latitude <= "' . $north . '"';
    return createQuery_GetAllSites() . $where;
}

function createQuery_GetSiteByCode($shortCode)
{
    $where = 'AND SiteCode = "' . $shortCode . '"';
    return createQuery_GetAllSites() . $where;
}

function createQuery_GetSitesByCodes($fullSiteCodeArray)
{
    //split array of site codes
    $where = 'AND SiteCode IN (';
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

function db_GetSiteByCode($shortCode, $siteTag = "siteInfo", $siteTagType = "")
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
		$retval = "<variable>";
		$retval .= "<variableCode vocabulary=" . to_attribute($serviceCode) . "default=\"true\"" . to_attribute($variableID) . ">";
		$retval .= to_xml("variableName",$variableName);
        $retVal .= to_xml("valueType", $valueType);
        $retVal .= to_xml("dataType", $row["DataType"]);
        $retVal .= to_xml("generalCategory", $row["GeneralCategory"]);
        $retVal .= to_xml("sampleMedium", $row["SampleMedium"]);
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

    //run SQL query
    $query_text =
        'SELECT VariableID, VariableCode, VariableName, ValueType, DataType, GeneralCategory, SampleMedium,
   u1.UnitsName AS "VariableUnitsName", u1.UnitsType AS "VariableUnitsType", u1.UnitsAbbreviation AS "VariableUnitsAbbreviation", 
   VariableUnitsID, NoDataValue, IsRegular, 
   u2.UnitsName AS "TimeUnitsName", u2.UnitsType AS "TimeUnitsType", u2.UnitsAbbreviation AS "TimeUnitsAbbreviation", 
   TimeUnitsID, TimeSupport, Speciation
   FROM ' . get_table_name('Variables') . 'v LEFT JOIN ' .
   get_table_name('Units') . ' u1 ON v.VariableUnitsID = u1.UnitsID LEFT JOIN ' .
   get_table_name('Units') . ' u2 ON v.TimeUnitsID = u2.UnitsID';

    if (!is_null($shortvariablecode)) {
        $query_text .= ' WHERE VariableCode = "' . $shortvariablecode . '"';
    }

    $result = mysql_query($query_text);

    if (!$result) {
        die("<p>Error in executing the SQL query " . $query_text . ": " .
            mysql_error() . "</p>");
    }

    $retVal = '';

    while ($row = mysql_fetch_row($result)) {
        $retVal .= '<variable>';
        $retVal .= '<variableCode vocabulary="' . SERVICE_CODE . '" default="true" variableID="' . $row[0] . '">' . $row[1] . '</variableCode>';
        $retVal .= "<variableName>{$row[2]}</variableName>";
        $retVal .= "<valueType>{$row[3]}</valueType>";
        $retVal .= "<dataType>{$row[4]}</dataType>";
        $retVal .= "<generalCategory>{$row[5]}</generalCategory>";
        $retVal .= "<sampleMedium>{$row[6]}</sampleMedium>";
        $retVal .= "<unit><unitName>{$row[7]}</unitName><unitType>{$row[8]}</unitType><unitAbbreviation>{$row[9]}</unitAbbreviation><unitCode>{$row[10]}</unitCode></unit>";
        $retVal .= "<noDataValue>-9.99</noDataValue>";
        $isRegular = "true";
        if ($row[11] === false) {
            $isRegular = "false";
        }
        $retVal .= '<timeScale isRegular="' . $isRegular . '">';
        $retVal .= "<unit><unitName>{$row[13]}</unitName><unitType>{$row[14]}</unitType><unitAbbreviation>{$row[15]}</unitAbbreviation><unitCode>{$row[16]}</unitCode></unit>";
        $retVal .= "<timeSupport>{$row[12]}</timeSupport>";
        $retVal .= "</timeScale>";
        $retVal .= "<speciation>Not Applicable</speciation>";
        $retVal .= '</variable>';
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

        $row = mysql_fetch_row($result);

        return db_GetValues_OneSeries($row[0], $row[1], $row[2], $row[3], $row[4], $beginTime, $endTime);
    }
    else {

        $row = mysql_fetch_row($result);
        return db_GetValues_MultipleSeries($row[0], $row[1], $beginTime, $endTime);
    }
}

function db_GetValues_OneSeries($siteID, $variableID, $methodID, $sourceID, $qcID, $beginTime, $endTime)
{
    $queryval = 'SELECT LocalDateTime, UTCOffset, DateTimeUTC, DataValue FROM ' . get_table_name('DataValues') . ' WHERE ';
    $queryval .= "SiteID={$siteID} AND VariableID={$variableID} AND MethodID={$methodID} AND SourceID={$sourceID} AND QualityControlLevelID={$qcID}";
    $queryval .= " AND LocalDateTime >= '" . $beginTime . "' AND LocalDateTime <= '" . $endTime . "'";

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

function db_GetValues_MultipleSeries($siteID, $variableID, $beginTime, $endTime)
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

    $retVal .= "<censorCode><censorCode>nc</censorCode><censorCodeDescription>not censored</censorCodeDescription></censorCode>";

    $retVal .= "</values>";
    return $retVal;
}

function db_GetQualityControlLevelByID($qcID)
{
    $query = "SELECT QualityControlLevelCode, Definition, Explanation FROM " . get_table_name("QualityControlLevels") . " WHERE QualityControlLevelID = " . $qcID;
    $result = mysql_query($query);
    if (!$result) {
        die("<p>Error in executing the SQL query " . $query . ": " .
            mysql_error() . "</p>");
    }

    $row = mysql_fetch_row($result);
    $retVal = '<qualityControlLevel qualityControlLevelID="' . $qcID . '">';
    $retVal .= "<qualityControlLevelCode>" . $row[0] . "</qualityControlLevelCode>";
    $retVal .= "<definition>" . $row[1] . "</definition>";
    $retVal .= "<explanation>" . $row[2] . "</explanation>";
    $retVal .= "</qualityControlLevel>";
    return $retVal;
}

function db_GetMethodByID($methodID)
{
    $query = "SELECT MethodDescription, MethodLink FROM " . get_table_name("Methods") . " WHERE MethodID = " . $methodID;
    $result = mysql_query($query);
    if (!$result) {
        die("<p>Error in executing the SQL query " . $query . ": " .
            mysql_error() . "</p>");
    }

    $row = mysql_fetch_row($result);
    $retVal = '<method methodID="' . $methodID . '"><methodCode>' . $methodID . "</methodCode>";
    $retVal .= "<methodDescription>" . $row[0] . "</methodDescription>";
    $retVal .= "<methodLink>" . $row[1] . "</methodLink>";
    $retVal .= "</method>";
    return $retVal;
}

function db_GetSourceByID($sourceID)
{
    $query = "SELECT Organization, SourceDescription, ContactName, Phone, Email, Address, City, State, ZipCode, SourceLink, ";
    $query .= "Citation FROM " . get_table_name('Sources') . " WHERE SourceID = " . $sourceID;
    $result = mysql_query($query);
    if (!$result) {
        die("<p>Error in executing the SQL query " . $query . ": " .
            mysql_error() . "</p>");
    }
    $row = mysql_fetch_row($result);

    $retVal = '<source sourceID="' . $sourceID . '">';
    $retVal .= "<sourceCode>" . $sourceID . "</sourceCode>";
    $retVal .= "<organization>" . $row[0] . "</organization>";
    $retVal .= "<sourceDescription>" . $row[1] . "</sourceDescription>";
    $retVal .= "<contactInformation>";
    $retVal .= "<contactName>" . $row[2] . "</contactName>";
    $retVal .= "<typeOfContact>main</typeOfContact>";
    $retVal .= "<email>" . $row[3] . "</email>";
    $retVal .= "<phone>" . $row[4] . "</phone>";
    $retVal .= '<address xsi:type="xsd:string">' . $row[5] . ", " . $row[6] . ", " . $row[7] . ", " . $row[8];
    $retVal .= "</address></contactInformation>";
    $retVal .= "<sourceLink>" . $row[9] . "</sourceLink>";
    $retVal .= "<citation>" . $row[10] . "</citation>";
    $retVal .= "</source>";
    return utf8_encode($retVal);
}