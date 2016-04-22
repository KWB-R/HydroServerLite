<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|-------------------------------------------------------------------------|
| Variables Controller                                                    |
|-------------------------------------------------------------------------|
*/

class Variable extends MY_Controller 
{
	function __construct()
	{
		$this->dontAuth = array(
			'getAllJSON', 'getAllJSON2', 'getSiteJSON', 'getTypes', 
			'updateVarID', 'getUnit', 'getWithUnit', 'getTable', 'getUnitTypes',
			'getUnitsByType'
		);

		parent::__construct();

		$this->loadModel('variables');
	}
	
	//
	// Public functions
	//

	public function add()
	{
		$this->addOrEdit(true);
	}

	public function edit()
	{
		$this->addOrEdit(false);
	}

	public function getAllJSON()
	{
		$this->getAll_JSON(1);
	}

	public function getAllJSON2()
	{
		//Returns the variableName as a combination.
		$this->getAll_JSON(2);
	}

	public function getSiteJSON()
	{
		$siteID = $this->getXssCleanInput('siteid');
		$withType = $this->getXssCleanInput('withtype');

		if ($siteID) {

			$result = $this->variables->getSite($siteID, $withType ? 'DataType' : '');

			foreach($result as &$var) {

				$name = translateTerm($var['VariableName']);

				$var['VariableName'] = $name;

				if ($withType) {

					$type = translateTerm($var["DataType"]);

					$var['VarNameMod'] = $name . " (" . $type . ")";
				}
			}

			echo $this->jsonEncoded($result);
		}
		else {
			$this->loadApiErrorView("Siteid", "getSiteJSON?siteid=1");
		}
	}

	public function getTypes()
	{
		// in fact this is the VariableID!
		$variableID = $this->getXssCleanInput('varname');
		$siteID     = $this->getXssCleanInput('siteid');

		if ($siteID && $variableID) {

			$variableName = $this->getVariableName($variableID);

			if ($variableName !== '') {

				$types = $this->variables->getTypes($siteID, $variableName);

				foreach ($types as &$type) {
					$type['display'] = translateTerm($type['DataType']);
				}
			}
			else {
				$types = array();
			}

			echo $this->jsonEncoded($types);
		}
		else {
			$this->loadApiErrorView(
				"Siteid,Variable Name", "getTypes?siteid=1&varname=1"
			);
		}
	}

	public function updateVarID()
	{
		// in fact this is the VariableID!
		$variableID = $this->getXssCleanInput('varname');

		$result = $this->variables->getVarID(
			$this->getXssCleanInput('siteid'),
			$this->getVariableName($variableID),
			$this->getXssCleanInput('type')
		);

		echo $result[0]['VariableID'];
	}

	public function getUnit()
	{
		$this->getWithOrWithoutUnit(false);
	}

	public function getWithUnit()
	{
		$this->getWithOrWithoutUnit(true);
	}

	public function getTable()
	{
		$valueid = end($this->uri->segment_array());

		if ($valueid == "getTable") {

			$this->loadApiErrorView("TableName", "getTable/variablenamecv");

			return;
		}

		$selectEllipsis = getTxt('SelectEllipsis');
		$otherSlashNew = getTxt('OtherSlashNew');

		$result = array();

		$result[] = array(
			'Term'        => $selectEllipsis,
			'Definition'  => "-1",
			'displayTerm' => $selectEllipsis,
			'displayDef'  => "-1",
			'value'       => $selectEllipsis
		);

		$tempResult = $this->variables->getByTable($valueid);

		foreach ($tempResult as &$var) {
			$var['displayTerm'] = translateTerm($var['Term']);
			$var['value'] = $var['Term'];
			$var['displayDef'] = translateTerm($var['Definition']);
		}

		$result = array_merge($result, $tempResult);

		if (! $this->getXssCleanInput('noNew')) {
			$result[] = array(
				'Term'        => $otherSlashNew,
				'Definition'  => "-10",
				'displayTerm' => $otherSlashNew,
				'displayDef'  => "-10",
				'value'       => $otherSlashNew
			);
		}

		echo $this->jsonEncoded($result);
	}

	public function getUnitTypes()
	{
		$result = array();

		$selectEllipsis = getTxt('SelectEllipsis');
		$otherSlashNew = getTxt('OtherSlashNew');

		$result[] = array(
			'unitype' => $selectEllipsis,
			'unitid'  => "-1",
			'orgtype' => $selectEllipsis
		);

		$unitTypes = $this->variables->getUnitTs();

		foreach ($unitTypes as $unit) {
			$result[] = array(
				'unitype' => translateTerm($unit['unitsType']),
				'orgtype' => $unit['unitsType'],
				'unitid'  => "1"
			);
		}

		$result[] = array(
			'unitype' => $otherSlashNew,
			'unitid'  => "-10",
			'orgtype' => $otherSlashNew
		);

		echo $this->jsonEncoded($result);
	}

	public function getUnitsByType()
	{
		$type = $this->getXssCleanInput('type');

		if ($type !== false) {

			$result1 = $this->variables->getUnitsByType($type);

			$result = array();

			$result[] = array(
				'unit'  => getTxt('SelectEllipsis'),
				'unitid'=> "-1"
			);

			foreach ($result1 as $unit) {
				$result[] = array(
					'unit'   => translateTerm($unit['unitsName']),
					'unitid' => $unit['unitsID']
				);
			}

			if (! $this->getXssCleanInput('noNew')) {
				$result[] = array(
					'unit'   => getTxt('OtherSlashNew'),
					'unitid' => "-10"
				);
			}
			echo $this->jsonEncoded($result);
		}
		else {
			$this->loadApiErrorView("unitsType", "getUnitTypes?type=Area");
		}
	}

	public function delete()
	{
		$varid = end($this->uri->segment_array());

		if ($varid == "delete") {

			$this->loadApiErrorView("methodid", "delete/1");

			return;
		}

		$result = $this->variables->delete($varid);

		if ($this->getXssCleanInput('ui')) {

			$this->addSuccessOrError($result, 'VariableSuccess');
		}

		echo $this->jsonEncoded(array("status" => ($result? "success" : "failed")));
	}

	//
	// Private functions
	//

	private function addOrEdit($add)
	{
		if ($_POST) {

			$variable = $this->buildVariable();

			if ($add) {
				$result = $this->variables->add($variable);
			}
			else {
				$varid = $this->input->post('VariableID');
				$result = $this->variables->update($variable, $varid);
			}

			if ($result > 0) {

				//Add to varmeth
				$varMeth = array(
					'VariableCode' => $variable['VariableCode'],
					'VariableName' => $variable['VariableName'],
					'DataType'     => $variable['DataType'],
					'MethodID'     => $this->input->post('jqxWidget')
				);

				if ($add) {
					$success = $this->variables->addVM(
						array_merge(array('VariableID' => $result), $varMeth)
					);
				}
				else {	
					$success = $this->variables->updateVM($varMeth, $varid);
				}

				$this->addSuccessOrError(
					$success, 
					$add ? 'VariableSuccessfullyAdded' : 'VariableSuccess',
					"Error in varmeth."
				);
			}
			else {
				addError(getTxt('ProcessingError'));
			}
		}

		//List of CSS to pass to this view

		$data = $this->StyleData;

		if ($add) {

			$data['DefaultVarcode']= $this->config->item('default_varcode');
			$data['DefaultTS']     = $this->config->item('time_support');
		}

		$this->load->view('variables/' . ($add ? 'addvar':'editvar'), $data);
	}

	private function buildVariable()
	{
		$Variable = array(
			'VariableCode'    => $this->input->post('VariableCode'),
			'TimeSupport'     => $this->input->post('tsup'),
			'NoDataValue'     => -9999,
			'GeneralCategory' => $this->input->post('gc'),
			'DataType'        => $this->input->post('datatype'),
			'TimeunitsID'     => $this->input->post('timeunit'),
			'IsRegular'       => 
				($this->input->post('isreg') == getTxt('Regular'))? 1 : 0
		);

		//The above are the static variables.

		$Variable['VariableName'] = $this->getControlledName(
			'varname', 'NewVarName', 'variablenamecv', 'vardef'
		);

		$Variable['Speciation'] = $this->getControlledName(
			'specdata', 'other_spec', 'speciationcv', 'specdef'
		);

		$Variable['SampleMedium'] = $this->getControlledName(
			'samplemedium', 'smnew', 'samplemediumcv', 'smnew'
		);

		//Unit Checking. First Check New UNIT TYPE.

		$Variable['VariableunitsID'] = $this->getControlledUnit();

		//Check Value Type

		$Variable['ValueType'] = $this->getControlledName(
			'valuetype', 'valuetypenew', 'valuetypecv', 'vtdef'
		);

		return $Variable;
	}

	private function getControlledName($id, $id_new, $table, $id_def)
	{
		$name = $this->input->post($id);

		if ($name == getTxt('OtherSlashNew')) {

			$name = $this->input->post($id_new);

			$this->variables->addTDef($table, $name, $this->input->post($id_def));
		}

		return $name;
	}

	private function getControlledUnit()
	{
		$utype = $this->input->post('unittype');
		$unit = $this->input->post('unit');

		if ($utype == getTxt('OtherSlashNew')) {

			//New Unit and unit type Processing.

			$unit = $this->variables->addUnit(
				$this->input->post('new_unit_type'),
				$this->input->post('new_unit_name'),
				$this->input->post('new_unit_abb')
			);
		}
		else {

			//Is there a new unit?

			if ($unit == -10) {

				//New Unit Processing with the above type.

				$unit = $this->variables->addUnit(
					$utype,
					$this->input->post('new_unit_name'),
					$this->input->post('new_unit_abb')
				);
			}
		}

		return $unit;
	}

	private function getAll_JSON($variant = 1)
	{
		$variables = $this->variables->getAll();

		foreach ($variables as &$var) {

			$name = translateTerm($var['VariableName']);
			$var['VariableName'] = $name;

			if ($variant === 2) {
				$type = translateTerm($var["DataType"]);
				$var['VarNameMod'] = $name . " (" . $type . ")";
			}
		}

		// Sort the array by 'VarNameMod'
		usort($variables, function($a, $b) {
			return ($a['VarNameMod'] < $b['VarNameMod']) ? -1 : 1;
		});

		echo $this->jsonEncoded($variables);
	}

	private function getWithOrWithoutUnit($withUnit)
	{
		$variableID = $this->getXssCleanInput('varid');

		if ($variableID !== false) {

			echo $this->jsonEncoded(
				$withUnit ?
				$this->variables->getVariableWithUnit(0 + $variableID) :
				$this->variables->getUnit(0 + $variableID)
			);
		}
		else {

			$this->loadApiErrorView(
				"VariableID", 
				($withUnit ? "getWithUnit" : "getUnit") . "?varid=1"
			);
		}
	}

	private function getVariableName($variableID)
	{
		$variable = $this->variables->getVariableWithUnit($variableID);

		return ((count($variable) > 0) ? $variable[0]['VariableName'] : '');
	}

	private function loadApiErrorView($parameters, $example)
	{
		$message  = "One of the parameters: " . $parameters . " is not defined. ";
		$message .= "An example request would be " . $example;

		$data['errorMsg'] = $message;

		$this->load->view('templates/apierror', $data);
	}

	private function addSuccessOrError($success, $successKey, $errorMessage = '')
	{
		if ($success) {
			addSuccess(getTxt($successKey));
		}
		else {
			addError(getTxt('ProcessingError') . " " . $errorMessage);
		}
	}
}
