<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';
//check authority to be here
require_once 'authorization_check.php';
//All queries go through a translator. 
require_once 'DBTranslator.php';
//redirect anyone that is not an administrator
	if (!isAdmin()){
	header("Location: index.php?state=pass2");
	exit;	
	}
if (isset($_POST['username'])) {
if ((!$_POST['username']) || (!$_POST['authority'])) {
	header("Location: changeauthority.php");
	exit;
}
$option_block="";
//add the user's data
$sql ="UPDATE moss_users SET authority='$_POST[authority]' WHERE username='$_POST[username]'";

$result = transQuery($sql,0,-1);

//get a good message for display upon success
if ($result) {
$msg ="<p class=em2> $CongratulationsChanged. $_POST[username]. $AddAnother</p>";
}
}
//add the user's data
$sql2 ="Select username FROM moss_users WHERE (authority='teacher' OR authority='student') ORDER BY username";

$result2 = transQuery($sql2,0,0);
$num = count($result2);
	if ($num < 1) {
		$msg2 = "<P><em2> $SorryNoUsers </em></p>";
	} else {
	foreach ($result2 as $row) {
		$users = $row["username"];
		$option_block .= "<option value=$users>$users</option>";
		}
	}

	require_once "_html_parts.php";
	HTML_Render_Head();
	
	echo $CSS_Main;
	
	echo $JS_JQuery;

	HTML_Render_Body_Start(); ?>
    <div class='col-md-9'>
<br /><p class="em" align="right"><?php echo $RequiredFieldsAsterisk; ?></p><?php if(isset($msg)){echo $msg;} ?>
<?php if(isset($msg2)){echo $msg2;} ?>
	  <h1><?php echo $ChangeUserAuthority; ?></h1>
      <p>&nbsp;</p>
      <form method="post" class="form-horizontal" action="changeauthority.php">
        <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $UserName; ?></label>
        <div class="col-sm-9">
        <select name="username" class="form-control" id="username"><option value=""><?php echo $SelectUsernameEllipisis ?></option><?php echo $option_block; ?></select><span class="required">*</span>
		</div>             
      </div>
     <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $NewAuthority; ?></label>
        <div class="col-sm-9">
     	   <select class="form-control" name="authority" id="authority">
              <option value=""><?php $SelectLevel; ?></option>			  
              <option value="admin"><?php echo $Administrator; ?></option>
              <option value="teacher"><?php echo $Teacher; ?></option>
              <option value="student"><?php echo $Student;?></option></select><span class="required">*</span>
		</div>             
      </div>
       <div class="col-md-3 col-md-offset-9">
      <input type="submit" name="submit2" value="<?php echo $ChangeAuthorityButton; ?>" class="button" style="width: auto" /></div>
</form>
</div>
	<?php HTML_Render_Body_End(); ?>