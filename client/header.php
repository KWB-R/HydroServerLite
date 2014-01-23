<?php 

//This is required to get the international text strings dictionary

	$urlExtraName="header.php";
	require 'internationalize.php';

	require_once 'fetchMainConfig.php';
	//echo("<font color=#FFFFFF face=Arial, Helvetica, sans-serif size=2><i><a href='".$homelink."' class='button2'>Back to the ".$homename."</a></i></font>");
	echo("<font color=#FFFFFF face=Arial, Helvetica, sans-serif size=2><i><a href='".$homelink."' class='button2'> $BackTo ".$homename."</a></i></font>");
?>


