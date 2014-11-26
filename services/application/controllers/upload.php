<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class upload extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('seriescatalog_model');
		$this->load->model('values_model');
	}
	
	public function index()
	{
		$this->load->view('upload');
	}
	
	public function values()
	{
		// reading the POST data
		$postdata = file_get_contents('php://input');
		
		$data = json_decode($postdata);
		
		if (json_last_error() !== '') {
			header('HTTP/1.0 400 Bad request');
			echo '{"status": "400", "message":"the data is not in valid json format"}';
			exit;
		}
		//print_r($data);
		
		// checking user name and password
		if (property_exists($data, "user") && property_exists($data, "password")) {
			$user = $data->user;
			$password = $data->password;
		} else {
			header('HTTP/1.0 401 Unauthorized');
			echo '{"status": "401", "message":"username or password not supplied"}';
			exit;
		}
		
		$valid = $this->values_model->validate_password($user, $password);
		if ($valid === 0) {
			header('HTTP/1.0 403 Forbidden');
			echo '{"status": "403", "message":"Bad username or password"}';
			exit;
		}
		
		// checking siteCode
		if (property_exists($data, "sitecode")) {
			$sitecode = $data->sitecode;
		} else { 
			header('HTTP/1.0 400 Bad request');
			echo '{"status": "400", "message":"sitecode parameter not specified"}';
			exit;
		}
		$valid_site = $this->values_model->validate_sitecode($sitecode);
		if ($valid_site === 0) {
			header('HTTP/1.0 400 Bad request');
			echo '{"status": "400", "message":"the sitecode='.$sitecode
			.' was not found in the database. Please supply a valid sitecode."}';
			exit;
		}
		
		// checking variableCode
		if (property_exists($data, "variablecode")) {
			$variablecode = $data->variablecode;
		} else { 
			header('HTTP/1.0 400 Bad request');
			echo '{"status": "400", "message":"variablecode parameter not specified"}';
			exit;
		}
		$valid_variable = $this->values_model->validate_variablecode($variablecode);
		if ($valid_variable === 0) {
			header('HTTP/1.0 400 Bad request');
			echo '{"status": "400", "message":"the variablecode='.$variablecode
			.' was not found in the database. Please supply a valid variablecode."}';
			exit;
		}
		
		// checking methodID
		if (property_exists($data, "methodid")) {
			$methodid = $data->methodid;
		} else {
			header('HTTP/1.0 400 Bad request');
			echo '{"status": "400", "message":"methodid parameter not specified"}';
			exit;
		}
		$valid_method = $this->values_model->validate_methodid($methodid);
		if ($valid_method === 0) {
			header('HTTP/1.0 400 Bad request');
			echo '{"status": "400", "message":"the methodid='.$methodid
			.' was not found in the database. Please supply a valid methodid."}';
			exit;
		}
		
		// checking sourceID
		if (property_exists($data, "sourceid")) {
			$sourceid = $data->sourceid;
		} else {
			header('HTTP/1.0 400 Bad request');
			echo '{"status": "400", "message":"sourceid parameter not specified"}';
			exit;
		}
		$valid_source = $this->values_model->validate_sourceid($sourceid);
		if ($valid_source === 0) {		
			header('HTTP/1.0 400 Bad request');
			echo '{"status": "400", "message":"the sourceid='.$sourceid
			.' was not found in the database. Please supply a valid sourceid."}';
			exit;
		}
		
		// checking utc offset
		$utcOffset = -7;
		if (property_exists($data, "utcoffset")) {
			if (is_int($data->utcoffset)) {
				$utcOffset = $data->utcoffset;
			}
		}
		$utcOffset_Seconds = $utcOffset * 3600;
		
		// now, checking the Values
		if (!property_exists($data, "values")) {
			header('HTTP/1.0 400 Bad request');
			echo '{"status": "400", "message":"values not specified. Please set a valid values array."}';
			exit;
		}		
		
		$vals = $data->values;
		
		$seriesInfo = $this->seriescatalog_model->get_series_timerange($valid_site, $valid_variable, $valid_method, $valid_source, 0);
		if ($seriesInfo === 0) {
			$checkTimes = 0;
		} else {
			$checkTimes = 1;
			$beginTimeStamp = strtotime($seriesInfo->BeginDateTime);
			$endTimeStamp = strtotime($seriesInfo->EndDateTime);
			$seriesID = $seriesInfo->SeriesID;
			//print "beginTime: " . date("Y-m-d H:i:s", $beginTimeStamp) . "\n";
			//print "endTime: " . date("Y-m-d H:i:s", $endTimeStamp) . "\n";
		}
		
		//checking if values is an array
		if (!is_array($vals)) {
			header('HTTP/1.0 400 Bad request');
			echo '{"status": "400", "message":"values is not an array.'.
			'Please specify a json array in form [[time1, value1],[time2, value2],[time3, value3]...]"';
			exit;
		}
		
		//checking if the times, values are valid
		$insertvals = array();	
		foreach($vals as $val) {
			$timestamp = strtotime($val[0]);
			//we don't insert values that are in the existing data's <beginDateTime, endDateTime> interval
			if ($checkTimes && $timestamp >= $beginTimeStamp && $timestamp <= $endTimeStamp) {
				continue;
			}

			$insertvals[] = array(
				"DataValue" => $val[1],
				"LocalDateTime" => $val[0], 
				"UTCOffset" => $utcOffset,
				"DateTimeUTC" => date("Y-m-d H:i:s", $timestamp - $utcOffset_Seconds),
				"SiteID" => $valid_site,
				"VariableID" => $valid_variable,
				"MethodID" => $valid_method, 
				"SourceID" => $valid_source,
				"QualityControlLevelID" => 0, 
				"CensorCode" => "nc");
		}			
		
		//inserting valid data values to database
		$inserted = $this->values_model->insert_values($insertvals);
		$inserted_msg = $inserted . ' rows inserted to datavalues table';
		
		//updating seriescatalog table
		if ($checkTimes === 1) {
			$sc_updated = $this->seriescatalog_model->update_series($seriesID, $valid_site, $valid_variable, $valid_method, $valid_source, 0);
			$sc_message = $sc_updated . ' row updated in series catalog';
		} else {
			$sc_inserted = $this->seriescatalog_model->insert_series($valid_site, $valid_variable, $valid_method, $valid_source, 0);
			$sc_message = $sc_inserted . ' row inserted to series catalog';
		}
		
		//show response status
		$response = array('status'=>'200 OK', 'message'=> $inserted_msg . ',' . $sc_message);
		echo json_encode($response);
		exit;
	}
}