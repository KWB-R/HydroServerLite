<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Variables Controller
|--------------------------------------------------------------------------
|
| 
*/
class Variable extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('variables','',TRUE);
	}
	
	public function addvariable()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('variables/addvar',$data);
	}
	
	public function editvariable()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('variables/editvar',$data);
	}
	
	public function getAllJSON()
	{
		$variables = $this->variables->getAll();
		echo json_encode($variables);
	}
}
