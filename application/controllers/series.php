<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| SeriesCatalog Controller
|--------------------------------------------------------------------------
|
| 
*/
class Series extends MY_Controller {
	
	function __construct()
	{
		$this->dontAuth = array('getDateJSON');
		parent::__construct();
		$this->load->model('sc','',TRUE);
		$this->load->library('form_validation');
	}
	
	public function index()
	{	
		$data=$this->StyleData;
		$this->load->view('series/edit',$data);	
	}
	
	public function update()
	{
		$updatedSeries=array();
		$seriesID = $this->input->post('SeriesID');
		$actualSeries = $this->sc->get($seriesID);
		$actualSeries=$actualSeries[0];
		//Check if any editable fields have been changed. 
		if($actualSeries['SiteID']!=$this->input->post('SiteID'))
		{
			$this->load->model('site','',TRUE);
			$site = $this->site->getSite($this->input->post('SiteID'));
			if(count($site)>0)
			{
				$updatedSeries['SiteID']=$site[0]['SiteID'];
				$updatedSeries['SiteCode']=$site[0]['SiteCode'];
				$updatedSeries['SiteType']=$site[0]['SiteType'];
				$updatedSeries['SiteName']=$site[0]['SiteName'];
			}
			else
			{
				$output = array("status"=>"failed","reason"=>getTxt('invalid').' '.getTxt('siteid'));
				echo json_encode($output);	
				return;
			}
		}
		
		if($actualSeries['VariableID']!=$this->input->post('VariableID'))
		{
			$this->load->model('variables','',TRUE);
			$site = $this->variables->getVariableWithUnit($this->input->post('VariableID'));
			if(count($site)>0)
			{
				$unitname = $this->variables->getUnitName($site[0]['VariableunitsID']);
				$timeunitname = $this->variables->getUnitName($site[0]['TimeunitsID']);
				$updatedSeries['VariableID']=$site[0]['VariableID'];
				$updatedSeries['VariableCode']=$site[0]['VariableCode'];
				$updatedSeries['VariableName']=$site[0]['VariableName'];
				$updatedSeries['Speciation']=$site[0]['Speciation'];
				$updatedSeries['VariableunitsID']=$site[0]['VariableunitsID'];
				$updatedSeries['VariableunitsName']=$unitname[0]['unitsName'];
				$updatedSeries['SampleMedium']=$site[0]['SampleMedium'];
				$updatedSeries['ValueType']=$site[0]['ValueType'];
				$updatedSeries['TimeSupport']=$site[0]['TimeSupport'];
				$updatedSeries['TimeunitsID']=$site[0]['TimeunitsID'];
				$updatedSeries['TimeunitsName']=$timeunitname[0]['unitsName'];
				$updatedSeries['DataType']=$site[0]['DataType'];
				$updatedSeries['GeneralCategory']=$site[0]['GeneralCategory'];
			}
			else
			{
				$output = array("status"=>"failed","reason"=>getTxt('invalid').' '.getTxt('varid'));
				echo json_encode($output);	
				return;
			}
		}
		
		if($actualSeries['MethodID']!=$this->input->post('MethodID'))
		{
			$this->load->model('method','',TRUE);
			$site = $this->method->getByID($this->input->post('MethodID'));
			if(count($site)>0)
			{
				$updatedSeries['MethodID']=$site[0]['MethodID'];
				$updatedSeries['MethodDescription']=$site[0]['MethodDescription'];
			}
			else
			{
				$output = array("status"=>"failed","reason"=>getTxt('invalid').' '.getTxt('methodid'));
				echo json_encode($output);	
				return;
			}
		}
		
		if($actualSeries['SourceID']!=$this->input->post('SourceID'))
		{
			$this->load->model('sources','',TRUE);
			$site = $this->sources->get($this->input->post('SourceID'));
			if(count($site)>0)
			{
				$updatedSeries['SourceID']=$site[0]['SourceID'];
				$updatedSeries['Organization']=$site[0]['Organization'];
				$updatedSeries['SourceDescription']=$site[0]['SourceDescription'];
			}
			else
			{
				$output = array("status"=>"failed","reason"=>getTxt('invalid').' '.getTxt('sourceid'));
				echo json_encode($output);	
				return;
			}
		}
		
		if($actualSeries['ValueCount']!=$this->input->post('ValueCount'))
		{
			$updatedSeries['ValueCount']=$this->input->post('ValueCount');
		}
		
		$result = $this->sc->update($updatedSeries,$seriesID);
		if($result)
		{
			addSuccess("Series Updated");
			$output = array("status"=>"success");		
		}
		else
		{
			$output = array("status"=>"failed","reason"=>getTxt('ProcessingError'));
		}
		echo json_encode($output);
	}
	
	public function getJSON()
	{
		$result = $this->sc->getAll();
		$finalresult=array();
		foreach($result as $row)
		{
			if($row['VariableID']!=null)
			$finalresult[]=$row;
		}
		echo json_encode($finalresult);
	}
	
	public function getDateJSON()
	{
		$var = $this->input->get('varid', TRUE);
		$site = $this->input->get('siteid', TRUE);	
		$method = $this->input->get('methodid', TRUE);
		if($var!==false&&$site!==false&&$method!==false)
		{
			$result = $this->sc->getDateRange($site,$var,$method);
			echo json_encode($result[0]);
		}
		else
		{
			$data['errorMsg']="One of the parameters: VariableID, SiteID,MethodID is not defined. An example request would be getDateJSON?varid=1&&siteid=2&&methodid=1";
			$this->load->view('templates/apierror',$data);	
		}
	}
	
	public function updateSC()
	{
		$connection = mysqli_connect($this->config->item('database_host'), $this->config->item('database_username'), $this->config->item('database_password'),$this->config->item('database_name'))
		or die("<p>Error connecting to database: " . 
				   mysqli_error() . "</p>");
		mysqli_set_charset ($connection,"utf8");
		require_once APPPATH.'../assets/update_series_catalog.php';
		addSuccess("Series Catalog Updated");
	}
	
}
