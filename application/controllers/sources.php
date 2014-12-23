<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Sites Controller
|--------------------------------------------------------------------------
|
| 
*/
class Sources extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('site','',TRUE);
	}
	
	public function addsource()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('add_source',$data);
	}
	
	public function editsource()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('change_source',$data);
	}
	
}
