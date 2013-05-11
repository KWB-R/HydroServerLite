<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cuahsi extends CI_Controller {

	/**
	 * Implement cuahsi service GetVariablesObject
	 *
	 */
	public function GetVariablesObject()
	{
		$this->load->helper("hydroservice");
		GetVariablesObject();
  		exit;
	}

	/**
	 * Implement cuahsi service GetSites
	 *
	 */
	public function GetSites()
	{
		$this->load->helper("hydroservice");
		GetSites();
  		exit;
	}
}