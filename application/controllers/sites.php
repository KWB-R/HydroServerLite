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
		$this->dontAuth = array('map','details','displayAll','siteSearch','getSitesJSON','getSiteJSON');
		parent::__construct();
		$this->load->model('site','',TRUE);
		$this->load->model('sources','',TRUE);
		

	}
	public function index()
	{		
		$this->map();
	}
	public function map()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('map',$data);
	}
	
	public function details()
	{	
		$siteid = end($this->uri->segment_array());
		if($siteid=="details")
		{
			$data['errorMsg']="One of the parameters: SiteID is not defined. An example request would be details/1";
			$this->load->view('templates/apierror',$data);
			return;
		}
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$siteData = $this->site->getSite($siteid);
		$data['site']=$siteData[0];
		$data['SiteID']=$siteid;
		$this->load->model('variables','',TRUE);
		$result = $this->variables->getSite($siteid);
		$data['Variables']=$result;
		$this->load->view('details',$data);
	}
	
	private function createSite()
	{
		
		$Site = array
		(
			'SiteCode' => $this->input->post('SiteCode'),
			'SiteName' => $this->input->post('SiteName'),
			'Latitude' =>  $this->input->post('Latitude'),
			'Longitude' =>$this->input->post('Longitude'),
			'LatLongDatumID' =>$this->input->post('LatLongDatumID'),
			'SiteType' => $this->input->post('SiteType'),
			'Elevation_m' =>  $this->input->post('Elevation'),
			'VerticalDatum' =>$this->input->post('VerticalDatum'),
			'State' => $this->input->post('state'),
			'County' =>  $this->input->post('county'),
			'Comments' =>  $this->input->post('value')
		);	
		
		return $Site;
	}
	
	public function add()
	{	
	
		if($_POST)
		{
			$name = 'siteimg'.time();
			//Processing the SiteImage. 
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size']	= '1024';
			$config['max_width']  = '0';
			$config['max_height']  = '0';
			$config['file_name']  = $name;
			
			$this->load->library('upload', $config);
	
			if ( ! $this->upload->do_upload('picture'))
			{
				addError(getTxt('FailMoveFile').$this->upload->display_errors());
			}
			else
			{
				$uploaddata = $this->upload->data();
				$name = $uploaddata['file_name'];
				//Create the site.
				$site = $this->createSite();
				//Add the site.
				$result = $this->site->add($site); 
				if($result<=0)
				{
					addError(getTxt('ProcessingError')." Error while adding site. ");
				}	
				else
				{
					$siteID = $result;
					//Add image to sitepic table. 
					$this->site->addPic($name,$siteID);
					//Get the source
					$source = $this->sources->get($this->input->post('SourceID'));
					
					$series = array
					(
						'SiteID' => $siteID,
						'SiteCode' => $this->input->post('SiteCode'),
						'SiteName' =>  $this->input->post('SiteName'),
						'SiteType' => $this->input->post('SiteType'),
						'SourceID' =>  $this->input->post('SourceID'),
						'Organization' =>$source[0]['Organization'],
						'SourceDescription' => $source[0]['SourceDescription'],
						'Citation' =>  $source[0]['Citation'],
						'ValueCount' =>  0
					);	
					$this->load->model('sc','',TRUE);
					//Add to the series catalog
					$result=$this->sc->add($series);
					if($result)
					{
						addSuccess(getTxt('SiteAddedSuccessfully'));
					}	
					else
					{
						addError(getTxt('ProcessingError')." Error while adding Series. ");	
					}
				}
			}	
		}
		
		$sources = $this->sources->getAll();
		$sourceOptions = optionsSource($sources);
		
		$types = $this->site->getSiteTypes();
		$typesArray = array();
		foreach($types as $type)
		{
			$typesArray[$type['Term']]=$type['Term'];
		}
		$typeOptions = genOptions($typesArray);
		
		$vds = $this->site->getVD();
		$verticalDatumArray = array();
		foreach($vds as $vd)
		{
			$verticalDatumArray[$vd['Term']]=$vd['Term'];
		}
		$vdOptions = genOptions($verticalDatumArray);
		
		$srs = $this->site->getSR();
		$srArray = array();
		foreach($srs as $sr)
		{
			$srArray[$sr['SpatialReferenceID']]=$sr['SRSName'];
		}
		$srOptions = genOptions($srArray);
		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$data['sourceOptions']=$sourceOptions;
		$data['typeOptions']=$typeOptions;
		$data['vdOptions']=$vdOptions;
		$data['srOptions']=$srOptions;
		//Getting the states dropdown
		$states=getStates();
		$states['NULL']=getTxt('International');
		$stateOptions  = genOptions($states);
		$data['stateOptions']=$stateOptions;
		$this->load->view('sites/addsite',$data);
	}
	
	public function change()
	{		
	
	
		if($_POST)
		{
			//Try uploading the site image. If no new image is set, it should be an error. 	
			$name = 'siteimg'.time();
			//Processing the SiteImage. 
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size']	= '1024';
			$config['max_width']  = '0';
			$config['max_height']  = '0';
			$config['file_name']  = $name;
			
			$this->load->library('upload', $config);
			$name="";
			if ($this->upload->do_upload('picture'))
			{
				$uploaddata = $this->upload->data();
				$name = $uploaddata['file_name'];
			}
			//Create the site.
			$site = $this->createSite();
			$siteID = $this->input->post('SiteID');
			if($name!="") //Add image to sitepic table. 
			$this->site->addPic($name,$siteID);
			$result = $this->site->update($site,$siteID);
			if(!$result)
			{
				addError(getTxt('ProcessingError')." Error while adding sites. ");
			}
			else
			{
				//Update Series. 
				$series = array
					(
						'SiteCode' => $this->input->post('SiteCode'),
						'SiteName' =>  $this->input->post('SiteName'),
						'SiteType' => $this->input->post('SiteType'),
					);
					$this->load->model('sc','',TRUE);
					//Add to the series catalog
					$result=$this->sc->updateSite($series,$siteID);
					if($result)
					{
						addSuccess(getTxt('SiteSuccessfullyEdited'));
					}	
					else
					{
						addError(getTxt('ProcessingError')." Error while updating Series. ");	
					}
					
			}
		}
	
		$sources = $this->sources->getAll();
		$sourceOptions = optionsSource($sources);
		
		$types = $this->site->getSiteTypes();
		$typesArray = array();
		foreach($types as $type)
		{
			$typesArray[$type['Term']]=$type['Term'];
		}
		$typeOptions = genOptions($typesArray);
		
		$vds = $this->site->getVD();
		$verticalDatumArray = array();
		foreach($vds as $vd)
		{
			$verticalDatumArray[$vd['Term']]=$vd['Term'];
		}
		$vdOptions = genOptions($verticalDatumArray);
		
		$srs = $this->site->getSR();
		$srArray = array();
		foreach($srs as $sr)
		{
			$srArray[$sr['SpatialReferenceID']]=$sr['SRSName'];
		}
		$srOptions = genOptions($srArray);
		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$data['sourceOptions']=$sourceOptions;
		$data['typeOptions']=$typeOptions;
		$data['vdOptions']=$vdOptions;
		$data['srOptions']=$srOptions;
		//Getting the states dropdown
		$states=getStates();
		$states['NULL']=getTxt('International');
		$stateOptions  = genOptions($states);
		$data['stateOptions']=$stateOptions;
		
		$this->load->view('sites/editsite',$data);
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
	
	public function getSitesJSON()
	{
		if($this->input->get('source', TRUE))
		{
			$result = $this->site->getSitebySource($this->input->get('source', TRUE));
			echo json_encode($result);
		}
		else
		{
			$data['errorMsg']="One of the parameters: Source is not defined. An example request would be getSitesJSON?source=1";
			$this->load->view('templates/apierror',$data);	
		}
	}
	
	public function getSiteJSON()
	{
		if($this->input->get('siteid', TRUE))
		{
			$result = $this->site->getSite($this->input->get('siteid', TRUE));
			echo json_encode($result);
		}
		else
		{
			$data['errorMsg']="One of the parameters: Siteid is not defined. An example request would be getSiteJSON?siteid=1";
			$this->load->view('templates/apierror',$data);	
		}	
	}
	
	public function delete()
	{
		$siteid = end($this->uri->segment_array());
		if($siteid=="delete")
		{
			$data['errorMsg']="One of the parameters: siteid is not defined. An example request would be delete/1";
			$this->load->view('templates/apierror',$data);
			return;
		}
		$result = $this->site->delete($siteid);
		$this->load->model('sc','',TRUE);
		$this->sc->delSite($siteid);
		if($result)
			{	
				if($this->input->get('ui', TRUE))
				addSuccess(getTxt('SiteSuccessfullyDeleted'));	
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
