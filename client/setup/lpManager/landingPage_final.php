<?php
	
	//This is required to get the international text strings dictionary
	if (!isset($_SESSION)) {
	session_start();
	
	}
	$setup="yes";
	$urlExtraName="install_final.php";
	require_once 'internationalize.php';
	$mainDir=$_SESSION['mainDir'];
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
    </td>
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br />
      <h1><?php echo $InstallationComplete;?></h1>
        <p><?php echo $CongratsInstall;?></p>
        <p><?php echo $Login;?></p>
        <p><a href="../../<?php echo $_SESSION['dir']?>/index.php" class="button"><?php echo $GoToSite;?></a></p>
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
</html>
<?php



?>