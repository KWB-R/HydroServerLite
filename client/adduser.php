<?php

//This is required to get the international text strings dictionary
require_once 'internationalize.php';
//check authority to be here
require_once 'authorization_check.php';
//All queries go through a translator. 
require_once 'DBTranslator.php';

//check for required fields
if (isset($_POST['firstname'])) {
	
	if ((!$_POST['firstname']) || (!$_POST['lastname']) || (!$_POST['username']) || (!$_POST['password']) || (!$_POST['authority'])){
		//Check if all variables are defined. 	
		header("Location: adduser.php");
		exit;
	}

	//add the user's data
	$sql ="INSERT INTO moss_users(firstname, lastname, username, password, authority) VALUES ('$_POST[firstname]', '$_POST[lastname]', '$_POST[username]', PASSWORD('$_POST[password]'), '$_POST[authority]')";

	$result = transQuery($sql,0,-1);

//get a good message for display upon success
	if ($result){ 
	$msg = "<p class=em2>".$Congrats." ".$_POST['firstname'].". ".$AddAnother."</p>";
	}
	
}
//Display the appropriate user authority to add depending on the user's authority
if (isAdmin()){
	$selection = "<select  class=\"form-control\" name='authority' id=authority><option value=>".$SelectEllipsis."</option><option value=admin>".$Administrator."</option><option value=teacher>".$Teacher."</option><option value=student>".$Student."</option></select>";	
	}
elseif (isTeacher()){
	$selection = "<select class=\"form-control\" name='authority' id=authority><option value=>".$SelectEllipsis."</option><option value=teacher>".$Teacher."</option><option value=student>".$Student."</option></select>";
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
<div class='col-md-9'>
<br /><p class="em" align="right"><<?php echo $RequiredFieldsAsterisk;?></p><?php if(isset($msg)){echo $msg;} ?>
	  <h1><?php echo $AddNewUser; ?></h1>
      <p>&nbsp;</p>
      <FORM METHOD="POST" id="newuser" class="form-horizontal" ACTION="adduser.php">
     
     <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $FirstName; ?></label>
        <div class="col-sm-9">
        <input type="text"  class="form-control" id="firstname" name="firstname" size=25 maxlength=50 onBlur="GetFirstLetter()" /><span class="required">*</span>
		</div>             
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $LastName; ?></label>
        <div class="col-sm-9">
     	   <input type="text" class="form-control" id="lastname" name="lastname" size=25 maxlength=50 onBlur="GetLastName()"  /><span class="required">*</span>
		</div>             
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $UserName; ?></label>
        <div class="col-sm-9">
     	   <input type="text" class="form-control" id="username" name="username" /><span class="required">*</span>
           <span class="help-block"><br/><?php echo $FirstLastNameExample;?></span>
		</div>             
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $Password; ?></label>
        <div class="col-sm-9">
     	   <input type="text" class="form-control" name="password" maxlength=25 /><span class="required">*</span>
           <span class="help-block"><br/><?php echo $CaseSensitive;?></span>
		</div>             
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $Authority; ?></label>
        <div class="col-sm-9">
     	   <?php echo "$selection"; ?><span class="required">*</span>
		</div>             
      </div>
	 <div class="col-md-2 col-md-offset-10">
       <input type="SUBMIT" name="submit" value="<?php echo $AddUser;?>" class="button"/></div>
       </FORM>
    </div>
	<?php HTML_Render_Body_End(); ?>