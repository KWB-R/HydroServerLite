<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

//This controller is responsible for setting up the assets for each page. 
//Possibly this page will also take care of the mainConfig file and translation stuff

	function __construct()
	{
		parent::__construct();
		
		//Initialize Library
		$this->load->library('assetor');
		
		$this->assetor->load('bootstrap/bootstrap.min.css','CSS_Main');
		$this->assetor->load('default/main_css.css','CSS_Main');
		
		$this->assetor->minify();
		
		$CSS_Main = $this->assetor->generate('CSS_Main');
		
		/*
		$CSS_Main =  <<<CSSMain
		<!-- Bootstrap -->
		<link href="styles/bootstrap/bootstrap.min.css" rel="stylesheet">
		<link href="styles/default/main_css.css" rel="stylesheet" type="text/css" media="screen" />
		
		
CSSMain;

$CSS_JQuery_UI =  <<<CSSJQueryUI
			<link rel="stylesheet" href="styles/jqstyles/jquery.ui.all.css" />
			<link rel="stylesheet" href="styles/jqstyles/jquery.ui.timepicker.css" />
			
CSSJQueryUI;


$CSS_JQStyles =  <<<CSSJQStyles
			<link rel="stylesheet" href="styles/jqstyles/demos.css" />
			
CSSJQStyles;

$CSS_JQX =  <<<JQXStyles
			<link rel="stylesheet" href="js/jqwidgets/styles/jqx.base.css" type="text/css" />
			<link rel="stylesheet" href="js/jqwidgets/styles/jqx.darkblue.css" type="text/css" />
			<link rel="stylesheet" href="js/jqwidgets/styles/jqx.classic.css" type="text/css" />
			
JQXStyles;
		*/
		
	}
}
