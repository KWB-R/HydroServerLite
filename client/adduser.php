<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
require_once 'authorization_check.php';

//Display the appropriate user authority to add depending on the user's authority
if ($_COOKIE[power] == "admin"){
	//$selection = "<select name=authority id=authority><option value=>Select....</option><option value=admin>Administrator</option><option value=teacher>Teacher</option><option value=student>Student</option></select>";
	$selection = "<select name=authority id=authority><option value=>".$SelectEllipsis."</option><option value=admin>".$Administrator."</option><option value=teacher>".$Teacher."</option><option value=student>".$Student."</option></select>";			
	}
elseif ($_COOKIE[power] == "teacher"){
	//$selection = "<select name=authority id=authority><option value=>Select....</option><option value=teacher>Teacher</option><option value=student>Student</option></select>";
	$selection = "<select name=authority id=authority><option value=>".$SelectEllipsis."</option><option value=teacher>".$Teacher."</option><option value=student>".$Student."</option></select>";
	}
elseif ($_COOKIE[power] == "student"){
	header("Location: index.php?state=pass2");
	exit;	
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<title>HydroServer Lite Web Client</title>-->
<title><?php echo $WebClient; ?></title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="favicon.ico" >
<link href="styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />
<!-- JQuery JS -->
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>

<script type="text/javascript" src="js/create_username.js"></script>
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
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br /><p class="em" align="right"><!--Required fields are marked with an asterick (*).--><?php echo $RequiredFieldsAsterisk;?></p><?php echo "$msg"; ?></p>
      <!--<h1>Add a New User</h1>-->
      <h1><?php echo $AddNewUser; ?></h1>
      <p>&nbsp;</p>
      <FORM METHOD="POST" ACTION="do_adduser.php" name="newuser">
      <table width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <!--<td width="95" valign="top"><strong>First Name:</strong></td>-->
		  <td width="95" valign="top"><strong><?php echo $FirstName; ?></strong></td>
          <td width="157" valign="top"><input type="text" id="firstname" name="firstname" size=25 maxlength=50 onBlur="GetFirstLetter()"/></td>
          <td width="348" valign="top">*&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td width="157" valign="top">&nbsp;</td>
          <td width="348" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <!--<td width="95" valign="top"><strong>Last Name:</strong></td>-->
		  <td width="95" valign="top"><strong><?php echo $LastName; ?></strong></td>
          <td valign="top"><input type="text" id="lastname" name="lastname" size=25 maxlength=50 onBlur="GetLastName()"/></td>
          <td valign="top">*&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <!--<td width="95" valign="top"><strong>Username:</strong></td>-->
		  <td width="95" valign="top"><strong><?php echo $UserName; ?></strong></td>
          <td valign="top"><input type="text" id="username" name="username" size=25 maxlength=25 />
          <div class="em"></div></td>
          <!--<td valign="top"><span class="em">*&nbsp;(First initial and last name; ex: &quot;jdoe&quot; for John Doe)</span></td>-->
		  <td valign="top"><span class="em">*&nbsp;<?php echo $FirstLastNameExample; ?></span></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <!--<td width="95" valign="top"><strong>Password:</strong></td>-->
		  <td width="95" valign="top"><strong><?php echo $Password; ?></strong></td>
          <td valign="top"><input type="text" name="password" size=25 maxlength=25 /><div class="em"></div></td>
          <!--<td valign="top"><span class="em">*&nbsp;(Case sensitive; enter 6-8 characters)</span></td>-->
          <td valign="top"><span class="em">*&nbsp; <?php echo $CaseSensitive; ?></span></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <!--<td width="95" valign="top"><strong>Authority:</strong></td>-->
		  <td width="95" valign="top"><strong><?php echo $Authority; ?> </strong></td>
          <td valign="top"><?php echo "$selection"; ?>*</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td width="95" valign="top">&nbsp;</td>
          <!--<td valign="top"><input type="SUBMIT" name="submit" value="Add User" class="button" /></td>-->
          <td valign="top"><input type="SUBMIT" name="submit" value="<?php echo $AddUser;?>" class="button"/></td>
          <td valign="top">&nbsp;</td>
        </tr>
      </table></FORM>

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