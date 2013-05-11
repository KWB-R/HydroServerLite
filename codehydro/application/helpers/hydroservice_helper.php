<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('write_XML_header')) {

    function write_XML_header()
	{
	    header("Content-type: text/xml; charset=utf-8'");
	    echo chr(60) . chr(63) . 'xml version="1.0" encoding="utf-8" ' . chr(63) . chr(62);
	}
}

if (!function_exists('wof_GetVariables')) {

    function wof_GetVariables() {
	  	$retVal = '<variablesResponse xmlns="http://www.cuahsi.org/waterML/1.1/">';
	  	$retVal .= wof_queryInfo_variables();
	  	$retVal .= '<variables>';
	  	$retVal .= db_GetVariableByCode(NULL);
	  	$retVal .= '</variables></variablesResponse>';	  
	  	return $retVal;
	}

}

if (!function_exists('wof_queryInfo_variables')) {

    function wof_queryInfo_variables() {
	  	$retVal = '<queryInfo><creationTime>' . date('c') . '</creationTime>';
	  	$retVal .= '<criteria MethodCalled="GetVariables"><parameter name="variable" value="" />';
	  	$retVal .= "</criteria></queryInfo>";
	  	return $retVal;  
	}
}

if (!function_exists('wof_start')) {

    //this function writes the header, the xml declaration and the SOAP:Envelope elements
	function wof_start() {
  		//Set the content-type header to xml
  		header("Content-type: text/xml");
  		//echo the XML declaration
  		echo chr(60).chr(63).'xml version="1.0" encoding="utf-8" '.chr(63).chr(62);
  		echo '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body>';
	}
}

if (!function_exists('wof_finish')) {

    function wof_finish() {
  		echo '</soap:Body></soap:Envelope>';
	}
}

if (!function_exists('GetVariablesObject')) {

    function GetVariablesObject() {
    	wof_start();
	  	echo '<GetVariablesObjectResponse xmlns="http://www.cuahsi.org/his/1.1/ws/">';
	  	echo wof_GetVariables();
	  	echo '</GetVariablesObjectResponse>';
	  	wof_finish();
    }
}


if (!function_exists('db_GetVariableByCode')) {

    function db_GetVariableByCode($shortvariablecode = NULL)
	{
		$ci = &get_instance();
    	$ci->db->select("VariableID, VariableCode, VariableName, ValueType, DataType, GeneralCategory, SampleMedium,
u1.UnitsName AS \"VariableUnitsName\", u1.UnitsType AS \"VariableUnitsType\", u1.UnitsAbbreviation AS \"VariableUnitsAbbreviation\", VariableUnitsID, NoDataValue, IsRegular, u2.UnitsName AS \"TimeUnitsName\", u2.UnitsType AS \"TimeUnitsType\", u2.UnitsAbbreviation AS \"TimeUnitsAbbreviation\", TimeUnitsID, TimeSupport, Speciation");
    	$ci->db->join("units u1","v.VariableUnitsID = u1.UnitsID","left");
    	$ci->db->join("units u2","v.TimeUnitsID = u2.UnitsID","left");

		if (isset($shortvariablecode)) {
			$ci->db->where("VariableCode",$shortvariablecode);
		}

		$retVal = '';

  		$vars = $ci->db->get("variables v");

		if ($vars->num_rows() > 0) {
			foreach($vars->result_array() as $row) {
				$retVal .= variableFromDataRow($row);
			}
		} else {
			die("<p>Error in executing the SQL query \"db_GetVariableByCode\": " .
	            mysql_error() . "</p>");
		}

	    return $retVal;
	}
}

if (!function_exists('variableFromDataRow')) {

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
}

if (!function_exists('to_xml')) {

    function to_xml($xml_tag, $value) {
   	return "<$xml_tag>$value</$xml_tag>";
	}
}

if (!function_exists('to_attribute')) {

    function to_attribute($attribute_name, $value) {
   		return "$attribute_name=\"$value\"";
	}
}

if (!function_exists('GetSites')) {

    function GetSites() {
  		wof_start();
  		echo '<GetSitesResponse xmlns="http://www.cuahsi.org/his/1.1/ws/"><GetSitesResult>';
  		echo htmlspecialchars(wof_GetSites());
  		echo '</GetSitesResult></GetSitesResponse>';
  		wof_finish();
	}
}

if (!function_exists('wof_GetSites')) {

    function wof_GetSites() {
  		$retVal = '<sitesResponse xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.cuahsi.org/waterML/1.1/">';
  		$retVal .= wof_queryInfo_GetSites();
  		$retVal .= db_GetSites();  
  		$retVal .= '</sitesResponse>';
  		return $retVal;
	}
}

if (!function_exists('wof_queryInfo_GetSites')) {

    function wof_queryInfo_GetSites($site = null) {
  		$retVal = '<queryInfo><creationTime>' . date('c') . '</creationTime>';
  		$retVal .= '<criteria MethodCalled="GetSites">';
  		$retVal .= '<parameter name="site" value="ALL SITES" /></criteria></queryInfo>';
  		return $retVal;
	}
}

if (!function_exists('db_GetSites')) {

    function db_GetSites() {
    	$sitesArray = query_GetAllSites();
    	$retVal = '';

    	foreach ($sitesArray as $site) {
        	$retVal .= "<site>";
        	$retVal .= $site;
        	$retVal .= "</site>";
    	}
    	return $retVal;
	}
}

if (!function_exists('query_GetAllSites')) {

    function query_GetAllSites() {
		$ci = &get_instance();
		$ci->db->distinct();
    	$ci->db->select("s.SiteName, s.SiteID, s.SiteCode, s.Latitude, s.Longitude, sr.SRSID, s.LocalProjectionID, s.LocalX, s.LocalY, s.Elevation_m, s.VerticalDatum, s.State, s.County, s.Comments");
    	$ci->db->join("seriescatalog sc","s.SiteID = sc.SiteID");
    	$ci->db->join("spatialreferences sr","s.LocalProjectionID = sr.SpatialReferenceID","left");

		if (isset($_GET["site"])) {
			$siteCode = str_replace(SERVICE_CODE.":","",$_GET["site"]);
			$ci->db->where("s.SiteCode",$siteCode);
		}

  		$sites = $ci->db->get("sites s");

		$siteArray[0] = '';

		if ($sites->num_rows() > 0) {
	    	$siteIndex = 0;

	    	$fullSiteTag = "siteInfo";

			foreach($sites->result_array() as $row) {
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
	        	$retVal .= "</siteInfo>";       
				$siteArray[$siteIndex] = $retVal;
				$siteIndex++;
			}
		} else {
			die("<p>Error in executing the SQL query \"query_GetAllSites\": " .
	            mysql_error() . "</p>");
		}

    	return $siteArray;
	}
}