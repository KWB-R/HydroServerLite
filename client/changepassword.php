<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';
//check authority to be here
require_once 'authorization_check.php';
//All queries go through a translator. 
require_once 'DBTranslator.php';

//check for required fields
if (isset($_POST['username'])) {
if ((!$_POST['username']) || (!$_POST['password'])) {
	header("Location: changepassword.php");
	exit;
}
//add the user's data
$uname = $_POST['username'];
$pass = $_POST['password'];
$sql ="UPDATE moss_users SET password=PASSWORD('$pass') WHERE username='$uname'";
$result = transQuery($sql,0,-1);
//get a good message for display upon success
if ($result) {
$msg ="<p class=em2>".$CongratulationsChangedPassword." ". $_POST['username'].". ". $AddAnother. "</p>";
}}
$option_block="";
//Display the appropriate user authority to add depending on the user's authority
if (isAdmin()){
	//select the users
	$sql ="Select username FROM moss_users WHERE (authority='teacher' OR authority='student') ORDER BY username";
	$result = transQuery($sql,0,0);
	if (count($result) < 1) {
		$msg2 = "<P><em2> $SorryNoUsers </em></p>";
	} else {
	foreach ($result as $row) {
		$users = $row["username"];
		$option_block .= "<option value=$users>$users</option>";
		}
	}
}
elseif (isTeacher()){
	//select the users
	$sql ="Select username FROM moss_users WHERE authority='student' ORDER BY username";
	$result = transQuery($sql,0,0);
	if (count($result) < 1) {
		$msg2 = "<P><em2> $SorryNoUsers </em></p>";
	} else {
	foreach ($result as $row) {
		$users = $row["username"];
		$option_block .= "<option value=$users>$users</option>";
		}
	}
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
<br /><p class="em" align="right"><?php echo $RequiredFieldsAsterisk; ?></p><?php if(isset($msg)){echo $msg;} ?>
<?php if(isset($msg2)){echo $msg2;} ?>
	  <h1><?php echo $ChangeUserPassword; ?></h1>
      <p>&nbsp;</p>
      <FORM METHOD="POST" class="form-horizontal" ACTION="changepassword.php">
    
	  <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $UserName; ?></label>
        <div class="col-sm-9">
        <select name="username" class="form-control" id="username"><option value=""><?php echo $SelectUsernameEllipisis ?></option><?php echo "$option_block"; ?></select><span class="required">*</span>
		</div>             
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $NewPassword; ?></label>
        <div class="col-sm-9">
     	   <input type="text" class="form-control" name="password" size="25" maxlength="25"  /><span class="required">*</span>
		</div>             
      </div>
		 <div class="col-md-3 col-md-offset-9">
      <input type="SUBMIT" name="submit" value="<?php echo $ChangePassword;?>" class="button"/></div>
  </FORM> 
    </div>
	<?php HTML_Render_Body_End(); ?>