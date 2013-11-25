<?php

	//If this is a single installation we will need additional processing. Make sure NO main_config file exists. 
	$singleInstallLocal = 1;
	if (file_exists("../main_config.php"))
	{
	require_once("../main_config.php");
	if ($singleInstall != "Yes")
	{$singleInstallLocal = 0;
	if (!isset($_SESSION)) {
	session_start();
	}
	header ("Location: ../../".$_SESSION['dir']."/manageLP/index.php");
	}
	}
	
	//This is required to get the international text strings dictionary
	$setup="yes";
	require_once 'internationalize.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<title>HydroServer Lite Web Client: Install</title>-->
<title><?php echo $TitleInstall;?></title>
<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="../favicon.ico" >

<link href="../styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />

<!-- JQuery JS -->
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>

</head>

<body background="../images/bkgrdimage.jpg">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><img src="../images/WebClientBanner.png" width="960" height="200" alt="Adventure Learning Banner" /></td>
  </tr>
  
  <tr>
    <td width="240" valign="top" bgcolor="#f2e6d6">
    </td>
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br />
      <h1><!--Installation Complete--><?php echo $InstallationComplete;?></h1>
        <p><!--Congratulations. You have successfully installed HydroServer Lite on your Web Server.Click Below to go to your site.--><?php echo $CongratsInstall;?></p>
        <p><!--You can login using the Administrator password set on the previous page. The username is 'his_admin'(without the quotes).--><?php echo $Login;?></p>
        <!--<p><a href="../index.php" class="button">Go to Site</a></p>-->
        <p><a href="..<?php 
		if (!$singleInstallLocal)
		{echo "/../".$_SESSION['dir'];}?>/index.php" class="button"><?php echo $GoToSite;?></a></p>
        <p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

    </blockquote></td>
  </tr>
  <tr>
    <script src="../js/footer.js"></script>
  </tr>
</table>

</body>
</html>
