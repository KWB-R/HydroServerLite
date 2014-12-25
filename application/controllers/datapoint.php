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
		$this->load->model('variables','',TRUE);
		$this->load->model('sources','',TRUE);
		$this->load->model('datapoints','',TRUE);
		$this->load->library('form_validation');
	}
	
	private function cNull($val)
	{
		if(strtolower($val)=="null")
		{
			return NULL;	
		}
		return $val;
	}
	
	public function addvalue()
	{	
	
		if($_POST)
		{
			$this->form_validation->set_rules('SourceID', 'SourceID', 'trim|required');
			$this->form_validation->set_rules('SiteID', 'SiteID', 'trim|required');	
			$this->form_validation->set_rules('VariableID', 'SourceID', 'trim|required');
			$this->form_validation->set_rules('MethodID', 'SiteID', 'trim|required');	
			$this->form_validation->set_rules('value', 'SourceID', 'trim|required|xss_clean');
			$this->form_validation->set_rules('datepicker', 'SiteID', 'trim|required');
			$this->form_validation->set_rules('timepicker', 'SiteID', 'trim|required');
		}
		if ($this->form_validation->run() == FALSE)
		{
			  $errors = validation_errors();
			  if(!empty($errors))
			  {addError($errors);}
		}
		else
		{
			$LocalDateTime = $this->input->post('datepicker') . " " . $this->input->post('timepicker') . ":00";
			$localtimestamp = strtotime($LocalDateTime);
			$ms = $this->config->item('UTCOffset') * 3600;
			$utctimestamp = $localtimestamp - ($ms);
			$DateTimeUTC = date("Y-m-d H:i:s", $utctimestamp);
			
			$dataPoint = array(
			'DataValue' => $this->input->post('value'),  
			'ValueAccuracy' => $this->cNull($this->config->item('ValueAccuracy')),
			'LocalDateTime' => $LocalDateTime, 
			'UTCOffset' => $this->cNull($this->config->item('UTCOffset')), 
			'DateTimeUTC' => $DateTimeUTC, 
			'SiteID' => $this->input->post('SiteID'),
			'VariableID' => $this->input->post('VariableID'), 
			'OffsetValue' => $this->cNull($this->config->item('OffsetValue')),
			'OffsetTypeID' => $this->cNull($this->config->item('OffsetTypeID')),  
			'CensorCode' => $this->cNull($this->config->item('CensorCode')),
			'QualifierID' => $this->cNull($this->config->item('QualifierID')), 
			'MethodID' => $this->input->post('MethodID'), 
			'SourceID' => $this->input->post('SourceID'), 
			'SampleID' => $this->cNull($this->config->item('SampleID')),
			'DerivedFromID' => $this->cNull($this->config->item('DerivedFromID')), 
			'QualityControlLevelID' => $this->cNull($this->config->item('QualityControlLevelID')));
			
			$result=$this->datapoints->addPoint($dataPoint);
			if($result)
			{
				addSuccess(getTxt('ValueSuccessfully'));	
			}
			else
			{
				addError(getTxt('ProcessingError'));
			}
		}
		
		//GetSources
		$sources = $this->sources->getAll();
		$sourceOptions = optionsSource($sources);
		//Get Variables
		$variables = $this->variables->getAll();
		$varOptions = optionsVariable($variables);
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$data['sourcesOptions']=$sourceOptions;
		$data['variableOptions']=$varOptions;
		$this->load->view('datapoint/addvalue',$data);
	}
	
	public function addmultiplevalues()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('datapoint/addmultiplevalues',$data);
	}
	
	public function importfile()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('datapoint/importfile',$data);
	}
	
}
