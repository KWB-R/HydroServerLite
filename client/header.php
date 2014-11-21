<?php 

//This is required to get the international text strings dictionary
	global $_SITE_homename;
	global $_SITE_homelink;
	$urlExtraName="header.php";
	require 'internationalize.php';
	require_once 'fetchMainConfig.php';
	
	echo("<font color=#FFFFFF face=Arial, Helvetica, sans-serif size=2>Language : <i><div class='button' style='cursor: pointer;' id='langChange'>English</div></i></font>
<font color=#FFFFFF face=Arial, Helvetica, sans-serif size=2><i><div class='button' style='cursor: pointer;' id='langChange'>Spanish</div></i></font>
");
	//commented out by Jeremy Fowler (Nov. 20, 2014)
	//echo("<font color=#FFFFFF face=Arial, Helvetica, sans-serif size=2><i><a href='".$_SITE_homelink."' class='button2' > $BackTo ".$_SITE_homename."</a></i></font>");
?>

<font color=#FFFFFF face=Arial, Helvetica, sans-serif size=2><i><a href='http://worldwater.byu.edu/' class='button2'> BYU World Water</a></i></font>

