<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';
require_once "_html_parts.php";

//check authority to be here
require_once 'authorization_check.php';

//All queries go through a translator. 
require_once 'DBTranslator.php';

$option_block="";
//Display the appropriate user authority to add depending on the user's authority
if (isAdmin()){
	//select the users
	$sql ="Select username FROM moss_users WHERE (authority='teacher' OR authority='student') ORDER BY username";
	$result = transQuery($sql,0,0);
	if (count($result) < 1) {
		$msg = "<P><em2> $SorryNoUsers </em></p>";
	} else {
	foreach ($result as $row) {
		$users = $row["username"];
		$option_block .= "<option value=$users>$users</option>";
		}
	}
}
elseif (isTeacher()){
	//select the users
	$sql ="SELECT username FROM moss_users WHERE authority LIKE 'student' ORDER BY username";
	$result = transQuery($sql,0,0);
	if (count($result) < 1) {
		$msg = "<P><em2>" + $SorryNoUsers + "</em></p>";
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


	HTML_Render_Head();
	
	echo $CSS_Main;
	
	echo $JS_JQuery;
	
	HTML_Render_Body_Start(); ?>

<br /><p class="em" align="right"><?php echo $RequiredFieldsAsterisk; ?></p><?php echo "$msg"; ?>
      <h1><?php echo $RemoveExistingUser; ?></h1>
 
      <p>&nbsp;</p>
      <FORM METHOD="POST" ACTION="do_removeuser.php">
        <table width="300" border="0" cellspacing="0" cellpadding="0">
        <tr>
           <td width="100" valign="top"><strong><?php echo $UserName; ?></strong></td>
          <td width="200" valign="top"><select name="username" id="username"><option value=""><?php echo $SelectUsernameEllipisis; ?></option><?php echo "$option_block"; ?></select><span class="required">*</span></td>
        </tr>
        <tr>
          <td width="100" valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top"><input type="SUBMIT" name="submit" value="<?php echo $RemoveUser;?>" class="button" style="width: auto" /></td>

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
    
	<?php HTML_Render_Body_End(); ?>
