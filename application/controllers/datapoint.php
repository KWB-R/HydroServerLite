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
		$dontAuth = array('getData','getDataJSON','compare','export');

		$publicAccess = config_item("public_access");
		$publicAccess = (isset($publicAccess) && ($publicAccess === TRUE));

		$this->dontAuth = ($publicAccess? $dontAuth : array());

		parent::__construct();

		$models = array('variables', 'sources', 'datapoints', 'sc', 'site');

		array_walk($models, array($this, 'loadModel'));

		$this->load->library('form_validation');
		$this->load->library('API_Config');
	}
	
	private function getUtcFromTimeIfApplicable(&$fields)
	{
		if (! isset($fields['UTCOffset'])) {

			$timestring = $fields['time'];

			$posPlus = stripos($timestring, "+");
			$posMinus = stripos($timestring, "-");

			if (($posPlus !== false) || ($posMinus !== false)) {

				$pos = (($posPlus !== false) ? $posPlus : $posMinus);

				$fields['UTCOffset'] = (0 + substr($timestring, $pos + 1));
				$fields['time'] = substr($timestring, 0, $pos);
			}
		}
	}

	private function createDataPoint($fields)
	{
		$dateFormat = "Y-m-d H:i:s";

		// If no UTCOffset is given in the fields, check if the $fields['time']
		// contains information on the UTC Offset (e.g. 14:15:00+02). If yes,
		// strip off the UTC information from $fields['time'] and set
		// $fields['UTCOffset'] to the according value (in hours).

		$this->getUtcFromTimeIfApplicable($fields);

		$localtime = strtotime($fields['date'] . " " . $fields['time']);

		$dataPoint = array(
			'DataValue' => $fields['DataValue'],
			'LocalDateTime' => date($dateFormat, $localtime),
			'SiteID' => $fields['SiteID'],
			'VariableID' => $fields['VariableID'],
			'MethodID' => $fields['MethodID'],
			'SourceID' => $fields['SourceID']
		);

		$optionalFields = array(
			'ValueAccuracy', 'UTCOffset', 'OffsetValue', 'OffsetTypeID',
			'CensorCode', 'QualifierID', 'SampleID', 'DerivedFromID',
			'QualityControlLevelID'
		);

		foreach ($optionalFields as $field) {
			$dataPoint[$field] = (
				isset($fields[$field]) ? $fields[$field] : $this->getConfigItem($field)
			);
		}

		$offsetInSeconds = 3600 * $dataPoint['UTCOffset'];

		$dataPoint['DateTimeUTC'] = date($dateFormat, $localtime - $offsetInSeconds);

		log_message('debug', "createDataPoint: " . print_r($dataPoint, TRUE));

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
				// fileUploadHandler now in MY_Controller
				$files = $this->fileUploadHandler('csv|CSV|xls|XLS|xlsx|XLSX', 'files');
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

	private function processFiles($files, $check)
	{
		$this->load->library('Excel');

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
			$extension = $file['file_ext'];

			if ($extension === '.csv' || $extension === '.CSV') {
				$content = $this->excel->read_csv($file['full_path']);
			}
			else if ($extension === '.xls' || $extension === '.XLS') {
				$content = $this->excel->read_xls($file['full_path'], 'xls');
			}
			else if ($extension === '.xlsx' || $extension === '.XLSX') {
				$content = $this->excel->read_xls($file['full_path'], 'xlsx');
			}

			if (is_null($content))
			{
				addError(getTxt('FailInputStream'));
				return FALSE;
			}

			$ok = $this->processFile(
				$content, $file, $check, $keyIDs, $existingIDs, $dataset
			);

			if (! $ok) {
				return FALSE;
			}
		} // end of foreach($files)

		return $dataset;
	}

	private function processFile($content, $file, $check, $keyIDs, $existingIDs, &$dataset)
	{
		for ($row = 1; $row <= count($content); $row++) {

			$data = $content[$row];

			// Is this the header row?
			if ($row == 1)
			{
				// Try to get an assignment between column name and column index.
				// If NULL is returned, there were missing or unexpected columns.
				$columnIndex = $this->handleHeaderRow($data, $check, $file);

				if (is_null($columnIndex))
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

		} // end of for

		return TRUE;
	}

	private function processXlsFile($file, $check, $keyIDs, $existingIDs, &$dataset)
	{
		$dataset = $this->excel->read_xls($file['full_path']);
		$ok = (count($dataset) > 0);

		addSuccess(print_r($dataset, TRUE));

		return false;
	}

	private function handleHeaderRow($captions, $idsInFile, $file)
	{
		$required = array("LocalDateTime", "DataValue");
		$required2 = array("SourceID", "SiteID", "VariableID", "MethodID");

		if ($idsInFile)	{
			$required = array_merge($required2, $required);
		}

		// Are there missing captions?
		$missing = array_values(array_diff($required, $captions));

		// Are there unexpected captions?
		if ($idsInFile) {
			$unexpected = array();
		}
		else {
			$unexpected = array_intersect($required2, $captions);
		}

		// Prepare return value
		$columnIndex = NULL;

		if (count($missing) > 0) {
			addError($this->headerErrorMessage($required, $missing, $file, TRUE));
		}
		elseif (count($unexpected) > 0) {
			addError($this->headerErrorMessage($required, $unexpected, $file, FALSE));
		}
		else {
			$columnIndex = array_flip($captions);
		}

		// Return the assignment between caption and column index (or NULL,
		// if an error occurred)
		return $columnIndex;
	}

	private function handleDataRow
	(
		$data, $columnIndex, $keyIDs, $existingIDs, $row, $file
	)
	{
		$date = NULL; // will be set in validateFields

		// Check for a valid date and a valid data value or raise an error
		if (!$this->validateFields($data, $columnIndex, $row, $file, $date))
		{
			return NULL;
		}

		$objects = array('Source', 'Site', 'Variable', 'Method');

		$optObjects = array('QualityControlLevel', 'Qualifier');

		// Extend $objects by names of objects that are given in the file
		foreach ($optObjects as $object) {
			if (isset($columnIndex[$object . "ID"])) {
				array_push($objects, $object);
			}
		}

		if (is_null($keyIDs))
		{
			// Copy ID values of current row into array $keyIDs and verify that
			// all IDs exist.

			$anyInvalid = FALSE;

			foreach ($objects as $object)
			{
				// if there is no "<object>ID" column, there must be a
				// "<object>Code" column (TODO: check in handleHeaderRow).
				// Read the Code from the "<object>Code" column, check if the
				// code is valid and use the ID corresponding to the code
				if (! isset($columnIndex[$object . "ID"])) {

					$code = $data[$columnIndex[$object . "Code"]];

					$invalid = $this->addErrorIf(
						! isset($existingIDs[$object][$code]),
						strtolower($object) . "code", // keyword for language table
						$row,
						$file
					);

					$id = $existingIDs[$object][$code];
				}
				else {
					// Given ID value in the <object>ID column of the current data row
					$id = $data[$columnIndex[$object . "ID"]];

					// is the ID in the array of available IDs?
					$invalid = $this->addErrorIf(
						! in_array($id, $existingIDs[$object]),
						strtolower($object) . "id", // keyword for language table
						$row,
						$file
					);
				}

				// Parentheses are important since "or" has lower precedence than "="!
				$anyInvalid = ($anyInvalid or $invalid);

				// Copy ID into array $keyIDs
				$keyIDs[$object] = $id;
			}

			if ($anyInvalid)
			{
				return NULL;
			}
		}

		// Create a result dataset
		$dataset = array(
			'date' => $date->format("Y-m-d"),
			'time' => $date->format("H:i:s"),
			'DataValue' => $data[$columnIndex['DataValue']]
		);

		// Copy fields that may be given in the file into the result dataset
		$fieldnames = array(
			'UTCOffset', 'CensorCode', 'ValueAccuracy', 'OffsetValue'
		);

		foreach ($fieldnames as $fieldname) {
			if (isset($columnIndex[$fieldname])) {
				$dataset[$fieldname] = $data[$columnIndex[$fieldname]];
			}
		}

		// Copy ID fields into the result dataset
		foreach ($objects as $object) {
			$dataset[$object . "ID"] = $keyIDs[$object];
		}

		return $dataset;
	}

	private function getExistingIDs()
	{
		$this->loadModel('method');
		$this->loadModel('qualitycontrollevel');
		$this->loadModel('qualifier');

		return array(
			'Site' => array_column(
				$this->site->getAll(),
				'SiteID',
				'SiteCode'
			),
			'Source' => array_column(
				$this->sources->getAll(),
				'SourceID'
			),
			'Variable' => array_column(
				$this->variables->getAll(),
				'VariableID',
				'VariableCode'
			),
			'Method' => array_column(
				$this->method->getAll(),
				'MethodID'
			),
			'QualityControlLevel' => array_column(
				$this->qualitycontrollevel->getAll(),
				'QualityControlLevelID',
				'QualityControlLevelCode'
			),
			'Qualifier' => array_column(
				$this->qualifier->getAll(),
				'QualifierID',
				'QualifierCode'
			)
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

	private function validateFields($fields, $columnIndex, $row, $file, &$date)
	{
		// Try to convert timestamp to DateTime object or raise an error
		// As required, $date will be in UTC timezone since UTC was set as the
		// default timezone in processLang(), being called in the constructor of
		// the MY_Controller class
		$timeValue = $fields[$columnIndex['LocalDateTime']];

		// If the value in column LocalDateTime is numeric then we assume that this numeric
		// represents the number of days since 1970-01-01 as it is done in MS Excel
		if (gettype($timeValue) == 'double') {
			$timeValue = "@" . round(($timeValue - 25569.0) * 86400);
		}

		$error = $this->toDateTime($timeValue, $date);

		if ($error != "") {

			addError($this->typeErrorMessage('InvalidTime', $row, $file, $error));

			return false;
		}

		// Check if $value is numeric
		$numeric = is_numeric($fields[$columnIndex['DataValue']]);

		// If UTCOffset is given, check if it is numeric
		if ($numeric && isset($columnIndex['UTCOffset'])) {

			$numeric = ($numeric && is_numeric($fields[$columnIndex['UTCOffset']]));
		}

		if (! $numeric) {

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

	private function addErrorIf($condition, $idName, $row, $file)
	{
		if ($condition)
		{
			addError($this->idErrorMessage($idName, $row, $file));
		}

		return $condition;
	}

	private function idErrorMessage($idName, $row, $file)
	{
		// Try to translate $idName
		$translated = getTxt($idName);

		// Keep $idName if no translation was found
		if (! $translated) {
			$translated = $idName;
		}

		$message = sprintf("%s %s. Row:", getTxt('invalid'), $translated);

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

	private function headerErrorMessage($captions, $invalid, $file, $missing)
	{
		$message = getTxt('InvalidHeading') . implode(",", $captions) . ". Row";
		$invalids = implode(", ", $invalid);

		if ($missing) {
			$error = "missing: " . $invalids;
		}
		else {
			$error = "unexpected: " . $invalids;
			$error .= ". Did you forget to check the \"ID's in File?\" option?";
		}

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

	public function exportXls()
	{
		$this->getData_generic('exportXls');
	}

	public function getSeries()
	{
		$this->getData_generic('getSeries');
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

		$config = $this->getApiConfiguration();

		$parameterMapping = API_Config::parameterMapping($config[$method]);

		$inputs = $this->getInputs($parameterMapping);

		$missing = $this->getMissing($inputs);

		return (count($missing) == 0);
	}

	private function getDataFromModel($method, $inputs)
	{
		// Read list of fields to be provided from the config file
		// or use the default fields
		$default = 'ValueID, DataValue, LocalDateTime';
		$fieldList = $this->getConfigItem("field_list_$method", $default);

		$methods = array(
			'getData', 'getDataJSON', 'export', 'exportXls', 'getSeries'
		);

		if (in_array($method, $methods)) {

			$isExport = ($method === 'getSeries') ?
				($inputs['format'] !== 'json') :
				(substr($method, 0, 6) === 'export');

			$extended = (
				$isExport ?
				(boolean) $this->getConfigItem('extended_export', FALSE) :
				FALSE
			);

			if ($method === 'getSeries') {
				$catalogFields = 'SourceID, SiteID, VariableID, MethodID';
				$condition = $this->sc->get($inputs['SeriesID'], $catalogFields);
			}
			else {
				$condition = array(
					'SiteID' => $inputs['SiteID'],
					'VariableID' => $inputs['VariableID'],
					'MethodID' => $inputs['MethodID']
				);
			}

			$defaultStart = ''; //'1900-01-01';
			$defaultEnd = ''; //'2100-01-01';

			$startdate = isset($inputs['startdate']) ?
				$inputs['startdate'] : $defaultStart;

			$enddate = isset($inputs['enddate']) ?
				$inputs['enddate'] : $defaultEnd;

			$result = $this->datapoints->getResultData(
				$condition,
				$startdate,
				$enddate,
				$fieldList,
				$extended
			);

			if (! $isExport) {
				$result = $result->result_array();
			}
		}
		else {
			addError("Unknown method in getDataFromModel: ", $method);
			$result = NULL;
		}

		return $result;
	}

	private function outputOrExportData($method, $inputs, $result)
	{
		$format = $this->getFormat($method, $inputs);

		if ($format === 'code')
		{
			// hsonne: Why filter for non-NoDataValues only for getData?
			$variable = $this->variables->getVariableWithUnit($inputs['VariableID']);

			// [deleted because not used: Additional logic to get the unit...]

			$noValue = ((count($variable) > 0)? $variable[0]['NoDataValue'] : -9999);

			$this->echoJavaScriptAssignment2($result, $noValue);
		}
		elseif ($format === 'json')
		{
			echo $this->jsonEncoded($result);
		}
		elseif (in_array($format, array('csv', 'xls', 'xlsx')))
		{
			$nameparts = ($method === 'getSeries' ? array() : $inputs);

			$filename = $this->toExportName($nameparts, '.' . $format);

			$this->exportToSpreadsheet($result, $format, $filename);
		}
		else {
			addError("Unknown method in outputOrExportData: ", $method);
		}
	}

	private function getFormat($method, $inputs)
	{
		if ($method === 'getSeries') {
			return $inputs['format'];
		}

		switch($method) {
			case 'getData': return 'code';
			case 'getDataJSON': return 'json';
			case 'export': return 'csv';
			case 'exportXls': return 'xls';
			case 'exportXlsx': return 'xlsx';
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

	private function toExportName($inputs, $extension = ".csv")
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

		return $filename . $extension;
	}

	private function getMissing($values)
	{
		return array_keys(array_filter($values, function($value) {
			return ($value === FALSE);
		}));
	}

	private function getInputs($names)
	{
		return array_map(array($this, 'getXssCleanInput'), $names);
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
		$config = $this->getApiConfiguration();

		$data['errorMsg'] = $this->apiErrorMessage(
			API_Config::requiredString($config[$method]),
			API_Config::exampleCall($config, $method)
		);

		$this->load->view('templates/apierror', $data);
	}

	private function getApiConfiguration()
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

		return array(
			'getData' => $getParameters,
			'getDataJSON' => $getParameters,
			'export' => $getParameters,
			'exportXls' => $getParameters,
			'getSeries' => array(
				'SeriesID' => API_Config::parameter('seriesid', array(1,3,4)),
				'format' => API_Config::parameter('format', '[json|csv|xls|xlsx]')
			),
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
			// Is the UTCOffset coded in the time string?
			$this->getUtcFromTimeIfApplicable($inputs);

			// If not, use the default from the configuration
			if (! isset($inputs['UTCOffset'])) {
				$inputs['UTCOffset'] = $this->getConfigItem('UTCOffset');
			}

			$LocalDateTime = sprintf("%s %s:00", $inputs['date'], $inputs['time']);

			$localtime = strtotime($LocalDateTime);

			//$ms = $this->config->item('UTCOffset') * 3600;
			$ms = $inputs['UTCOffset'] * 3600;

			$DateTimeUTC = date("Y-m-d H:i:s", $localtime - $ms);

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
			$siteid = $this->getXssCleanInput('siteid');
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

