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

$option_block="";
if (isset($_POST['username'])) {
if ((!$_POST['username'])) {
	header("Location: changeauthority.php");
	exit;
}

$sql ="DELETE FROM moss_users WHERE username='$_POST[username]'";
$result = transQuery($sql,0,-1);
//get a good message for display upon success
if ($result) {
$msg ="<p class=em2>$Congrats $_POST[username]. $Another</p>";
}
}
//Display the appropriate user authority to add depending on the user's authority
if (isAdmin()){
	//select the users
	$sql ="Select username FROM moss_users WHERE (authority='teacher' OR authority='student') ORDER BY username";
	$result = transQuery($sql,0,0);
	if (count($result) < 1) {
    	$msg2 = "<P><em2>$sorry</em></p>";
	} else {
	foreach ($result as $row) {
		$users = $row["username"];
		$option_block .= "<option value=$users>$users</option>";
		}
	}
	$Admin_notification = "As an Administrator you have all of the user privileges except removing or changing the profile of another Administrator once they are added.  ";
}
elseif (isTeacher()){
	//select the users
	$sql ="SELECT username FROM moss_users WHERE authority LIKE 'student' ORDER BY username";
		$result = transQuery($sql,0,0);
	if (count($result) < 1) {
    	$msg2 = "<P><em2>$sorry</em></p>";
	} else {
	foreach ($result as $row) {
		$users = $row["username"];
		$option_block .= "<option value=$users>$users</option>";
		}
	}
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

	HTML_Render_Body_Start(); ?>
    <div class='col-md-9'>
<br /><p class="em" align="right">
<?php echo $RequiredFieldsAsterisk; ?></p><?php if(isset($msg)){echo $msg;} ?>
<?php if(isset($msg2)){echo $msg2;} ?>

      <h1><?php echo $RemoveExistingUser; ?></h1>
      <p>&nbsp;</p>
      
      <FORM METHOD="POST" class="form-horizontal" ACTION="removeuser.php">
         <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $UserName; ?></label>
        <div class="col-sm-9">
        <select name="username" class="form-control" id="username"><option value=""><?php echo $SelectUsernameEllipisis ?></option><?php echo $option_block; ?></select><span class="required">*</span>
		</div>             
      </div>
     <div class="col-md-3 col-md-offset-9">
     <input type="SUBMIT" name="submit" value=<?php echo $RemoveUser; ?> class="button" style="width:auto" /></div>  
  </FORM>
    
			<p><br>
			</p>
			<p><br>
			</p>
			<p class="em" align="center"><?php echo $Admin_notification;?></p><?php echo "$msg"; ?></p>
			<p class="em" align="center"><?php echo $Teacher_notification;?></p><?php echo "$msg"; ?></p>
      <p>&nbsp;</p>
	  <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    
</div>
<?php HTML_Render_Body_End(); ?>