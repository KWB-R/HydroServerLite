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

		array_walk($models, array($this, 'loadModel'));

		$this->load->library('form_validation');
		$this->load->library('API_Config');
	}
	
	private function createDataPoint($fields)
	{
		$dateFormat = "Y-m-d H:i:s";
		
		$localtimestamp = strtotime($fields['date'] . " " . $fields['time']);

		$offsetInSeconds = $this->config->item('UTCOffset') * 3600;
		
		$dataPoint = array(
			'DataValue' => $fields['DataValue'],
			'LocalDateTime' => date($dateFormat, $localtimestamp), 
			'DateTimeUTC' => date($dateFormat, $localtimestamp - $offsetInSeconds), 
			'SiteID' => $fields['SiteID'],
			'VariableID' => $fields['VariableID'],
			'MethodID' => $fields['MethodID'],
			'SourceID' => $fields['SourceID']
		);

		$configFields = array(
			'ValueAccuracy', 'UTCOffset', 'OffsetValue', 'OffsetTypeID',	
			'CensorCode', 'QualifierID', 'SampleID', 'DerivedFromID', 
			'QualityControlLevelID'
		);
		
		foreach ($configFields as $configField) {
			$dataPoint[$configField] = $this->getConfigItem($configField);
		}

		return $dataPoint;
	}

	public function addvalue()
	{
		$this->addvalue_generic('addvalue');
	}

	public function addmultiplevalues()
	{
		$this->addvalue_generic('addmultiplevalues');
	}

	public function importfile()
	{
		$this->addvalue_generic('importfile');
	}

	private function addvalue_generic($method)
	{
		// Define the keys of the messages in the language table to be shown on
		// success (at index 0) and an error message (if required, at index 1)
		$messageKeys = array(
			'addvalue' => array('ValueSuccessfully', ''),
			'addmultiplevalues' => array('DataEnteredSuccessfully', ''),
			'importfile' => array('Success', 'Error in data input')
		);

		// Array of files to be imported (only relevant for method 'importfile')
		$files = NULL;

		if ($_POST)
		{
			if ($method == 'addvalue')
			{
				$this->setFormValidationRules();
				$valid = $this->form_validation->run();
			}
			elseif ($method == 'addmultiplevalues')
			{
				// hsonne: no form validation for 'addmultiplevalues'?
				$valid = TRUE;
			}
			elseif ($method == 'importfile')
			{
				$files = $this->fileUploadHandler('csv|CSV', 'files');
				$valid = $files;
			}

			if ($valid)
			{
				$success = $this->applyMethod($method, $files);

				// since method 'importfile' shows its own error messages show
				// only success message here for this method
				if (($method != 'importfile') or $success)
				{
					$this->addSuccessOrError($success, $messageKeys[$method][0],
						$messageKeys[$method][1]);
				}
			}
			else
			{
				$errors = validation_errors();

				if (! empty($errors))
				{
					addError($errors);
				}
			}
		} // if ($_POST)

		// Set style and option values (sources, variables) and load the view
		$variableOptions = ($method != 'addmultiplevalues');
		
		$this->loadViewWithStyleAndOptions("datapoint/$method", $variableOptions);
	}

	private function applyMethod($method, $files = NULL)
	{
		if ($method == 'addvalue')
		{
			$datapoint = $this->createDataPoint($this->inputsToDataPointFields());
			
			$result = $this->datapoints->addPoint($datapoint);
			
			$success = $result;
		}
		elseif ($method == 'addmultiplevalues')
		{
			$rows = $this->input->post('finalRows');

			$dataset = array_map(
				function($i) // callback function (defined inline)
				{ 
					return $this->createDataPoint($this->inputsToDataPointFields($i));
				},
				range(1, $rows) // array to loop through (values 1...$rows)
			);

			$result = $this->datapoints->addPoints($dataset);
			$success = ($result == $rows);
		}
		elseif ($method == 'importfile')
		{
			$dataset = $this->processFiles($files, $this->input->post('valueSpec'));

			if ($dataset)
			{
				$result = $this->datapoints->addPoints($dataset);
			}

			$success = ($dataset and $result);
		}
		else
		{
			addError("Unknown method in applyMethod: $method");
		}

		return $success;
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

	private function inputsToDataPointFields($postfix = '')
	{
		return array(
			'date' => $this->input->post('datepicker' . $postfix),
			'time' => $this->input->post('timepicker' . $postfix),
			'DataValue' => $this->input->post('value' . $postfix),
			'SiteID' => $this->input->post('SiteID'),
			'VariableID' => $this->input->post('VariableID' . $postfix),
			'MethodID' => $this->input->post('MethodID' . $postfix),
			'SourceID' => $this->input->post('SourceID')
		);
	}

	//
	// private function fileUploadHandler() now defined in MY_Controller
	//

	private function processFiles($files, $check)
	{
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
			if (! $this->processFile($file, $check, $keyIDs, $existingIDs, $dataset))
			{
				return FALSE;
			}
		} // end of foreach($files)

		return $dataset;
	}

	private function processFile($file, $check, $keyIDs, $existingIDs, &$dataset)
	{
		$handle = fopen($file['full_path'], "r");

		if (! $handle)
		{
			addError(getTxt('FailInputStream'));
			return false;
		}

		$row = 1;

		$columnIndex = array();

		while (($data = fgetcsv($handle)) !== FALSE) 
		{
			// Is this the header row?
			if ($row == 1)
			{
				if (!$this->handleHeaderRow($data, $check, $file, $columnIndex))
				{
					return false;
				}
			}
			else 
			{
				$fields = $this->handleDataRow(
					$data, $columnIndex, $keyIDs, $existingIDs, $row, $file
				);

				if (is_null($fields))
				{
					return false;
				}

				$dataset[] = $this->createDataPoint($fields);
			}

			$row++;

		} // end of while(fgetcsv())

		return TRUE;
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
	
		return array(
			'date' => $date->format("Y-m-d"),
			'time' => $date->format("H:i:s"),
			'DataValue' => $value,
			'SiteID' => $keyIDs['Site'],
			'VariableID' => $keyIDs['Variable'],
			'MethodID' => $keyIDs['Method'],
			'SourceID' => $keyIDs['Source']
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
	
	public function getData()
	{
		$this->getData_generic('getData');
	}

	public function getDataJSON()
	{
		$this->getData_generic('getDataJSON');
	}

	public function export()
	{
		$this->getData_generic('export');
	}

	private function getData_generic($method)
	{
		$inputs = NULL;

		if ($this->getAndValidateInputs($method, $inputs))
		{
			// The data fetched depends on the method
			$result = $this->getDataFromModel($method, $inputs);

			// The output action also depends on the method
			$this->outputOrExportData($method, $inputs, $result);
		}
		else
		{
			$this->loadApiErrorView($method);
		}
	}

	private function getAndValidateInputs($method, &$inputs)
	{
		$config = $this->getApiConfiguration($method);

		$inputs = $this->getInputs($config['parameterMapping']);

		$missing = $this->getMissing($inputs);

		return (count($missing) == 0);
	}

	private function getDataFromModel($method, $inputs)
	{
		// Read list of fields to be provided from the config file
		// or use the default fields
		$default = 'ValueID, DataValue, LocalDateTime';

		$fieldList = $this->getConfigItem("field_list_$method", $default);

		if (($method == 'getData') or ($method == 'getDataJSON'))
		{
			$result = $this->datapoints->getData(
				$inputs['SiteID'],
				$inputs['VariableID'],
				$inputs['MethodID'],
				$inputs['startdate'],
				$inputs['enddate'],
				$fieldList
			);
		}
		else if ($method == 'export')
		{
			$result = $this->datapoints->getResultData(
				$inputs['SiteID'],
				$inputs['VariableID'],
				$inputs['MethodID'],
				$inputs['startdate'],
				$inputs['enddate'],
				$fieldList
			);
		}
		else
		{
			addError("Unknown method in outputOrExportData: ", $method);
		}

		return $result;
	}

	private function outputOrExportData($method, $inputs, $result)
	{
		if ($method == 'getData')
		{
			// hsonne: Why filter for non-NoDataValues only for getData?
			$variable = $this->variables->getVariableWithUnit($inputs['VariableID']);

			// [deleted because not used: Additional logic to get the unit...]

			$noValue = ((count($variable) > 0)? $variable[0]['NoDataValue'] : -9999);

			$this->echoJavaScriptAssignment2($result, $noValue);
		}
		elseif ($method == 'getDataJSON')
		{
			$options = $this->getConfigItem("json_encode_options", 0);
			echo json_encode($result, $options);
		}
		elseif ($method == 'export')
		{
			$filename = $this->toExportName($inputs);

			header('Content-Type: text/csv');
			header("Content-Disposition: attachment; filename=$filename");

			$this->load->dbutil();
			echo $this->dbutil->csv_from_result($result);
		}
		else {
			addError("Unknown method in outputOrExportData: ", $method);
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

	private function echoJavaScriptAssignment2($rows, $noValue, $EOL = "\r\n")
	{
		$lines = array();

		foreach ($rows as $row)
		{
			//Check for NoDataValue (Default is -9999))
			if ($row['DataValue'] != $noValue)
			{
				// add a [time, value] tuple to the $lines array
				$lines[] = sprintf("[%s,%s]",
					Datapoint::javaScriptDateUTC($row['LocalDateTime']),
					(string) $row['DataValue']
				);
			}
		}

		echo(sprintf("var data_test = [$EOL%s$EOL];", implode(',' . $EOL, $lines)));
	}

	private function toExportName($inputs)
	{
		// Mapping between the keys in $inputs and short names in the file name
		$shortNames = array(
			'SiteID' => 'Site',
			'VariableID' => 'Var',
			'MethodID' => 'Meth'
		);

		$filename = 'HSL';

		foreach($shortNames as $longName => $shortName)
		{
			if (isset($inputs[$longName]))
			{
				$filename .= sprintf('_%s_%s', $shortName, $inputs[$longName]);
			}
		}

		return $filename . '.csv';
	}

	private function getMissing($values)
	{
		return array_keys(array_filter($values, function($value) {
			return ($value === FALSE);
		}));
	}

	private function getInputs($names)
	{
		return array_map(array($this, "getInputOrTRUE"), $names);
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
		$currentDate = date('Y-m-d');
		$currentTime = date('H:i');

		// same parameters and example for getData, getDataJSON and export
		$getParameters = array(
			'VariableID' => API_Config::parameter('varid', '1'),
			'SiteID' => API_Config::parameter('siteid', '1'),
			'MethodID' => API_Config::parameter('meth', '1'),
			'startdate' => API_Config::parameter('startdate', '2012-04-02 00:00:00', FALSE),
			'enddate' => API_Config::parameter('enddate',
				// use the current date and time as enddate
				$currentDate . ' ' . $currentTime . ':00', // '2012-04-02 00:00:00'
				FALSE
			)
		);

		$config = array(
			'getData' => $getParameters,
			'getDataJSON' => $getParameters,
			'export' => $getParameters,
			'delete' => array(
				'ValueID' => API_Config::parameter('')
			),
			'edit' => array(
				'ValueID' => API_Config::parameter(''),
				'date' => API_Config::parameter('dt',
					// use the current date as date
					$currentDate // '2001-01-01'
				),
				'time' => API_Config::parameter('time',
					// use the current time as time
					$currentTime // '12:00'
				),
				'DataValue' => API_Config::parameter('val', '2')
			),
			'add' => array(
				'VariableID' => API_Config::parameter('varid', '1'),
				'date' => API_Config::parameter('dt',
					// use the current date as date
					$currentDate // '2001-01-01'
				),
				'time' => API_Config::parameter('time',
					// use the current time as time
					$currentTime // '12:00'
				),
				'DataValue' => API_Config::parameter('val', '2'),
				'SiteID' => API_Config::parameter('sid', '1'),
				'MethodID' => API_Config::parameter('mid', '1')
			),
			'compare' => array(
				'compareID' => API_Config::parameter('')
			)
		);

		$methodConfig = $config[$method];
		$validationConfig = API_Config::withName($methodConfig);

		return array(
			'parameterMapping' => array_combine(
				array_keys($validationConfig),
				array_column($validationConfig, 'name')
			),
			'parameters' => API_Config::requiredString($methodConfig),
			'example' => API_Config::exampleCall($config, $method)
		);
	}

	public function delete()
	{
		$this->action_generic('delete');
	}
	
	public function edit()
	{
		$this->action_generic('edit');
	}

	private function action_generic($method)
	{
		$inputs = NULL;

		$valueid = end($this->uri->segment_array());

		if (($valueid !== $method) and ($valueid !== false)
			and $this->getAndValidateInputs($method, $inputs)
		)
		{
			$result = $this->applyAction($valueid, $method, $inputs);

			$this->updateSeriesCatalogIf($result);

			$output = array("status" => $this->successStatus($result));

			echo json_encode($output);
		}
		else 
		{
			$this->loadApiErrorView($method);
		}
	}

	private function applyAction($valueid, $method, $inputs)
	{
		if ($method == 'delete')
		{
			$result = $this->datapoints->delete($valueid);
		}
		else if ($method == 'edit')
		{
			$LocalDateTime = sprintf("%s %s:00", $inputs['date'], $inputs['time']);

			$localtimestamp = strtotime($LocalDateTime);

			$ms = $this->config->item('UTCOffset') * 3600;

			$DateTimeUTC = date("Y-m-d H:i:s", $localtimestamp - $ms);

			$result = $this->datapoints->editPoint(
				$valueid, $inputs['DataValue'],	$LocalDateTime, $DateTimeUTC
			);
		}

		return $result;
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

	public function add()
	{	
		$method = 'add';
		$inputs = NULL;
		
		if ($this->getAndValidateInputs($method, $inputs))
		{
			$source = $this->sc->getSourceBySite($inputs['SiteID']);
			$sourceID = $source[0]['SourceID'];
		
			$inputs['SourceID'] = $sourceID;

			$dataPoint = $this->createDataPoint($inputs);

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
			$this->loadApiErrorView($method);
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

