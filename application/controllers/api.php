<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Upload API Controller 
|--------------------------------------------------------------------------
| It manages all the data points. 
| 
*/
class api extends MY_Controller {

	public function __construct()
	{
		$this->dontAuth = array('api','index','values','sources','sites','variables','methods');
		parent::__construct();
		$this->load->model('sc');
		$this->load->model('datapoints');
		$this->load->model('sources');
		$this->load->model('variables');
		$this->load->model('Site');
		$this->load->model('method');
		$this->load->model('users');		
	}
	
	public function index()
	{
		$this->load->view('services/api');
	}
	
	private function auth($jsondata) 
	{
		// checking user name and password
		if (isset($jsondata->user) && isset($jsondata->password)) {
			$user = $jsondata->user;
			$password = $jsondata->password;
		} else {
			header('HTTP/1.0 401 Unauthorized');
			echo '{"status": "401", "message":"username or password not supplied"}';
			exit;
		}
		
		$valid = $this->users->login($user, $password);
		if (!$valid) {
			header('HTTP/1.0 403 Forbidden');
			echo '{"status": "403", "message":"Bad username or password"}';
			exit;
		}	
	}
	
	private function check_json($jsondata) 
	{
		$data = json_decode($jsondata);	
		if (json_last_error() !== JSON_ERROR_NONE) {
			header('HTTP/1.0 400 Bad request');
			echo '{"status": "400", "message":"the data is not in valid json format"}';
			echo json_last_error();
			exit;
		}
		return $data;
	}
	
	private function exit_missing_parameter($parameter_name)
	{
		header('HTTP/1.0 400 Bad request');
		echo '{"status": "400", "message":"'.$parameter_name.' parameter not specified"}';
		exit;
	}
	
	private function exit_bad_parameter($parameter_name, $parameter_value)
	{
		header('HTTP/1.0 400 Bad request');
		echo '{"status": "400", "message":"the '.$parameter_name.'='.$parameter_value
		.' was not found in the database. Please supply a valid '.$parameter_name.'"}';
		exit;
	}
	
	private function getIDS($array, $id)
	{
		$ids=array();
		foreach($array as $row)
		{
			$ids[]=$row[$id];
		}
		return $ids;
	}
	
	private function cNull($val) 
	{
		if(strtolower($val)=="null")
		{
			return NULL;	
		}
		return $val;
	}
	
	private function createDP($localtimestamp,$val,$siteid,$varid,$methid,$sourceid)
	{
		$ms = $this->config->item('UTCOffset') * 3600;
		$utctimestamp = $localtimestamp - ($ms);
		$DateTimeUTC = date("Y-m-d H:i:s", $utctimestamp);
		$LocalDateTime = date("Y-m-d H:i:s", $localtimestamp);
		
		$dataPoint = array(
		'DataValue' => $val,  
		'ValueAccuracy' => $this->cNull($this->config->item('ValueAccuracy')),
		'LocalDateTime' => $LocalDateTime, 
		'UTCOffset' => $this->cNull($this->config->item('UTCOffset')), 
		'DateTimeUTC' => $DateTimeUTC, 
		'SiteID' => $siteid,
		'VariableID' => $varid, 
		'OffsetValue' => $this->cNull($this->config->item('OffsetValue')),
		'OffsetTypeID' => $this->cNull($this->config->item('OffsetTypeID')),  
		'CensorCode' => $this->cNull($this->config->item('CensorCode')),
		'QualifierID' => $this->cNull($this->config->item('QualifierID')), 
		'MethodID' => $methid, 
		'SourceID' => $sourceid, 
		'SampleID' => $this->cNull($this->config->item('SampleID')),
		'DerivedFromID' => $this->cNull($this->config->item('DerivedFromID')), 
		'QualityControlLevelID' => $this->cNull($this->config->item('QualityControlLevelID')));		
		
		return $dataPoint;
	}
	
	
	
	
	public function sources()
	{
		// adds a source to hydroserver
		// reading the POST data
		$postdata = file_get_contents('php://input');
		
		// read and check the JSON in the POST data
		$data = $this->check_json($postdata);
		
		// checking user name and password
		$this->auth($data);
		
		//check the parameters for source
		//check the parameters
		if (!isset($data->organization)) {
			$this->exit_missing_parameter("organization");
		}
		if (!isset($data->description)) {
			$this->exit_missing_parameter("description");
		}
		if (!isset($data->link)) {
			$this->exit_missing_parameter("link");
		}
		if (!isset($data->name)) {
			$this->exit_missing_parameter("name");
		}
		if (!isset($data->phone)) {
			$this->exit_missing_parameter("phone");
		}
		if (!isset($data->email)) {
			$this->exit_missing_parameter("email");
		}
		if (!isset($data->address)) {
			$this->exit_missing_parameter("address");
		}
		if (!isset($data->city)) {
			$this->exit_missing_parameter("city");
		}
		if (!isset($data->state)) {
			$this->exit_missing_parameter("state");
		}
		if (!isset($data->zipcode)) {
			$this->exit_missing_parameter("zipcode");
		}
		if (!isset($data->citation)) {
			$this->exit_missing_parameter("citation");
		}
		if (!isset($data->citation)) {
			$this->exit_missing_parameter("metadata");
		}
		
		// now we can use the model for adding one or more sources to DB
		$status = $this->sources->addsource(
					$data->organization,
					$data->description,
					$data->link,
					$data->name,
					$data->phone,
					$data->email,
					$data->address,
					$data->city,
					$data->state,
					$data->zipcode,
					$data->citation,
					$data->metadata);
		//show response status
		$response = array('status'=>'200 OK', 'message'=> 'source added: '.$status);
		echo json_encode($response);
		exit;
	}
	
	
	public function values()
	{
		// reading the POST data
		$postdata = file_get_contents('php://input');
		
		// read and check the JSON in the POST data
		$data = $this->check_json($postdata);
		
		// checking user name and password
		$this->auth($data);
		
		//Values in the JSON will be checked and fetched while processing by comparing to the ID tables.
		$siteIDS = $this->getIDS($this->site->getAll(),"SiteID");
		$sourceIDS = $this->getIDS($this->sources->getAll(),"SourceID");
		$varIDS = $this->getIDS($this->variables->getAll(),"VariableID");
		$methIDS = $this->getIDS($this->method->getAll(),"MethodID");
		
		//check the parameters
		if (!isset($data->siteid)) {
			$this->exit_missing_parameter("siteid");
		}
		if (!isset($data->sourceid)) {
			$this->exit_missing_parameter("sourceid");
		}
		if (!isset($data->variableid)) {
			$this->exit_missing_parameter("variableid");
		}
		if (!isset($data->methodid)) {
			$this->exit_missing_parameter("methodid");
		}
		if (!isset($data->values)) {
			$this->exit_missing_parameter("values");
		}		
		
		$source = $data->sourceid;
		if(!in_array($source,$sourceIDS))
		{
			$this->exit_bad_parameter("sourceid", $source);
		}			
		$site = $data->siteid;
		if(!in_array($site,$siteIDS))
		{
			$this->exit_bad_parameter("siteid", $site);
		}
		$var = $data->varid;
		if(!in_array($var,$varIDS))
		{
			$this->exit_bad_parameter("varid", $var);
		}
		$meth = $data->methodid;
		if(!in_array($meth,$methIDS))
		{
			$this->exit_bad_parameter("methodid", $meth);
		}
		
		// setting default QualityControlLevelID to 0
		$qc_id = 0;
		
		// checking utc offset
		$utcoffset = $this->config->item('UTCOffset');
		$utcOffset_Seconds = $utcoffset * 3600;
		
		$vals = $data->values;
		
		//checking the format of data values
		if (!is_array($vals)) {
			header('HTTP/1.0 400 Bad request');
			echo '{"status": "400", "message":"values is not an array.'.
			'Please specify a json array in form [[time1, value1],[time2, value2],[time3, value3]...]"';
			exit;
		}		
		
		$insertvals = array();	
		foreach($vals as $val) {
		    $LocalDateTime = $val[0];
			$datavalue = $val[1];
			$datetime = explode(" ", $LocalDateTime);
			$date = $datetime[0];
			$time = $datetime[1];
			$dataPoint = $this->createDP($date, $time,$datavalue,$site,$var,$meth,$source);
			$insertvals[]=$dataPoint;
		}			
		
		//inserting valid data values to database, using datapoint model
		$this->datapoints->addPoints($insertvals);
		$inserted_msg = count($insertvals) . ' rows successfully inserted to datavalues table';		 
	}
}