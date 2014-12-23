<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Sites Controller
|--------------------------------------------------------------------------
|
| 
*/
class Users extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('site','',TRUE);
	}
	
	public function adduser()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('adduser',$data);
	}
	
	public function changeauthority()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('changeauthority',$data);
	}
	
	public function changepassword()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('changepassword',$data);	
	}
	
	public function changeyourpassword()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('change_yourpassword',$data);	
	}
	
	public function removeuser()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('removeuser',$data);	
	}
	
	
}
