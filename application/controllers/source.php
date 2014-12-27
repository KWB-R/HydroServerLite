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
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		$this->addsource();	
	}
	
	private function cNull($val)
	{
		if(strtolower($val)=="null")
		{
			return NULL;	
		}
		return $val;
	}
	
	public function add()
	{	
	
		if($_POST)
		//Form Validation for the addsource page
		{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('Organization', 'Organization', 'trim|required|xss_clean');
		$this->form_validation->set_rules('SourceDescription', 'Source Description', 'trim|required|xss_clean');
		$this->form_validation->set_rules('ContactName', 'Contact Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('Phone', 'Phone Number', 'trim|required|xss_clean');
		$this->form_validation->set_rules('Email', 'Email Address', 'trim|required|xss_clean');
		$this->form_validation->set_rules('Address', 'Address', 'trim|required|xss_clean');
		$this->form_validation->set_rules('City', 'City', 'trim|required|xss_clean');
		$this->form_validation->set_rules('State', 'State', 'trim|required|xss_clean');
		$this->form_validation->set_rules('ZipCode', 'Zip Code', 'trim|required|xss_clean');
		$this->form_validation->set_rules('TopicCategory', 'Topic Category', 'trim|required|xss_clean');
		$this->form_validation->set_rules('Title', 'Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('Abstract', 'Abstract', 'trim|required|xss_clean');
		}
		if ($this->form_validation->run() == FALSE)
		{
			
			$errors = validation_errors();
			if(!empty($errors))
			{addError($errors);}
		}
		else
		{
				//Array for generating MetadatID
				$dataPoint = array(
				'TopicCategory' => $this->input->post('TopicCategory'),  
				'Title' => $this->input->post('Title'),
				'Abstract' => $this->input->post('Abstract'), 
				'ProfileVersion' => $this->cNull($this->config->item('ProfileVersion')), 
				'MetadataLink' => $this->input->post('MetadataLink'));
				
					
			$metaID = $this->sources->genMD($dataPoint);
			$result = $this->sources->addsource($this->input->post('Organization'),$this->input->post('SourceDescription'),$this->input->post('SourceLink'),$this->input->post('ContactName'),$this->input->post('Phone'),$this->input->post('Email'),$this->input->post('Address'),$this->input->post('City'),$this->input->post('State'),$this->input->post('ZipCode'),$this->input->post('Citation'),$metaID);
			
			if($result==1)
			{
				addSuccess(getTxt('SourceSuccessfullyAdded'));
			}	
			
		}
		
		
		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		//Getting the states dropdown
		$states=getStates();
		$states['NULL']=getTxt('International');
		$stateOptions  = genOptions($states);
		$data['stateOptions']=$stateOptions;
		//Gets the topicCategory dropdown
		//getTC was created in the model
		$topics = $this->sources->getTC();
		$topicsArray = array();
		foreach($topics as $topic)
		{
			$topicsArray[$topic['Term']]=$topic['Term'];
		}
		$topicOptions = genOptions($topicsArray);
		$data['topicOptions'] = $topicOptions;
		$this->load->view('sources/addsource',$data);
	}
	
	public function change()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('sources/changesource',$data);
	}
	
	public function deletesource()
	{
		if(isStudent())
		$this->kickOut();
		if($_POST)
		{
		$this->form_validation->set_rules('SourceID', 'SourceID', 'trim|required|xss_clean');
		}
		if($this->form_validation->run() == FALSE)
		{
			$errors = validation_errors();
			  if(!empty($errors))
			  {addError($errors);}
		}
		else
		{
		$name = $this->input->post('SourceID');
		$result = $this->sources->delete($name);
		}
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('sources/changesource',$data);	
	}
	
}
