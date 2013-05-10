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
    	$ci->db->join("Units u1","v.VariableUnitsID = u1.UnitsID","left");
    	$ci->db->join("Units u2","v.TimeUnitsID = u2.UnitsID","left");

		if (isset($shortvariablecode)) {
			$ci->db->where("VariableCode",$shortvariablecode);
		}

		$retVal = '';

  		$vars = $ci->db->get("Variables v");

		if ($vars->num_rows() > 0) {
			foreach($vars->result_array() as $row) {
				$retVal .= variableFromDataRow($row);
			}
		} else {
			die("<p>Error in executing the SQL query " . $query_text . ": " .
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