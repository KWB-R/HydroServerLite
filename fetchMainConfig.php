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

if (file_exists($_SESSION['mainpath']))
{
require_once($urlAdd.$_SESSION['mainpath']);
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
echo file_exists($urlAdd."main_config.php");
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