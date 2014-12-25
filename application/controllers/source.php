<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Sources Controller
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
	
	public function index()
	{
		$this->addsource();	
	}
	
	public function addsource()
	{	
		//Form Validation for the addsource page
		$this->load->library('form_validation');
		$this->form_validation->set_rules('Organization', 'Organization', 'trim|required|xss_clean');
		$this->form_validation->set_rules('SourceDescription', 'Source Description', 'trim|required|xss_clean');
		$this->form_validation->set_rules('ContactName', 'Contact Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('Phone', 'Phone Number', 'trim|required|xss_clean');
		$this->form_validation->set_rules('Email', 'Email Address', 'trim|required|xss_clean');
		$this->form_validation->set_rules('Address', 'Address', 'trim|required|xss_clean');
		$this->form_validation->set_rules('City', 'City', 'trim|required|xss_clean');
		$this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
		$this->form_validation->set_rules('ZipCode', 'Zip Code', 'trim|required|xss_clean');
		$this->form_validation->set_rules('TopicCategory', 'Topic Category', 'trim|required|xss_clean');
		$this->form_validation->set_rules('Title', 'Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('Abstract', 'Abstract', 'trim|required|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
		{
			  $errors = validation_errors();
			  if(!empty($errors))
			  {addError($errors);}
		}
		else
		{
			$result = $this->sources->addsource($this->input->post('Organization'),$this->input->post('SourceDescription'),$this->input->post('ContactName'),$this->input->post('Phone'),$this->input->post('Email'),$this->input->post('Address'),$this->input->post('City'),$this->input->post('state'),$this->input->post('ZipCode'),$this->input->post('TopicCategory'),$this->input->post('Title'),$this->input->post('Abstract'),$this->input->post('SourceLink'),$this->input->post('Citation'),$this->input->post('MetadataID'));
			
			if($result==1)
			{
				addSuccess(getTxt('Congrats').getTxt('AddAnother'));
			}	
			
		}
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
