<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Sites Controller
|--------------------------------------------------------------------------
|
| 
*/
class Source extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('sources','',TRUE);
	}
	
	public function addsource()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('sources/addsource',$data);
	}
	
	public function editsource()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('sources/changesource',$data);
	}
	
}
