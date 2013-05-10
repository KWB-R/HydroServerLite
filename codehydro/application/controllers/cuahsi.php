<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cuahsi extends CI_Controller {

	/**
	 * Implement cuahsi service
	 *
	 */
	public function GetVariablesObject ()
	{
		$this->load->helper("hydroservice");
		GetVariablesObject();
  		exit;
	}
}