<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
require_once 'authorization_check.php';
require_once "_html_parts.php";

//These variables are for the notification about their respective privileges.
$Admin_notification = "";
$Teacher_notification = "";

//Display the appropriate user authority to add depending on the user's authority
if (isAdmin()){
	$selection = "<select name=authority id=authority><option value=>".$SelectEllipsis."</option><option value=admin>".$Administrator."</option><option value=teacher>".$Teacher."</option><option value=student>".$Student."</option></select>";			
	$Admin_notification = "As an Administrator you have all of the user privileges except removing or changing the password of another Administrator once they are added. ";
	}
	elseif (isTeacher()){
	$Admin_selection = "<select name=authority id=authority><option value=>".$SelectEllipsis."</option><option value=teacher>".$Teacher."</option><option value=student>".$Student."</option></select>";
	$Teacher_notification = "As a Teacher you will not be able to remove another Teacher's profile or change their password once they are added. You will only be able to change the profile and password of a Student.";
	}
elseif (isStudent()){
	header("Location: index.php?state=pass2");
	exit;	
	}

echo HTML_Render_Head();

echo $CSS_Main;

echo $JS_JQuery;

echo $JS_CreateUserName;
//#type $__User Alias
 HTML_Render_Body_Start(); ?>
<p class="em" align="right"><?php echo $RequiredFieldsAsterisk; ?></p><?php echo "$msg"; ?></p>
	      <h1><?php echo $AddNewUser; ?></h1>
      <p>&nbsp;</p>
      <FORM METHOD="POST" ACTION="do_adduser.php" name="newuser">
      <table width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
		  <td width="95" valign="top"><strong><?php echo $FirstName; ?></strong></td>
          <td width="157" valign="top"><input type="text" id="firstname" name="firstname" maxlength="50"/></td>
          <td width="348" valign="top"><span class="required">*</span></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td width="157" valign="top">&nbsp;</td>
          <td width="348" valign="top">&nbsp;</td>
        </tr>
        <tr>
		  <td width="95" valign="top"><strong><?php echo $LastName; ?></strong></td>
          <td valign="top"><input type="text" id="lastname" name="lastname" maxlength="50" onBlur="GetLastName()"/></td>
          <td valign="top"><span class="required">*</span></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
		  <td width="95" valign="top"><strong><?php echo $UserName; ?></strong></td>
          <td valign="top"><input type="text" id="username" name="username" maxlength="25" />
          <div class="em"></div></td>
		  <td valign="top"><span class="em"><span id="user-result"></span><span class="required">*</span><?php echo $FirstLastNameExample; ?></span></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
		  <td width="95" valign="top"><strong><?php echo $Password; ?></strong></td>
          <td valign="top"><input type="text" name="password" maxlength=25 id ="password"/><div class="em"></div></td>
          <td valign="top"><span class="em"><span class="required">*</span><?php echo $CaseSensitive; ?></span></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
		  <td width="95" valign="top"><strong><?php echo $Authority; ?> </strong></td>
          <td valign="top"><?php echo "$selection"; ?><span class="required">*</span></td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td width="95" valign="top"><input type="SUBMIT" name="submit" value="<?php echo $AddUser;?>" class="button"/></td>
          <td valign="top"><input type="reset" name="Reset" value="<?php echo $Cancel; ?>" class="button" style="width: auto" /></td>
          <td valign="top">&nbsp;</td>
        </tr>
      </table></FORM>
	  <p><br>
	  </p>
	  <p><br>
		</p>
	  <p class="em" align="center"><?php echo $Admin_notification;?></p><?php echo "$msg"; ?></p>
	  <p class="em" align="center"><?php echo $Teacher_notification;?></p><?php echo "$msg"; ?></p>
<div id="checkStatus" hidden="true"></div>
      <p>&nbsp;</p>
    
	<?php HTML_Render_Body_End(); ?>
    
<script type= "text/javascript">
$(document).ready(function(){


$("#username").blur(function (e){
	var username = $("#username").val();
    $.post("check_username.php",{ username :username}, function(data){
    $("#user-result").html(data);
	if(data == '<img src="images/not-available.png" />'){
		$("#checkStatus").html(1);
	}
	else
	{
		$("#checkStatus").html(0);
	}
    });
});


$("#lastname").blur(function (e){
	var username = $("#username").val();
    $.post("check_username.php",{ username :username}, function(data){
    $("#user-result").html(data);
	if(data == '<img src="images/not-available.png" />'){
	$("#checkStatus").html(1);
	}
	else
	{
		$("#checkStatus").html(0);
	}
    });
});

$("form").submit(function(e){

if(($("#firstname").val())==""){
		alert("Please enter your First Name");
		return false;
	}
if(($("#lastname").val())==""){
		alert("Please enter your Last Name");
		return false;
	}
if(($("#username").val())==""){
		alert("Please enter a Username");
		return false;
	}

if(($("#password").val())==""){
		alert("Please enter a Password");
		return false;
	} 
if(($("#authority").val())==""){
		alert("Please Select an Authority");
		return false;
	}

if($("#checkStatus").html()==1)
{
	alert("Username already exists. Please choose a different one.");
	return false;	
}

});
});
</script>