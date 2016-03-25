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

		$Variable['SampleMedium'] = getControlledName(
			'samplemedium', 'smnew', 'samplemediumcv', 'smnew'
		);

		//Unit Checking. First Check New UNIT TYPE.

		$Variable['VariableunitsID'] = getControlledUnit();

		//Check Value Type

		$Variable['ValueType'] = getControlledName(
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

	function getControlledUnit()
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

	public function add()
	{
		if ($_POST) {

			$variable = $this->buildVariable();
			$result = $this->variables->add($variable);

			if ($result > 0) {

				//Add to varmeth

				$varMeth = array(
					'VariableID'   => $result,
					'VariableCode' => $variable['VariableCode'],
					'VariableName' => $variable['VariableName'],
					'DataType'     => $variable['DataType'],
					'MethodID'     => $this->input->post('jqxWidget')
				);

				if($this->variables->addVM($varMeth)) {
					addSuccess(getTxt('VariableSuccessfullyAdded'));
				}
				else {
					addError(getTxt('ProcessingError')." Error in varmeth.");
				}
			}
			else {
				addError(getTxt('ProcessingError'));
			}
		}

		//List of CSS to pass to this view

		$data = $this->StyleData;

		$data['DefaultVarcode']= $this->config->item('default_varcode');
		$data['DefaultTS']= $this->config->item('time_support');

		$this->load->view('variables/addvar', $data);
	}

	public function edit()
	{
		if ($_POST) {

			$variable = $this->buildVariable();
			$varid = $this->input->post('VariableID');
			$result = $this->variables->update($variable, $varid);

			if($result) {

				//Add to varmeth

				$varMeth = array(
					'VariableCode' => $variable['VariableCode'],
					'VariableName' => $variable['VariableName'],
					'DataType'     => $variable['DataType'],
					'MethodID'     => $this->input->post('jqxWidget')
				);

				if($this->variables->updateVM($varMeth, $varid)) {
					addSuccess(getTxt('VariableSuccess'));
				}
				else {
					addError(getTxt('ProcessingError') . " Error in varmeth.");
				}
			}
			else {
				addError(getTxt('ProcessingError'));
			}
		}

		//List of CSS to pass to this view

		$this->load->view('variables/editvar', $this->StyleData);
	}

	public function getAllJSON()
	{
		$variables = $this->variables->getAll();

		foreach ($variables as &$var) {
			$var['VariableName'] = translateTerm($var['VariableName']);
		}

		echo json_encode($variables);
	}

	public function getAllJSON2()
	{
		//Returns the variableName as a combination.
		$variables = $this->variables->getAll();

		foreach ($variables as &$var) {
			$var['VarNameMod'] = translateTerm($var['VariableName']) . ' ' .
				"(" . translateTerm($var["DataType"]) . ")";
		}

		echo json_encode($variables);
	}

	public function getSiteJSON()
	{
		if ($this->getXssCleanInput('siteid')) {

			$result = $this->variables->getSite($this->getXssCleanInput('siteid'));

			foreach($result as &$var) {
				$var['VariableName'] = translateTerm($var['VariableName']);
			}

			echo json_encode($result);
		}
		else
		{
			$data['errorMsg'] = "One of the parameters: Siteid is not defined. An example request would be getSiteJSON?siteid=1";
			$this->load->view('templates/apierror', $data);
		}
	}

	public function getTypes()
	{
		if (
			$this->getXssCleanInput('siteid') && 
			$this->getXssCleanInput('varname')
		) {

			$variable = $this->variables->getVariableWithUnit(
				$this->getXssCleanInput('varname')
			);

			$varname = $variable[0]['VariableName'];

			$result = $this->variables->getTypes(
				$this->getXssCleanInput('siteid'),
				$varname
			);

			foreach($result as &$var) {
				$var['display'] = translateTerm($var['DataType']);
			}

			echo json_encode($result);
		}
		else {
			$data['errorMsg'] = "One of the parameters: Siteid,Variable Name is not defined. An example request would be getTypes?siteid=1&&varname=abc";
			$this->load->view('templates/apierror', $data);
		}
	}

	public function updateVarID()
	{
		$variable = $this->variables->getVariableWithUnit(
			$this->getXssCleanInput('varname')
		);

		$varname = $variable[0]['VariableName'];

		$result = $this->variables->getVarID(
			$this->getXssCleanInput('siteid'),
			$varname,
			$this->getXssCleanInput('type')
		);

		echo $result[0]['VariableID'];
	}

	public function getUnit()
	{
		$var = $this->getXssCleanInput('varid');

		if ($var !== false) {
			echo json_encode($this->variables->getUnit($var));
		}
		else {
			$data['errorMsg'] = "One of the parameters: VariableID is not defined. An example request would be getUnit?varid=1";
			$this->load->view('templates/apierror', $data);
		}
	}

	public function getWithUnit()
	{
		$var = $this->getXssCleanInput('varid');

		if ($var !== false) {
			echo json_encode($this->variables->getVariableWithUnit($var));
		}
		else {
			$data['errorMsg'] = "One of the parameters: VariableID is not defined. An example request would be getWithUnit?varid=1";
			$this->load->view('templates/apierror', $data);
		}
	}

	public function getTable()
	{
		$valueid = end($this->uri->segment_array());

		if ($valueid == "getTable") {
			$data['errorMsg'] = "One of the parameters: TableName is not defined. An example request would be getTable/variablenamecv";
			$this->load->view('templates/apierror', $data);
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

		echo json_encode($result);
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

		echo json_encode($result);
	}

	public function getUnitsByType()
	{
		$type = $this->getXssCleanInput('type');

		if($type !== false) {

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
			echo json_encode($result);
		}
		else {
			$data['errorMsg'] = "One of the parameters: unitsType is not defined. An example request would be getUnitTypes?type=Area";
			$this->load->view('templates/apierror', $data);
		}
	}

	public function delete()
	{
		$varid = end($this->uri->segment_array());

		if ($varid == "delete") {
			$data['errorMsg']="One of the parameters: methodid is not defined. An example request would be delete/1";
			$this->load->view('templates/apierror',$data);
			return;
		}

		$result = $this->variables->delete($varid);

		if ($this->getXssCleanInput('ui')) {
			if ($result) {
				addSuccess(getTxt('VariableSuccess'));
			}
			else {
				addError(getTxt('ProcessingError'));
			}
		}

		echo json_encode(array("status" => ($result? "success" : "failed")));
	}
}
