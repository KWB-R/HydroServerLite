<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Methods Controller
|--------------------------------------------------------------------------
|
| 
*/
class Methods extends MY_Controller {
	
	function __construct()
	{
		$this->dontAuth = array('getMethodsJSON', 'getJSON', 'getSiteVarJSON');
		parent::__construct();
		$this->loadModel('method');
		$this->load->library('form_validation');
	}

	public function add()
	{
		$this->kickNonAdminOut();

		if ($_POST) {
			$this->form_validation->set_rules('MethodDescription', 'MethodDescription', 'trim|required');
			$this->form_validation->set_rules('MethodLink', 'MethodLink', 'trim');
			$this->form_validation->set_rules('jqxWidget', 'VariableList', 'trim|required');
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = validation_errors();

			if (! empty($errors)) {
				addError($errors);
			}
		}
		else {
			$result = $this->method->add(
				$this->input->post('MethodDescription'),
				$this->input->post('MethodLink'),
				$this->input->post('jqxWidget')
			);

			$this->addSuccessOrError($result, 'MethodSuccessfully');
		}
		
		//List of CSS to pass to this view
		$data = $this->StyleData;
		$this->load->view('methods/addmethod', $data);
	}
	
	public function change()
	{
		$this->kickNonAdminOut();

		if ($_POST) {

			$this->form_validation->set_rules('MethodDescription2', 'MethodDescription', 'trim|required');
			$this->form_validation->set_rules('MethodLink2', 'MethodLink', 'trim');
			$this->form_validation->set_rules('MethodID2', 'Method ID', 'trim|required');

			if ($this->form_validation->run() == FALSE) {

				$errors = validation_errors();

				if (! empty($errors)) {
					addError($errors);
				}
			}
			else {
				$result = $this->method->update(
					$this->input->post('MethodID2'),
					$this->input->post('MethodDescription2'),
					$this->input->post('MethodLink2')
				);

				$this->addSuccessOrError($result, 'MethodEdited');
			}
		}

		// Get methods that can be edited
		$methods = $this->method->getEditable();
		$methodsArray = array();

		foreach($methods as $method) {
			$methodsArray[$method['MethodID']] = $method['MethodDescription'];
		}

		$methodOptions = genOptions($methodsArray);
		
		// List of CSS to pass to this view
		$data = $this->StyleData;
		$data['methodOptions'] = $methodOptions;
		$this->load->view('methods/changemethod', $data);
	}
	
	public function delete()
	{
		$methodID = end($this->uri->segment_array());

		if ($methodID == "delete") {
			$this->loadApiErrorView(
				"One of the parameters: methodid is not defined. " .
				"An example request would be delete/1"
			);
			return;
		}

		$result = $this->method->delete($methodID);

		if ($this->input->get('ui', TRUE)) {
			$this->addSuccessOrError($result, 'MethodDeleted');
		}

		if ($result) {
			$output = "success";
			$delMethodID = $this->method->updateVarMeth2($methodID);
		}
		else {
			$output = "failed";
		}

		echo $this->jsonEncoded(array("status" => $output));
	}

	public function methodInfo()
	{
		$this->kickNonAdminOut();

		$methodID = end($this->uri->segment_array());

		if ($methodID == "methodInfo") {
			$this->LoadApiErrorView(
				"One of the parameters: MethodID is not defined. " .
				"An example request would be methodInfo/1"
			);
			return;
		}

		$method = $this->method->getByID($methodID);

		// List of CSS to pass to this view
		$data = $this->StyleData;
		$data['Method'] = $method[0];
		$this->load->view('methods/methodinfo', $data);
	}
	
	public function getMethodsJSON()
	{
		$variableID = $this->input->get('var', TRUE);

		if ($variableID) {

			$result = $this->method->getMethodsByVar($variableID);

			$result = $this->translateTerms($result);

			echo $this->jsonEncoded($result);
		}
		else {
			$this->loadApiErrorView(
				"One of the parameters: Variable is not defined. " .
				"An example request would be getMethodsJSON?var=1"
			);
		}
	}

	public function getSiteVarJSON()
	{
		$variableID = $this->input->get('varid', TRUE);
		$siteID = $this->input->get('siteid', TRUE);

		if ($variableID !== false && $siteID !== false) {

			$result = $this->method->getByVarSite($variableID, $siteID);

			$result = $this->translateTerms($result);

			echo $this->jsonEncoded($result);
		}
		else {
			$this->loadApiErrorView(
				"One of the parameters: VariableID, SiteID is not defined. ".
				"An example request would be getSiteVarJSON?varid=1&siteid=2"
			);
		}
	}

	public function getJSON()
	{
		$result = $this->method->getAll();

		$result = $this->translateTerms($result);

		echo $this->jsonEncoded($result);
	}
	
	private function kickNonAdminOut()
	{
		if (! isAdmin()) {
			$this->kickOut();
		}
	}

	private function addSuccessOrError($success, $successKeyword)
	{
		if ($success) {
			addSuccess(getTxt($successKeyword));
		}
		else {
			addError(getTxt('ProcessingError'));
		}
	}

	private function translateTerms($records)
	{
		foreach ($records as &$record) {
			$record['MethodDescription'] = translateTerm($record['MethodDescription']);
		}

		return $records;
	}

	private function loadApiErrorView($errorMessage)
	{
		$data['errorMsg'] = $errorMessage;
		$this->load->view('templates/apierror', $data);
	}
}
