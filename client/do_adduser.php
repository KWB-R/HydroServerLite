<?php

//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check for required fields
if ((!$_POST['firstname']) || (!$_POST['lastname']) || (!$_POST['username']) || (!$_POST['password']) || (!$_POST['authority'])) {
	header("Location: adduser.php");
	exit;
}

//check authority to be here
require_once 'authorization_check.php';

//All queries go through a translator. 
require_once 'DBTranslator.php';

//add the user's data
$sql ="INSERT INTO moss_users(firstname, lastname, username, password, authority) VALUES ('$_POST[firstname]', '$_POST[lastname]', '$_POST[username]', PASSWORD('$_POST[password]'), '$_POST[authority]')";

$result = transQuery($sql,0,-1);

//get a good message for display upon success
if ($result){ 

$msg = "<p class=em2> $Congrats  $_POST[firstname].  $AddAnother  </p>";
	}


//Display the appropriate user authority to add depending on the user's authority
if (isAdmin()){
	$selection = "<select name=authority id=authority><option value=>".$SelectEllipsis."</option><option value=admin>".$Administrator."</option><option value=teacher>".$Teacher."</option><option value=student>".$Student."</option></select>";	
	}
elseif (isTeacher()){
	$selection = "<select name=authority id=authority><option value=>".$SelectEllipsis."</option><option value=teacher>".$Teacher."</option><option value=student>".$Student."</option></select>";
	}
elseif (isStudent()){
	header("Location: unauthorized.php");
	exit;	
	}

require_once "_html_parts.php";
	HTML_Render_Head();

echo $CSS_Main;

echo $JS_JQuery;

echo $JS_CreateUserName;

 HTML_Render_Body_Start(); ?>

<br /><p class="em" align="right"><<?php echo $RequiredFieldsAsterisk;?></p><?php echo "$msg"; ?>
	  <h1><?php echo $AddNewUser; ?></h1>
      <p>&nbsp;</p>
      <FORM METHOD="POST" ACTION="do_adduser.php">
      <table width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
		  <td width="95" valign="top"><strong><?php echo $FirstName; ?></strong></td>
          <td width="153" valign="top"><input type="text" name="firstname" size=25 maxlength=50 onBlur="GetFirstLetter()" /></td>
          <td width="352" valign="top"><span class="required">*</span></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td width="153" valign="top">&nbsp;</td>
          <td width="352" valign="top">&nbsp;</td>
        </tr>
        <tr>
		  <td width="95" valign="top"><strong><?php echo $LastName; ?></strong></td>
          <td valign="top"><input type="text" name="lastname" size=25 maxlength=50 onBlur="GetLastName()" /></td>
          <td valign="top"><span class="required">*</span></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
		  <td width="95" valign="top"><strong><?php echo $UserName; ?></strong></td>
          <td valign="top"><input type="text" name="username" maxlength=25 />
          <div class="em"></div></td>
		  <td valign="top"><span class="em"><span class="required">*</span><?php echo $FirstLastNameExample; ?></span></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
		  <td width="95" valign="top"><strong><?php echo $Password; ?></strong></td>
          <td valign="top"><input type="text" name="password" maxlength=25 /><div class="em"></div></td>
          <td valign="top"><span class="em"><span class="required">*</span><?php echo $CaseSensitive; ?></span></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
		  <td width="95" valign="top"><strong><?php echo $Authority; ?> </strong></td>
          <td valign="top"><?php echo "$selection"; ?><span class="required">*</span></td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td width="95" valign="top">&nbsp;</td>
          <td valign="top"><input type="SUBMIT" name="submit" value="<?php echo $AddUser;?>" class="button"/></td>
          <td valign="top">&nbsp;</td>
          
        </tr>
      </table></FORM>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    
	<?php HTML_Render_Body_End(); ?>

