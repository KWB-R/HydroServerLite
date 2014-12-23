<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Sites Controller
|--------------------------------------------------------------------------
|
| 
*/
class Methods extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('site','',TRUE);
	}
	
	public function addmethod()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('add_method',$data);
	}
	
	public function editmethod()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('change_method',$data);
	}
	
}
