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
		$this->load->view('add_site',$data);
	}
	
	public function edit()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('edit_site',$data);
	}
	public function addsource()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('add_source',$data);
		
	}
	public function changesource()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('change_source',$data);
		
	}
	public function editvariable()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('edit_var',$data);
		
	}
	public function addvariable()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('add_variable',$data);
		
	}
	public function addmethod()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('add_method',$data);
		
	}
	public function changemethod()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('change_method',$data);
		
	}
	public function adduser()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('adduser',$data);
		
	}
	public function changeyourpassword()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('change_yourpassword',$data);
		
	}
	public function changepassword()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('changepassword',$data);
		
	}
	public function changeauthority()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('changeauthority',$data);
		
	}
	public function removeuser()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('removeuser',$data);
		
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
	public function importdatafile()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('import_data_file',$data);
		
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
