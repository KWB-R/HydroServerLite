<?php
//This is required to get the international text strings dictionary
/*
//These variables are for the notification about their respective privileges.
$Admin_notification = "";


//redirect anyone that is not an administrator
if (!isAdmin()){
	header("Location: index.php?state=pass2");
	exit;	
	}
require_once "_html_parts.php";

$option_block = "";
//add the user's data
$sql ="SELECT username FROM moss_users WHERE (authority='teacher' OR authority='student') ORDER BY username";
$result = transQuery($sql,0,0);
$msg = ""; 
if (count($result) < 1) {
	$msg = "<P><em2>$SorryNoUsers</em></p>";
} else {
	foreach ($result as $row) {
		$users = $row["username"];
		$option_block .= "<option value=$users>$users</option>";
	}
}
*/
$Admin_notification = "As an Administrator you have all of the user privileges, however, once a user is changed from a student or teacher to administrator you will no longer be able to remove or change their profile.";


HTML_Render_Head();

echo $CSS_Main;

echo $JS_JQuery;

HTML_Render_Body_Start(); ?>

		<p class="em" align="right"><!--Required fields are marked with an asterick (*).--><?php echo getTxt('RequiredFieldsAsterisk');?></p>
		<?php 
			if($msg != 0) 
			{
				echo $msg; 
			}
		?>
      <h1><?php echo getTxt('ChangeUserAuthority');?></h1>
      <p>&nbsp;</p>
      <form method="post" action="do_changeauthority.php">
        <table width="350" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="95" valign="top"><strong><?php echo getTxt('UserName');?></strong></td>
            <td width="205" valign="top"><select name="username" id="username"><option value=""><?php echo getTxt('SelectUsernameEllipisis');?></option><?php echo "$option_block"; ?></select>*</td>
          </tr>
          <tr>
            <td width="95" valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><strong><?php echo getTxt('NewAuthority');?></strong></td>
            <td valign="top"><select name="authority" id="authority">
              <option value=""><?php echo getTxt('SelectLevel');?></option>            
              <option value="admin"><?php echo getTxt('Administrator');?></option>
              <option value="teacher"><?php echo getTxt('Teacher');?></option>
              <option value="student"><?php echo getTxt('Student');?></option></select>*</td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <!--<td valign="top"><input type="submit" name="submit2" value="Change Authority" class="button" style="width: 145px" /></td>-->
            <td valign="top"><input type="submit" name="submit2" value="<?php echo getTxt('ChangeAuthorityButton');?>" class="button" style="width: auto" /></td>
          </tr>
        </table>
  </form>
	<p><br>
	  </p>
	<p><br>
		</p>
	  <p class="em" align="center"><?php echo getTxt('Admin_notification');?></p><?php echo getTxt('msg'); ?></p>
<p>&nbsp;</p>
      <p>&nbsp;</p>
	  <p>&nbsp;</p>
	  <p>&nbsp;</p>
	  <p>&nbsp;</p>
      <p>&nbsp;</p>
    	<?php HTML_Render_Body_End(); ?>