<?php

//Editing made on pages to make sure that in case of world water the landing page is shown or else its redirected to the default view in case of a multiinstallation. 
$WorldWater = "No"; //Use "Yes" If this is on world water servers. 
$MainDir = "workingHSL"; //This is the location to the main directory where all the files are contained on the server. 

//The landing page in each directory. This will contain the MainConfig file for the specific page. 
//Check the URL for the page. 
include ("manageLP/LpConfig.php");
session_start();
$url=$_SERVER['PHP_SELF'];
$urlParts=explode("/",$url);
$dir=$urlParts[count($urlParts)-2];
$_SESSION["mainpath"]="..\\".$dir."\main_config.php";
//Clean up from landing Page manager setup

if (isset($_SESSION['dir']))
{
	unset($_SESSION['dir']);
}


if ($WorldWater=="No")
{
header ("Location: ../".$MainDir."/");
	}


?>

<!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	
	<title>Initiatives</title>
	<meta name="description" content="Description about your department." />
	<meta name="author" content="Your Department Name" />
	<meta name="keywords" content="keyword 1, keyword 2, keyword 3">
	
	<meta name="viewport" content="width=device-width" />
	
	<link rel="shortcut icon" href="../../template/img/favicon.ico" />
	<link rel="stylesheet" href="../../template/css/style.css" />

	<script src="../../template/js/libs/modernizr-2.0-basic.min.js"></script>
	
</head>

<body>
	<?php include '../../header.php'; ?>
	<div id="content" class="wrapper clearfix">
		<div id="breadcrumb">
				<a href="../../index.php">Home</a> › <?php 
			include ("manageLP/LpConfig.php");
			echo $title; ?>
	  </div>
		<h1><?php echo $title; ?></h1>
		<!--<h2>Initiatives</h2>-->
		<h3><?php echo $group; ?></h3>
        <p>&nbsp;</p>
	  	<p><?php echo stripslashes($description) ?></p>
	  	<p>&nbsp;</p>
        <?php require("manageLP/cite.php"); ?>
        <p>&nbsp;</p>
        <a class="button2" href="<?php echo $link; ?>">Enter Database</a>
           <p>&nbsp;</p>
        <a class="button2" href="manageLP/index.php">Edit this page</a>

</div>
    </div>
        <?php include '../../footer.php'; ?>
	<!-- PLUGINS -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="../../template/js/libs/jquery.min.js"%3E%3C/script%3E'))</script>
	<script src="../../template/js/script.js"></script>
	
	<!--[if lt IE 7 ]>
	<script src="template/js/libs/dd_belatedpng.js"></script>
	<script> DD_belatedPNG.fix('.arrow a, header h1, #search-button');</script>
	<![endif]-->
</body>
</html>
