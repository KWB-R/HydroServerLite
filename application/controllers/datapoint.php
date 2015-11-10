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

		array_walk($models,  array($this, 'loadModel'));

		$this->load->library('form_validation');
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
			'LocalDateTime' => date($dateFormat, $localtimestamp), 
			'DateTimeUTC' => date($dateFormat, $localtimestamp - $offsetInSeconds), 
			'SiteID' => $siteid,
			'VariableID' => $varid, 
			'MethodID' => $methid, 
			'SourceID' => $sourceid
		);

		$fields = array(
			'ValueAccuracy', 'UTCOffset', 'OffsetValue', 'OffsetTypeID',	
			'CensorCode', 'QualifierID', 'SampleID', 'DerivedFromID', 
			'QualityControlLevelID'
		);
		
		foreach ($fields as $field) {
			$dataPoint[$field] = $this->getConfigItem($field);
		}

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
			$rows = $this->input->post('finalRows');
			
			$dataset = array_map(
				array($this, "createDataPointFromInputs"), // callback function
				range(1, $rows)                            // array to loop through
			);
			
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
	
	private function processFiles($files)
	{
		$check = $this->input->post('valueSpec');

		if ($check)
		{
			//Values will be checked and fetched while processing.
			//Build ID tables.
			$keyIDs = NULL;
			$existingIDs = $this->getExistingIDs();
		}
		else
		{
			$keyIDs = $this->getKeyIDsFromInput();
			$existingIDs = NULL;
		}

		$dataset = array();

		foreach ($files as $file)
		{
			$filepath = $file['full_path'];	
			$handle = fopen($filepath, "r");

			if(!$handle)
			{
				addError(getTxt('FailInputStream'));
				return false;	
			}

			$row = 0;

			$columnIndex = array();

			while (($data = fgetcsv($handle)) !== FALSE) 
			{
				// Is this the header row?
				if ($row == 0)
				{
					if (!$this->handleHeaderRow($data, $check, $file, $columnIndex))
					{
						return false;
					}
				}
				else 
				{
					$dataPoint = $this->handleDataRow(
						$data, $columnIndex, $keyIDs, $existingIDs, $row, $file
					);

					if (is_null($dataPoint))
					{
						return false;
					}

					$dataset[] = $dataPoint;
				}

				$row++;

			} // end of while(fgetcsv())
			
		} // end of foreach($files)

		return $dataset;
	}

	private function handleHeaderRow($data, $check, $file, &$columnIndex)
	{
		$captionString = "LocalDateTime,DataValue";

		if ($check)
		{
			$captionString = "SourceID,SiteID,VariableID,MethodID," . $captionString;
		}

		$captions = explode(",", $captionString);

		//Allow switching of columns.
		$columnIndex = array();

		foreach ($captions as $caption)
		{
			$index = array_search(
				strtolower($caption),
				array_map('strtolower', $data)
			);

			$columnIndex[$caption] = (($index === FALSE)? -1 : $index);
		}

		// Could all expected captions be found?
		$missing = array_keys(array_filter($columnIndex, function($i) {
			return ($i === -1);
		}));

		$valid = TRUE;
		
		if (count($missing) > 0)
		{
			$valid = FALSE;
			addError($this->headerErrorMessage($captions, $missing, $file));
		}

		if (!$check)
		{
			// Are captions found that we do not expect?
			if (in_array('SourceID', $data)	or in_array('SiteID', $data) 
				or in_array('VariableID', $data) or in_array('MethodID', $data)
			)
			{
				$valid = FALSE;
				addError("I found one of the columns 'SourceID', 'SiteID', " . 
					"'VariableID', 'MethodID' in " . $file['file_name']. " " .
					"which I do not expect since you selected these IDs in the form. " .
					"Did you forget to check the \"ID's in File?\" option?"
				);
			}
		}

		return $valid;
	}

	private function handleDataRow
	(
		$data, $columnIndex, $keyIDs, $existingIDs, $row, $file
	)
	{
		$timestamp = $data[$columnIndex['LocalDateTime']];
		$value = $data[$columnIndex['DataValue']];

		$date = NULL; // will be set in validateFields

		// Check for a valid date and a valid data value or raise an error
		if (!$this->validateFields($timestamp, $value, $row, $file, $date)) 
		{
			return NULL;
		}

		if (is_null($keyIDs))
		{
			// Verify if entered IDS are correct.
			$keyIDs = array(
				'Source' => $data[$columnIndex['SourceID']],
				'Site' => $data[$columnIndex['SiteID']],
				'Variable' => $data[$columnIndex['VariableID']],
				'Method' => $data[$columnIndex['MethodID']]
			);

			// This assignment just associates the keyword to be looked up in the
			// language table
			$idNames = array(
				'Source' => 'sourceid',
				'Site' => 'siteid',
				'Variable' => 'varid',
				'Method' => 'methodid'
			);

			$invalid = FALSE;

			foreach ($idNames as $key => $idName)
			{
				// parentheses are important since "or" has lower precedence than "="!
				$invalid = ($invalid or $this->addErrorIfInvalid(
					$keyIDs[$key], $existingIDs[$key], $idName, $row, $file
				));
			}

			if ($invalid)
			{
				return NULL;
			}
		}
	
		return $this->createDP(
			$date->format("Y-m-d"),
			$date->format("H:i:s"),
			$value,
			$keyIDs['Site'],
			$keyIDs['Variable'],
			$keyIDs['Method'],
			$keyIDs['Source']
		);
	}

	private function getExistingIDs()
	{
		$this->loadModel('method');

		return array(
			'Site' => array_column($this->site->getAll(), 'SiteID'),
			'Source' => array_column($this->sources->getAll(), 'SourceID'),
			'Variable' => array_column($this->variables->getAll(), 'VariableID'),
			'Method' => array_column($this->method->getAll(), 'MethodID')
		);
	}

	private function getKeyIDsFromInput()
	{
		return array(
			'Site' => $this->input->post('SiteID'),
			'Source' => $this->input->post('SourceID'),
			'Variable' => $this->input->post('VariableID'),
			'Method' => $this->input->post('MethodID')
		);
	}

	private function validateFields($timestamp, $value, $row, $file, &$date)
	{
		// Try to convert timestamp to DateTime object or raise an error
		$error = $this->toDateTime($timestamp, $date);

		if ($error != "") {

			addError($this->typeErrorMessage('InvalidTime', $row, $file, $error));

			return false;
		}

		// Check if $value is numeric or raise an error
		if (!is_numeric($value)) {

			addError($this->typeErrorMessage('InvalidChar', $row, $file));

			return false;
		}

		return true;
	}

	private function toDateTime($timestamp, &$date)
	{
		try {
			$date = new DateTime($timestamp);
			$errorMessage = "";
		}
		catch (Exception $e) {
			$errorMessage = $e->getMessage();
		}

		return $errorMessage;
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
		$message = sprintf("%s %s. Row:", getTxt('invalid'), getTxt($idName));

		return $this->fileErrorMessage($message, $row, $file);
	}

	private function typeErrorMessage($errorKey, $row, $file, $error = "")
	{
		return $this->fileErrorMessage(getTxt($errorKey), $row, $file, $error);
	}

	private function fileErrorMessage($message, $row, $file, $error = "")
	{
		$message .= sprintf(" %d %s %s", $row, getTxt('In'), $file['file_name']);

		if ($error != "") {
			$message .= " (" . $error . ")";
		}

		return $message . getTxt('PleaseFix');
	}

	private function apiErrorMessage($parameters, $example)
	{
		return sprintf(
			"One of the parameters: %s is not defined. " .
			"An example request would be: %s",
			$parameters, $example
		);
	}

	private function headerErrorMessage($captions, $missing, $file)
	{
		$message = getTxt('InvalidHeading') . implode(",", $captions) . ". Row";
		$error = "missing: " . implode(", ", $missing);

		return $this->fileErrorMessage($message, 1, $file, $error);
	}

	public function importfile()
	{
		if ($_POST)
		{
			$result = $this->fileUploadHandler();

			if ($result)
			{
				$dataset = $this->processFiles($result);

				if ($dataset)
				{
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
		$required = array('varid', 'siteid', 'meth', 'startdate', 'enddate');

		$inputs = $this->getInputs($required);

		$missing = $this->getMissing($inputs);

		if (count($missing) == 0)
		{
			$result = $this->datapoints->getData($inputs['siteid'],	$inputs['varid'],
				$inputs['meth'], $inputs['startdate'], $inputs['enddate']
			);

			$variable = $this->variables->getVariableWithUnit($inputs['varid'])[0];

			// [deleted because not used: Additional logic to get the unit...]

			$this->echoJavaScriptAssignment($result, $variable['NoDataValue']);
		}
		else
		{
			$this->loadApiErrorView('getData');
		}
	}

	private function echoJavaScriptAssignment($rows, $noValue)
	{
		$EOL = "\r\n";

		echo("var data_test = [");

		$first = TRUE;

		//To echo Data in javascript format
		foreach ($rows as $row)
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

	private function getMissing($values)
	{
		return array_keys(array_filter($values, function($value) {
			return ($value === FALSE);
		}));
	}

	private function getInputs($names)
	{
		return array_combine(
			$names,                                           // keys 
			array_map(array($this, "getInputOrTRUE"), $names) // values
		);		
	}

	private function getInputOrTRUE($name)
	{
		return $this->input->get($name, TRUE);
	}

	public static function javaScriptDateUTC($timestamp)
	{
		// split timestamp into named components
		$parts = array_slice(date_parse($timestamp), 0, 6);

		// JavaScript's Date.UTC requires month to be an integer between 0 and 11
		$parts['month']--;

		return sprintf("Date.UTC(%s)", implode(",", $parts));
	}

	private function loadApiErrorView($method)
	{
		$config = $this->getApiConfiguration($method);

		$data['errorMsg'] = $this->apiErrorMessage(
			$config['parameters'], $config['example']
		);

		$this->load->view('templates/apierror', $data);
	}

	private function getApiConfiguration($method)
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
		
		return array(
			'parameters' => $parameters, 
			'example' => $example
		);
	}

	public function getDataJSON()
	{
		$required = array('varid', 'siteid', 'meth', 'startdate', 'enddate');

		$inputs = $this->getInputs($required);

		$missing = $this->getMissing($inputs);

		if (count($missing) == 0)
		{
			$result = $this->datapoints->getData($inputs['siteid'], $inputs['varid'],
				$inputs['meth'], $inputs['startdate'], $inputs['enddate']
			);
			
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

		$required = array('val', 'dt', 'time');
		
		$inputs = $this->getInputs($required);
		
		$missing = $this->getMissing($inputs);
				
		if ($valueid !== false && count($missing) == 0)
		{
			$LocalDateTime = sprintf("%s %s:00", $inputs['dt'], $inputs['time']);
			$localtimestamp = strtotime($LocalDateTime);
			$ms = $this->config->item('UTCOffset') * 3600;

			$DateTimeUTC = date("Y-m-d H:i:s", $localtimestamp - $ms);

			$result = $this->datapoints->editPoint($valueid, $inputs['val'],
				$LocalDateTime, $DateTimeUTC
			);

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
		$required = array('varid', 'siteid', 'meth', 'startdate', 'enddate');

		$inputs = $this->getInputs($required);

		$missing = $this->getMissing($inputs);
	
		if (count($missing) == 0)
		{		
			$this->load->dbutil();

			$result = $this->datapoints->getResultData(
				$inputs['siteid'], $inputs['varid'], $inputs['meth'], 
				$inputs['startdate'], $inputs['enddate']
			);

			$filename = 'HSLDataSite' . $inputs['siteid'] . '.csv';
			
			header('Content-Type: text/csv');
			header("Content-Disposition: attachment; filename=$filename");
			
			echo $this->dbutil->csv_from_result($result);
		}
		else
		{
			$this->loadApiErrorView('export');
		}	
	}
	
	public function add()
	{	
		$required = array('varid', 'val', 'dt', 'time', 'sid', 'mid');

		$inputs = $this->getInputs($required);

		$missing = $this->getMissing($inputs);
		
		if (count($missing) == 0)
		{
			$source = $this->sc->getSourceBySite($inputs['sid']);
			$sourceID = $source[0]['SourceID'];
		
			$dataPoint = $this->createDP($inputs['dt'], $inputs['time'],
				$inputs['val'], $inputs['sid'], $inputs['varid'],
				$inputs['mid'], $sourceID
			);

			$result = $this->datapoints->addPoint($dataPoint);

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

