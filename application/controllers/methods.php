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
		$this->dontAuth = array('getMethodsJSON','getJSON','getSiteVarJSON');
		parent::__construct();
		$this->load->model('method','',TRUE);
		$this->load->library('form_validation');
	}
	
	public function add()
	{	
		if(!isAdmin())
		{
			$this->kickOut();	
		}
		if($_POST)
		{
			$this->form_validation->set_rules('MethodDescription', 'MethodDescription', 'trim|required');
			$this->form_validation->set_rules('MethodLink', 'MethodLink', 'trim');	
			$this->form_validation->set_rules('jqxWidget', 'VariableList', 'trim|required');
		}
		if ($this->form_validation->run() == FALSE)
		{
			  $errors = validation_errors();
			  if(!empty($errors))
			  {addError($errors);}
		}
		else
		{
			$result=$this->method->add($this->input->post('MethodDescription'),$this->input->post('MethodLink'),$this->input->post('jqxWidget'));
			if($result)
			{
				addSuccess(getTxt('MethodSuccessfully'));	
			}
			else
			{
				addError(getTxt('ProcessingError'));
			}
		}
		
		
		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('methods/addmethod',$data);
	}
	
	public function change()
	{	
		if(!isAdmin())
		{
			$this->kickOut();	
		}
		if($_POST)
		{
			$this->form_validation->set_rules('MethodDescription2', 'MethodDescription', 'trim|required');
			$this->form_validation->set_rules('MethodLink2', 'MethodLink', 'trim');	
			$this->form_validation->set_rules('MethodID2', 'Method ID', 'trim|required');
		
		if ($this->form_validation->run() == FALSE)
		{
			  $errors = validation_errors();
			  if(!empty($errors))
			  {addError($errors);}
		}
		else
		{
			$result = $this->method->update($this->input->post('MethodID2'),$this->input->post('MethodDescription2'),$this->input->post('MethodLink2'));
			if($result)
			{	
				addSuccess(getTxt('MethodEdited'));		
			}
			else
			{
				addError(getTxt('ProcessingError'));	
			}		
		}
		}
		//Get methods that can be edited. 
		$methods = $this->method->getEditable();
		$methodsArray = array();
		foreach($methods as $method)
		{
			$methodsArray[$method['MethodID']]=$method['MethodDescription'];
		}
		$methodOptions = genOptions($methodsArray);
		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$data['methodOptions']=$methodOptions;
		$this->load->view('methods/changemethod',$data);
	}
	
	public function delete()
	{
		$methodid = end($this->uri->segment_array());
		if($methodid=="delete")
		{
			$data['errorMsg']="One of the parameters: methodid is not defined. An example request would be delete/1";
			$this->load->view('templates/apierror',$data);
			return;
		}
		$result = $this->method->delete($methodid);
		if($result)
			{	
				if($this->input->get('ui', TRUE))
				addSuccess(getTxt('MethodDeleted'));	
				$output="success";	
			}
		else
			{
				if($this->input->get('ui', TRUE))
				addError(getTxt('ProcessingError'));	
				$output="failed";
			}		
		$output = array("status"=>$output);
		echo json_encode($output);	
	}
	
	public function methodInfo()
	{	
		if(!isAdmin())
		{
			$this->kickOut();	
		}
		$methodid = end($this->uri->segment_array());
		if($methodid=="methodInfo")
		{
			$data['errorMsg']="One of the parameters: MethodID is not defined. An example request would be methodInfo/1";
			$this->load->view('templates/apierror',$data);
			return;
		}
		
		$method = $this->method->getByID($methodid);
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$data['Method']=$method[0];
		$this->load->view('methods/methodinfo',$data);
	}
	
	public function getMethodsJSON()
	{
		if($this->input->get('var', TRUE))
		{
			$result = $this->method->getMethodsByVar($this->input->get('var', TRUE));
			foreach($result as &$var)
			{
				$var['MethodDescription']=translateTerm($var['MethodDescription']);
			}
			echo json_encode($result);
		}
		else
		{
			$data['errorMsg']="One of the parameters: Variable is not defined. An example request would be getMethodsJSON?var=1";
			$this->load->view('templates/apierror',$data);	
		}
	}
	
	public function getSiteVarJSON()
	{
		$var = $this->input->get('varid', TRUE);
		$site = $this->input->get('siteid', TRUE);
		if($var!==false&&$site!==false)
		{
			$result = $this->method->getByVarSite($var,$site);
			foreach($result as &$var)
			{
				$var['MethodDescription']=translateTerm($var['MethodDescription']);
			}
			echo json_encode($result);
		}
		else
		{
			$data['errorMsg']="One of the parameters: VariableID, SiteID is not defined. An example request would be getSiteVarJSON?varid=1&&siteid=2";
			$this->load->view('templates/apierror',$data);	
		}
	}
	public function getJSON()
	{
		$result = $this->method->getAll();
		foreach($result as &$var)
			{
				$var['MethodDescription']=translateTerm($var['MethodDescription']);
			}
		echo json_encode($result);
	}
	
}
