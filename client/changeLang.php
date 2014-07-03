<?php

if (!isset($_SESSION))
{
	session_start();	
}


$lang = "en";

if ($_POST["lang"]):
	switch ($_POST["lang"]):
		case "English":$lang="en";break;
		case "Spanish":$lang="es";break;
	endswitch;
endif;

$_SESSION['lang']=$lang;

echo ("langChanged");

?>