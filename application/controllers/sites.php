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
		$this->map();
	}
	
	private function genNodes($result)
	{
		$dom = new DOMDocument("1.0");
		$node = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);
		foreach ($result as $row) {
			$node = $dom->createElement("marker");
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("name", $row['SiteName']);
			$newnode->setAttribute("siteid", $row['SiteID']);
			$newnode->setAttribute("sitecode", $row['SiteCode']);
			$newnode->setAttribute("lat", $row['Latitude']);
			$newnode->setAttribute("lng", $row['Longitude']);
			$newnode->setAttribute("sitetype", $row['SiteType']); 
			$newnode->setAttribute("sourcename", $row['Organization']);
			$newnode->setAttribute("sourcecode", $row['SourceID']);
			$newnode->setAttribute("sourcelink", $row['SourceLink']);
			$newnode->setAttribute("sitepic", $row['picname']);
		  }
		return $dom->saveXML();
	}
	
	public function displayAll()
	{
		$record_num = end($this->uri->segment_array());
		$result = $this->site->displayAll($record_num);
		$data['dump'] = $this->genNodes($result);
		$this->load->view('templates/xml_dump',$data);	
	}
	
	public function siteSearch()
	{
		//Check that required parameters are defined. 
		if($this->input->get('lat', TRUE)&&$this->input->get('long', TRUE)&&$this->input->get('radius', TRUE))
		{
			$result = $this->site->searchSite($this->input->get('lat', TRUE),$this->input->get('long', TRUE),$this->input->get('radius', TRUE));
			$data['dump'] = $this->genNodes($result);
			$this->load->view('templates/xml_dump',$data);	
		}
		else
		{
			$data['errorMsg']="One of the parameters: Latitude, Longitude or Radius is not defined. An example request would be siteSearch?lat=12.34&&long=14.56&&radius=12";
			$this->load->view('templates/apierror',$data);	
		}
			
	}
	
}
