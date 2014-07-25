<?php
require_once('main_config.php');
//connect to server and select database
require_once("authorization_check.php");
require_once('data_access_layer.php');
require_once "_html_parts.php";
HTML_Render_Head();

echo $CSS_Main;

//echo $JS_JQuery;

//echo $JS_Maps;	

HTML_Render_Body_Start(); 

echo "<p>Use this page to verify application versions are high enough to run all required features..</p>";

echo "<p>You server configuration is:".
	"<ul class=\"messages\">";

$phpNewEnough = isVersionGreater(phpversion(),$_SITE_Minimum_PHP_Version);
echo "<li class=\"".($phpNewEnough?"success":"error").
	"\">PHP: (".phpversion().")<span class=\"notice\">".($phpNewEnough?"Passed":"Failed; PHP(".$_SITE_Minimum_PHP_Version.") is required")."</span></li>";
	
if ($dbConn = DAL::Get()->_DatabaseObject())
{
	$mySQLVersion = $dbConn->getAttribute(constant("PDO::ATTR_SERVER_VERSION"));
	$pdoEnabled = in_array("mysql",PDO::getAvailableDrivers());	
	$mySQLNewEnough = isVersionGreater($mySQLVersion,$_SITE_Minimum_SQL_Version);
	echo "<li class=\"".	($mySQLNewEnough?"success":"error").
		"\">MySQL: (".$mySQLVersion.")<span class=\"notice\">".($mySQLNewEnough?"Passed":"Failed; MySQL(".$_SITE_Minimum_SQL_Version.") is required")."</span></li>".
		"<li class=\"".	($pdoEnabled?"success":"error").
		"\">MySQL PDO Client: (".$dbConn->getAttribute(constant("PDO::ATTR_CLIENT_VERSION")).")<span class=\"notice\">".
		($pdoEnabled?"Passed":"Failed; PDO is not enabled")."</span></li>";
}else{
	echo "<li class='error'>Error getting MySQL</li>";
}

	
	echo "</ul>";
HTML_Render_Body_End(); ?>
