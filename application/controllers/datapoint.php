<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Data Controller 
|--------------------------------------------------------------------------
| It manages all the data points. 
| 
*/
class Datapoint extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('site','',TRUE);
	}
	
	public function addvalue()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('datapoint/addvalue',$data);
	}
	
	public function addmultiplevalues()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('datapoint/addmultiplevalues',$data);
	}
	
	public function importfile()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('datapoint/importfile',$data);
	}
	
}
