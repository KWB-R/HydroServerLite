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
		$lang="English";
		$this->lang->load('hsl', $lang);
		
		$this->authenticate();
		
		//Initialize Library and all Types of CSS and JS files. They will be merged according to the requirement.
		$this->load->library('assetor');
		
		//To add a new stylesheet to the mix, put it in the right category that is being loaded and the code should take on from there. 
		
		$this->assetor->load('bootstrap/bootstrap.min.css','CSS_Main');
		$this->assetor->load('default/main_css.css','CSS_Main');
	
		$this->assetor->load('jqstyles/jquery.ui.all.css','CSS_JQuery_UI');
		$this->assetor->load('jqstyles/jquery.ui.timepicker.css','CSS_JQuery_UI');
		
		$this->assetor->load('jqstyles/demos.css','CSS_JQStyles');
		
		$this->assetor->load('jqwstyles/jqx.base.css','CSS_JQX');
		$this->assetor->load('jqwstyles/jqx.darkblue.css','CSS_JQX');
		$this->assetor->load('jqwstyles/jqx.classic.css','CSS_JQX');
		
		//Same stuff for Javascript files. 
		
		$this->assetor->load('jquery.js','JS_JQuery');
		$this->assetor->load('common.js','JS_JQuery');
		$this->assetor->load('bootstrap.min.js','JS_JQuery');
		
		$this->assetor->load('forms.js','JS_Forms');
		
		$this->assetor->load('ui/jquery.ui.core.js','JS_JQueryUI');
		$this->assetor->load('ui/jquery.ui.widget.js','JS_JQueryUI');
		$this->assetor->load('ui/jquery.ui.datepicker.js','JS_JQueryUI');
		$this->assetor->load('ui/jquery.ui.timepicker.js','JS_JQueryUI');
		
		$this->assetor->load('datevalidation.js','JS_FormValidation');
		$this->assetor->load('timevalidation.js','JS_FormValidation');
		$this->assetor->load('numbervalidation.js','JS_FormValidation');
		
		$this->assetor->load('jqwidgets/jqxcore.js','JS_JQX');
		$this->assetor->load('jqwidgets/jqxdata.js','JS_JQX');
		$this->assetor->load('jqwidgets/jqxbuttons.js','JS_JQX');
		$this->assetor->load('jqwidgets/jqxscrollbar.js','JS_JQX');
		$this->assetor->load('jqwidgets/jqxlistbox.js','JS_JQX');
		$this->assetor->load('jqwidgets/jqxcombobox.js','JS_JQX'); //add variable 
		$this->assetor->load('jqwidgets/jqxdropdownbutton.js','JS_JQX'); //details 
		$this->assetor->load('jqwidgets/jqxdropdownlist.js','JS_JQX');  //add variable | details | add_multiple_values 
		$this->assetor->load('jqwidgets/jqxwindow.js','JS_JQX'); //change metadata | change method | change source | details 
		$this->assetor->load('jqwidgets/jqxpanel.js','JS_JQX'); //change metadata | change method | change source | details 
		$this->assetor->load('jqwidgets/jqxtabs.js','JS_JQX'); //change metadata | change method | change source 
		$this->assetor->load('jqwidgets/jqxcheckbox.js','JS_JQX'); //change metadata | change method | change source | details 
		$this->assetor->load('jqwidgets/jqxdatetimeinput.js','JS_JQX'); //details 
		$this->assetor->load('jqwidgets/jqxcalendar.js','JS_JQX'); //details 
		$this->assetor->load('jqwidgets/jqxtooltip.js','JS_JQX'); //details 
		$this->assetor->load('jqwidgets/jqxmenu.js','JS_JQX'); //details 
		$this->assetor->load('jqwidgets/jqxgrid.js','JS_JQX'); //details 
		$this->assetor->load('jqwidgets/jqxgrid.selection.js','JS_JQX'); //details 
		$this->assetor->load('jqwidgets/jqxgrid.columnsresize.js','JS_JQX');  //details 
		$this->assetor->load('jqwidgets/jqxgrid.pager.js','JS_JQX'); //details 
		$this->assetor->load('jqwidgets/jqxgrid.sort.js','JS_JQX'); //details 
		$this->assetor->load('jqwidgets/jqxgrid.edit.js','JS_JQX'); //details 
		$this->assetor->load('jqwidgets/jqxexpander.js','JS_JQX'); //details	
			
		$this->assetor->load('gettheme.js','JS_GetTheme');
		
		$this->assetor->load('create_site_code.js','JS_SiteCreate');
			
		$this->assetor->load('jqwidgets/globalization/jquery.global.js','JS_Globalization');
		$this->assetor->load('highstock.js','JS_Globalization'); 
		$this->assetor->load('modules/exporting.js','JS_Globalization');
	
		$this->assetor->load('googleMaps.js','JS_Maps'); 
		$this->assetor->load('markerclusterer.js','JS_Maps');
		$this->assetor->load('map.js','JS_Maps');

		$this->assetor->load('create_username.js','JS_CreateUserName');

		$this->assetor->load('uploader/plupload.js','JS_Uploaders');
		$this->assetor->load('uploader/plupload.gears.js','JS_Uploaders');
		$this->assetor->load('uploader/plupload.silverlight.js','JS_Uploaders');
		$this->assetor->load('uploader/plupload.flash.js','JS_Uploaders');
		$this->assetor->load('uploader/plupload.browserplus.js','JS_Uploaders');
		$this->assetor->load('uploader/plupload.html4.js','JS_Uploaders');
		$this->assetor->load('uploader/plupload.html5.js','JS_Uploaders');
			
		$this->assetor->load('import_data.js','JS_ImportData');
			
		$this->assetor->load('drop_down.js','JS_DropDown');
	
		
		//$this->assetor->merge(TRUE); TEMP DISABLING FOR DEVELOPMENT
		//$this->assetor->minify();
	
		$this->StyleData['CSS_Main'] = $this->assetor->generate('CSS_Main');
		$this->StyleData['CSS_JQuery_UI'] = $this->assetor->generate('CSS_JQuery_UI');
		$this->StyleData['CSS_JQStyles'] = $this->assetor->generate('CSS_JQStyles');
		$this->StyleData['CSS_JQX'] = $this->assetor->generate('CSS_JQX');
		
		$this->StyleData['JS_JQuery'] = $this->assetor->generate('JS_JQuery');
		$this->StyleData['JS_Forms'] = $this->assetor->generate('JS_Forms');
		$this->StyleData['JS_JQueryUI'] = $this->assetor->generate('JS_JQueryUI');
		$this->StyleData['JS_FormValidation'] = $this->assetor->generate('JS_FormValidation');
		$this->StyleData['JS_JQX'] = $this->assetor->generate('JS_JQX');
		$this->StyleData['JS_GetTheme'] = $this->assetor->generate('JS_GetTheme');
		$this->StyleData['JS_SiteCreate'] = $this->assetor->generate('JS_SiteCreate');
		$this->StyleData['JS_Globalization'] = $this->assetor->generate('JS_Globalization');
		$this->StyleData['JS_Maps'] = $this->assetor->generate('JS_Maps');
		$this->StyleData['JS_CreateUserName'] = $this->assetor->generate('JS_CreateUserName');
		$this->StyleData['JS_Uploaders'] = $this->assetor->generate('JS_Uploaders');
		$this->StyleData['JS_ImportData'] = $this->assetor->generate('JS_ImportData');
		$this->StyleData['JS_DropDown'] = $this->assetor->generate('JS_DropDown');
		
		//Initializing Javascript Variable for ajax requests. 
		
		$this->StyleData['js_vars'] = 'var base_url = "'.base_url().'index.php/";'; 
	}
	
	function kickOut()
	{
		//Send user to home page for inadequete permissions
		addError(getTxt('NotAuthorized'));
		redirect('/home', 'refresh');
	}
	
	protected function authenticate()
	{
		if(!isLoggedIn())
		{
			$this->kickOut();	
		}
	}
}
