<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
require_once 'authorization_check.php';

//redirect anyone that is not an administrator
	if (!isAdmin()){
	header("Location: index.php?state=pass2");
	exit;	
	}

//check for required fields
if ((!$_POST['username']) || (!$_POST['authority'])) {
	header("Location: changeauthority.php");
	exit;
}

//All queries go through a translator. 
require_once 'DBTranslator.php';

//add the user's data
$sql ="UPDATE moss_users SET authority='$_POST[authority]' WHERE username='$_POST[username]'";

$result = transQuery($sql,0,-1);

//get a good message for display upon success
if ($result) {
$msg ="<p class=em2> $CongratulationsChanged. $_POST[username]. $AddAnother</p>";
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
<br /><p class="em" align="right"><?php echo $RequiredFieldsAsterisk; ?></p><?php echo "$msg $msg2"; ?>
	  <h1><?php echo $ChangeUserAuthority; ?></h1>
      <p>&nbsp;</p>
      <form method="post" action="do_changeauthority.php">
        <table width="300" border="0" cellspacing="0" cellpadding="0">
          <tr>
			<td width="95" valign="top"><strong><?php echo $UserName; ?></strong></td>
			<td width="205" valign="top"><select name="username" id="username"><option value=""><?php echo $SelectUsernameEllipisis ?></option><?php echo "$option_block"; ?></select>*</td>
          </tr>
          <tr>
            <td width="95" valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>

			<td valign="top"><strong><?php echo $NewAuthority; ?></strong></td>
            <td valign="top"><select name="authority" id="authority">
              <option value=""><?php $SelectLevel; ?></option>			  
              <option value="admin"><?php echo $Administrator; ?></option>
              <option value="teacher"><?php echo $Teacher; ?></option>
              <option value="student"><?php echo $Student;?></option></select><span class="required">*</span></td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top"><input type="submit" name="submit2" value="<?php echo $ChangeAuthorityButton; ?>" class="button" style="width: auto" /></td>
          </tr>
        </table>
</form>
<p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    
	<?php HTML_Render_Body_End(); ?>
