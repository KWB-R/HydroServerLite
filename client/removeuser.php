<?php
//check authority to be here
require_once 'authorization_check.php';

//connect to server and select database
require_once 'database_connection.php';

//Display the appropriate user authority to add depending on the user's authority
if ($power1 == "admin"){
	//select the users
	$sql ="Select username FROM moss_users WHERE (authority='teacher' OR authority='student') ORDER BY username";
	$result = @mysql_query($sql,$connection)or die(mysql_error());
	$num = @mysql_num_rows($result);
	if ($num < 1) {
    	$msg = "<P><em2>Sorry, there are no users.</em></p>";
	} else {
	while ($row = mysql_fetch_array ($result)) {
		$users = $row["username"];
		$option_block .= "<option value=$users>$users</option>";
		}
	}
}
elseif ($power1 == "teacher"){
	//select the users
	$sql ="SELECT username FROM moss_users WHERE authority LIKE 'student' ORDER BY username";
	$result = @mysql_query($sql,$connection)or die(mysql_error());
	$num = @mysql_num_rows($result);
	if ($num < 1) {
    	$msg = "<P><em2>Sorry, there are no users.</em></p>";
	} else {
	while ($row = mysql_fetch_array ($result)) {
		$users = $row["username"];
		$option_block .= "<option value=$users>$users</option>";
		}
	}
}
elseif ($power1 == "student"){
	header("Location: index.php?state=pass2");
	exit;	
	}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IDAH2O Web App</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="favicon.ico" >
<link href="styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>

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
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br /><p class="em" align="right">Required fields are marked with an asterisk(*).</p><?php echo "$msg"; ?><div id="msg">
      <p class=em2>User successfully removed!</p></div>
      <h1>Remove an Existing User</h1>
      <p>&nbsp;</p>
      <FORM METHOD="POST" ACTION="" name="r_user" id="r_user">
        <table width="300" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="100" valign="top"><strong>Username:</strong></td>
          <td width="200" valign="top"><select name="username" id="username"><option value="-1">Select a username....</option><?php echo "$option_block"; ?></select>*</td>
        </tr>
        <tr>
          <td width="100" valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top"><input type="SUBMIT" name="submit" value="Remove User" class="button" style="width: 110px" /></td>
        </tr>
      </table>
  </FORM>
      <p>&nbsp;</p>
	  <p>&nbsp;</p>
      <p>&nbsp;</p>
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

$("#r_user").submit(function(){

	//Validate all fields
	if(($("#username option:selected").val())==-1){
		alert("Please select a Username.");
		return false;
	}

//Validation is all complete, so now process it

	$.post("do_removeuser.php", $("#r_user").serialize(), function(data){
  
		 if(data==1){
			$("#msg").show(2000);
			$("#msg").hide(3500);
			$("#username").val("-1");
			setTimeout(function(){
				window.open("removeuser.php","_self");
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