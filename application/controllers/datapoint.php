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
		$this->dontAuth = array('getData','getDataJSON','compare','export');
		parent::__construct();
		$this->load->model('variables','',TRUE);
		$this->load->model('sources','',TRUE);
		$this->load->model('datapoints','',TRUE);
		$this->load->model('sc','',TRUE);
		$this->load->model('site','',TRUE);
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
				$this->updateSC();	
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
				$this->updateSC();
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
	
	public function getData()
	{
		$var = $this->input->get('varid', TRUE);
		$site = $this->input->get('siteid', TRUE);	
		$method = $this->input->get('meth', TRUE);
		$start = $this->input->get('startdate', TRUE);	
		$end = $this->input->get('enddate', TRUE);
		if($var!==false&&$site!==false&&$method!==false&&$start!==false&&$end!==false)
		{
			$result = $this->datapoints->getData($site,$var,$method,$start,$end);
			$variable = $this->variables->getVariableWithUnit($var);
			$variable = $variable[0];
			$unit = $variable['unitsType'];
			$noValue = $variable['NoDataValue'];
			
			echo("var data_test = [\r\n");
			$num_rows = count($result);
			$count=1;		
			//To echo Data in javascript format
			foreach ($result as $row) 
			{
				$pieces = explode("-", $row['LocalDateTime']);
				$pieces2 = explode(" ", $pieces[2]);
				$pieces3 = explode(":", $pieces2[1]);
				$pieces[1]=$pieces[1]-1;
				
				$output="[Date.UTC(".$pieces[0].",".$pieces[1].",".$pieces2[0].",".$pieces3[0].",".$pieces3[1].",".$pieces3[2]."),".$row['DataValue']."]";
				
				//Check for NoDataValue (Default is -9999))
				if (!($row['DataValue'] == $noValue))
				{
					echo $output;
					 if($count!=$num_rows)
						{echo ",";}
					 $count=$count+1;
					 echo ("\r\n");
				}
			}
			echo("];");
		}
		else
		{
			$data['errorMsg']="One of the parameters: VariableID, SiteID,MethodID is not defined. An example request would be getData?varid=1&siteid=2&methodid=1&startdate=2012-04-02 00:00:00&enddate=2012-04-02 00:00:00";
			$this->load->view('templates/apierror',$data);	
		}
		
	}
	
	public function getDataJSON()
	{
		$var = $this->input->get('varid', TRUE);
		$site = $this->input->get('siteid', TRUE);	
		$method = $this->input->get('meth', TRUE);
		$start = $this->input->get('startdate', TRUE);	
		$end = $this->input->get('enddate', TRUE);
		if($var!==false&&$site!==false&&$method!==false&&$start!==false&&$end!==false)
		{
			$result = $this->datapoints->getData($site,$var,$method,$start,$end);
			echo json_encode($result);
		}
		else
		{
			$data['errorMsg']="One of the parameters: VariableID, SiteID,MethodID is not defined. An example request would be getDateJSON?varid=1&siteid=2&methodid=1&startdate=2012-04-02 00:00:00&enddate=2012-04-02 00:00:00";
			$this->load->view('templates/apierror',$data);	
		}	
	}
	
	public function delete()
	{
		$valueid = end($this->uri->segment_array());
		if($valueid=="delete")
		{
			$data['errorMsg']="One of the parameters: ValueID is not defined. An example request would be delete/1";
			$this->load->view('templates/apierror',$data);
			return;
		}
		$result = $this->datapoints->delete($valueid);
		if($result)
			{
				$output="success";
				$this->updateSC();	
			}
		else
			{
				$output="failed";
			}		
		$output = array("status"=>$output);
		echo json_encode($output);	
	}
	
	public function edit()
	{
		$valueid = end($this->uri->segment_array());
		if($valueid=="edit")
		{
			$data['errorMsg']="One of the parameters: ValueID,date,time,value is not defined. An example request would be edit/1?val=2&dt=2001-01-01&time=12:00";
			$this->load->view('templates/apierror',$data);
			return;
		}
		$value = $this->input->get('val', TRUE);
		$dt=$this->input->get('dt', TRUE);	
		$time = $this->input->get('time', TRUE);
		
		if($valueid!==false&&$value!==false&&$dt!==false&&$time!==false)
		{
			$LocalDateTime = $dt . " " . $time . ":00";
			$localtimestamp = strtotime($LocalDateTime);
			$ms = $this->config->item('UTCOffset') * 3600;
			$utctimestamp = $localtimestamp - ($ms);
			$DateTimeUTC = date("Y-m-d H:i:s", $utctimestamp);
			$result = $this->datapoints->editPoint($valueid,$value,$LocalDateTime,$DateTimeUTC);
			if($result)
			{
				$output="success";	
				$this->updateSC();
			}
			else
			{
				$output="failed";
			}		
			$output = array("status"=>$output);
			echo json_encode($output);	
		}
		else
		{
			$data['errorMsg']="One of the parameters: ValueID,date,time,value is not defined. An example request would be edit/1?val=2&dt=2001-01-01&time=12:00";
			$this->load->view('templates/apierror',$data);	
		}	
	}
	

	
	public function export()
	{
		$var = $this->input->get('varid', TRUE);
		$site = $this->input->get('siteid', TRUE);	
		$method = $this->input->get('meth', TRUE);
		$start = $this->input->get('startdate', TRUE);	
		$end = $this->input->get('enddate', TRUE);
		if($var!==false&&$site!==false&&$method!==false&&$start!==false&&$end!==false)
		{
			$result = $this->datapoints->getResultData($site,$var,$method,$start,$end);
			header( 'Content-Type: text/csv' );
			header('Content-Disposition: attachment; filename=HSLDataSite'.$site.'.csv');
			$this->load->dbutil();
			echo $this->dbutil->csv_from_result($result);
		}
		else
		{
			$data['errorMsg']="One of the parameters: VariableID, SiteID,MethodID is not defined. An example request would be export?varid=1&siteid=2&methodid=1&startdate=2012-04-02 00:00:00&enddate=2012-04-02 00:00:00";
			$this->load->view('templates/apierror',$data);	
		}	
	}
	
	public function add()
	{	
		$var = $this->input->get('varid', TRUE);
		$site = $this->input->get('sid', TRUE);	
		$method = $this->input->get('mid', TRUE);
		$value = $this->input->get('val', TRUE);
		$dt=$this->input->get('dt', TRUE);	
		$time = $this->input->get('time', TRUE);
		$source = $this->sc->getSourceBySite($site);
		$sourceID = $source[0]['SourceID'];
		if($var!==false&&$value!==false&&$dt!==false&&$time!==false&&$site!==false&&$method!==false)
		{
			$dataPoint = $this->createDP($dt,$time,$value,$site,$var,$method,$sourceID);
			$result=$this->datapoints->addPoint($dataPoint);
			if($result)
			{
				$output="success";
				$this->updateSC();
			}
			else
			{
				$output="failed";
			}		
			$output = array("status"=>$output,"id"=>$result);
			echo json_encode($output);	
		}
		
		else
		{
			$data['errorMsg']="One of the parameters: VariableID,date,time,value,SiteID,MethodID, is not defined. An example request would be add?val=2&dt=2001-01-01&time=12:00&sid=1&mid=1&varid=1";
			$this->load->view('templates/apierror',$data);	
		}	
	}
	public function compare()
	{
		$valueid = end($this->uri->segment_array());
		if($valueid=="compare")
		{
			$data['errorMsg']="One of the parameters: compareID. An example request would be compare/1";
			$this->load->view('templates/apierror',$data);
			return;
		}
		//List of CSS to pass to this view
		$data=$this->StyleData;
		
		if($valueid==2)
		{
			$siteid = $this->input->get('siteid', TRUE);
			$site = $this->site->getSite($siteid);
			$data['SiteName']=$site[0]['SiteName'];
		}
		
		$this->load->view("compare/".$valueid,$data);	
	}
	
	public function updateSC()
	{
		//UPDATES THE SERIES CATALOG.
		//BREAKING FROM CODEIGINITER POLICIES HERE 
		//Its just better for now to use the same script. 
		
		
		$connection = mysql_connect($this->config->item('database_host'), $this->config->item('database_username'), $this->config->item('database_password'))
		or die("<p>Error connecting to database: " . 
				   mysql_error() . "</p>");
		mysql_set_charset ("utf8");
		  //echo "<p>Connected to MySQL!</p>";
		  
		  $db = mysql_select_db($this->config->item('database_name'),$connection)
			or die("<p>Error selecting the database " . $this->config->item('database_name') .
			  mysql_error() . "</p>");
		
		require_once APPPATH.'../assets/update_series_catalog.php';
	}
}
