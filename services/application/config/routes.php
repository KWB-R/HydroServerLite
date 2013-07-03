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

$route['default_controller'] = "welcome";

// REST routing
$route["services/cuahsi_1_1.asmx"] 							= "services/cuahsi";
$route["services/cuahsi_1_1.asmx/GetSites"] 				= "services/cuahsi/GetSites";
$route["services/cuahsi_1_1.asmx/GetSiteInfo"] 				= "services/cuahsi/GetSiteInfo";
$route["services/cuahsi_1_1.asmx/GetSiteInfoMultpleObject"] = "services/cuahsi/GetSiteInfoMultpleObject";
$route["services/cuahsi_1_1.asmx/GetSiteInfoObject"] 		= "services/cuahsi/GetSiteInfoObject";
$route["services/cuahsi_1_1.asmx/GetSitesObject"] 			= "services/cuahsi/GetSitesObject";
$route["services/cuahsi_1_1.asmx/GetSitesByBoxObject"] 		= "services/cuahsi/GetSitesByBoxObject";
$route["services/cuahsi_1_1.asmx/GetValues"] 				= "services/cuahsi/GetValues";
$route["services/cuahsi_1_1.asmx/GetValuesObject"] 			= "services/cuahsi/GetValuesObject";
$route["services/cuahsi_1_1.asmx/GetValuesForASiteObject"] 	= "services/cuahsi/GetValuesForASiteObject";
$route["services/cuahsi_1_1.asmx/GetVariables"] 			= "services/cuahsi/GetVariables";
$route["services/cuahsi_1_1.asmx/GetVariablesObject"] 		= "services/cuahsi/GetVariablesObject";
$route["services/cuahsi_1_1.asmx/GetVariableInfo"] 			= "services/cuahsi/GetVariableInfo";
$route["services/cuahsi_1_1.asmx/GetVariableInfoObject"] 	= "services/cuahsi/GetVariableInfoObject";
// end of REST routing

$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */