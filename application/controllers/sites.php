<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Sites Controller
|--------------------------------------------------------------------------
|
| 
*/
class Sites extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('site','',TRUE);
	}
	
	public function map()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->displayAll();
		$this->load->view('map',$data);
	}
	
	public function add()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('site/add',$data);
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
	
	public function displayAll()
	{
		$result = $this->site->displayAll();
		//Parse it out
		$dom = new DOMDocument("1.0");
		$node = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);
		
		foreach ($result as $row)
		{
			print_r($row);
		}
		
	}
	
}
