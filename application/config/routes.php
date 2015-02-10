<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "default/home";

// Test Page routing
$route["(:any)/services/test"] 										= "$1/cuahsi/test";

// REST routing
$route["(:any)/services/cuahsi_1_1.asmx"] 								= "$1/cuahsi";
$route["(:any)/services/cuahsi_1_1.asmx/GetSites"] 					= "$1/cuahsi/GetSites";
$route["(:any)/services/cuahsi_1_1.asmx/GetSiteInfo"] 					= "$1/cuahsi/GetSiteInfo";
$route["(:any)/services/cuahsi_1_1.asmx/GetSiteInfoMultpleObject"] 	= "$1/cuahsi/GetSiteInfoMultpleObject";
$route["(:any)/services/cuahsi_1_1.asmx/GetSiteInfoObject"] 			= "$1/cuahsi/GetSiteInfoObject";
$route["(:any)/services/cuahsi_1_1.asmx/GetSitesObject"] 				= "$1/cuahsi/GetSitesObject";
$route["(:any)/services/cuahsi_1_1.asmx/GetSitesByBoxObject"] 			= "$1/cuahsi/GetSitesByBoxObject";
$route["(:any)/services/cuahsi_1_1.asmx/GetValues"] 					= "$1/cuahsi/GetValues";
$route["(:any)/services/cuahsi_1_1.asmx/GetValuesObject"] 				= "$1/cuahsi/GetValuesObject";
$route["(:any)/services/cuahsi_1_1.asmx/GetValuesForASiteObject"] 		= "$1/cuahsi/GetValuesForASiteObject";
$route["(:any)/services/cuahsi_1_1.asmx/GetVariables"] 				= "$1/cuahsi/GetVariables";
$route["(:any)/services/cuahsi_1_1.asmx/GetVariablesObject"] 			= "$1/cuahsi/GetVariablesObject";
$route["(:any)/services/cuahsi_1_1.asmx/GetVariableInfo"] 				= "$1/cuahsi/GetVariableInfo";
$route["(:any)/services/cuahsi_1_1.asmx/GetVariableInfoObject"] 		= "$1/cuahsi/GetVariableInfoObject";

// Update CV Page routing
$route["(:any)/services/updatecv"] 								= "$1/updatecv";

$route["(:any)/services/api"] 								= "$1/api";
$route["(:any)/services/api/values"] 								= "$1/api/values";
$route["(:any)/services/api/sources"] 								= "$1/api/sources";
$route["(:any)/services/api/sites"] 								= "$1/api/sites";
$route["(:any)/services/api/variables"] 								= "$1/api/variables";
$route["(:any)/services/api/methods"] 								= "$1/api/methods";
$route["(:any)/services/api/GetSitesJSON"] 								= "$1/api/GetSitesJSON";
$route["(:any)/services/api/GetVariablesJSON"] 								= "$1/api/GetVariablesJSON";
$route["(:any)/services/api/GetMethodsJSON"] 								= "$1/api/GetMethodsJSON";
$route["(:any)/services/api/GetSourcesJSON"] 								= "$1/api/GetSourcesJSON";

$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */