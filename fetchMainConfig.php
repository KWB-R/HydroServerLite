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

//unset($_SESSION['mainpath']);

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