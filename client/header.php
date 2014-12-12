<?php 

//This is required to get the international text strings dictionary
	global $_SITE_homename;
	global $_SITE_homelink;
	$urlExtraName="header.php";
	require 'internationalize.php';
	require_once 'fetchMainConfig.php';
	
	if (!isset($_SESSION))
	{
		session_start();	
	}
	
	echo '<div hidden="true" id="existingLanguage">'.$_SESSION['lang'].'</div>';
	
	echo("<font color=#FFFFFF face=Arial, Helvetica, sans-serif size=2>Language :</font>");
	echo("<select id='langChange' name = 'langChange'>
		<option value='English'>English</option>
		<option value='Spanish'>Spanish</option>
		<option value='Italian'>Italian</option>
		<option value='Portuguese'>Portuguese</option>
		<option value='German'>German</option>
		<option value='Dutch'>Dutch</option>
		<option value='Bulgarian'>Bulgarian</option>
		<option value='Croatian'>Croatian</option>
		<option value='Ukranian'>Ukranian</option>
		<option value='French'>French</option>
		<option value='Russian'>Russian</option>
		<option value='Tagalog'>Tagalog</option>
		<option value='Czech'>Czech</option>
		</select>");
	//commented out by Jeremy Fowler (Nov. 20, 2014)
	//echo("<font color=#FFFFFF face=Arial, Helvetica, sans-serif size=2><i><a href='".$_SITE_homelink."' class='button2' > $BackTo ".$_SITE_homename."</a></i></font>");
?>



