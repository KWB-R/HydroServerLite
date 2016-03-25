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
		$otherSlashNew = getTxt('OtherSlashNew');

		$regular = ($this->input->post('isreg') == getTxt('Regular'))? 1 : 0;
		
		$Variable = array(
			'VariableCode'    => $this->input->post('VariableCode'),
			'TimeSupport'     => $this->input->post('tsup'),
			'NoDataValue'     => -9999,
			'GeneralCategory' => $this->input->post('gc'),
			'DataType'        => $this->input->post('datatype'),
			'TimeunitsID'     => $this->input->post('timeunit'),
			'IsRegular'       => $regular
		);

		//The above are the static variables.

		$varname = $this->input->post('varname');

		if ($varname == $otherSlashNew) {

			$varname = $this->input->post('NewVarName');

			$this->variables->addTDef(
				'variablenamecv', $varname, $this->input->post('vardef')
			);
		}

		$Variable['VariableName'] = $varname;

		$spec = $this->input->post('specdata');

		if ($spec == $otherSlashNew) {

			//New Spec Processing.

			$spec = $this->input->post('other_spec');

			$this->variables->addTDef(
				'speciationcv', $spec, $this->input->post('specdef')
			);
		}

		$Variable['Speciation'] = $spec;

		$smed = $this->input->post('samplemedium');

		if ($smed == $otherSlashNew) {

			//New Sample Medium Name Processing.

			$smed = $this->input->post('smnew');

			$this->variables->addTDef(
				'samplemediumcv', $smed, $this->input->post('smnew')
			);
		}

		$Variable['SampleMedium'] = $smed;
		
		//Unit Checking
		//First Check New UNIT TYPE.

		$utype = $this->input->post('unittype');
		$unit = $this->input->post('unit');

		if ($utype == $otherSlashNew) {

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

		$Variable['VariableunitsID'] = $unit;

		//Check Value Type

		$vt = $this->input->post('valuetype');

		if ($vt == $otherSlashNew) {

			//New Sample Medium Name Processing.

			$vt = $this->input->post('valuetypenew');

			$this->variables->addTDef(
				'valuetypecv',
				$vt,
				$this->input->post('vtdef')
			);
		}

		$Variable['ValueType'] = $vt;

		return $Variable;
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
		if ($this->input->get('siteid', TRUE)) {

			$result = $this->variables->getSite(
				$this->input->get('siteid', TRUE)
			);

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
			$this->input->get('siteid', TRUE) && 
			$this->input->get('varname', TRUE)
		) {

			$variable = $this->variables->getVariableWithUnit(
				$this->input->get('varname', TRUE)
			);

			$varname = $variable[0]['VariableName'];

			$result = $this->variables->getTypes(
				$this->input->get('siteid', TRUE),
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
			$this->input->get('varname', TRUE)
		);

		$varname = $variable[0]['VariableName'];

		$result = $this->variables->getVarID(
			$this->input->get('siteid', TRUE),
			$varname,
			$this->input->get('type', TRUE)
		);

		echo $result[0]['VariableID'];
	}

	public function getUnit()
	{
		$var = $this->input->get('varid', TRUE);

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
		$var = $this->input->get('varid', TRUE);

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

		if (! $this->input->get('noNew', TRUE)) {
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
		$type = $this->input->get('type', TRUE);

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

			if (! $this->input->get('noNew', TRUE)) {
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

		if($this->input->get('ui', TRUE)) {
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
