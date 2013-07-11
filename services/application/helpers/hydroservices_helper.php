<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//run the service
if (!function_exists('RunService')) {

    function RunService() {
    	$ci = &get_instance();    	

		$wsdl_is_set = (isset($_REQUEST['wsdl']) or isset($_REQUEST['WSDL']));

		//if the POST request contains the SoapACTION parameter
		$action_is_set = (isset($_SERVER['HTTP_SOAPACTION']));

		//if the query string contains ?op
		$op_is_set = (isset($_REQUEST['op']));

		//when the SOAPAction is set, call the appropriate web method
		if ($action_is_set == 1) {
			$action_name = $_SERVER['HTTP_SOAPACTION'];
			$action_name2 = str_replace('"', "", $action_name); //removes quotes
			$action = substr($action_name2, strlen('http://www.cuahsi.org/his/1.1/ws/'));

			//read the input parameters
			$postdata = file_get_contents('php://input');
			$authtoken = wof_read_authtoken($postdata);

			//load authToken
		    $validToken = $ci->config->item('auth_token');

			if ($validToken == "" || ($validToken != "" && $validToken == $authtoken)) {
				header('Content-Type: text/html; charset=utf-8');

				if ($action == 'GetSiteInfo') {
	  				$site = wof_read_parameter($postdata, 'site');
	  				GetSiteInfo($site);
	  				exit;
				} elseif ($action == 'GetSiteInfoMultpleObject') {
	  				$site = wof_read_array_parameter($postdata, 'site');
	  				$siteArray = $site == ""? array():explode(",",$site);
	  				GetSiteInfoMultpleObject($siteArray);
	  				exit;
				} elseif ($action == 'GetSiteInfoObject') {
	  				$site = wof_read_parameter($postdata, 'site');
	  				GetSiteInfoObject($site);
	  				exit;
				} elseif ($action == 'GetSites') {
	  				$site = wof_read_array_parameter($postdata, 'site');
	  				$siteArray = $site == ""? array():explode(",",$site);
	  				GetSites($siteArray);
	  				exit;
				} elseif ($action == 'GetSitesByBoxObject') {
	  				$north = wof_read_parameter($postdata, 'north');
	  				$south = wof_read_parameter($postdata, 'south');
	  				$east =  wof_read_parameter($postdata, 'east');
	  				$west = wof_read_parameter($postdata, 'west');
	  				$IncludeSeries = wof_read_parameter($postdata, 'includeseries');
	  				GetSitesByBoxObject($north, $south, $east, $west, $IncludeSeries);
	  				exit;
				} elseif ($action == 'GetSitesObject') {
	  				$site = wof_read_array_parameter($postdata, 'site');
	  				$siteArray = $site == ""? array():explode(",",$site);
	  				GetSitesObject($siteArray);
	  				exit;
				} elseif ($action == 'GetValues') {
	  				$location = wof_read_parameter($postdata, 'location');
	  				$variable = wof_read_parameter($postdata, 'variable');
	  				$startDate =  wof_read_parameter($postdata, 'startDate');
	  				$endDate = wof_read_parameter($postdata, 'endDate');
	  				GetValues($location, $variable, $startDate, $endDate);
	  				exit;
				} elseif ($action == 'GetValuesForASiteObject') {
	  				$site = wof_read_parameter($postdata, 'site');
	  				$startDate =  wof_read_parameter($postdata, 'startDate');
	  				$endDate = wof_read_parameter($postdata, 'endDate');
	  				GetValuesForASiteObject($site, $startDate, $endDate);
	  				exit;
				} elseif ($action == 'GetValuesObject') {
	  				$location = wof_read_parameter($postdata, 'location');
	  				$variable = wof_read_parameter($postdata, 'variable');
	  				$startDate =  wof_read_parameter($postdata, 'startDate');
	  				$endDate = wof_read_parameter($postdata, 'endDate');
	  				GetValuesObject($location, $variable, $startDate, $endDate);
	  				exit;
				} elseif ($action == 'GetVariableInfo') {
	  				$variable = wof_read_parameter($postdata, 'variable');
	  				GetVariableInfo($variable);
	  				exit;
				} elseif ($action == 'GetVariableInfoObject') {
	  				$variable = wof_read_parameter($postdata, 'variable');
	  				GetVariableInfoObject($variable);
	  				exit;
				} elseif ($action == 'GetVariables') {
	  				GetVariables();
	  				exit;
				} elseif ($action == 'GetVariablesObject') {
	  				GetVariablesObject();
	  				exit;
				} else {
	  				echo "ACTION NOT FOUND!! " . $action;
	  				exit;
				}
			} else {
				header("Status: 401 Unauthorized");
				header("Content-type: text/plain");
				echo "HTTP/1.0 401 Unauthorized";
			}
		} elseif ($op_is_set == 1) { //when the op parameter is set, return the web method test page
			$operation_name = $_REQUEST['op'];
			$name_file = APPPATH.'views/operations/operation_' . $operation_name . '.html';
			$name = @implode ('', @file (APPPATH.'views/operations/operation_' . $operation_name . '.html'));

			// send the right headers
			$complete_uri = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
			$absolute_uri = "http://" . substr($complete_uri, 0, strrpos($complete_uri, '/')) . "/cuahsi_1_1.asmx";
			$pattern = "/ABSOLUTEURI_TO_REPLACE/";
			$name2 = preg_replace($pattern, $absolute_uri, $name);
			header("Content-Type: text/html");
			header("Content-Length: " . filesize($name_file));
			echo $name2;
			exit;
		} elseif ($wsdl_is_set == 1) { //when the WSDL query string document is set, return the WSDL
			// Return the WSDL
			$wsdl = @implode ('', @file (APPPATH.'views/wateroneflow.wsdl'));
			if (strlen($wsdl) > 1) {
  				//replace the absolute uri
  				//$absolute_uri = "http://localhost:333/HIS/hydroserver/webservice/cuahsi_1_1.php";
  				$complete_uri = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
  				$absolute_uri = "http://" . substr($complete_uri, 0, strrpos($complete_uri, '/')) . "/cuahsi_1_1.asmx";
  				$pattern = "/ABSOLUTEURI_TO_REPLACE/";
  				$wsdl2 = preg_replace($pattern, $absolute_uri, $wsdl);
  				header("Content-type: text/xml");
  				echo $wsdl2;
  				exit;
			} else {
  				header("Status: 500 Internal Server Error");
  				header("Content-type: text/plain");
  				echo "HTTP/1.0 500 Internal Server Error";
			}
		} else {
  			header("Content-Type: text/html");
  			$asmx_file_name = APPPATH.'views/asmx_page.html';
  			header("Content-Length: " . filesize($asmx_file_name));
  			header("File-Name: " . $asmx_file_name);

  			// display the file and stop this script
  			readfile($asmx_file_name);
  			exit;
		}
	}
}
// end of run service

// web tester
//get random site
if (!function_exists('get_random_site')) {

    function get_random_site() {
		$ci = &get_instance();

		$sites_table = get_table_name('Sites');
		$series_catalog_table = get_table_name('SeriesCatalog');

		$siteSC = $ci->db->select("SiteID")->get($series_catalog_table);

		if ($siteSC->num_rows() > 0) {
			$where = '';
		    foreach ($siteSC->result_array() as $row) {
		        $where .= '"' . $row["SiteID"] . '",';
		    }
		    $whereID = "(".substr($where, 0, strlen($where) - 1).")";
	    }

		$ci->db->distinct();
		$ci->db->select("SiteCode");

		if (isset($whereID)) {
			$ci->db->where("SiteID IN",$whereID,FALSE);
		}

		$result = $ci->db->get($sites_table);

		$sites = array();
		$i = 0;
		foreach($result->result_array() as $row) {
			$sites[$i] = $row["SiteCode"];
			$i++;
		}

		return $sites[rand(0,(count($sites)-1))];
	}
}

//get random site
if (!function_exists('get_random_site_coordinate')) {

    function get_random_site_coordinate() {
		$ci = &get_instance();

		$sites_table = get_table_name('Sites');
		$series_catalog_table = get_table_name('SeriesCatalog');

		$siteSC = $ci->db->select("SiteID")->get($series_catalog_table);

		if ($siteSC->num_rows() > 0) {
			$where = '';
		    foreach ($siteSC->result_array() as $row) {
		        $where .= '"' . $row["SiteID"] . '",';
		    }
		    $whereID = "(".substr($where, 0, strlen($where) - 1).")";
	    }

		$ci->db->distinct();
		$ci->db->select("Latitude,Longitude");

		if (isset($whereID)) {
			$ci->db->where("SiteID IN",$whereID,FALSE);
		}

		$result = $ci->db->get($sites_table);

		$sites = array();
		$i = 0;
		foreach($result->result_array() as $row) {
			$sites[$i] = $row;
			$i++;
		}

		return $sites[rand(0,(count($sites)-1))];
	}
}

//get random variable
if (!function_exists('get_random_variable')) {

    function get_random_variable() {
		$ci = &get_instance();

		$variables_table = get_table_name('Variables');

		$ci->db->select("VariableCode");

		$result = $ci->db->get($variables_table);

		$vars = array();
		$i = 0;
		foreach($result->result_array() as $row) {
			$vars[$i] = $row["VariableCode"];
			$i++;
		}

		return $vars[rand(0,(count($vars)-1))];
	}
}
//end of web tester

//re-write from wof
if (!function_exists('wof_read_parameter')) {

    function wof_read_parameter($soap_envelope, $parameter_name) {
	  // parts to test--> parameter name may contain a prefix. parameter name
	  // may be empty tag

	  //case 1 test for an empty tag such as <variable />
	  $empty_tag_pattern = "/<\s*[ws:]*" . $parameter_name . "\s*\/>/";'<' . $parameter_name . '/>';
	  //case 2 test for a populated tag such as <variable>VALUE</variable>
	  //$full_tag_pattern =  "/<\s*[ws:]*" . $parameter_name . "\s*>(.*?)<\/\s*[ws:]*" . $parameter_name . "\s*>/";
	  $full_tag_pattern =  "/<\s*[tns:]*" . $parameter_name . "\s*>(.*?)<\/\s*[tns:]*" . $parameter_name . "\s*>/";

	  $num_matches = preg_match($full_tag_pattern, $soap_envelope, $matches);

	  if ($num_matches == 1) {
	    $result = $matches[1];
		return $result;
	  }
	  else {
	    //no match found: interpret this as an empty parameter
		return "";
	  }
	}
}

if (!function_exists('wof_read_array_parameter')) {

    function wof_read_array_parameter($soap_envelope, $parameter_name) {
	  // parts to test--> parameter name may contain a prefix. parameter name
	  // may be empty tag

	  //case 1 test for an empty tag such as <variable />
	  $empty_tag_pattern = "/<\s*[ws:]*" . $parameter_name . "\s*\/>/";'<' . $parameter_name . '/>';
	  //case 2 test for a populated tag such as <variable>VALUE</variable>
	  //$full_tag_pattern =  "/<\s*[ws:]*" . $parameter_name . "\s*>(.*?)<\/\s*[ws:]*" . $parameter_name . "\s*>/";
	  $full_tag_pattern =  "/<\s*[tns:]*" . $parameter_name . "\s*>(.*?)<\/\s*[tns:]*" . $parameter_name . "\s*>/";

	  $num_matches = preg_match($full_tag_pattern, $soap_envelope, $matches);

	  if ($num_matches == 1) {
	    $result = $matches[1];

                $ret = (str_replace("<tns:string>","",$result));
                $ret = str_replace("</tns:string>",",",$ret);
                $ret = str_replace(",,","",$ret);

                $ret = substr($ret,(strlen($ret)-1)) == ","? substr($ret,0,-1):$ret;
                
		return $ret;
	  }
	  else {
	    //no match found: interpret this as an empty parameter
		return "";
	  }
	}
}

if (!function_exists('wof_read_authtoken')) {

    function wof_read_authtoken($soap_envelope) {
  		$pos1 = stripos($soap_envelope, "<tns:authtoken>");
  
  		if ($pos1 === false) {
    		return "";
  		} else {
    		$pos2 = stripos($soap_envelope, "</tns:authtoken>");
			if ($pos2 === false) {
	  			return "";
			}
			$result = substr($soap_envelope, $pos1 + 15, $pos2 - $pos1 - 15);

			return $result;
  		}
	}
}

if (!function_exists('wof_read_site_array')) {
    //special case - reads an array of sites
	function wof_read_site_array($soap_envelope) {
  		$pattern = "/<string>(.*)<\/string>/";
  		preg_match_all($pattern, $soap_envelope, $matches);
  		return $matches[1];
	}
}

if (!function_exists('wof_GetShortSiteCode')) {
	//given a full site code NETWORK:CODE returns the CODE part
	function wof_GetShortSiteCode($full_site_code) {  
  		return substr($full_site_code, strpos($full_site_code, ':') + 1);
	}
}

if (!function_exists('wof_GetSiteNetwork')) {
	//given a full site code NETWORK:CODE returns the NETWORK part
	function wof_GetSiteNetwork($full_site_code) {
  		return substr($full_site_code, 0, strpos($full_site_code, ':'));
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

if (!function_exists('wof_queryInfo_variables')) {

	function wof_queryInfo_variables() {
  		$retVal = '<queryInfo><creationTime>' . date('c') . '</creationTime>';
  		$retVal .= '<criteria MethodCalled="GetVariables"><parameter name="variable" value="" />';
  		$retVal .= "</criteria></queryInfo>";
  		return $retVal;  
	}
}

if (!function_exists('wof_queryInfo_variable')) {

	function wof_queryInfo_variable($variable) {
  		$retVal = "<queryInfo><creationTime>" . date('c') . "</creationTime>";
  		$retVal .= '<criteria MethodCalled="GetVariableInfo">';
  		$retVal .= "<variableParam>" . $variable . "</variableParam>";
  		$retVal .= '<parameter name="variable" value="' . $variable . '" />';
  		$retVal .= "</criteria></queryInfo>";
  		return $retVal;
	}
}

if (!function_exists('wof_queryInfo_GetSites')) {

	function wof_queryInfo_GetSites($site = null) {
  		$retVal = '<queryInfo><creationTime>' . date('c') . '</creationTime>';
  		$retVal .= '<criteria MethodCalled="GetSites">';

  		if (is_array($site) && count($site) > 0) {
  			$retVal .= '<parameter name="site" value="'.implode(",",$site).'" /></criteria></queryInfo>';
  		} else {
  			$retVal .= '<parameter name="site" value="ALL SITES" /></criteria></queryInfo>';
  		}

  		return $retVal;
	}
}

if (!function_exists('wof_queryInfo_site')) {

	function wof_queryInfo_site($site = null) {
  		$retVal = '<queryInfo><creationTime>' . date('c') . '</creationTime>';
  		$retVal .= '<criteria MethodCalled="GetSiteInfo">';
  		$retVal .= '<parameter name="site" value="' . $site . '" /></criteria></queryInfo>';
  		return $retVal;
	}
}

if (!function_exists('wof_queryInfo_MultipleSites')) {

	function wof_queryInfo_MultipleSites($siteArray) {
  		$retVal = '<queryInfo><creationTime>' . date('c') . '</creationTime><criteria MethodCalled="GetSiteInfo">';
  		foreach($siteArray as $param) {
    		$retVal .= '<parameter name="site" value="' . $param . '" />';
  		}
  		$retVal .= '</criteria></queryInfo>';
  		return $retVal;
	}
}

if (!function_exists('wof_queryInfo_SitesByBox')) {

	function wof_queryInfo_SitesByBox($north, $south, $east, $west, $IncludeSeries) {
  		$retVal = '<queryInfo><creationTime>' . date('c') . '</creationTime>
  		<criteria MethodCalled="GetSitesByBoxObject">';
  		$retVal .= '<parameter name="north" value="' . $north . '" />';
  		$retVal .= '<parameter name="south" value="' . $south . '" />';
  		$retVal .= '<parameter name="east" value="' . $east . '" />';
  		$retVal .= '<parameter name="west" value="' . $west . '" />';
  		$retVal .= '<parameter name="IncludeSeries" value="' . $IncludeSeries . '" />';
  		$retVal .= '</criteria></queryInfo>';
  		return $retVal;
	}
}

if (!function_exists('wof_queryInfo_Values')) {

	function wof_queryInfo_Values($location, $variable, $startDate, $endDate) {
  		$retVal = '<queryInfo><creationTime>' . date('c') . '</creationTime>';
  		$retVal .= '<criteria MethodCalled="GetValues">';
  		$retVal .= '<parameter name="site" value="' . $location . '" />';
  		$retVal .= '<parameter name="variable" value="' . $variable . '" />';

		if (isset($startDate) && $startDate != "") {
  			$retVal .= '<parameter name="startDate" value="' . $startDate . '" />';
  		}

		if (isset($endDate) && $endDate != "") {
  			$retVal .= '<parameter name="endDate" value="' . $endDate . '" />';
  		}

  		$retVal .= '</criteria></queryInfo>';
  		return $retVal;
	}
}

if (!function_exists('wof_queryInfo_ValuesForSite')) {

	function wof_queryInfo_ValuesForSite($site, $startDate, $endDate) {
    	$retVal = '<queryInfo><creationTime>' . date('c') . '</creationTime>';
    	$retVal .= '<criteria MethodCalled="GetValuesForASite">';
    	$retVal .= '<parameter name="site" value="' . $site . '" />';
    	$retVal .= '<parameter name="startDate" value="' . $startDate . '" />';
    	$retVal .= '<parameter name="endDate" value="' . $endDate . '" />';
    	$retVal .= '</criteria></queryInfo>';
    	return $retVal;
	}
}

if (!function_exists('wof_GetSiteInfoByCode')) {

	//auxiliary function: gets the <site> element corresponding to the site code
	function wof_GetSiteInfoByCode($sitecode, $includeSeriesCatalog) {
  		$split = explode(":", $sitecode);
  		$shortcode = $split[1]; 
  		$retVal = "<site>";
  		$retVal .= db_GetSiteByCode($shortcode);
 
  		if ($includeSeriesCatalog) {
    		$retVal .=  db_GetSeriesCatalog($shortcode);
  		}
  		$retVal .= '</site>';
  		return $retVal;
	}
}

if (!function_exists('wof_GetSiteInfo')) {

	function wof_GetSiteInfo($fullSiteCode) {
  		$retVal = '<sitesResponse xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.cuahsi.org/waterML/1.1/">';
  		$retVal .= '<queryInfo><creationTime>';
  		$retVal .= date('c');
  		$retVal .= '</creationTime><criteria MethodCalled="GetSiteInfo"><parameter name="site" value="';
  		$retVal .= $fullSiteCode;
  		$retVal .= '" /></criteria></queryInfo>';
 
  		$split = explode(":", $fullSiteCode);
  		$shortcode = $split[1];

  		$retVal .= "<site>";
  		$retVal .= db_GetSiteByCode($shortcode, "siteInfo", "");
  		$retVal .= db_GetSeriesCatalog($shortcode);
  		$retVal .= '</site>';
  		$retVal .= '</sitesResponse>';
  		return $retVal;
	}
}

if (!function_exists('wof_GetSiteInfo_REST')) {

	function wof_GetSiteInfo_REST($fullSiteCode) {
  		$retVal = '<sitesResponse xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.cuahsi.org/waterML/1.1/">';
  		$retVal .= '<queryInfo><creationTime>';
  		$retVal .= date('c');
  		$retVal .= '</creationTime><criteria MethodCalled="GetSiteInfo"><parameter name="site" value="';
  		$retVal .= $fullSiteCode;
  		$retVal .= '" /></criteria></queryInfo>';

  		$split = explode(":", $fullSiteCode);
  		$shortcode = $split[1];

  		$retVal .= "<site>";
  		$xsi = 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.cuahsi.org/waterML/1.1/';
  		$retVal .= db_GetSiteByCode($shortcode, "siteInfo", $xsi);
  		$retVal .=  db_GetSeriesCatalog($shortcode);
  		$retVal .= '</site>';
  		$retVal .= '</sitesResponse>';
  		return $retVal;
	}
}

if (!function_exists('wof_GetSiteInfoMultipleObject')) {

	//returns full information about multiple sites according to the array of site codes
	function wof_GetSiteInfoMultipleObject($siteArray) {
  		$retVal = '<sitesResponse xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.cuahsi.org/waterML/1.1/">' .
  		wof_queryInfo_MultipleSites($siteArray);

  		foreach($siteArray as $sitecodeparam) {
    		$retVal .= wof_GetSiteInfoByCode($sitecodeparam, true); 
  		}
  
  		$retVal .= '</sitesResponse>';
  		return $retVal;
	}
}

if (!function_exists('wof_GetSites')) {

	function wof_GetSites($site = NULL) {
  		$retVal = '<sitesResponse xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.cuahsi.org/waterML/1.1/">';
  		$retVal .= wof_queryInfo_GetSites($site);
  		$retVal .= db_GetSites($site);
  		$retVal .= '</sitesResponse>';
  		return $retVal;
	}
}

if (!function_exists('wof_GetSitesByBox')) {

	function wof_GetSitesByBox($west, $south, $east, $north, $IncludeSeries) {
  		//TODO add support for IncludeSeries (now assumed FALSE)
  		$retVal = '<sitesResponse xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.cuahsi.org/waterML/1.1/">';
  		$retVal .= wof_queryInfo_SitesByBox($north, $south, $east, $west, $IncludeSeries);
  		$retVal .= db_GetSitesByBox($west, $south, $east, $north);
  		$retVal .= '</sitesResponse>';
  		return $retVal;
	}
}

if (!function_exists('wof_GetValues')) {

	function wof_GetValues($location, $variable, $startDate, $endDate ) {
    	//get the short variable code and short site code
    	$shortSiteCode = $location;
    	$shortVariableCode = $variable;
    	$pos1 = strpos($location, ":");
    	if ($pos1 >= 0) {
        	$split1 = explode(":", $location);
        	$shortSiteCode = $split1[1];
    	}
    	$pos2 = strpos($variable, ":");
	    if ($pos2 >= 0) {
	        $split2 = explode(":", $variable);
	        $shortVariableCode = $split2[1];
	    }
 
	    $retVal = '<timeSeriesResponse xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
	    $retVal .= wof_queryInfo_Values($location, $variable, $startDate, $endDate);
	    $retVal .= '<timeSeries>';
  
	    //write site information
	    $retVal .= db_GetSiteByCode($shortSiteCode, "sourceInfo", "SiteInfoType");
 
	    //write variable information
	    $retVal .= db_GetVariableByCode($shortVariableCode);

	    //write list of data values
	    $retVal .= db_GetValues($shortSiteCode, $shortVariableCode, $startDate, $endDate);

	    $retVal .= "</timeSeries>";
	    $retVal .= "</timeSeriesResponse>";
	    return $retVal;
	}
}

if (!function_exists('wof_GetValuesForASite')) {

	function wof_GetValuesForASite($site, $startDate, $endDate) {
	    $shortSiteCode = $site;
	    $pos1 = strpos($site, ":");
	    if ($pos1 >= 0) {
	        $split1 = explode(":", $site);
	        $shortSiteCode = $split1[1];
	    }

	    $retVal = '<timeSeriesResponse xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
	    $retVal .= wof_queryInfo_ValuesForSite($site, $startDate, $endDate);
	    $variableCodes = db_GetVariableCodesBySite($shortSiteCode);
	    $siteInformation = db_GetSiteByCode($shortSiteCode, "sourceInfo", "SiteInfoType");

	    foreach($variableCodes as $varCode ) {
	        $retVal .= '<timeSeries>';

	        //write site information
	        $retVal .= $siteInformation;

	        //write variable information
	        $retVal .= db_GetVariableByCode($varCode);

	        //write list of data values
	        $retVal .= db_GetValues($shortSiteCode, $varCode, $startDate, $endDate);

        	$retVal .= "</timeSeries>";
    	}

	    $retVal .= "</timeSeriesResponse>";
	    return $retVal;
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

if (!function_exists('wof_GetVariableInfo')) {

	// GetVariableInfo Web Method
	function wof_GetVariableInfo($variable) {
  		$retVal = '<variablesResponse>';  
  		$retVal .= wof_queryInfo_variable($variable);

	  	//checking for variable code: send NULL or send the short code
  		$short_code = NULL;
  		if (strlen($variable) > 0) {
    		$short_code = substr($variable, strpos($variable, ':') + 1);
  		}

  		$retVal .= '<variables>';
  		$retVal .= db_GetVariableByCode($short_code);
  		$retVal .= '</variables></variablesResponse>';	  
  		return $retVal;
	}
}

if (!function_exists('GetSiteInfo')) {

	function GetSiteInfo($site) {
  		wof_start();
  		echo '<GetSiteInfoResponse xmlns="http://www.cuahsi.org/his/1.1/ws/"><GetSiteInfoResult>';
  		echo htmlspecialchars(wof_GetSiteInfo($site));
  		echo '</GetSiteInfoResult></GetSiteInfoResponse>';
  		wof_finish();
	}
}

if (!function_exists('GetSiteInfoObject')) {

	function GetSiteInfoObject($site) {
	  	wof_start();
	  	echo '<GetSiteInfoObjectResponse xmlns="http://www.cuahsi.org/his/1.1/ws/">';
	  	echo wof_GetSiteInfo($site);
	  	echo '</GetSiteInfoObjectResponse>';
	  	wof_finish();
	}
}

if (!function_exists('GetSiteInfoMultpleObject')) {

	function GetSiteInfoMultpleObject($sitearray) {
  		wof_start();
  		echo '<GetSiteInfoMultpleObjectResponse xmlns="http://www.cuahsi.org/his/1.1/ws/">';
  		echo wof_GetSiteInfoMultipleObject($sitearray);
  		echo '</GetSiteInfoMultpleObjectResponse>';
  		wof_finish();
	}
}

if (!function_exists('GetSites')) {

	function GetSites($site = NULL) {
	  	wof_start();
	  	echo '<GetSitesResponse xmlns="http://www.cuahsi.org/his/1.1/ws/"><GetSitesResult>';
	  	echo htmlspecialchars(wof_GetSites($site));
	  	echo '</GetSitesResult></GetSitesResponse>';
	  	wof_finish();
	}
}

if (!function_exists('GetSitesByBoxObject')) {

	function GetSitesByBoxObject($north, $south, $east, $west, $includeSeries) {
  		wof_start();
  		echo '<GetSitesByBoxObjectResponse xmlns="http://www.cuahsi.org/his/1.1/ws/">';
  		echo wof_GetSitesByBox($west, $south, $east, $north, $includeSeries);
  		echo '</GetSitesByBoxObjectResponse>';
  		wof_finish();
	}
}

if (!function_exists('GetSitesObject')) {

	function GetSitesObject($site = NULL) {
	  	wof_start();
	  	echo '<GetSitesObjectResponse xmlns="http://www.cuahsi.org/his/1.1/ws/">';
	  	echo wof_GetSites($site);
	  	echo '</GetSitesObjectResponse>';
	  	wof_finish();
	}
}

if (!function_exists('GetValues')) {

	function GetValues($location, $variable, $startDate, $endDate) {
	  	wof_start();
	  	echo '<GetValuesResponse xmlns="http://www.cuahsi.org/his/1.1/ws/"><GetValuesResult>';
	  	echo htmlspecialchars(wof_GetValues($location, $variable, $startDate, $endDate));
	  	echo '</GetValuesResult></GetValuesResponse>';
	  	wof_finish();
	}
}

if (!function_exists('GetValuesForASiteObject')) {

	function GetValuesForASiteObject($site, $startDate, $endDate) {
  		wof_start();
  		echo '<GetValuesForASiteObjectResponse xmlns="http://www.cuahsi.org/his/1.1/ws/">';
  		echo wof_GetValuesForASite($site, $startDate, $endDate);
  		echo '</GetValuesForASiteObjectResponse>';
  		wof_finish();
	}
}

if (!function_exists('GetValuesObject')) {

	function GetValuesObject($location, $variable, $startDate, $endDate) {
	  	wof_start();
	  	echo '<TimeSeriesResponse xmlns="http://www.cuahsi.org/waterML/1.1/">';
	  	echo wof_GetValues($location, $variable, $startDate, $endDate);
	  	echo '</TimeSeriesResponse>';
	  	wof_finish();
	}
}

if (!function_exists('GetVariableInfo')) {

	function GetVariableInfo($variable) {
  		wof_start();
	  	echo '<GetVariableInfoResponse xmlns="http://www.cuahsi.org/his/1.1/ws/"><GetVariableInfoResult>';
	  	echo htmlspecialchars(wof_GetVariableInfo($variable));
	  	echo '</GetVariableInfoResult></GetVariableInfoResponse>';
	  	wof_finish();
	}
}

if (!function_exists('GetVariableInfoObject')) {

	function GetVariableInfoObject($variable) {
	  	wof_start();
	  	echo '<VariablesResponse  xmlns="http://www.cuahsi.org/waterML/1.1/">';
	  	echo wof_GetVariableInfo($variable);
	  	echo '</VariablesResponse>';
	  	wof_finish();
	}
}

if (!function_exists('GetVariables')) {

	function GetVariables() {
	  	wof_start();
	  	echo '<GetVariablesResponse xmlns="http://www.cuahsi.org/his/1.1/ws/"><GetVariablesResult>';
	  	echo htmlspecialchars(wof_GetVariables());
	  	echo '</GetVariablesResult></GetVariablesResponse>';
	  	wof_finish();
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
//end of re-write from wof

//re-write from REST service
if (!function_exists('write_XML_header')) {

	// This function writes the PHP header  
	function write_XML_header() {
	    header("Content-type: text/xml; charset=utf-8'");
	    echo chr(60) . chr(63) . 'xml version="1.0" encoding="utf-8" ' . chr(63) . chr(62);
	}
}

//re-write from wof_read_db
if (!function_exists('get_table_name')) {

	function get_table_name($uppercase_table_name) {
	    return '`'. strtolower($uppercase_table_name) .'`';
	}
}

if (!function_exists('to_xml')) {

	function to_xml($xml_tag, $value) {
	   return "<$xml_tag><![CDATA[$value]]></$xml_tag>";
	}
}

if (!function_exists('to_attribute')) {

	function to_attribute($attribute_name, $value) {
	   return "$attribute_name=\"$value\"";
	}
}

if (!function_exists('db_GetSeriesCatalog')) {

	function db_GetSeriesCatalog($shortSiteCode) {
		$ci = &get_instance();

	   	//get the table names
	   	$variables_table = get_table_name('Variables');
	   	$seriescatalog_table = get_table_name('SeriesCatalog');
	   	$units_table = get_table_name('Units');
	   	$qc_table = get_table_name('QualityControlLevels');
	   	$methods_table = get_table_name('Methods');
	   
	   	//run SQL query
		$ci->db->select("s.VariableID, s.VariableCode, s.VariableName, s.ValueType, s.DataType, s.GeneralCategory, s.SampleMedium, s.VariableUnitsName, u.UnitsType AS \"VariableUnitsType\", u.UnitsAbbreviation AS \"VariableUnitsAbbreviation\", s.VariableUnitsID, v.NoDataValue, v.IsRegular, s.TimeUnitsName, tu.UnitsType AS \"TimeUnitsType\", tu.UnitsAbbreviation AS \"TimeUnitsAbbreviation\", s.TimeUnitsID, s.TimeSupport, s.Speciation, s.ValueCount, s.BeginDateTime, s.EndDateTime, s.BeginDateTimeUTC, s.EndDateTimeUTC, s.SourceID, s.Organization, s.SourceDescription, s.Citation, s.QualityControlLevelID, s.QualityControlLevelCode, qc.Definition, s.MethodID, s.MethodDescription, m.MethodLink");
		$ci->db->join($variables_table." v","s.VariableID = v.VariableID","LEFT");
		$ci->db->join($units_table." u","s.VariableUnitsID = u.UnitsID","LEFT");
		$ci->db->join($units_table." tu","s.TimeUnitsID = tu.UnitsID","LEFT");
		$ci->db->join($qc_table." qc","s.QualityControlLevelID = qc.QualityControlLevelID","LEFT");
		$ci->db->join($methods_table." m","m.MethodID = s.MethodID","LEFT");
		$ci->db->where("SiteCode",$shortSiteCode);

	    $result = $ci->db->get($seriescatalog_table." s");
	
	    if (!$result) {
	        die("<p>Error in executing the SQL query " . $ci->db->last_query() . ": " .
	            $ci->db->_error_message() . "</p>");
	    }
	
	    $retVal = '<seriesCatalog>';
	
	    foreach ($result->result_array() as $row) {
			$serviceCode = $ci->config->item('service_code');
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
}

if (!function_exists('fn_GetSiteArray')) {

	function fn_GetSiteArray($sites, $siteTag = "siteInfo", $siteTagType = "") {
		$ci = &get_instance();
	   	$siteArray[0] = '';
	    $siteIndex = 0;
	
	    $fullSiteTag = $siteTag;
	    if ($siteTagType != "") {
	        $fullSiteTag = $siteTag . ' xsi:type="' . $siteTagType . '"';
	    }
	
	    foreach ($sites->result_array() as $row) {
	        $retVal = '';
	        $retVal .= "<" . $fullSiteTag . ">";
	        $retVal .= to_xml("siteName", $row["SiteName"]);
	        $retVal .= '<siteCode network="' . $ci->config->item('service_code') . '">' . $row["SiteCode"] . "</siteCode>";
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
	        $county = $row["County"];
	        if ($county != '') {
	            $retVal .= '<siteProperty name="County">'.$county.'</siteProperty>';
	        }
	        $state = $row["State"];
	        if ($state != '') {
	            $retVal .= '<siteProperty name="State">'.$state.'</siteProperty>';
	        }
	        $comments = $row["Comments"];
	        if ($comments != '') {
	            $retVal .= '<siteProperty name="SiteComments">'.$comments.'</siteProperty>';
	        }
	        $posAccuracy_m = $row["PosAccuracy_m"];
	        if ($posAccuracy_m != '') {
	            $retVal .= '<siteProperty name="PosAccuracy_m">'.$posAccuracy_m.'</siteProperty>';
	        }
	        $retVal .= "</" . $siteTag . ">";
			$siteArray[$siteIndex] = $retVal;
			$siteIndex++;
	    }
	    return $siteArray;
	}
}

if (!function_exists('db_GetSiteByCode')) {

	function db_GetSiteByCode($shortCode, $siteTag = "siteInfo", $siteTagType = "") {
		$ci = &get_instance();

	    $sr_table = get_table_name('SpatialReferences');
		$sites_table = get_table_name('Sites');
		$series_catalog_table = get_table_name('SeriesCatalog');

		$siteSC = $ci->db->select("SiteID")->get($series_catalog_table);

		if ($siteSC->num_rows() > 0) {
			$where = '';
		    foreach ($siteSC->result_array() as $row) {
		        $where .= '"' . $row["SiteID"] . '",';
		    }
		    $whereID = "(".substr($where, 0, strlen($where) - 1).")";
	    }

		$ci->db->distinct();
		$ci->db->select("s.SiteName, s.SiteID, s.SiteCode, s.Latitude, s.Longitude, sr.SRSID, s.LocalProjectionID, s.LocalX, s.LocalY, s.Elevation_m, s.VerticalDatum, s.State, s.County, s.Comments, s.PosAccuracy_m");
		$ci->db->join($sr_table." sr","s.LocalProjectionID = sr.SpatialReferenceID","LEFT");
		$ci->db->where("s.SiteCode",$shortCode);

		if (isset($whereID)) {
			$ci->db->where("s.SiteID IN",$whereID,FALSE);
		}

		$result = $ci->db->get($sites_table." s");

	    if (!$result) {
	        die("<p>Error in executing the SQL query " . $ci->db->last_query() . ": " .
	            $ci->db->_error_message() . "</p>");
	    }

	    $sitesArray = fn_GetSiteArray($result);

	    return $sitesArray[0]; //what if no site is found?
	}
}

if (!function_exists('db_GetSiteByID')) {

	function db_GetSiteByID($siteID, $siteTag = "siteInfo", $siteTagType = "") {
		$ci = &get_instance();

	    $sr_table = get_table_name('SpatialReferences');
		$sites_table = get_table_name('Sites');
		$series_catalog_table = get_table_name('SeriesCatalog');

		$siteSC = $ci->db->select("SiteID")->get($series_catalog_table);

		if ($siteSC->num_rows() > 0) {
			$where = '';
		    foreach ($siteSC->result_array() as $row) {
		        $where .= '"' . $row["SiteID"] . '",';
		    }
		    $whereID = "(".substr($where, 0, strlen($where) - 1).")";
	    }

		$ci->db->distinct();
		$ci->db->select("s.SiteName, s.SiteID, s.SiteCode, s.Latitude, s.Longitude, sr.SRSID, s.LocalProjectionID, s.LocalX, s.LocalY, s.Elevation_m, s.VerticalDatum, s.State, s.County, s.Comments, s.PosAccuracy_m");
		$ci->db->join($sr_table." sr","s.LocalProjectionID = sr.SpatialReferenceID","LEFT");
		$ci->db->where("s.SiteID",$siteID);

		if (isset($whereID)) {
			$ci->db->where("s.SiteID IN",$whereID,FALSE);
		}

		$result = $ci->db->get($sites_table." s");

	    if (!$result) {
	        die("<p>Error in executing the SQL query " . $ci->db->last_query() . ": " .
	            $ci->db->_error_message() . "</p>");
	    }

	    $sitesArray = fn_GetSiteArray($result);

	    return $sitesArray[0]; //what if no site is found?
	}
}

if (!function_exists('db_GetSites')) {

	function db_GetSites($site = NULL) {
		$ci = &get_instance();

	    $sr_table = get_table_name('SpatialReferences');
		$sites_table = get_table_name('Sites');
		$series_catalog_table = get_table_name('SeriesCatalog');

		$siteSC = $ci->db->select("SiteID")->get($series_catalog_table);

		if ($siteSC->num_rows() > 0) {
			$where = '';
		    foreach ($siteSC->result_array() as $row) {
		        $where .= '"' . $row["SiteID"] . '",';
		    }
		    $whereID = "(".substr($where, 0, strlen($where) - 1).")";
	    }

		$ci->db->distinct();
		$ci->db->select("s.SiteName, s.SiteID, s.SiteCode, s.Latitude, s.Longitude, sr.SRSID, s.LocalProjectionID, s.LocalX, s.LocalY, s.Elevation_m, s.VerticalDatum, s.State, s.County, s.Comments, s.PosAccuracy_m");
		$ci->db->join($sr_table." sr","s.LocalProjectionID = sr.SpatialReferenceID","LEFT");

		if (isset($whereID)) {
			$ci->db->where("s.SiteID IN",$whereID,FALSE);
		}

		$where = '';
		if (is_array($site) && count($site) > 0) {
			foreach($site as $row) {
				$param_site = explode(":",$row);
				if (count($param_site) > 1) {
		        	$where .= '"' . $param_site[1] . '",';
				} else {
		        	$where .= '"' . $param_site[0] . '",';
				}
			}

	    	$whereCode = "(".substr($where, 0, strlen($where) - 1).")";
			$ci->db->where("s.SiteCode IN",$whereCode,FALSE);
		}

		$result = $ci->db->get($sites_table." s");

	    if (!$result) {
	        die("<p>Error in executing the SQL query " . $ci->db->last_query() . ": " .
	            $ci->db->_error_message() . "</p>");
	    }

	    $sitesArray = fn_GetSiteArray($result);
	    $retVal = '';
	
	    foreach ($sitesArray as $siteRow) {
	        $retVal .= "<site>";
	        $retVal .= $siteRow;
	        $retVal .= "</site>";
	    }
	    return $retVal;
	}
}

if (!function_exists('db_GetSitesByCodes')) {

	function db_GetSitesByCodes($fullSiteCodeArray) {
		$ci = &get_instance();

	    $where = '';
	    foreach ($fullSiteCodeArray as $fullCode) {
	        $split = explode(":", $fullCode);
	        $shortCode = $split[1];
	        $where .= '"' . $shortCode . '",';
	    }
	    $whereStr = "(".substr($where, 0, strlen($where) - 1).")";

		$siteSC = $ci->db->select("SiteID")->get($series_catalog_table);

		if ($siteSC->num_rows() > 0) {
			$where = '';
		    foreach ($siteSC->result_array() as $row) {
		        $where .= '"' . $row["SiteID"] . '",';
		    }
		    $whereID = "(".substr($where, 0, strlen($where) - 1).")";
	    }

	    $sr_table = get_table_name('SpatialReferences');
		$sites_table = get_table_name('Sites');
		$series_catalog_table = get_table_name('SeriesCatalog');

		$ci->db->distinct();
		$ci->db->select("s.SiteName, s.SiteID, s.SiteCode, s.Latitude, s.Longitude, sr.SRSID, s.LocalProjectionID, s.LocalX, s.LocalY, s.Elevation_m, s.VerticalDatum, s.State, s.County, s.Comments, s.PosAccuracy_m");
		$ci->db->join($sr_table." sr","s.LocalProjectionID = sr.SpatialReferenceID","LEFT");

		if (isset($whereID)) {
			$ci->db->where("s.SiteID IN",$whereID,FALSE);
		}

		$ci->db->where("s.SiteCode IN",$whereStr,FALSE);

		$result = $ci->db->get($sites_table." s");

	    if (!$result) {
	        die("<p>Error in executing the SQL query " . $ci->db->last_query() . ": " .
	            $ci->db->_error_message() . "</p>");
	    }


	    $sitesArray = fn_GetSiteArray($result);

	    $retVal = '';
	    foreach ($sitesArray as $site) {
	        $retVal .= "<site>";
	        $retVal .= $site;
	        $retVal .= "</site>";
	    }
	    return $retVal;
	}
}

if (!function_exists('db_GetSitesByBox')) {

	function db_GetSitesByBox($west, $south, $east, $north) {
		$ci = &get_instance();

	    $sr_table = get_table_name('SpatialReferences');
		$sites_table = get_table_name('Sites');
		$series_catalog_table = get_table_name('SeriesCatalog');

		$siteSC = $ci->db->select("SiteID")->get($series_catalog_table);

		if ($siteSC->num_rows() > 0) {
			$where = '';
		    foreach ($siteSC->result_array() as $row) {
		        $where .= '"' . $row["SiteID"] . '",';
		    }
		    $whereID = "(".substr($where, 0, strlen($where) - 1).")";
	    }

		$ci->db->distinct();
		$ci->db->select("s.SiteName, s.SiteID, s.SiteCode, s.Latitude, s.Longitude, sr.SRSID, s.LocalProjectionID, s.LocalX, s.LocalY, s.Elevation_m, s.VerticalDatum, s.State, s.County, s.Comments, s.PosAccuracy_m");
		$ci->db->join($sr_table." sr","s.LocalProjectionID = sr.SpatialReferenceID","LEFT");
		$ci->db->where("Longitude >=",$west);
		$ci->db->where("Longitude <=",$east);
		$ci->db->where("Latitude >=",$south);
		$ci->db->where("Latitude <=",$north);

		if (isset($whereID)) {
			$ci->db->where("s.SiteID IN",$whereID,FALSE);
		}

		$result = $ci->db->get($sites_table." s");

	    if (!$result) {
	        die("<p>Error in executing the SQL query " . $ci->db->last_query() . ": " .
	            $ci->db->_error_message() . "</p>");
	    }

	    $sitesArray = fn_GetSiteArray($result);

	    $retVal = '';
	    foreach ($sitesArray as $site) {
	        $retVal .= "<site>";
	        $retVal .= $site;
	        $retVal .= "</site>";
	    }
	    return $retVal;
	}
}

if (!function_exists('db_GetVariableCodesBySite')) {

	function db_GetVariableCodesBySite($shortSiteCode) {
		$ci = &get_instance();

		$ci->db->select("VariableCode");
		$ci->db->where("SiteCode",$shortSiteCode);

	    $result = $ci->db->get(get_table_name('SeriesCatalog'));
	
	    if (!$result) {
	        die("<p>Error in executing the SQL query " . $ci->db->last_query() . ": " .
	            $ci->db->_error_message() . "</p>");
	    }
	    $retVal = array();
	    $nr = 0;
	    foreach ($result->result_array() as $ret) {
	        $retVal[$nr] = $ret["VariableCode"];
	        $nr++;
	    }
	    return $retVal;
	}
}

if (!function_exists('variableFromDataRow')) {

	function variableFromDataRow($row) {
		$ci = &get_instance();
	    $variableID = $row["VariableID"];
	    $variableName = $row["VariableName"];
	    $variableCode = $row["VariableCode"];
		$valueType = $row["ValueType"];
		$dataType = $row["DataType"];
		$generalCategory = $row["GeneralCategory"];
		$sampleMedium = $row["SampleMedium"];
		$isRegular = $row["IsRegular"] ? "true" : "false";
			
		$retVal = "<variable>";
		$retVal .= "<variableCode vocabulary=\"" . $ci->config->item('service_code') . "\" default=\"true\" variableID=\"" . $variableID . "\" >" . $variableCode . "</variableCode>";
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

if (!function_exists('db_GetVariableByCode')) {

	function db_GetVariableByCode($shortvariablecode = NULL) {
	    $ci = &get_instance();

	    $variables_table = get_table_name('Variables');
		$units_table = get_table_name('Units');

	    //run SQL query
		$ci->db->select("VariableID, VariableCode, VariableName, ValueType, DataType, GeneralCategory, SampleMedium,
	   u1.UnitsName AS \"VariableUnitsName\", u1.UnitsType AS \"VariableUnitsType\", u1.UnitsAbbreviation AS \"VariableUnitsAbbreviation\", VariableUnitsID, NoDataValue, IsRegular, u2.UnitsName AS \"TimeUnitsName\", u2.UnitsType AS \"TimeUnitsType\", u2.UnitsAbbreviation AS \"TimeUnitsAbbreviation\", TimeUnitsID, TimeSupport, Speciation");
		$ci->db->join($units_table." u1","v.VariableUnitsID = u1.UnitsID","LEFT");
		$ci->db->join($units_table." u2","v.TimeUnitsID = u2.UnitsID");

	    if (!is_null($shortvariablecode)) {
	    	$ci->db->where("VariableCode",$shortvariablecode);
	    }

	    $result = $ci->db->get($variables_table." v");
	
	    if (!$result) {
	        die("<p>Error in executing the SQL query " . $ci->db->last_query() . ": " .
	            $ci->db->_error_message() . "</p>");
	    }
	
	    $retVal = '';
	
	    foreach ($result->result_array() as $row) {
		    $retVal .= variableFromDataRow($row);
	    }
	    return $retVal;
	}
}

if (!function_exists('db_GetValues')) {

	function db_GetValues($siteCode, $variableCode, $beginTime, $endTime) {
	    $ci = &get_instance();

	    //first get the metadata
		// implement sql query (because of complex query date range that too difficult if still using active record) with escape string to avoid from SQL injection
		$querymeta = "SELECT SiteID, VariableID, MethodID, SourceID, QualityControlLevelID FROM " . get_table_name('SeriesCatalog');
    	$querymeta .= " WHERE SiteCode = ? AND VariableCode = ? ";

		if ((isset($beginTime) && $beginTime != "") && (isset($endTime) && $endTime != "")) {
			$querymeta .= " AND ( (BeginDateTime <= ? AND EndDateTime >= ? ) OR (BeginDateTime >= ? AND BeginDateTime <= ? ) OR (EndDateTime >= ? AND EndDateTime <= ?) )";
  		}

		if ((isset($beginTime) && $beginTime != "") && (isset($endTime) && $endTime != "")) {
			$arr_param = array($siteCode,$variableCode,$beginTime,$endTime,$beginTime,$endTime,$beginTime,$endTime);
		} else {
			$arr_param = array($siteCode,$variableCode);
		}

		$result = $ci->db->query($querymeta,$arr_param);
	
	    if (!$result) {
	        die("<p>Error in executing the SQL query " . $ci->db->last_query() . ": " .
	            $ci->db->_error_message() . "</p>");
	    }
	
	    $numSeries = $result->num_rows();
	
	    if ($numSeries == 0) {
	        return "<values />";
	    }
	    else if ($numSeries == 1) {
	
	        $row = $result->row("0","array");
	
	        return db_GetValues_OneSeries($row["SiteID"], $row["VariableID"], $row["MethodID"], $row["SourceID"], $row["QualityControlLevelID"], $beginTime, $endTime);
	    }
	    else {
	    	$arrMethod = array();
	    	$arrSource = array();
	    	$arrQC = array();
			foreach ($result->result_array() as $row) {
				if (!in_array($row['MethodID'],$arrMethod)) {
					$arrMethod[] = $row['MethodID'];
				}
				if (!in_array($row['SourceID'],$arrSource)) {
					$arrSource[] = $row['SourceID'];
				}
				if (!in_array($row['QualityControlLevelID'],$arrQC)) {
					$arrQC[] = $row['QualityControlLevelID'];
				}
			}
	
	        $row = $result->row("0","array");
	        return db_GetValues_MultipleSeries($row["SiteID"], $row["VariableID"], $arrMethod, $arrSource, $arrQC, $beginTime, $endTime);
	    }
	}
}

if (!function_exists('db_GetValues_OneSeries')) {

	function db_GetValues_OneSeries($siteID, $variableID, $methodID, $sourceID, $qcID, $beginTime, $endTime) {
	    $ci = &get_instance();

	    $data_values_table = get_table_name('DataValues');
	    $samples_table = get_table_name('Samples');
		$ci->db->select("d.LocalDateTime, d.UTCOffset, d.DateTimeUTC, d.DataValue, s.LabSampleCode");
		$ci->db->join($samples_table." s","d.SampleID = s.SampleID","LEFT");
		$ci->db->where("d.SiteID",$siteID);
		$ci->db->where("d.VariableID",$variableID);
		$ci->db->where("d.MethodID",$methodID);
		$ci->db->where("d.SourceID",$sourceID);
		$ci->db->where("d.QualityControlLevelID",$qcID);
	
		if ((isset($beginTime) && $beginTime != "") && (isset($endTime) && $endTime != "")) {
			$ci->db->where("d.LocalDateTime >=",$beginTime);
			$ci->db->where("d.LocalDateTime <=",$endTime);
	    }

	    $result = $ci->db->get($data_values_table." d");
	    if (!$result) {
	        die("<p>Error in executing the SQL query " . $ci->db->last_query() . ": " .
	            $ci->db->_error_message() . "</p>");
	    }
	    $retVal = "<values>";
	    foreach ($result->result_array() as $row) {
	        $retVal .= '<value censorCode="nc" dateTime="' . $row["LocalDateTime"] . '"';
	        $retVal .= ' timeOffset="' . $row["UTCOffset"] . '" dateTimeUTC="' . $row["DateTimeUTC"] . '" ';
	        $retVal .= ' methodCode="' . $methodID . '" ';
	        $retVal .= ' sourceCode="' . $sourceID . '" ';
	        $retVal .= ($row['LabSampleCode'] != ''? 'labSampleCode="'.$row['LabSampleCode'].'"':'');
	        $retVal .= ' qualityControlLevelCode="' . $qcID . '" ';
	        $retVal .= ">".$row["DataValue"]."</value>";
	    }
	    $retVal .= db_GetQualityControlLevelByID($qcID);
	    $retVal .= db_GetMethodByID($methodID);
	    $retVal .= db_GetSourceByID($sourceID);
	
	    $retVal .= "<censorCode><censorCode>nc</censorCode><censorCodeDescription>not censored</censorCodeDescription></censorCode>";
	
	    $retVal .= "</values>";
	
	    return $retVal;
	}
}

if (!function_exists('db_GetValues_MultipleSeries')) {

	function db_GetValues_MultipleSeries($siteID, $variableID, $arrMethod, $arrSource, $arrQC, $beginTime, $endTime) {
	    $ci = &get_instance();

	    $samples_table = get_table_name('Samples');
		$ci->db->select("d.LocalDateTime, d.UTCOffset, d.DateTimeUTC, d.MethodID, d.SourceID, d.QualityControlLevelID, d.DataValue, s.LabSampleCode");
		$ci->db->join($samples_table." s","d.SampleID = s.SampleID","LEFT");
		$ci->db->where("d.SiteID",$siteID);
		$ci->db->where("d.VariableID",$variableID);

		if ((isset($beginTime) && $beginTime != "") && (isset($endTime) && $endTime != "")) {
			$ci->db->where("d.LocalDateTime >=",$beginTime);
			$ci->db->where("d.LocalDateTime <=",$endTime);
	    }
	
	    $result = $ci->db->get(get_table_name('DataValues')." d");
	    if (!$result) {
	        die("<p>Error in executing the SQL query " . $ci->db->last_query() . ": " .
	            $ci->db->_error_message() . "</p>");
	    }
	    $retVal = "<values>";
	    //$metadata = 'methodCode="' . $methodID . '" sourceCode="' . $sourceID . '" qualityControlLevelCode="' . $qcID . '"';
	    foreach ($result->result_array() as $row) {
	        $retVal .= '<value censorCode="nc" dateTime="' . $row["LocalDateTime"] . '"';
	        $retVal .= ' timeOffset="' . $row["UTCOffset"] . '" dateTimeUTC="' . $row["DateTimeUTC"] . '"';
	        $retVal .= ' methodCode="' . $row["MethodID"] . '" ';
	        $retVal .= ' sourceCode="' . $row["SourceID"] . '" ';
	        $retVal .= ($row['LabSampleCode'] != ''? 'labSampleCode="'.$row['LabSampleCode'].'"':'');
	        $retVal .= ' qualityControlLevelCode="' . $row["QualityControlLevelID"] . '" ';
	        $retVal .= ">".$row["DataValue"]."</value>";
	    }

		foreach ($arrQC as $row) {
	    	$retVal .= db_GetQualityControlLevelByID($row);
		}

		foreach ($arrMethod as $row) {
	    	$retVal .= db_GetMethodByID($row);
		}

		foreach ($arrSource as $row) {
	    	$retVal .= db_GetSourceByID($row);
		}

	    $retVal .= "<censorCode><censorCode>nc</censorCode><censorCodeDescription>not censored</censorCodeDescription></censorCode>";
	
	    $retVal .= "</values>";
	    return $retVal;
	}
}

if (!function_exists('db_GetQualityControlLevelByID')) {

	function db_GetQualityControlLevelByID($qcID) {
	    $ci = &get_instance();

    	$qc_table = get_table_name("QualityControlLevels");

		$ci->db->select("QualityControlLevelCode, Definition, Explanation");
		$ci->db->where("QualityControlLevelID",$qcID);

	    $result = $ci->db->get($qc_table);
	    if (!$result) {
	        die("<p>Error in executing the SQL query " . $ci->db->last_query() . ": " .
	            $ci->db->_error_message() . "</p>");
	    }
	
	    $row = $result->row("0","array");
	    $retVal = '<qualityControlLevel qualityControlLevelID="' . $qcID . '">';
	    $retVal .= "<qualityControlLevelCode>" . $row["QualityControlLevelCode"] . "</qualityControlLevelCode>";
	    $retVal .= "<definition>" . $row["Definition"] . "</definition>";
	    $retVal .= "<explanation>" . $row["Explanation"] . "</explanation>";
	    $retVal .= "</qualityControlLevel>";
	    return $retVal;
	}
}

if (!function_exists('db_GetMethodByID')) {

	function db_GetMethodByID($methodID) {
	    $ci = &get_instance();

	    $method_table = get_table_name("Methods");
		$ci->db->select("MethodDescription, MethodLink");
		$ci->db->where("MethodID",$methodID);

	    $result = $ci->db->get($method_table);
	    if (!$result) {
	        die("<p>Error in executing the SQL query " . $ci->db->last_query() . ": " .
	            $ci->db->_error_message() . "</p>");
	    }
	
	    $row = $result->row("0","array");
	    $retVal = '<method methodID="' . $methodID . '"><methodCode>' . $methodID . "</methodCode>";
	    $retVal .= "<methodDescription>" . $row["MethodDescription"] . "</methodDescription>";
	    $retVal .= "<methodLink>" . $row["MethodLink"] . "</methodLink>";
	    $retVal .= "</method>";
	    return $retVal;
	}
}

if (!function_exists('db_GetSourceByID')) {

	function db_GetSourceByID($sourceID) {
	    $ci = &get_instance();

	    $sources_table = get_table_name('Sources');
		$ci->db->select("Organization, SourceDescription, ContactName, Phone, Email, Address, City, State, ZipCode, SourceLink, Citation");
		$ci->db->where("SourceID",$sourceID);

	    $result = $ci->db->get($sources_table);
	    if (!$result) {
	        die("<p>Error in executing the SQL query " . $ci->db->last_query() . ": " .
	            $ci->db->_error_message() . "</p>");
	    }
	    $row = $result->row("0","array");
	
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
}
//end of re-write from wof_read_db