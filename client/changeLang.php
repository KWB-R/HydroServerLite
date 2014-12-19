<?php

if (!isset($_SESSION))
{
	session_start();	
}


$lang = "English";

if(isset($_POST['lang']))
{
	$lang=$_POST['lang'];	
}

$_SESSION['lang']=$lang;

echo ("langChanged");

?>