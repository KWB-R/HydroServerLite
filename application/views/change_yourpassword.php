<?php

/*
if(!isset($_SESSION))
{
	session_start();	
	
}
//add the user's data
$uname = $_SESSION['username'];
$sql ="UPDATE moss_users SET password=PASSWORD('$_POST[password]') WHERE username='".$uname."'";

$result = transQuery($sql,0,-1);

//Display the appropriate user authority to add depending on the user's authority
if (isStudent()){
	header("Location: unauthorized.php");
	exit;	
	}
	require_once "_html_parts.php";
	*/
	HTML_Render_Head();
	
	echo $CSS_Main;
	
	echo $JS_JQuery;

	HTML_Render_Body_Start(); ?>
<br /><p class="em" align="right"><!--Required fields are marked with an asterick (*).--><?php echo getTxt('RequiredFieldsAsterisk');?></p>
      <h1><?php echo getTxt('ChangeYourPassword');?></h1>
      <p>&nbsp;</p>
      <FORM METHOD="POST" ACTION="change_yourpassword.php" name="change_yourpassword">
        <table width="400" border="0" cellspacing="0" cellpadding="0">
       <tr>
          <!--<td valign="top"><strong>Enter New Password:</strong></td>-->
		  <td valign="top"><strong><?php echo getTxt('NewPassword'); ?></strong></td>
          <td width="251" valign="top"><input type="text" id="password1" name="password1" size="16" maxlength="20" />*</td>
        </tr>
        <tr>
          <td width="149" valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <!--<td valign="top"><strong>Re-Enter New Password:</strong></td>-->
		  <td valign="top"><strong><?php echo getTxt('NewPassword1'); ?></strong></td>
          <td valign="top"><input type="text" id="password" name="password" size="16" maxlength="20" />*</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <!--<td valign="top"><input type="SUBMIT" name="submit" value="Change Password" class="button" style="width: 145px" /></td>-->
          <td valign="top"><input type="SUBMIT" name="submit" value="<?php echo getTxt('ChangePassword');?>" class="button" style="width: auto" /></td>
        </tr>
      </table>
  </FORM>
      <p>&nbsp;</p>
	  <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
	<?php HTML_Render_Body_End(); ?>
<script type= "text/javascript">
$(document).ready(function(){
	$("form").submit(function(e){
		if(($("#password1").val())==""){
		alert("Please enter a Password");
		return false;
		}
		if(($("#password").val())==""){
		alert("Please confirm your Password");
		return false;
		}
		var pass1 = document.getElementById('password'); 
		var pass2 = document.getElementById('password1')
		if(pass1.value !== pass2.value){
		alert ("The passwords don't match please try again.");
		return false;
		}
		alert ("Password succesffully changed");
});
});
</script>