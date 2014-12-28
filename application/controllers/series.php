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
	
}
