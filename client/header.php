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
		<option value='Spanish'>Español</option>
		<option value='Italian'>Italiano</option>
		<option value='Portuguese'>Portugués</option>
		<option value='German'>Alemán</option>
		<option value='Dutch'>Nederlands</option>
		<option value='Bulgarian'>български</option>
		<option value='Croatian'>Hrvatski</option>
		<option value='Ukranian'>Українська</option>
		<option value='French'>Français</option>
		<option value='Russian'>Русский</option>
		<option value='Tagalog'>Tagalog</option>
		<option value='Czech'>Český</option>
		</select>");
		
	//commented out by Jeremy Fowler (Nov. 20, 2014)
	//echo("<font color=#FFFFFF face=Arial, Helvetica, sans-serif size=2><i><a href='".$_SITE_homelink."' class='button2' > $BackTo ".$_SITE_homename."</a></i></font>");
	//Old Header : keeping here for temporary purposes. 
	/*echo("<font color=#FFFFFF face=Arial, Helvetica, sans-serif size=2>Language : <i><div class='button' style='cursor: pointer;' id='langChange'>English</div></i></font>
<font color=#FFFFFF face=Arial, Helvetica, sans-serif size=2><i><div class='button' style='cursor: pointer;' id='langChange'>Spanish</div></i></font>
");
	echo("<font color=#FFFFFF face=Arial, Helvetica, sans-serif size=2><i><a href='".$_SITE_homelink."' class='button2' > $BackTo ".$_SITE_homename."</a></i></font>");
	*/
	
	echo '<div class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
			   <span class="visible-xs navbar-brand">Language Menu</span>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
              <li>Language:</li>
              <li><div class="button" style="cursor: pointer;" id="langChange">English</div></li>
              <li><div class="button" style="cursor: pointer;" id="langChange">Spanish</div></li>
			  <li><font color=#FFFFFF face=Arial, Helvetica, sans-serif size=2><a href="'.$_SITE_homelink.'" class="button2" > '.$BackTo.$_SITE_homename.'</a></font></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </div>';
?>



