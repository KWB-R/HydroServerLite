<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
require_once 'authorization_check.php';

//All queries go through a translator. 
require_once 'DBTranslator.php';

//Display the appropriate user authority to add depending on the user's authority
if ($_COOKIE[power] == "admin"){
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
elseif ($_COOKIE[power] == "teacher"){
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
elseif ($_COOKIE[power] == "student"){
	header("Location: unauthorized.php");
	exit;	
	}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<title>HydroServer Lite Web Client</title>-->
<title><?php echo $WebClient; ?></title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="favicon.ico" >
<link href="styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script> 
</head>

<body background="images/bkgrdimage.jpg">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><?php include "topBanner.php" ; ?></td>
  </tr>
  <tr>
    <td colspan="2" align="right" valign="middle" bgcolor="#3c3c3c"><?php require_once 'header.php'; ?></td>
  </tr>
  <tr>
    <td width="240" valign="top" bgcolor="#f2e6d6"><?php echo "$nav"; ?></td>
    <!--<td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br /><p class="em" align="right">Required fields are marked with an asterick (*).</p><?php echo "$msg"; ?>
      <h1>Remove an Existing User</h1>-->
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br /><p class="em" align="right"><?php echo $RequiredFieldsAsterisk; ?></p><?php echo "$msg"; ?>
      <h1><?php echo $RemoveExistingUser; ?></h1>
 
      <p>&nbsp;</p>
      <FORM METHOD="POST" ACTION="do_removeuser.php">
        <table width="300" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <!--<td width="100" valign="top"><strong>Username:</strong></td>
          <td width="200" valign="top"><select name="username" id="username"><option value="">Select a username....</option><?php echo "$option_block"; ?></select>*</td>-->
          <td width="100" valign="top"><strong><?php echo $UserName; ?></strong></td>
          <td width="200" valign="top"><select name="username" id="username"><option value=""><?php echo $SelectUsernameEllipisis; ?></option><?php echo "$option_block"; ?></select>*</td>
        </tr>
        <tr>
          <td width="100" valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <!--<td valign="top"><input type="SUBMIT" name="submit" value="Remove User" class="button" style="width: 110px" /></td>-->
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
    </blockquote>
    <p></p></td>
  </tr>
  <tr>
    <script src="js/footer.js"></script>
  </tr>
</table>
</body>
</html>