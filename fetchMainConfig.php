<?php
if (!isset($_SESSION))
{
session_start();
}

$urlAdd="";

if (isset ($urlExtra))
{
$urlAdd=$urlExtra;
}

//Code Editing Begins - Major edit to add both single and enterprise support - Edit made by Rohit Khattar - Date 11/4/13

//Check if main_config file exists. If it doesn't exist its implied that either the setup has not been run or this is an enterprise version. 

if (file_exists($urlAdd."main_config.php"))
{
require_once($urlAdd."main_config.php");
if ($singleInstall == "Yes")
	{
		//Clear Session and let the script do its work.
		if (isset($_SESSION['mainpath']))
		{
		unset($_SESSION['mainpath']);
	}}

}

//Editing Ends	
if (isset($_SESSION['mainpath']))
{

//Check if the file exists, if not,clear session variables and proceed to get the static file
$str = str_replace('\\', '/', $_SESSION['mainpath']);
if (file_exists($str))
{
require_once($urlAdd.$str);

}
else
{

unset($_SESSION['mainpath']);
if (file_exists($urlAdd."main_config.php"))
{
require_once($urlAdd."main_config.php");
}
else
{
header ("Location: setup/index.php");
}
}
}
else
{

if (file_exists($urlAdd."main_config.php"))
{
require_once($urlAdd."main_config.php");
}
else
{
header ("Location: setup/index.php");
}
}
?>