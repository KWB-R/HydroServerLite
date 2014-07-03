<?php
	//This file provides internationalization to the HydroServer Lite web application
	//Set the language code below, e.g. "en" for English, "es" for Spanish, etc.
	//dpa 4/1/2013
	
	//If running setup, the Language is loaded from the user's session variable
	
	if (isset($setup))
	{
		if (!isset($_SESSION))
		{
			session_start();
		}
		$lang=$_SESSION['setupLang'];
	}
	else
	{
	require("fetchMainConfig.php");
	}
	$lang_code = $lang;	
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
{	include($page_text);}
if (file_exists($common_text)){
	include_once($common_text);
}
?>
