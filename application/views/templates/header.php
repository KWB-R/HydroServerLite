<!--
//This is required to get the international text strings dictionary
	//global $_SITE_homename;
	//global $_SITE_homelink;
	$urlExtraName="header.php";
	//require 'internationalize.php';
	//require_once 'fetchMainConfig.php';
	
    CHECK ABOVE ELEMENTS
    ALSO NEED TO FIX THE HEADER SO THAT THE DROP DOWN FITS WELL WITH BOOTSTRAPPING. 
    
    -->
    
	<?php
		fetch_session();
	?>
	
	<div hidden="true" id="existingLanguage"><?php //echo $_SESSION['lang']?></div><!--@TODO-->
	
	<font color='#FFFFFF' face='Arial, Helvetica, sans-serif' size=2>Language :</font>
	<select id='langChange' name = 'langChange'>
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
		</select>
	<div class="navbar navbar-default" role="navigation">
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
			  <li><font color=#FFFFFF face=Arial, Helvetica, sans-serif size=2><a href="<?php echo $this->config->item('homelink');?>" class="button2" ><?php echo getText('BackTo').' '.$this->config->item('homename');?></a></font></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </div>




