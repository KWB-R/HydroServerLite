<?php

//check authority to be here
require_once 'authorization_check.php';

//Display the appropriate user authority to add depending on the user's authority
if ($power1 == "admin"){
	$selection = "<select name=authority id=authority><option value=>Select....</option><option value=admin>Program Manager</option><option value=teacher>Project Coordinator</option><option value=student>Volunteer</option></select>";		
	}
elseif ($power1 == "teacher"){
	$selection = "<select name=authority id=authority><option value=>Select....</option><option value=teacher>Project Coordinator</option><option value=student>Volunteer</option></select>";
	}
elseif ($power1 == "student"){
	header("Location: index.php?state=pass2");
	exit;	
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IDAH2O Web App</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="favicon.ico" >
<link href="styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />
<!-- JQuery JS -->
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>

<script type="text/javascript" src="js/create_username.js"></script>

<script type="text/javascript">

$(document).ready(function(){

	$("#msg").hide();

});

</script>
</head>

<body background="images/bkgrdimage.jpg">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><img src="images/WebClientBanner.png" width="960" height="200" alt="logo" /></td>
  </tr>
  <tr>
    <td colspan="2" align="right" valign="middle" bgcolor="#3c3c3c"><?php require_once 'header.php'; ?></td>
  </tr>
  <tr>
    <td width="240" valign="top" bgcolor="#f2e6d6"><?php echo "$nav"; ?></td>
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br /><p class="em" align="right">Required fields are marked with an asterisk(*).</p><div id="msg">
      <p class=em2>User successfully added!</p></div>
      <h1>Add a New User</h1>
      <p>&nbsp;</p>
      <FORM METHOD="POST" ACTION="" name="newuser" id="newuser">
      <table width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="95" valign="top"><strong>First Name:</strong></td>
          <td width="157" valign="top"><input type="text" id="firstname" name="firstname" size=25 maxlength=50 onBlur="GetFirstLetter()"/></td>
          <td width="348" valign="top">*&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td width="157" valign="top">&nbsp;</td>
          <td width="348" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td width="95" valign="top"><strong>Last Name:</strong></td>
          <td valign="top"><input type="text" id="lastname" name="lastname" size=25 maxlength=50 onBlur="GetLastName()"/></td>
          <td valign="top">*&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td width="95" valign="top"><strong>Username:</strong></td>
          <td valign="top"><input type="text" id="username" name="username" size=25 maxlength=25 />
          <div class="em"></div></td>
          <td valign="top"><span class="em">*&nbsp;(First initial and last name; ex: &quot;jdoe&quot; for John Doe)</span></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td width="95" valign="top"><strong>Password:</strong></td>
          <td valign="top"><input type="text" name="password" size=25 maxlength=25 /><div class="em"></div></td>
          <td valign="top"><span class="em">*&nbsp;(Case sensitive; enter 6-8 characters)</span></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td width="95" valign="top"><strong>Authority:</strong></td>
          <td valign="top"><?php echo "$selection"; ?>*</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td width="95" valign="top">&nbsp;</td>
          <td valign="top"><input type="SUBMIT" name="submit" value="Add User" class="button" /></td>
          <td valign="top">&nbsp;</td>
        </tr>
      </table></FORM>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </blockquote>
    <p></p></td>
  </tr>
  <tr>
    <script src="js/footer.js"></script>
  </tr>
</table>
</body>
</html>

<script>

$("#newuser").submit(function(){

	//Validate all fields
	if(($("#firstname").val())==""){
		alert("Please enter a first name.");
		return false;
	}

	if(($("#lastname").val())==""){
		alert("Please enter a last name.");
		return false;
	}

	if(($("#username").val())==""){
			alert("Please enter a username.");
			return false;
	}

	if(($("#password").val())==""){
		alert("Please enter a password.");
		return false;
	}

	if(($("#authority option:selected").val())==-1){
		alert("Please select an authority level.");
		return false;
	}

//Validation is all complete, so now process it

	$.post("do_adduser.php", $("#newuser").serialize(), function(data){
  
		 if(data==1){
			$("#msg").show(2000);
			$("#msg").hide(3500);
			$("#authority").val("-1");
			$("#password").val("");
			$("#username").val("");
			$("#lastname").val("");
			$("#firstname").val("");
			setTimeout(function(){
				window.open("adduser.php","_self");
				}, 5000);
			return true;
		}else{
			alert("Error during processing! Please refresh the page and try again.");
			return false;
		}
		
	});
return false;
});
</script>