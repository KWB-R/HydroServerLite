<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';
//check authority to be here
require_once 'authorization_check.php';
//All queries go through a translator. 
require_once 'DBTranslator.php';

//redirect anyone that is not an administrator
if ($_COOKIE[power] !="admin"){
	header("Location: index.php?state=pass2");
	exit;	
	}

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
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br />
		<p class="em" align="right"><!--Required fields are marked with an asterick (*).--><?php echo $RequiredFieldsAsterisk;?></p>
		<?php 
			if($msg != 0) 
			{
				echo $msg; 
			}
		?>
      <h1><!--Change a User's Authority--><?php echo $ChangeUserAuthority;?></h1>
      <p>&nbsp;</p>
      <form method="post" action="do_changeauthority.php">
        <table width="350" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="95" valign="top"><strong><!--Username:--><?php echo $UserName;?></strong></td>
            <td width="205" valign="top"><select name="username" id="username"><option value=""><!--Select a username....--><?php echo $SelectUsernameEllipisis;?></option><?php echo "$option_block"; ?></select>*</td>
          </tr>
          <tr>
            <td width="95" valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><strong><!--New Authority:--><?php echo $NewAuthority;?></strong></td>
            <td valign="top"><select name="authority" id="authority">
              <option value=""><!--Select a level....--><?php echo $SelectLevel;?></option>            
              <option value="admin"><!--Administrator--><?php echo $Administrator;?></option>
              <option value="teacher"><!--Teacher--><?php echo $Teacher;?></option>
              <option value="student"><!--Student--><?php echo $Student;?></option></select>*</td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <!--<td valign="top"><input type="submit" name="submit2" value="Change Authority" class="button" style="width: 145px" /></td>-->
            <td valign="top"><input type="submit" name="submit2" value="<?php echo $ChangeAuthorityButton;?>" class="button" style="width: auto" /></td>
          </tr>
        </table>
  </form>
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