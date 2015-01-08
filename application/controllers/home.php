<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	public function installation()
	{
	$_SESSION['setup'] = true;	
	$data=$this->StyleData;
	$this->load->view('edit_mainconfig',$data);	
	}
	public function backup()
	{
	$siteid = end($this->uri->segment_array());
		if($siteid=="details")
		{
			$data['errorMsg']="One of the parameters: SiteID is not defined. An example request would be details/1";
			$this->load->view('templates/apierror',$data);
			return;
		}
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->model('site','',true);
		$siteData = $this->site->getSite($siteid);
		$data['site']=$siteData[0];
		$data['SiteID']=$siteid;
		$this->load->model('variables','',TRUE);
		$result = $this->variables->getSite($siteid);
		$data['Variables']=$result;
		$this->load->view('detailsbackup',$data);
	}
	public function index()
	{
		
		//Check if user is logged in from the helper and load the view depending on that. 

		if(isLoggedIn())
		{
			
		}
		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('welcome',$data);
	}
	
	public function help()
	{
		$data=$this->StyleData;
		$this->load->view('help',$data);	
	}
	
	public function changeLang()
	{
		changeLang($this->input->post('lang'),$this->input->post('disp'));
	}
	
	protected function authenticate()
	{
		//Home is open access
	}
}
