<?php

$option_block="";
/*
//These variables are for the notification about their respective privileges.
$Admin_notification = "";
$Teacher_notification = "";


//Display the appropriate user authority to add depending on the user's authority
if (isAdmin()){
	//select the users
	$sql ="Select username FROM moss_users WHERE (authority='teacher' OR authority='student') ORDER BY username";
	$result = transQuery($sql,0,0);
	if (count($result) < 1){
		$msg = "<P><em2>$SorryNoUsers</em></p>";
	} else {
	foreach ($result as $row) {
		$users = $row["username"];
		$option_block .= "<option value=$users>$users</option>";
		}
	}
	$Admin_notification = "As an Administrator you have all of the user privileges except removing or changing the password of another Administrator once they are added. ";
}
elseif (isTeacher()){
	$sql ="Select username FROM moss_users WHERE (authority ='student') ORDER BY username";
	$result = transQuery($sql,0,0);
	if (count($result) < 1){
		$msg = "<P><em2>" + $NoUsers + "</em></p>";
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
	*/

	HTML_Render_Head($js_vars);
	
	echo $CSS_Main;
	
	echo $JS_JQuery;

	HTML_Render_Body_Start(); ?>
<p class="em" align="right"><!--Required fields are marked with an asterick (*).--><?php echo getTxt('RequiredFieldsAsterisk');?></p><?php echo getTxt('msg'); ?>
      <h1><?php echo getTxt('ChangeUserPassword');?></h1>
      <p>&nbsp;</p>
      <FORM METHOD="POST" ACTION="do_changepassword.php">
        <table width="350" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="107" valign="top"><strong><!--Username:--><?php echo getTxt('UserName');?></strong></td>
          <td width="193" valign="top"><select name="username" id="username"><option value=""><!--Select a username....--><?php echo getTxt('SelectUsernameEllipisis');?></option><?php echo "$option_block"; ?></select>*</td>
        </tr>
        <tr>
          <td width="107" valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--New Password:--><?php echo getTxt('NewPassword');?></strong></td>
          <td valign="top"><input type="text" name="password" size="16" maxlength="25" />*</td>
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
	<p><br>
	  </p>
	  <p><br>
		</p>
	  <p class="em" align="center"><?php echo getTxt('Admin_notification');?></p><?php echo getTxt('msg'); ?></p>
	  <p class="em" align="center"><?php echo getTxt('Teacher_notification');?></p><?php echo getTxt('msg'); ?></p>
      <p>&nbsp;</p>
	  <p>&nbsp;</p>
	  <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
	<?php HTML_Render_Body_End(); ?>