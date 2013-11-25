<?php
	//This file provides internationalization to the HydroServer Lite web application
	//Set the language code below, e.g. "en" for English, "es" for Spanish, etc.
	//dpa 4/1/2013
	if(isset($setup))
	{
	$lang_code="en";
	}
	else
	{
	require_once("fetchMainConfig.php");
	$lang_code = $lang;	
	}
	
	
	$lang_file = str_replace(".php", "_text.php", basename($_SERVER["SCRIPT_NAME"]));
	$page_text = "../languages/" . $lang_code . "/" . $lang_file;
	$common_text = "../languages/" . $lang_code . "/_common_text.php";
	
	include($page_text);
	include_once($common_text);
?>
