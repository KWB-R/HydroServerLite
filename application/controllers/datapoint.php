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
	
	public function adddatavalue()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('add_data_value',$data);
	}
	
	public function addmultiplevalues()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('add_multiple_values',$data);
	}
	
	public function importfile()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('import_data_file',$data);
	}
	
}
