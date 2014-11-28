<?php
	//This file provides internationalization to the HydroServer Lite web application
	//Set the language code below, e.g. "en" for English, "es" for Spanish, etc.
	//dpa 4/1/2013
	
	//If running setup, the Language is loaded from the user's session variable
	
	if (!isset($_SESSION))
	{
		session_start();
	}
	
	if (isset($setup))
	{
		$lang=$_SESSION['setupLang'];
	}
	elseif (isset($_SESSION['lang']))
	{
		$lang=$_SESSION['lang'];
	}
	else
	{
	require("fetchMainConfig.php");
	}
	
	$lang_code = $lang;	
	
	//A check to see if the file is within 4 hours? and existing. 
	
	// Send a query to the server to get a view for that $lang_code
	
	//Build the file with all the vars making sure if var for that lang does not exist, english takes over
	
	//Include that file and end the script. However if any errors occur, continue the script. 
	
	if (isset($urlExtraName))
	{

	$lang_file = str_replace(".php", "_text.php", $urlExtraName);
	}
	
	else
	{
	$lang_file = str_replace(".php", "_text.php", basename($_SERVER["SCRIPT_FILENAME"]));
	}
	
	if (isset($urlExtra))
	{
	$urlAddon=$urlExtra;
	}
	else
	{
	$urlAddon="";
	}
	$page_text = $urlAddon."languages/" . $lang_code . "/" . $lang_file;
	$common_text = $urlAddon."languages/" . $lang_code . "/_common_text.php";
		
	//Check If files exist before opening

if (file_exists($page_text))
	{
	include($page_text);}
if (file_exists($common_text)){
	include_once($common_text);
}
?>
