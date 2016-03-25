<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

//This controller is responsible for setting up the assets for each page. 
//Possibly this page will also take care of the mainConfig file and translation stuff

	function __construct()
	{
		parent::__construct();

		//Loading Helpers
		
		$this->load->helper('auth_helper.php');
		$this->load->helper('html_helper.php');
		$this->load->helper('language_helper.php');
		
		//From the language helper we shall get the right language we need to show and then load the language file
		
		$this->lang->load('hsl', processLang());

		$this->authenticate();

		// Initialize Library and all Types of CSS and JS files. 
		// They will be merged according to the requirement.
		$this->load->library('assetor');
		
		// To add a new stylesheet to the mix, put it in the right category that is
		// being loaded and the code should take on from there.
		$loadConfig = array(
			'CSS_Main' => array(
				'bootstrap/bootstrap.min.css',
				'default/main_css.css'
			),
			'CSS_JQuery_UI' => array(
				'jqstyles/jquery.ui.all.css',
				'jqstyles/jquery.ui.timepicker.css',
			),
			'CSS_JQStyles' => array(
				'jqstyles/demos.css',
			),
			'CSS_JQX' => array(
				'jqwstyles/jqx.base.css',
				'jqwstyles/jqx.darkblue.css',
				'jqwstyles/jqx.classic.css',
			),
			//Same stuff for Javascript files.
			'JS_JQuery' => array(
				'jquery.js',
				'common.js',
				'bootstrap.min.js'
			),
			'JS_Forms' => array(
				'forms.js'
			),
			'JS_JQueryUI' => array(
				'ui/jquery.ui.core.js',
				'ui/jquery.ui.widget.js',
				'ui/jquery.ui.datepicker.js',
				'ui/jquery.ui.timepicker.js'
			),
			'JS_FormValidation' => array(
				'datevalidation.js',
				'timevalidation.js',
				'numbervalidation.js'
			),
			'JS_JQX' => array(
				'jqwidgets/jqxcore.js',
				'jqwidgets/jqxdata.js',
				'jqwidgets/jqxbuttons.js',
				'jqwidgets/jqxscrollbar.js',
				'jqwidgets/jqxlistbox.js',
				'jqwidgets/jqxcombobox.js', //add variable
				'jqwidgets/jqxdropdownbutton.js', //details
				'jqwidgets/jqxdropdownlist.js', //add variable | details | add_multiple_values
				'jqwidgets/jqxwindow.js', //change metadata | change method | change source | details
				'jqwidgets/jqxpanel.js', //change metadata | change method | change source | details 
				'jqwidgets/jqxtabs.js', //change metadata | change method | change source
				'jqwidgets/jqxcheckbox.js', //change metadata | change method | change source | details
				'jqwidgets/jqxdatetimeinput.js', //details
				'jqwidgets/jqxcalendar.js', //details
				'jqwidgets/jqxtooltip.js', //details
				'jqwidgets/jqxmenu.js', //details
				'jqwidgets/jqxgrid.js', //details
				'jqwidgets/jqxgrid.selection.js', //details
				'jqwidgets/jqxgrid.columnsresize.js', //details
				'jqwidgets/jqxgrid.pager.js', //details
				'jqwidgets/jqxgrid.sort.js', //details
				'jqwidgets/jqxgrid.edit.js', //details
				'jqwidgets/jqxexpander.js' //details
			),
			'JS_GetTheme' => array(
				'gettheme.js'
			),
			'JS_SiteCreate' => array(
				'create_site_code.js'
			),
			'JS_Globalization' => array(
				'jqwidgets/globalization/jquery.global.js',
				'highstock.js',
				'modules/exporting.js'
			),
			'JS_Maps' => array(
				'googleMaps.js',
				'markerclusterer.js',
				'map.js'
			),
			'JS_CreateUserName' => array(
				'create_username.js'
			),
			'JS_ImportData' => array(
				'import_data.js'
			),
			'JS_DropDown' => array(
				'drop_down.js'
			)
		);

		foreach ($loadConfig as $groupName => $files) {
			foreach ($files as $file) {
				$this->assetor->load($file, $groupName);
			}
		}
		
		//$this->assetor->merge(TRUE); Merging is causing issues with google maps. 
		//$this->assetor->minify();
	
		foreach (array_keys($loadConfig) as $groupName) {
			$this->StyleData[$groupName] = $this->assetor->generate($groupName);
		}

		//Initializing Javascript Variable for ajax requests. 
		
		$this->StyleData['js_vars'] = 'var asset_url= "'.base_url().'assets/";
		base_url = "'.site_url().'";'; 
		
		header('Content-Type: text/html; charset=utf-8');
	}
	
	function kickOut()
	{
		//Send user to home page for inadequete permissions
		addError(getTxt('NotAuthorized'));
		redirect('/home', 'refresh');
	}
	
	protected function authenticate()
	{
		$segments = $this->uri->segment_array();

		$ok = isset($this->dontAuth) &&	(
			($this->dontAuth === '*') ||
			($this->any_in_array($this->dontAuth, $segments))
		);

		if (! $ok && ! isLoggedIn()) {
			$this->kickOut();
		}
	}

	private function any_in_array($array1, $array2) 
	{
		foreach ($array1 as $element) {
			if (in_array($element, $array2)) {
				return true;
			}
		}

		return false;
	}

	protected function loadModel($modelName)
	{
		$this->load->model($modelName, '', TRUE);
	}

	protected function getXssCleanInput($name)
	{
		return $this->input->get($name, TRUE);
	}

	protected function getConfigItem($name, $default = FALSE)
	{
		$value = $this->config->item($name);

		// return the default value if the item is not in the config file
		if ($value === FALSE) {
			$value = $default;
		}
		elseif(strtolower($value) == "null") {
			$value = NULL;
		}

		return $value;
	}

	protected function fileUploadHandler($allowed_types, $field)
	{
		$newDir = $this->createTempUploadDir();

		if (! $newDir) {
			addError(getTxt('FailTemp'));
			return false;
		}

		// Upload files
		$config['upload_path'] = $newDir;
		$config['allowed_types'] = $allowed_types;

		$this->load->library('upload', $config);

		if (! $this->upload->do_multi_upload($field)) {
		  addError(getTxt('FailMoveFile').$this->upload->display_errors());
		  return false;
	  }

		return $this->upload->get_multi_upload_data();
	}

	protected function createTempUploadDir()
	{
		$newDir = "./uploads/temp".time().rand();

		$oldmask = umask(0);
		$result = mkdir($newDir,0777);
		umask($oldmask);

		// Return the path of the directory created or '' in case of an error
		return (($result)? $newDir : '');
	}
}
