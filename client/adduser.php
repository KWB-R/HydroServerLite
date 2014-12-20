<?php

//This is required to get the international text strings dictionary
require_once 'internationalize.php';
//check authority to be here
require_once 'authorization_check.php';
//All queries go through a translator. 
require_once 'DBTranslator.php';

//These variables are for the notification about their respective privileges.
$Admin_notification = "";
$Teacher_notification = "";

//check for required fields
if (isset($_POST['firstname'])) {
	
	if ((!$_POST['firstname']) || (!$_POST['lastname']) || (!$_POST['username']) || (!$_POST['password']) || (!$_POST['authority'])){
		//Check if all variables are defined. 	
		header("Location: adduser.php");
		exit;
	}

	//add the user's data
	$sql ="INSERT INTO moss_users(firstname, lastname, username, password, authority) VALUES ('$_POST[firstname]', '$_POST[lastname]', '$_POST[username]', PASSWORD('$_POST[password]'), '$_POST[authority]')";

	$result = transQuery($sql,0,-1);

//get a good message for display upon success
	if ($result){ 
	$msg = "<p class=em2>".$Congrats." ".$_POST['firstname'].". ".$AddAnother."</p>";
	}
	
}
//Display the appropriate user authority to add depending on the user's authority
if (isAdmin()){
	$selection = "<select  class=\"form-control\" name='authority' id=authority><option value=>".$SelectEllipsis."</option><option value=admin>".$Administrator."</option><option value=teacher>".$Teacher."</option><option value=student>".$Student."</option></select>";	
	$Admin_notification = "As an Administrator you have all of the user privileges except removing or changing the password of another Administrator once they are added. ";
	}
	elseif (isTeacher()){
	$Admin_selection = "<select name=authority id=authority><option value=>".$SelectEllipsis."</option><option value=teacher>".$Teacher."</option><option value=student>".$Student."</option></select>";
	$Teacher_notification = "As a Teacher you will not be able to remove another Teacher's profile or change their password once they are added. You will only be able to change the profile and password of a Student.";
	}
elseif (isStudent()){
	header("Location: unauthorized.php");
	exit;	
	}

require_once "_html_parts.php";
	HTML_Render_Head();

echo $CSS_Main;

echo $JS_JQuery;

echo $JS_CreateUserName;

 HTML_Render_Body_Start(); ?>
<div class='col-md-9'>
<br /><p class="em" align="right"><<?php echo $RequiredFieldsAsterisk;?></p><?php if(isset($msg)){echo $msg;} ?>
	  <h1><?php echo $AddNewUser; ?></h1>
      <p>&nbsp;</p>
      <FORM METHOD="POST" id="newuser" class="form-horizontal" ACTION="adduser.php">
     
     <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $FirstName; ?></label>
        <div class="col-sm-9">
        <input type="text"  class="form-control" id="firstname" name="firstname" size=25 maxlength=50 onBlur="GetFirstLetter()" /><span class="required">*</span>
		</div>             
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $LastName; ?></label>
        <div class="col-sm-9">
     	   <input type="text" class="form-control" id="lastname" name="lastname" size=25 maxlength=50 onBlur="GetLastName()"  /><span class="required">*</span>
		</div>             
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $UserName; ?></label>
        <div class="col-sm-9">
     	   <input type="text" class="form-control" id="username" name="username" /><span class="required">*</span>
           <span class="help-block"><br/><?php echo $FirstLastNameExample;?></span>
		</div>             
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $Password; ?></label>
        <div class="col-sm-9">
     	   <input type="text" class="form-control" name="password" maxlength=25 /><span class="required">*</span>
           <span class="help-block"><br/><?php echo $CaseSensitive;?></span>
		</div>             
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $Authority; ?></label>
        <div class="col-sm-9">
     	   <?php echo "$selection"; ?><span class="required">*</span>
		</div>             
      </div>
	 <div class="col-md-2 col-md-offset-10">
       <input type="SUBMIT" name="submit" value="<?php echo $AddUser;?>" class="button"/></div>
       </FORM>
    </div>
	<?php HTML_Render_Body_End(); ?>
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