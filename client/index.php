<?php

//Clear the session variable for language so that default language loading can happen. 
//However if langchange variable is set in the url, then session remains in place
if (!isset($_GET["langChang"]))
{
if (!isset($_SESSION))
	{
		session_start();
	}
	if (isset($_SESSION['lang']))
	{
		unset($_SESSION['lang']);
	}
	if (isset($_SESSION['power']))
	{
		unset($_SESSION['power']);
	}
		
}

//This is required to get the international text strings dictionary
require_once 'internationalize.php';
require_once("fetchMainConfig.php");
require_once("session_handler.php");
require_once "authorization_check.php";


require_once "_html_parts.php";

HTML_Render_Head();

echo $CSS_Main;

echo $JS_JQuery;

echo $JS_Forms;

 HTML_Render_Body_Start();
?>
<br />
      <p>
<?php 
if (isset($_GET['state'])) {
	if ($_GET['state']=="pass"){
		echo "<p class=em2>***Incorrect username and/or password!</p>";
	} elseif ($_GET['state']=="pass2"){
		echo "<p class=em2>***You are not authorized to view that page!</p>";
		//require_once "login.php";
	} elseif ($_GET['state']=="pass3"){
		echo "<p class=em2>You have logged out.</p>";
	}
}
?>
	  </p>
	<div class="welcome">
      <h1>Welcome</h1>
 		 <p><?php echo $Paragraph1; ?></p>
        <p><?php echo $Paragraph2; ?></p>
        <p><?php echo $Paragraph3; ?></p>

	<img src="images/homepage_shot.jpg" alt="site picture"/>
        </div>
    
<?php HTML_Render_Body_End(); ?>
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
