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
		
		$LocalDateTime = $date . " " . $time;
		$localtimestamp = strtotime($LocalDateTime);
		$ms = $this->config->item('UTCOffset') * 3600;
		$utctimestamp = $localtimestamp - ($ms);
		$DateTimeUTC = date("Y-m-d H:i:s", $utctimestamp);
		$LocalDateTime = date("Y-m-d H:i:s", $localtimestamp);
		
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
	
	private function fileUploadHandler()
	{
		$newDir = "./uploads/temp".time().rand();
		$oldmask = umask(0);
		$result = mkdir($newDir,0777);
		umask($oldmask);
		if(!$result)
		{
			addError(getTxt('FailTemp'));
			return false;
		}
		
		//Upload files. 
		$config['upload_path'] = $newDir;
		$config['allowed_types'] = 'csv|CSV';	
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_multi_upload('files'))
		  {
			  addError(getTxt('FailMoveFile').$this->upload->display_errors());
			  return false;
		  }
		return $this->upload->get_multi_upload_data();
	}
	
	private function getIDS($array, $id)
	{
		$ids=array();
		foreach($array as $row)
		{
			$ids[]=$row[$id];
		}
		return $ids;
	}
	
	private function processFiles($files)
	{
		$check=false;
		if($this->input->post('valueSpec'))
		{
			$check=true;
			//Values will be checked and fetched while processing.
			//Build ID tables.
			$siteIDS = $this->getIDS($this->site->getAll(),"SiteID");
			$sourceIDS = $this->getIDS($this->sources->getAll(),"SourceID");
			$varIDS = $this->getIDS($this->variables->getAll(),"VariableID");
			$this->load->model('method','',true);
			$methIDS = $this->getIDS($this->method->getAll(),"MethodID");
		}
		else
		{
			$source =  $this->input->post('SourceID');
			$site =  $this->input->post('SiteID');
			$var =  $this->input->post('VariableID');
			$meth =  $this->input->post('MethodID');
		}
		$dataset=array();
		foreach($files as $file)
		{
			$filepath = $file['full_path'];	
			$handle = fopen($filepath, "r");
			if(!$handle)
			{
				addError(getTxt('FailInputStream'));
				return;	
			}
			$flag=0;
			$row=1;
			$tracker=1;
			$dtIndex = 0;
			$valIndex = 1;
			while (($data = fgetcsv($handle)) !== FALSE) {
				if($flag==0)
				{
					if($check)
					{
						$dtIndex=4;
						$valIndex = 5;
						if(($data[0]!="SourceID")||($data[1]!="SiteID")||($data[2]!="VariableID")||($data[3]!="MethodID")||($data[4]!="LocalDateTime")||($data[5]!="DataValue"))	
						{
						addError(getTxt('InvalidHeading')."SourceID,SiteID,VariableID,MethodID,LocalDateTime,DataValue".getTxt('PleaseFix'));
						return false;					
						}	
					}
					else
					{
						if(($data[0]!="LocalDateTime")||($data[1]!="DataValue"))	
						{
						addError(getTxt('InvalidHeading')."LocalDateTime,DataValue".getTxt('PleaseFix'));
						return false;					
						}
					}
					$flag=1;
					continue;
				}
				
				try {
					$date = new DateTime($data[$dtIndex]);
				} catch (Exception $e) {
					//add error for invalid datatime format. on $row in file
					$msg=getTxt('InvalidTime').' '.$row.' '.getTxt('In').' '.$file['file_name'];
					addError($msg.getTxt('PleaseFix')."(".$e->getMessage().")");
					return false;
				}
				//Validate Value
				$value = $data[$valIndex];
				$regex="/^[\-+]?[0-9]*\.?[0-9]+$/";

				if (!preg_match($regex,$value)) {
				   $msg=getTxt('InvalidChar').' '.$row.' '.getTxt('In').' '.$file['file_name'];
				   addError($msg.getTxt('PleaseFix'));
				   return false;
				} 
				$dateVal = $date->format("Y-m-d");
				$timeVal = $date->format("H:i:s");
				
				if($check)
				{
					//Verify if entered IDS are correct. 
					$source =  $data[0];
					$site =  $data[1];
					$var =  $data[2];
					$meth =  $data[3];
					if(!in_array($source,$sourceIDS))
					{
						$msg=getTxt('invalid').' '.getTxt('sourceid').'.Row: '.$row.' '.getTxt('In').' '.$file['file_name'];
						addError($msg.getTxt('PleaseFix'));
						return false;
					}
					if(!in_array($site,$siteIDS))
					{
						$msg=getTxt('invalid').' '.getTxt('siteid').'.Row: '.$row.' '.getTxt('In').' '.$file['file_name'];
						addError($msg.getTxt('PleaseFix'));
						return false;
					}
					if(!in_array($var,$varIDS))
					{
						$msg=getTxt('invalid').' '.getTxt('varid').'.Row: '.$row.' '.getTxt('In').' '.$file['file_name'];
						addError($msg.getTxt('PleaseFix'));
						return false;
					}
					if(!in_array($meth,$methIDS))
					{
						$msg=getTxt('invalid').' '.getTxt('methodid').'.Row: '.$row.' '.getTxt('In').' '.$file['file_name'];
						addError($msg.getTxt('PleaseFix'));
						return false;
					}
				}
				
				$dataPoint = $this->createDP($dateVal,$timeVal,$value,$site,$var,$meth,$source);
				$dataset[]=$dataPoint;
				$row++;
			}
			
		}
		return $dataset;
	}
	
	public function importfile()
	{
		
		if($_POST)
		{
			$result = $this->fileUploadHandler();
			if($result)
			{
				$dataset = $this->processFiles($result);
				if($dataset)
				{
					$rows = count($dataset);
					$result=$this->datapoints->addPoints($dataset);
					if($result)
					{
						addSuccess(getTxt('Success'));	
						$this->updateSC();
					}
					else
					{
						addError(getTxt('ProcessingError')."Error in data input");
					}
				}
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
		
		
		$connection = mysqli_connect($this->config->item('database_host'), $this->config->item('database_username'), $this->config->item('database_password'),$this->config->item('database_name'))
		or die("<p>Error connecting to database: " . 
				   mysqli_error() . "</p>");
		mysqli_set_charset ($connection,"utf8");
		  //echo "<p>Connected to MySQL!</p>";
		  
		/*  $db = mysql_select_db($this->config->item('database_name'),$connection)
			or die("<p>Error selecting the database " . $this->config->item('database_name') .
			  mysql_error() . "</p>");
		*/
		require_once APPPATH.'../assets/update_series_catalog.php';
	}
}
