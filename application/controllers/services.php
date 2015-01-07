<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	public function index()
	{
		$data['title'] = "Test commit title";
		$this->load->view('services/index', $data);
	}
	

	

}
