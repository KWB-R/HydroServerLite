<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Variables Controller
|--------------------------------------------------------------------------
|
| 
*/
class Variable extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('variables','',TRUE);
	}
	
	public function addvariable()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('variables/addvar',$data);
	}
	
	public function editvariable()
	{		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('variables/editvar',$data);
	}
	
	public function getAllJSON()
	{
		$variables = $this->variables->getAll();
		echo json_encode($variables);
	}
	
	public function getSiteJSON()
	{
		if($this->input->get('siteid', TRUE))
		{
			$result = $this->variables->getSite($this->input->get('siteid', TRUE));
			echo json_encode($result);
		}
		else
		{
			$data['errorMsg']="One of the parameters: Siteid is not defined. An example request would be getSiteJSON?siteid=1";
			$this->load->view('templates/apierror',$data);	
		}	
	}
	
	public function getTypes()
	{
		if($this->input->get('siteid', TRUE)&&$this->input->get('varname', TRUE))
		{
			$result = $this->variables->getTypes($this->input->get('siteid', TRUE),$this->input->get('varname', TRUE));
			echo json_encode($result);
		}
		else
		{
			$data['errorMsg']="One of the parameters: Siteid,Variable Name is not defined. An example request would be getTypes?siteid=1&&varname=abc";
			$this->load->view('templates/apierror',$data);	
		}		
	}
	
	public function updateVarID()
	{
			$result = $this->variables->getVarID($this->input->get('siteid', TRUE),$this->input->get('varname', TRUE),$this->input->get('type', TRUE));
			echo $result[0]['VariableID'];
	}
	
		
	public function getUnit()
	{
		$var = $this->input->get('varid', TRUE);
		if($var!==false)
		{
			$result = $this->variables->getUnit($var);
			echo json_encode($result);
		}
		else
		{
			$data['errorMsg']="One of the parameters: VariableID is not defined. An example request would be getUnit?varid=1";
			$this->load->view('templates/apierror',$data);	
		}
	}
	
	public function getWithUnit()
	{
		$var = $this->input->get('varid', TRUE);
		if($var!==false)
		{
			$result = $this->variables->getVariableWithUnit($var);
			echo json_encode($result);
		}
		else
		{
			$data['errorMsg']="One of the parameters: VariableID is not defined. An example request would be getWithUnit?varid=1";
			$this->load->view('templates/apierror',$data);	
		}
	}
}
