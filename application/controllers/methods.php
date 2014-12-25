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
	}
	
	public function addmethod()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('methods/addmethod',$data);
	}
	
	public function editmethod()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('methods/changemethod',$data);
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
	
}
