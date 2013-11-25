<?php
	//This is required to get the international text strings dictionary
	
	session_start();
	
	//Check if not redirected from the main setup and this is a direct access
	//If direct access then fetch the directory from which it is being accessed. 
	
	if(!isset($_SESSION['dir']))
	{	
	$url=$_SERVER['PHP_SELF'];
	$urlParts=explode("/",$url);
	$_SESSION['dir']=$urlParts[count($urlParts)-3];
	}
	
	if(isset($_SESSION['mainDir']))
	{	unset($_SESSION['mainDir']);	
	}
	$mainDir="../../main/";
	$_SESSION['mainDir']=$mainDir;
	$_SESSION['setup']="yes";
    $setup="yes";
	$urlExtraName="landingPage.php";
	
	require_once 'internationalize.php';
	
	//Check if previous Landing page exists. If yes, the USER Name and password will be required to be entered to continue.
	
	if (file_exists("LpConfig.php")) {
  //Set Login Display to true. 
  
  $GuideText = $InstallGuide2Login;
  $displayLogin = 1;
	} else {
  $GuideText = $InstallGuide2;
	$displayLogin = 0;
	}
	
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $TitleInstall;?></title>
<link rel="shortcut icon" href="<?php echo $mainDir; ?>favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="<?php echo $mainDir; ?>favicon.ico" >

<link href="<?php echo $mainDir; ?>styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />

<!-- JQuery JS -->
<script type="text/javascript" src="<?php echo $mainDir; ?>js/jquery-1.7.2.min.js"></script>

</head>

<body background="<?php echo $mainDir; ?>images/bkgrdimage.jpg">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><img src="<?php echo $mainDir; ?>images/WebClientBanner.png" width="960" height="200" alt="Adventure Learning Banner" /></td>
  </tr>
  
  <tr>
    <td width="240" valign="top" bgcolor="#f2e6d6">
	 <?php 
		 
			if ($displayLogin)
			{
	
	 echo '<p>&nbsp;</p>
    <FORM METHOD="POST" ACTION="editLandingPage.php" name="login" id="login">
    <table width="200" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td><center>
      
            <font face="Arial, Helvetica, sans-serif" size="4"><strong>'.$Returning.'</strong></font>
          </center></td>
        </tr>
        <tr>
          <td><hr width="150" noshade="noshade" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
     
          <td><center><font face="Arial, Helvetica, sans-serif" size="2"><strong>'.$UserName.'
          </strong></font><br />
            <INPUT TYPE="text" id="username" name="username" SIZE=25 MAXLENGTH=25></center></td>
        </tr>
        <tr>
        
          <td><center><font face="Arial, Helvetica, sans-serif" size="2"><strong>'.$Password.'</strong></font><br />
            <INPUT TYPE="password" id="password" name="password" SIZE=25 MAXLENGTH=25></center></td>
        </tr>
        <tr>
   
          <td><center><INPUT TYPE="SUBMIT" NAME="submit" VALUE="'.$Login.'" class="button"></center></td>
        </tr>
    </table></FORM>';
	}
		 
		 ?>
    </td>
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br />
      <h1><?php echo $InstallationWelcome;?></h1>
        <p><?php echo $InstallGuide;?></p>
		 <p><?php echo $GuideText;?></p>
		  <?php 
		 
			if (!$displayLogin)
			{
			echo  '<p><a href="editLandingPage.php" class="button">'.$BeginInstallation.'</a></p>';
			};
			?>
       
        <p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

    </blockquote></td>
  </tr>
  <tr>
    <script src="<?php echo $mainDir; ?>js/footer.js"></script>
  </tr>
</table>

</body>

<script type="text/javascript">

//Validate username and password
$("form").submit(function(){

	if(($("#username").val())==""){
	alert("Please enter a username!");
	return false;
	}

	if(($("#password").val())==""){
	alert("Please enter a password!");
	return false;
	}

//Now that all validation checks are completed, allow the data to query database

	return true;
});
</script>

</html>
