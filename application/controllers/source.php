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
		if(!isAdmin())
		{
			$this->kickOut();	
		}
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
			else
			{
				addError(getTxt('ProcessingError'));
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
			$topicsArray[$topic['Term']]=translateTerm($topic['Term']);
		}
		$topicOptions = genOptions($topicsArray);
		$data['topicOptions'] = $topicOptions;
		$this->load->view('sources/addsource',$data);
	}
	
	public function change()
	{	
		if(!isAdmin())
		$this->kickOut();
		
		if($_POST)
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
				
					
				$metaID = $this->sources->updateMD($dataPoint,$this->input->post('MetadataID'));
				$result = $this->sources->updateSource($this->input->post('Organization'),$this->input->post('SourceDescription'),$this->input->post('SourceLink'),$this->input->post('ContactName'),$this->input->post('Phone'),$this->input->post('Email'),$this->input->post('Address'),$this->input->post('City'),$this->input->post('State'),$this->input->post('ZipCode'),$this->input->post('Citation'),$this->input->post('MetadataID'),$this->input->post('SourceID'));
			
			if($result)
			{
				addSuccess(getTxt('SourceEdited'));
			}	
			else
			{
				addError(getTxt('ProcessingError'));
			}
			
		}
		
		
		
		$sources = $this->sources->getAll();
		$sourceOptions = optionsSource($sources);
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$data['sourceOptions']=$sourceOptions;
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
			$topicsArray[$topic['Term']]=translateTerm($topic['Term']);
		}
		$topicOptions = genOptions($topicsArray);
		$data['topicOptions'] = $topicOptions;
		$this->load->view('sources/changesource',$data);
	}
	
	public function get()
	{
		$sourceID = end($this->uri->segment_array());

		if ($sourceID == "get") {
			$data['errorMsg'] = "One of the parameters: SourceID is not defined. An example request would be get/1";
			$this->load->view('templates/apierror', $data);
			return;
		}

		// Convert to numeric
		$sourceID = (0 + $sourceID);
		$result = $this->sources->get($sourceID);

		if (count($result) > 0 && $sourceID !== -1) {
			$result = $result[0];
		}

		echo json_encode($result, JSON_PRETTY_PRINT);
	}
	
	public function delete()
	{
		$sourceid = end($this->uri->segment_array());
		if($sourceid=="delete")
		{
			$data['errorMsg']="One of the parameters: SourceID is not defined. An example request would be delete/1";
			$this->load->view('templates/apierror',$data);
			return;
		}
		$result = $this->sources->delete($sourceid);
		if($result)
			{	
				if($this->input->get('ui', TRUE))
				addSuccess(getTxt('SourceMetadataDeleted'));	
				$output="success";	
			}
		else
			{
				if($this->input->get('ui', TRUE))
				addError(getTxt('ProcessingError'));	
				$output="failed";
			}		
		$output = array("status"=>$output);
		echo json_encode($output);	
	}
	
}
