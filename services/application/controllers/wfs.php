<?php

class Wfs extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('wfs_model');
	}
	
	function write_xml()
	{
		// TO DO: check to see the conditions of the different attribute
		// what to display and when to display
		
		if( ($this->input->get('request') == 'GetFeature') || ($this->input->get('REQUEST') == 'GetFeature') || ($this->input->post('request') == 'GetFeature') || ($this->input->post('REQUEST') == 'GetFeature') )
		{
			if( $this->input->get('SRSNAME') != NULL || strlen($this->input->get('SRSNAME')) > 0 )
			{
				$srsname	= explode(':', $this->input->get('SRSNAME'));
				$srsname	= explode('-', $srsname[1]);
				$siteID		= $srsname[0];
				$variableID = $srsname[1];
				$features	= $this->wfs_model->get_features( $siteID, $variableID );
			}
			else
			{
				//by default we choose a variable to display the features
				$features	= $this->wfs_model->get_features( 1, 1 );
			}
			header('Content-Type:text/xml; charset=UTF-8', TRUE);
			//$features			= $this->wfs_model->check_features($features);
			$data['watermlurl']	= htmlspecialchars(base_url() . 'services/' . 'cuahsi_1_1.asmx/GetValuesObject?location=' . $this->config->item('service_code') . ':' . trim($features->SiteCode) . '&variable=' . $this->config->item('service_code') . ':' . trim($features->VariableCode));
			$data['feat']		= $features;
			
			$this->load->view('get_feature', $data);
		}
		else if(($this->input->get('request') == 'DescribeFeatureType') || ($this->input->get('REQUEST') == 'DescribeFeatureType') || ($this->input->post('request') == 'DescribeFeatureType') || ($this->input->post('REQUEST') == 'DescribeFeatureType'))
		{
			header('Content-Type:text/xml; charset=UTF-8', TRUE);
			
			$this->load->view('describe_feature_type');		 
		}
		else if(($this->input->get('request') == 'GetCapabilities') || ($this->input->get('REQUEST') == 'GetCapabilities') || ($this->input->post('request') == 'GetCapabilities') || ($this->input->post('REQUEST') == 'GetCapabilities'))
		{
			header('Content-Type:text/xml; charset=UTF-8', TRUE);
			$data['sites']		= $this->wfs_model->get_sites();
			$data['variableID'] = $this->input->get('VariableID') ? $this->input->get('VariableID') : 1;
			
			$this->load->view('get_capabilities', $data);
		}
		else
		{
			header('Content-Type:text/xml; charset=UTF-8', TRUE);
			$this->load->view('request_error');
		}
	}
	
	function variables()
	{
		// show the variables from the Variables table in DB
		// based on them do the listing of the sites and seriescatalog on REQUESTS
		$data['variables'] = $this->wfs_model->get_variables();
		//print_r($variables);
		$this->load->view('variables', $data);
	}
	
	function wfs_server()
	{

		// Manually tested this piece of code in order to check the requests.
		// Code based on a similar idea but build in Java
		
		header('Content-Type:text/xml; charset=UTF-8', TRUE);
		header('Connection:close', TRUE);


		//$userFromUri = "mhoegh"; // for testing

		logfile::write($userFromUri."\n\n");

		
		// We connect to the users db
		$postgisdb = $userFromUri;
		$srs=$srsFromUri;
		$postgisschema = $schemaFromUri;



		$postgisObject = new postgis();
		//$user = new users($userFromUri);
		//$version = new version($user);

		$geometryColumnsObj = new GeometryColumns();

		function microtime_float()
		{
			list($utime, $time) = explode(" ", microtime());
			return ((float)$utime + (float)$time);
		}
		$startTime = microtime_float();

		//ini_set("display_errors", "On");

		$thePath= "http://".$_SERVER['SERVER_NAME'].$_SERVER['REDIRECT_URL'];
		//$thePath= "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
		$server="http://".$_SERVER['SERVER_NAME'];
		$BBox=null;
		//end added
		$currentTable=null;
		$currentTag=null;
		$gen=array();
		$gen[0]="";
		$level=0;
		$depth=0;
		$tables=array();
		$fields=array();
		$wheres=array();
		$limits=array();

		logfile::write("\nRequest\n\n");
		logfile::write($HTTP_RAW_POST_DATA."\n\n");

		$unserializer_options = array (
			'parseAttributes' => TRUE,
			'typeHints' => FALSE
		);
		$unserializer = new XML_Unserializer($unserializer_options);

		/*$HTTP_RAW_POST_DATA = '<?xml version="1.0" encoding="utf-8"?><wfs:Transaction service="WFS" version="1.0.0" xmlns="http://www.opengis.net/wfs" xmlns:mrhg="http://twitter/mrhg" xmlns:ogc="http://www.opengis.net/ogc" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><wfs:Insert idgen="GenerateNew"><mrhg:hej><the_geom><gml:MultiPolygon srsName="urn:x-ogc:def:crs:EPSG:6.9:4326"><gml:polygonMember><gml:Polygon><gml:exterior><gml:LinearRing><gml:coordinates>5.0657329559,-41.1107215881 8.4824724197,-39.3435783386 4.3241734505,-34.6001853943 5.0657329559,-41.1107215881 </gml:coordinates></gml:LinearRing></gml:exterior></gml:Polygon></gml:polygonMember></gml:MultiPolygon></the_geom></mrhg:hej></wfs:Insert></wfs:Transaction>';*/


		/*$HTTP_RAW_POST_DATA = '<?xml version="1.0"?><DescribeFeatureType  version="1.1.0"  service="WFS"  xmlns="http://www.opengis.net/wfs"  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"  xsi:schemaLocation="http://www.opengis.net/wfs http://schemas.opengis.net/wfs/1.1.0/wfs.xsd">    <TypeName>california_coastline</TypeName></DescribeFeatureType>';
		 */

		// Post method is used
		if ($HTTP_RAW_POST_DATA) {
			//$forUseInSpatialFilter = $HTTP_RAW_POST_DATA; // We store a unaltered version of the raw request
			$HTTP_RAW_POST_DATA = dropNameSpace($HTTP_RAW_POST_DATA);
			logfile::write($HTTP_RAW_POST_DATA."\n\n");

			$status = $unserializer->unserialize($HTTP_RAW_POST_DATA);
			$arr = $unserializer->getUnserializedData();
			$request = $unserializer->getRootName();
			//print_r($arr);
			switch ($request){
				case "GetFeature":
					if (!is_array($arr['Query'][0])){
						$arr['Query'] = array(0 => $arr['Query']);
					}
					for ($i=0;$i<sizeof($arr['Query']);$i++){
						if (!is_array($arr['Query'][$i]['PropertyName'])) {
							$arr['Query'][$i]['PropertyName'] = array(0 => $arr['Query'][$i]['PropertyName']);
						}
					}
					$HTTP_FORM_VARS["REQUEST"] = "GetFeature";
					foreach ($arr['Query'] as $queries) {
						$HTTP_FORM_VARS["TYPENAME"].= $queries['typeName'].",";
						if ($queries['PropertyName'][0]) {
							foreach ($queries['PropertyName'] as $PropertyNames) {
								// We check if typeName is prefix and add it if its not
								if (strpos($PropertyNames, ".")) {
									$HTTP_FORM_VARS["PROPERTYNAME"].= $PropertyNames.",";
								}
								else {
									$HTTP_FORM_VARS["PROPERTYNAME"].= $queries['typeName'].".".$PropertyNames.",";
								}
							}
						}
						if (is_array($queries['Filter']) && $arr['version']=="1.0.0") {
							@$checkXml = simplexml_load_string($queries['Filter']);
							if($checkXml===FALSE) {
								makeExceptionReport("Filter is not valid");
							}
							$wheres[$queries['typeName']] = parseFilter($queries['Filter'],$queries['typeName']);
						}
					}
					$HTTP_FORM_VARS["TYPENAME"] = dropLastChrs($HTTP_FORM_VARS["TYPENAME"], 1);
					$HTTP_FORM_VARS["PROPERTYNAME"] = dropLastChrs($HTTP_FORM_VARS["PROPERTYNAME"], 1);
					break;
				case "DescribeFeatureType":
					$HTTP_FORM_VARS["REQUEST"] = "DescribeFeatureType";
					$HTTP_FORM_VARS["TYPENAME"] = $arr['TypeName'];
					//if (!$HTTP_FORM_VARS["TYPENAME"]) $HTTP_FORM_VARS["TYPENAME"] = $arr['typeName'];
					break;
				case "GetCapabilities":
					$HTTP_FORM_VARS["REQUEST"] = "GetCapabilities";
					break;
				case "Transaction":
					$HTTP_FORM_VARS["REQUEST"] = "Transaction";
					if (isset($arr["Insert"])) {
						$transactionType = "Insert";
					}
					if ($arr["Update"]) {
						$transactionType = "update";
					}
					if ($arr["Delete"]) $transactionType = "Delete";

					break;
			}
		}
		// Get method is used
		else {
			if (sizeof($_GET) > 0) {
				logfile::write($_SERVER['QUERY_STRING']."\n\n");
				$HTTP_FORM_VARS = $_GET;
				$HTTP_FORM_VARS = array_change_key_case($HTTP_FORM_VARS,CASE_UPPER);// Make keys case insensative
				$HTTP_FORM_VARS["TYPENAME"] = dropNameSpace($HTTP_FORM_VARS["TYPENAME"]);// We remove name space, so $where will get key without it.

				if ($HTTP_FORM_VARS['FILTER']) {
					@$checkXml = simplexml_load_string($HTTP_FORM_VARS['FILTER']);
					if($checkXml===FALSE) {
						makeExceptionReport("Filter is not valid");
					}
					//$forUseInSpatialFilter = $HTTP_FORM_VARS['FILTER'];
					$status = $unserializer->unserialize(dropNameSpace($HTTP_FORM_VARS['FILTER']));
					$arr = $unserializer->getUnserializedData();
					$wheres[$HTTP_FORM_VARS['TYPENAME']] = parseFilter($arr,$HTTP_FORM_VARS['TYPENAME']);
				}
			}
			else {
				$HTTP_FORM_VARS = array("");
			}
		}

		//HTTP_FORM_VARS is set in script if POST is used
		$HTTP_FORM_VARS = array_change_key_case($HTTP_FORM_VARS,CASE_UPPER);// Make keys case
		$HTTP_FORM_VARS["TYPENAME"] = dropNameSpace($HTTP_FORM_VARS["TYPENAME"]);
		$tables = explode(",",$HTTP_FORM_VARS["TYPENAME"]);
		$properties = explode(",", dropNameSpace($HTTP_FORM_VARS["PROPERTYNAME"]));
		$featureids = explode(",", $HTTP_FORM_VARS["FEATUREID"]);
		$bbox = explode(",", $HTTP_FORM_VARS["BBOX"]);

		// Start HTTP basic authentication
		//if(!$_SESSION["oauth_token"]) {
		$auth = $postgisObject->getGeometryColumns($postgisschema.".".$HTTP_FORM_VARS["TYPENAME"],"authentication");
		
		//}
		// End HTTP basic authentication
		print ("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
		ob_start();
		if (!(empty($properties[0]))) {
			foreach ($properties as $property) {
				$__u=explode(".", $property); // Is it "/" for get method?
				// We first check if typeName is namespace
				if ($__u[1]) {
					foreach ($tables as $table) {
						if ($table==$__u[0]) {
							$fields[$table].=$__u[1].",";
						}
					}
				}
				// No, typeName is not a part of value
				else {
					foreach ($tables as $table) {
						$fields[$table].=$property.",";
					}
				}

			}
		}
		if (!(empty($featureids[0]))) {
			foreach ($featureids as $featureid) {
				$__u=explode(".", $featureid);
				foreach ($tables as $table) {
					$primeryKey = $postgisObject->getPrimeryKey($postgisschema.".".$table);
					if ($table==$__u[0]) {
						$wheresArr[$table][]="{$primeryKey['attname']}={$__u[1]}";
					}
					$wheres[$table] = implode(" OR ",$wheresArr[$table]);
				}
			}
		}
		
		//get the request
		switch (strtoupper($HTTP_FORM_VARS["REQUEST"])) {
			case "GETCAPABILITIES":
				getCapabilities($postgisObject);
				break;
			case "GETFEATURE":
				if (!$gmlFeatureCollection) {
					$gmlFeatureCollection = "wfs:FeatureCollection";
				}
				print "<".$gmlFeatureCollection."\n";
				print "xmlns=\"http://www.opengis.net/wfs\"\n";
				print "xmlns:wfs=\"http://www.opengis.net/wfs\"\n";
				print "xmlns:gml=\"http://www.opengis.net/gml\"\n";
				print "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
				print "xmlns:{$gmlNameSpace}=\"{$gmlNameSpaceUri}\"\n";

				if ($gmlSchemaLocation) {
					print "xsi:schemaLocation=\"{$gmlSchemaLocation}\"";
				}
				else {
					//print "xsi:schemaLocation=\"{$gmlNameSpaceUri} {$thePath}?REQUEST=DescribeFeatureType&amp;TYPENAME=".$HTTP_FORM_VARS["TYPENAME"]." http://www.opengis.net/wfs ".str_replace("server.php","",$thePath)."schemas/wfs/1.0.0/WFS-basic.xsd\"";
					print "xsi:schemaLocation=\"{$gmlNameSpaceUri} {$thePath}?REQUEST=DescribeFeatureType&amp;TYPENAME=".$HTTP_FORM_VARS["TYPENAME"]." http://www.opengis.net/wfs http://wfs.plansystem.dk:80/geoserver/schemas/wfs/1.0.0/WFS-basic.xsd\"";
				}
				print ">\n";
				doQuery("Select");
				print "</".$gmlFeatureCollection.">";

				break;
			case "DESCRIBEFEATURETYPE":
				getXSD($postgisObject);
				break;
			case "TRANSACTION":
				doParse($arr);
				break;
			default:
				makeExceptionReport("Don't know that request");
				break;
		}
	}
}
?>
