<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Data Controller 
|--------------------------------------------------------------------------
| It manages all the data points. 
| 
*/
class Datapoint extends MY_Controller {
	
	function __construct()
	{
		$this->dontAuth = array('getData','getDataJSON','compare','export');
		parent::__construct();

		$models = array('variables', 'sources', 'datapoints', 'sc', 'site');

		$this->loadModels($models);

		$this->load->library('form_validation');
	}
	
	private function loadModels($modelNames)
	{
		foreach ($modelNames as $modelName) {
			$this->loadModel($modelName);
		}
	}

	private function loadModel($modelName)
	{
		$this->load->model($modelName, '', TRUE);
	}

	private function createDP($date,$time,$val,$siteid,$varid,$methid,$sourceid)
	{
		$dateFormat = "Y-m-d H:i:s";
		
		$localtimestamp = strtotime($date . " " . $time);
		$offsetInSeconds = $this->config->item('UTCOffset') * 3600;
		
		$dataPoint = array(
		'DataValue' => $val,  
		'ValueAccuracy' => $this->getConfigItem('ValueAccuracy'),
		'LocalDateTime' => date($dateFormat, $localtimestamp), 
		'UTCOffset' => $this->getConfigItem('UTCOffset'), 
		'DateTimeUTC' => date($dateFormat, $localtimestamp - $offsetInSeconds), 
		'SiteID' => $siteid,
		'VariableID' => $varid, 
		'OffsetValue' => $this->getConfigItem('OffsetValue'),
		'OffsetTypeID' => $this->getConfigItem('OffsetTypeID'),
		'CensorCode' => $this->getConfigItem('CensorCode'),
		'QualifierID' => $this->getConfigItem('QualifierID'), 
		'MethodID' => $methid, 
		'SourceID' => $sourceid, 
		'SampleID' => $this->getConfigItem('SampleID'),
		'DerivedFromID' => $this->getConfigItem('DerivedFromID'), 
		'QualityControlLevelID' => $this->getConfigItem('QualityControlLevelID'));		
		
		return $dataPoint;
	}

	private function getConfigItem($name)
	{
		$value = $this->config->item($name);

		if(strtolower($value) == "null") {
			$value = NULL;
		}

		return $value;
	}
	
	public function addvalue()
	{		
		if($_POST)
		{
			$this->setFormValidationRules();
		}
		
		if ($this->form_validation->run() == FALSE)
		{
			  $errors = validation_errors();
			  if(!empty($errors))
			  {
			  	addError($errors);
			  }
		}
		else
		{
			$result = $this->datapoints->addPoint(
				$this->createDataPointFromInputs()
			);
			
			$this->addSuccessOrError($result, 'ValueSuccessfully');
		}
		
		// Set style and option values (sources, variables) and load the view
		$this->loadViewWithStyleAndOptions('datapoint/addvalue');
	}

	private function loadViewWithStyleAndOptions($view, $setVariableOptions = TRUE)
	{
		//List of CSS to pass to this view
		$data = $this->StyleData;

		$data['sourcesOptions'] = optionsSource($this->sources->getAll());

		if ($setVariableOptions) {
			$data['variableOptions'] = optionsVariable($this->variables->getAll());
		}
		
		$this->load->view($view, $data);
	}

	private function addSuccessOrError($success, $successKey, $errorMessage = '')
	{
		if ($success) {
			addSuccess(getTxt($successKey));
			$this->updateSC();	
		}
		else {
			addError(getTxt('ProcessingError') . $errorMessage);
		}
	}
	
	private function setFormValidationRules()
	{
		$this->form_validation->set_rules('SourceID', 'SourceID', 'trim|required');
		$this->form_validation->set_rules('SiteID', 'SiteID', 'trim|required');	
		$this->form_validation->set_rules('VariableID', 'SourceID', 'trim|required');
		$this->form_validation->set_rules('MethodID', 'SiteID', 'trim|required');	
		$this->form_validation->set_rules('value', 'SourceID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('datepicker', 'SiteID', 'trim|required');
		$this->form_validation->set_rules('timepicker', 'SiteID', 'trim|required');
	}

	private function createDataPointFromInputs($postfix = '')
	{
		return $this->createDP(
			$this->input->post('datepicker' . $postfix),
			$this->input->post('timepicker' . $postfix),
			$this->input->post('value' . $postfix),
			$this->input->post('SiteID'),
			$this->input->post('VariableID' . $postfix),
			$this->input->post('MethodID' . $postfix),
			$this->input->post('SourceID')
		);
	}

	public function addmultiplevalues()
	{	
		if($_POST)
		{
			$dataset = array();
			$rows = $this->input->post('finalRows');
			
			for ($i = 1; $i <= $rows; $i++)
			{
				$dataset[] = $this->createDataPointFromInputs($i);
			}
			
			$result = $this->datapoints->addPoints($dataset);
	
			$this->addSuccessOrError($result == $rows, 'DataEnteredSuccessfully');			
		}
		
		// Set style and option values (sources only) and load the view
		$this->loadViewWithStyleAndOptions('datapoint/addmultiplevalues', FALSE);
	}
	
	private function fileUploadHandler()
	{
		$newDir = "./uploads/temp".time().rand();
		$oldmask = umask(0);
		$result = mkdir($newDir,0777);
		umask($oldmask);
		if(!$result)
		{
			addError(getTxt('FailTemp'));
			return false;
		}
		
		//Upload files. 
		$config['upload_path'] = $newDir;
		$config['allowed_types'] = 'csv|CSV';	
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_multi_upload('files'))
		  {
			  addError(getTxt('FailMoveFile').$this->upload->display_errors());
			  return false;
		  }
		return $this->upload->get_multi_upload_data();
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
	
	private function processFiles($files)
	{
		$check=false;
		if($this->input->post('valueSpec'))
		{
			$check=true;
			//Values will be checked and fetched while processing.
			//Build ID tables.
			$siteIDS = $this->getIDS($this->site->getAll(),"SiteID");
			$sourceIDS = $this->getIDS($this->sources->getAll(),"SourceID");
			$varIDS = $this->getIDS($this->variables->getAll(),"VariableID");
			$this->loadModel('method');
			$methIDS = $this->getIDS($this->method->getAll(),"MethodID");
		}
		else
		{
			$source =  $this->input->post('SourceID');
			$site =  $this->input->post('SiteID');
			$var =  $this->input->post('VariableID');
			$meth =  $this->input->post('MethodID');
		}
		$dataset=array();
		foreach($files as $file)
		{
			$filepath = $file['full_path'];	
			$handle = fopen($filepath, "r");
			if(!$handle)
			{
				addError(getTxt('FailInputStream'));
				return;	
			}
			$flag=0;
			$row=1;
			$tracker=1;
			$dtIndex = 0;
			$valIndex = 1;
			while (($data = fgetcsv($handle)) !== FALSE) {
				if($flag==0)
				{
					if($check)
					{
						$dtIndex=4;
						$valIndex = 5;
						if(($data[0]!="SourceID")||($data[1]!="SiteID")||($data[2]!="VariableID")||($data[3]!="MethodID")||($data[4]!="LocalDateTime")||($data[5]!="DataValue"))	
						{
						addError(getTxt('InvalidHeading')."SourceID,SiteID,VariableID,MethodID,LocalDateTime,DataValue".getTxt('PleaseFix'));
						return false;					
						}	
					}
					else
					{

						//Allow switching of columns. 
						$dtIndex = array_search(strtolower("LocalDateTime"),array_map('strtolower',$data)); 
						$valIndex=array_search(strtolower("DataValue"),array_map('strtolower',$data));
						if($dtIndex===false||$valIndex===false)	
						{
						addError(getTxt('InvalidHeading')."LocalDateTime,DataValue".getTxt('PleaseFix'));
						return false;					
						}
					}
					$flag=1;
					continue;
				}
				
				try {
					$date = new DateTime($data[$dtIndex]);
				} catch (Exception $e) {
					//add error for invalid datatime format. on $row in file
					$msg=getTxt('InvalidTime').' '.$row.' '.getTxt('In').' '.$file['file_name'];
					addError($msg.getTxt('PleaseFix')."(".$e->getMessage().")");
					return false;
				}
				//Validate Value
				$value = $data[$valIndex];
				$regex="/^[\-+]?[0-9]*\.?[0-9]+$/";

				if (!preg_match($regex,$value)) {
				   $msg=getTxt('InvalidChar').' '.$row.' '.getTxt('In').' '.$file['file_name'];
				   addError($msg.getTxt('PleaseFix'));
				   return false;
				} 
				$dateVal = $date->format("Y-m-d");
				$timeVal = $date->format("H:i:s");
				
				if ($check)
				{
					//Verify if entered IDS are correct. 
					$source =  $data[0];
					$site =  $data[1];
					$var =  $data[2];
					$meth =  $data[3];
					
					if (	
						$this->addErrorIfInvalid($source, $sourceIDS, 'sourceid', $row, $file)
						or $this->addErrorIfInvalid($site, $siteIDS, 'siteid', $row, $file)
						or $this->addErrorIfInvalid($var, $varIDS, 'varid', $row, $file)
						or $this->addErrorIfInvalid($meth, $methIDS, 'methodid', $row, $file)
					) {
						return false;
					}
				}
				
				$dataPoint = $this->createDP($dateVal,$timeVal,$value,$site,$var,$meth,$source);
				$dataset[]=$dataPoint;
				$row++;
			}
			
		}
		return $dataset;
	}

	private function addErrorIfInvalid($id, $ids, $idName, $row, $file)
	{
		$isError = !in_array($id, $ids);
		
		if ($isError)
		{
			addError($this->idErrorMessage($idName, $row, $file));
		}
		
		return $isError;
	}
	
	private function idErrorMessage($idName, $row, $file)
	{
		$message = sprintf("%s %s. Row: %s %s %s", 
			getTxt('invalid'), getTxt($idName), $row, getTxt('In'), $file['file_name']
		);

		return $message . getTxt('PleaseFix');
	}

	public function importfile()
	{
		
		if($_POST)
		{
			$result = $this->fileUploadHandler();
			if($result)
			{
				$dataset = $this->processFiles($result);
				if($dataset)
				{
					$rows = count($dataset);
					$result = $this->datapoints->addPoints($dataset);
					
					$this->addSuccessOrError($result, 'Success', 'Error in data input');
				}
			}	
		}
		
		// Set style and option values (sources, variables) and load the view
		$this->loadViewWithStyleAndOptions('datapoint/importfile');
	}
	
	public function getData()
	{
		$var = $this->getInputOrTRUE('varid');
		$site = $this->getInputOrTRUE('siteid');	
		$method = $this->getInputOrTRUE('meth');
		$start = $this->getInputOrTRUE('startdate');	
		$end = $this->getInputOrTRUE('enddate');
		if($var!==false&&$site!==false&&$method!==false&&$start!==false&&$end!==false)
		{
			$result = $this->datapoints->getData($site,$var,$method,$start,$end);
			$variable = $this->variables->getVariableWithUnit($var);
			$variable = $variable[0];
			//Additional logic to get the unit. sometimes its unitsType and sometimes UnitsType
			$unit = "No unit Found";
			if(array_key_exists('UnitsType', $variable))
			{
				$unit = $variable['UnitsType'];
			}
			if(array_key_exists('unitsType', $variable))
			{
				$unit = $variable['unitsType'];
			}
			$noValue = $variable['NoDataValue'];
			
			$EOL = "\r\n";

			echo("var data_test = [");

			$first = TRUE;

			//To echo Data in javascript format
			foreach ($result as $row)
			{
				//Check for NoDataValue (Default is -9999))
				if ($row['DataValue'] != $noValue)
				{
					// not the first line -> separate from last tuple with comma
					echo (($first)? $EOL : ",$EOL");

					// echo a [time, value] tuple
					echo sprintf("[%s,%s]",
						Datapoint::javaScriptDateUTC($row['LocalDateTime']),
						(string) $row['DataValue']
					);

					// the next tuple will not be the first
					$first = FALSE;
				}
			}

			echo("$EOL];");
		}
		else
		{
			$this->loadApiErrorView('getData');
		}
	}

	private function getInputOrTRUE($name)
	{
		return $this->input->get($name, TRUE);
	}

	public static function javaScriptDateUTC($timestamp)
	{
		// split timestamp into named components
		$parts = Datapoint::splitTimestamp($timestamp);

		// JavaScript's Date.UTC requires month to be an integer between 0 and 11
		$parts['month']--;

		return sprintf("Date.UTC(%s)", implode(",", $parts));
	}

	public static function splitTimestamp($timestamp)
	{
		// split date from time at space
		$dateAndTime = explode(" ", $timestamp);

		// split date parts at "-"
		$dateParts = explode("-", $dateAndTime[0]);

		// split time parts at ":"
		$timeParts = explode(":", $dateAndTime[1]);

		return array(
			'year'   => $dateParts[0],
			'month'  => $dateParts[1],
			'day'    => $dateParts[2],
			'hour'   => $timeParts[0],
			'minute' => $timeParts[1],
			'second' => $timeParts[2]
		);
	}

	private function loadApiErrorView($method)
	{
		// same parameters and example for getData, getDataJSON and export
		if (in_array($method, array('getData', 'getDataJSON', 'export'))) {
			$parameters = "VariableID, SiteID, MethodID";
			$example = "$method?varid=1&siteid=2&methodid=1&startdate=2012-04-02 00:00:00&enddate=2012-04-02 00:00:00";
		}
		elseif ($method == 'delete') {
			$parameters = "ValueID";
			$example = "delete/1";
		}
		elseif ($method == 'edit') {
			$parameters = "ValueID, date, time, value";
			$example = "edit/1?val=2&dt=2001-01-01&time=12:00";
		}
		elseif ($method == 'add') {
			$parameters = "VariableID, date, time, value, SiteID, MethodID";
			$example = "add?val=2&dt=2001-01-01&time=12:00&sid=1&mid=1&varid=1";
		}
		elseif ($method == 'compare') {
			$parameters = "compareID";
			$example = "compare/1";
		}

		$data['errorMsg'] = sprintf(
			"One of the parameters: %s is not defined. " .
			"An example request would be: %s",
			$parameters, $example
		);

		$this->load->view('templates/apierror', $data);
	}

	public function getDataJSON()
	{
		$var = $this->getInputOrTRUE('varid');
		$site = $this->getInputOrTRUE('siteid');	
		$method = $this->getInputOrTRUE('meth');
		$start = $this->getInputOrTRUE('startdate');	
		$end = $this->getInputOrTRUE('enddate');
		if($var!==false&&$site!==false&&$method!==false&&$start!==false&&$end!==false)
		{
			$result = $this->datapoints->getData($site,$var,$method,$start,$end);
			echo json_encode($result);
		}
		else
		{
			$this->loadApiErrorView('getDataJSON');
		}	
	}
	
	public function delete()
	{
		$valueid = end($this->uri->segment_array());
		if($valueid=="delete")
		{
			$this->loadApiErrorView('delete');
			return;
		}
		$result = $this->datapoints->delete($valueid);

		$this->updateSeriesCatalogIf($result);

		$output = array("status" => $this->successStatus($result));

		echo json_encode($output);
	}
	
	private function updateSeriesCatalogIf($success)
	{
		if($success)
		{
			$this->updateSC();
		}
	}

	private function successStatus($success)
	{
		return (($success)? "success" : "failed");
	}

	public function edit()
	{
		$valueid = end($this->uri->segment_array());
		if($valueid=="edit")
		{
			$this->loadApiErrorView('edit');
			return;
		}
		$value = $this->getInputOrTRUE('val');
		$dt=$this->getInputOrTRUE('dt');	
		$time = $this->getInputOrTRUE('time');
		
		if($valueid!==false&&$value!==false&&$dt!==false&&$time!==false)
		{
			$LocalDateTime = $dt . " " . $time . ":00";
			$localtimestamp = strtotime($LocalDateTime);
			$ms = $this->config->item('UTCOffset') * 3600;
			$utctimestamp = $localtimestamp - ($ms);
			$DateTimeUTC = date("Y-m-d H:i:s", $utctimestamp);
			$result = $this->datapoints->editPoint($valueid,$value,$LocalDateTime,$DateTimeUTC);

			$this->updateSeriesCatalogIf($result);

			$output = array("status" => $this->successStatus($result));

			echo json_encode($output);
		}
		else
		{
			$this->loadApiErrorView('edit');
		}	
	}
	
	public function export()
	{
		$var = $this->getInputOrTRUE('varid');
		$site = $this->getInputOrTRUE('siteid');	
		$method = $this->getInputOrTRUE('meth');
		$start = $this->getInputOrTRUE('startdate');	
		$end = $this->getInputOrTRUE('enddate');
		if($var!==false&&$site!==false&&$method!==false&&$start!==false&&$end!==false)
		{
			$result = $this->datapoints->getResultData($site,$var,$method,$start,$end);
			header( 'Content-Type: text/csv' );
			header('Content-Disposition: attachment; filename=HSLDataSite'.$site.'.csv');
			$this->load->dbutil();
			echo $this->dbutil->csv_from_result($result);
		}
		else
		{
			$this->loadApiErrorView('export');
		}	
	}
	
	public function add()
	{	
		$var = $this->getInputOrTRUE('varid');
		$site = $this->getInputOrTRUE('sid');	
		$method = $this->getInputOrTRUE('mid');
		$value = $this->getInputOrTRUE('val');
		$dt=$this->getInputOrTRUE('dt');	
		$time = $this->getInputOrTRUE('time');
		$source = $this->sc->getSourceBySite($site);
		$sourceID = $source[0]['SourceID'];
		if($var!==false&&$value!==false&&$dt!==false&&$time!==false&&$site!==false&&$method!==false)
		{
			$dataPoint = $this->createDP($dt,$time,$value,$site,$var,$method,$sourceID);
			$result=$this->datapoints->addPoint($dataPoint);

			$this->updateSeriesCatalogIf($result);

			$output = array(
				"status" => $this->successStatus($result), 
				"id" => $result
			);

			echo json_encode($output);
		}
		
		else
		{
			$this->loadApiErrorView('add');
		}	
	}
	public function compare()
	{
		$valueid = end($this->uri->segment_array());
		if($valueid=="compare")
		{
			$this->loadApiErrorView('compare');
			return;
		}
		//List of CSS to pass to this view
		$data=$this->StyleData;
		
		if($valueid==2)
		{
			$siteid = $this->getInputOrTRUE('siteid');
			$site = $this->site->getSite($siteid);
			$data['SiteName']=$site[0]['SiteName'];
		}
		
		$this->load->view("compare/".$valueid,$data);	
	}
	
	public function updateSC()
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
