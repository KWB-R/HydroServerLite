<?php 

//This is required to get the international text strings dictionary
	global $_SITE_homename;
	global $_SITE_homelink;
	$urlExtraName="header.php";
	require 'internationalize.php';
	require_once 'fetchMainConfig.php';
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


