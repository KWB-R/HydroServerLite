<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Sites Controller
|--------------------------------------------------------------------------
|
| 
*/
class Variables extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('site','',TRUE);
	}
	
	public function addvariable()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('add_variable',$data);
	}
	
	public function editvariable()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('edit_var',$data);
	}
	
}
