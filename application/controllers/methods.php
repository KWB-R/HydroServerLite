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
		
		//Get methods that can be edited. 
		$methods = $this->method->getEditable();
		//Generate options for this. 
		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('methods/changemethod',$data);
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
		//Get methods that can be edited. 
		$method = $this->method->getByID($methodid);
		
		
		//Generate options for this. 
		
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
			echo json_encode($result);
		}
		else
		{
			$data['errorMsg']="One of the parameters: VariableID, SiteID is not defined. An example request would be getSiteVarJSON?varid=1&&siteid=2";
			$this->load->view('templates/apierror',$data);	
		}
	}
	
}
