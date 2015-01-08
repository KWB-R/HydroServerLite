<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends CI_Controller {
	
	function __construct()
	{
		//Loading Helpers
		parent::__construct();
		$this->load->helper('auth_helper.php');
		$this->load->helper('html_helper.php');
		$this->load->helper('language_helper.php');
		$lang=processLang();
		$this->lang->load('hsl', $lang);
	}
	
	
	public function index()
	{
		$this->load->view('services/index');
	}
	

	

}
