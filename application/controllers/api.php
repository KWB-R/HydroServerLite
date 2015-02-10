<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Data Uploading API Controller 
|--------------------------------------------------------------------------
| It manages all the REST API calls for uploading data to HydroServer
| 
*/
class api extends MY_Controller {

	public function __construct()
	{
		$this->dontAuth = array('api','index','values','sources','sites','variables','methods','GetSourcesJSON','GetSitesJSON','GetVariablesJSON');
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
	private function exit_duplicate_parameter($parameter_name, $parameter_value)
	{
		header('HTTP/1.0 400 Bad request');
		echo '{"status": "400", "message":"the '.$parameter_name.'='.$parameter_value
		.' already exists in the database. Please use a different '.$parameter_name.'"}';
		exit;
	}
	private function exit_error($message)
	{
		header('HTTP/1.0 400 Bad request');
		echo '{"status": "400", "database error":"'.$message.'"}';
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
	
	private function createDP($date,$time,$val,$siteid,$varid,$methid,$sourceid)
	{
		
		$LocalDateTime = $date . " " . $time;
		$localtimestamp = strtotime($LocalDateTime);
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
	
	public function GetSourcesJSON()
	{
		$allSources = $this->sources->GetWithMetadata();
		
		// display everything from sources in JSON
		header('Content-Type: application/json'); 
		echo json_encode($allSources);
	}
	
	public function GetSitesJSON()
	{
		$allSites = $this->Site->displayAllWithSource();
		
		// display everything from sources in JSON
		header('Content-Type: application/json'); 
		echo json_encode($allSites);
	}
	
	public function GetVariablesJSON()
	{
		$allVariables = $this->variables->GetAllWithUnits();
		
		// display everything from sources in JSON
		header('Content-Type: application/json'); 
		echo json_encode($allVariables);
	}
	
	public function GetMethodsJSON()
	{
		$allMethods = $this->method->GetAll();
		
		// display everything from sources in JSON
		header('Content-Type: application/json'); 
		echo json_encode($allMethods);
	}
	
	
	
	
	public function sites()
	{
		// adds a source to hydroserver
		// reading the POST data
		$postdata = file_get_contents('php://input');
		
		// read and check the JSON in the POST data
		$data = $this->check_json($postdata);
		
		// checking user name and password
		$this->auth($data);
		
		//check the parameters for site
		//check the parameters
		if (!isset($data->SourceID)) {
			$this->exit_missing_parameter("SourceID");
		}
		
		if (!isset($data->SiteName)) {
			$this->exit_missing_parameter("SiteName");
		}
		if (!isset($data->SiteCode)) {
			$this->exit_missing_parameter("SiteCode");
		}
		if (!isset($data->Latitude)) {
			$this->exit_missing_parameter("Latitude");
		}
		if (!isset($data->Longitude)) {
			$this->exit_missing_parameter("Longitude");
		}	
		if (!isset($data->SiteType)) {
			$this->exit_missing_parameter("SiteType");
		}
		if (!isset($data->Elevation_m)) {
			$this->exit_missing_parameter("Elevation_m");		
		}
		//set default vertical datum
		$VerticalDatum = "MSL";
		if (isset($data->VerticalDatum)) {
			$VerticalDatum = $data->VerticalDatum;
		}
		//set default LatLongDatumID = WGS84 [id=3]
		$LatLongDatumID = 3;
		if (isset($data->LatLongDatumID)) {
			$LatLongDatumID = $data->LatLongDatumID;
		}
		//set default state, county, comments
		$State = NULL;
		if (isset($data->State)) {
			$state = $data->State;
		}
		$County = NULL;
		if (isset($data->County)) {
			$county = $data->County;
		}
		$Comments = NULL;
		if (isset($data->Comments)) {
			$comments = $data->Comments;
		}
		
		//check if the SiteCode is valid: can't insert duplicate site code
		$siteCodes = $this->getIDS($this->Site->getAll(),"SiteCode");
		$SiteCode = $data->SiteCode;
		if(in_array($SiteCode,$siteCodes))
		{
			$this->exit_duplicate_parameter("SiteCode", $SiteCode);
		}	
		
		//check if source id is valid
		$sourceIDS = $this->getIDS($this->sources->getAll(),"SourceID");
		$SourceID = $data->SourceID;
		if(!in_array($SourceID,$sourceIDS))
		{
			$this->exit_bad_parameter("SourceID", $SourceID);
		}	
		
		$Site = array
		(
			'SiteCode' => $data->SiteCode,
			'SiteName' => $data->SiteName,
			'Latitude' =>  $data->Latitude,
			'Longitude' =>$data->Longitude,
			'LatLongDatumID' =>$LatLongDatumID,
			'SiteType' => $data->SiteType,
			'Elevation_m' =>  $data->Elevation_m,
			'VerticalDatum' =>$VerticalDatum,
			'State' => $State,
			'County' =>  $County,
			'Comments' =>  $Comments
		);	
		
		// now we can use the model for adding one site to DB
		$result = $this->Site->add($Site);
		if($result<=0)
		{
			exit_error(getTxt('ProcessingError')." Error while adding site. ");
		}		
		$siteID = $result;
		
		//In this part we use the series catalog to associate the Site and Source
		$source = $this->sources->get($SourceID);
					
		$series = array
		(
			'SiteID' => $siteID,
			'SiteCode' => $data->SiteCode,
			'SiteName' =>  $data->SiteName,
			'SiteType' => $data->SiteType,
			'SourceID' =>  $data->SourceID,
			'Organization' =>$source[0]['Organization'],
			'SourceDescription' => $source[0]['SourceDescription'],
			'Citation' =>  $source[0]['Citation'],
			'ValueCount' =>  0
		);	

		//Add to the series catalog
		$result=$this->sc->add($series);
		if($result)
		{
			//show response status
			$response = array('status'=>'200 OK', 'message'=> 'site added: ID='.$siteID);
			echo json_encode($response);
			exit;
		}	
		else
		{
			exit_error(getTxt('ProcessingError')." Error while editing SeriesCatalog for the site. ");	
		}
	}
	
	
	public  function variables()
    {
        // adds a variable to hydroserver
		// reading the POST data
		$postdata = file_get_contents('php://input');
		
		// read and check the JSON in the POST data
		$data = $this->check_json($postdata);
		
		// checking user name and password
		$this->auth($data);
		
        //check the parameters for variable
		if (!isset($data->VariableCode)) {
			$this->exit_missing_parameter("VariableCode");
		}
                
        if (!isset($data->VariableName)) {
			$this->exit_missing_parameter("VariableName");
		}
                
        if (!isset($data->Speciation)) {
			$this->exit_missing_parameter("Speciation");
		}
                
        if (!isset($data->VariableUnitsID)) {
			$this->exit_missing_parameter("VariableUnitsID");
		}
                
        if (!isset($data->SampleMedium)) {
			$this->exit_missing_parameter("SampleMedium");
		}
                
        if (!isset($data->ValueType)) {
			$this->exit_missing_parameter("ValueType");
		}
                
        if (!isset($data->IsRegular)) {
			$this->exit_missing_parameter("IsRegular");
		}
                
        if (!isset($data->TimeSupport)) {
			$this->exit_missing_parameter("TimeSupport");
		}
                
        if (!isset($data->TimeUnitsID)) {
			$this->exit_missing_parameter("TimeUnitsID");
		}
                
        if (!isset($data->DataType)) {
			$this->exit_missing_parameter("DataType");
		}
                
        if (!isset($data->GeneralCategory)) {
			$this->exit_missing_parameter("GeneralCategory");
		}
                               
        if (!isset($data->NoDataValue)) {
			$this->exit_missing_parameter("NoDataValue");
		}
		//check if the VariableCode is valid: can't insert duplicate variable code
		$VariableCodes = $this->getIDS($this->variables->getAll(),"VariableCode");
		$VariableCode = $data->VariableCode;
		if(in_array($VariableCode,$VariableCodes))
		{
			$this->exit_duplicate_parameter("VariableCode", $VariableCode);
		}	
		$Variable = array
		(
			'VariableCode' => $VariableCode,
			'VariableName' => $data->VariableName,
			'Speciation' =>  $data->Speciation,
			'VariableUnitsID' =>$data->VariableUnitsID,
			'SampleMedium' =>$data->SampleMedium,
			'ValueType' => $data->ValueType,
			'IsRegular' =>  $data->IsRegular,
            'TimeSupport' => $data->TimeSupport,
			'TimeUnitsID' =>  $data->TimeUnitsID,
            'DataType' =>  $data->DataType,
            'GeneralCategory' =>  $data->GeneralCategory,
            'NoDataValue' =>  $data->NoDataValue			
		);	
		
        // now we can use the model for adding one variable to the DB
		$result = $this->variables->add($Variable);
                
		if($result<=0) {
			exit_error(getTxt('ProcessingError')." Error while adding site. ");
		}	
        $variableID = $result;       
               
		if($result) {
			//show response status
			$response = array('status'=>'200 OK', 'message'=> 'variable added: ID='.$variableID);
			echo json_encode($response);
			exit;
		}	
		else {
			exit_error(getTxt('ProcessingError')." Error while adding variable. ");	
		}             
    }
	
	public function methods()
	{
		// adds a method to hydroserver
		// reading the POST data
		$postdata = file_get_contents('php://input');
		$data = $this->check_json($postdata);
		$this->auth($data);
		
		//check the parameters for method
		if (!isset($data->MethodDescription)) {
			$this->exit_missing_parameter("MethodDescription");
		}
		if (!isset($data->MethodLink)) {
			$this->exit_missing_parameter("MethodLink");
		}
		//variableID is an optional parameter.
		if (isset($data->VariableID)) {
			$VariableID = $data->VariableID;
		} else {
			$VariableID = 0;
		}
		$status = $this->method->add($data->MethodDescription, $data->MethodLink, $VariableID);
		//show response status
		$response = array('status'=>'200 OK', 'message'=> 'method added: '.$status);
		echo json_encode($response);
		exit;
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
		$siteIDS = $this->getIDS($this->Site->getAll(),"SiteID");
		$sourceIDS = $this->getIDS($this->sources->getAll(),"SourceID");
		$varIDS = $this->getIDS($this->variables->getAll(),"VariableID");
		$methIDS = $this->getIDS($this->method->getAll(),"MethodID");
		
		//check the parameters
		if (!isset($data->SiteID)) {
			$this->exit_missing_parameter("SiteID");
		}
		if (!isset($data->SourceID)) {
			$this->exit_missing_parameter("SourceID");
		}
		if (!isset($data->VariableID)) {
			$this->exit_missing_parameter("VariableID");
		}
		if (!isset($data->MethodID)) {
			$this->exit_missing_parameter("MethodID");
		}
		if (!isset($data->values)) {
			$this->exit_missing_parameter("values");
		}		
		
		$source = $data->SourceID;
		if(!in_array($source,$sourceIDS))
		{
			$this->exit_bad_parameter("SourceID", $source);
		}			
		$site = $data->SiteID;
		if(!in_array($site,$siteIDS))
		{
			$this->exit_bad_parameter("SiteID", $site);
		}
		$var = $data->VariableID;
		if(!in_array($var,$varIDS))
		{
			$this->exit_bad_parameter("VariableID", $var);
		}
		$meth = $data->MethodID;
		if(!in_array($meth,$methIDS))
		{
			$this->exit_bad_parameter("MethodID", $meth);
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

		//updating the series catalogue
		$this->updateSC();
		
		//returning the status message
		$response = array('status'=>'200 OK', 'message'=> $inserted_msg);
		echo json_encode($response);
		exit;
	}
	
	private function updateSC()
	{
		//UPDATES THE SERIES CATALOG.
		//BREAKING FROM CODEIGINITER POLICIES HERE 
		//Its just better for now to use the same script. 
		
		
		$connection = mysqli_connect($this->config->item('database_host'), $this->config->item('database_username'), $this->config->item('database_password'),$this->config->item('database_name'))
		or die("<p>Error connecting to database: " . 
				   mysqli_error() . "</p>");
		mysqli_set_charset ($connection,"utf8");
		  //echo "<p>Connected to MySQL!</p>";
		  
		/*  $db = mysql_select_db($this->config->item('database_name'),$connection)
			or die("<p>Error selecting the database " . $this->config->item('database_name') .
			  mysql_error() . "</p>");
		*/
		require_once APPPATH.'../assets/update_series_catalog.php';
	}
}