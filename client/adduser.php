<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
require_once 'authorization_check.php';
require_once "_html_parts.php";

//Display the appropriate user authority to add depending on the user's authority
if (isAdmin()){
	$selection = "<select name=authority id=authority><option value=>".$SelectEllipsis."</option><option value=admin>".$Administrator."</option><option value=teacher>".$Teacher."</option><option value=student>".$Student."</option></select>";			
	}
elseif (isTeacher()){
	$selection = "<select name=authority id=authority><option value=>".$SelectEllipsis."</option><option value=teacher>".$Teacher."</option><option value=student>".$Student."</option></select>";
	}
elseif (isStudent()){
	header("Location: index.php?state=pass2");
	exit;	
	}

echo HTML_Render_Head();

echo $CSS_Main;

echo $JS_JQuery;

echo $JS_CreateUserName;
//#type $__User Alias
 HTML_Render_Body_Start(); ?>
<br /><p class="em" align="right"><?php echo $RequiredFieldsAsterisk;?></p><?php echo "$msg"; ?></p>
      <h1><?php echo $AddNewUser; ?></h1>
      <p>&nbsp;</p>
      <FORM METHOD="POST" ACTION="do_adduser.php" name="newuser">
      <table width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
		  <td width="95" valign="top"><strong><?php echo $FirstName; ?></strong></td>
          <td width="157" valign="top"><input type="text" id="firstname" name="firstname" maxlength="50" onBlur="GetFirstLetter()"/></td>
          <td width="348" valign="top"><span class="required">*</span></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td width="157" valign="top">&nbsp;</td>
          <td width="348" valign="top">&nbsp;</td>
        </tr>
        <tr>
		  <td width="95" valign="top"><strong><?php echo $LastName; ?></strong></td>
          <td valign="top"><input type="text" id="lastname" name="lastname" maxlength="50" onBlur="GetLastName()"/></td>
          <td valign="top"><span class="required">*</span></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
		  <td width="95" valign="top"><strong><?php echo $UserName; ?></strong></td>
          <td valign="top"><input type="text" id="username" name="username" maxlength="25" />
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
    
	<?php HTML_Render_Body_End(); ?>
