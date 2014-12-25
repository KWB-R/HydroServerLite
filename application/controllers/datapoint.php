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
	
	private function createDP($date,$time,$val,$siteid,$varid,$methid,$sourceid)
	{
		
		$LocalDateTime = $date . " " . $time . ":00";
		$localtimestamp = strtotime($LocalDateTime);
		$ms = $this->config->item('UTCOffset') * 3600;
		$utctimestamp = $localtimestamp - ($ms);
		$DateTimeUTC = date("Y-m-d H:i:s", $utctimestamp);
		
		$dataPoint = array(
		'DataValue' => $val,  
		'ValueAccuracy' => $this->cNull($this->config->item('ValueAccuracy')),
		'LocalDateTime' => $LocalDateTime, 
		'UTCOffset' => $this->cNull($this->config->item('UTCOffset')), 
		'DateTimeUTC' => $DateTimeUTC, 
		'SiteID' => $siteid,
		'VariableID' => $varid, 
		'OffsetValue' => $this->cNull($this->config->item('OffsetValue')),
		'OffsetTypeID' => $this->cNull($this->config->item('OffsetTypeID')),  
		'CensorCode' => $this->cNull($this->config->item('CensorCode')),
		'QualifierID' => $this->cNull($this->config->item('QualifierID')), 
		'MethodID' => $methid, 
		'SourceID' => $sourceid, 
		'SampleID' => $this->cNull($this->config->item('SampleID')),
		'DerivedFromID' => $this->cNull($this->config->item('DerivedFromID')), 
		'QualityControlLevelID' => $this->cNull($this->config->item('QualityControlLevelID')));		
		
		return $dataPoint;
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

			$dataPoint = $this->createDP($this->input->post('datepicker'),$this->input->post('timepicker'),$this->input->post('value'),$this->input->post('SiteID'),$this->input->post('VariableID'),$this->input->post('MethodID'),$this->input->post('SourceID'));
			
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
		if($_POST)
		{
			$dataset=array();
			$rows = $this->input->post('finalRows');
			for($i=1;$i<=$rows;$i++)
			{
				$dataPoint = $this->createDP($this->input->post('datepicker'.$i),$this->input->post('timepicker'.$i),$this->input->post('value'.$i),$this->input->post('SiteID'),$this->input->post('VariableID'.$i),$this->input->post('MethodID'.$i),$this->input->post('SourceID'));
				$dataset[]=$dataPoint;
			}
			
			$result=$this->datapoints->addPoints($dataset);
			if($result==$rows)
			{
				addSuccess(getTxt('DataEnteredSuccessfully'));	
			}
			else
			{
				addError(getTxt('ProcessingError'));
			}
		}
		//GetSources
		$sources = $this->sources->getAll();
		$sourceOptions = optionsSource($sources);
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$data['sourcesOptions']=$sourceOptions;
		$this->load->view('datapoint/addmultiplevalues',$data);
	}
	
	public function importfile()
	{
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('datapoint/importfile',$data);
	}
	
}
