<?php
echo HTML_Render_Head($js_vars);
echo $CSS_Main;
echo $JS_JQuery;
echo $JS_CreateUserName;
 HTML_Render_Body_Start(); ?>
<div class='col-md-9'>
	<?php showMsgs();?>
<br /><p class="em" align="right"><?php echo getTxt('RequiredFieldsAsterisk'); ?></p><?php if(isset($msg)){echo $msg;} ?>
	  <h1><?php echo getTxt('AddNewUser'); ?></h1>
      <p>&nbsp;</p>
      <?php
	  	$attributes = array('class' => 'form-horizontal', 'id' => 'newuser');
		echo form_open('user/doadd', $attributes);
	  ?>
     <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo getTxt('FirstName'); ?></label>
        <div class="col-sm-9">
        <input type="text"  class="form-control" id="firstname" name="firstname" size=25 maxlength=50/><span class="required">*</span>
		</div>             
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo getTxt('LastName'); ?></label>
        <div class="col-sm-9">
     	   <input type="text" class="form-control" id="lastname" name="lastname" size=25 maxlength=50 onBlur="GetLastName()"  /><span class="required">*</span>
		</div>             
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo getTxt('UserName'); ?></label>
        <div class="col-sm-9">
     	   <input type="text" class="form-control" id="username" name="username" /><span id="user-result"></span><span class="required">*</span>
           <span class="help-block"><br/><?php getTxt('FirstLastNameExample');?></span>
		</div>             
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo getTxt('Password'); ?></label>
        <div class="col-sm-9">
     	   <input type="text" class="form-control" name="password" maxlength=25 /><span class="required">*</span>
           <span class="help-block"><br/><?php getTxt('CaseSensitive');?></span>
		</div>             
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo getTxt('Authority'); ?></label>
        <div class="col-sm-9">
     	   <?php echo $selection; ?><span class="required">*</span>
		</div>             
      </div>
       <div class="col-md-5 col-md-offset-5">
       <input type="SUBMIT" name="submit" value="<?php echo getTxt('AddUser');?>" class="button"/>
       <input type="reset" name="Reset" value="<?php echo getTxt('Cancel'); ?>" class="button" style="width: auto" />
       </div>
       <div id="checkStatus" hidden="true"></div>
       </FORM>
    </div>
<?php HTML_Render_Body_End(); ?>  
<script type= "text/javascript">
$(document).ready(function(){
$("#username").blur(function (e){
	var username = $("#username").val();
    $.post(base_url+"user/checkUname",{ username :username}, function(data){
    $("#user-result").html(data);
	if(data == '<img src="images/not-available.png" />'){
		$("#checkStatus").html(1);
	}
	else
	{
		$("#checkStatus").html(0);
	}
    });
});
$("#lastname").blur(function (e){
	var username = $("#username").val();
    $.post(base_url+"user/checkUname",{ username :username}, function(data){
    $("#user-result").html(data);
	if(data == '<img src="images/not-available.png" />'){
	$("#checkStatus").html(1);
	}
	else
	{
		$("#checkStatus").html(0);
	}
    });
});
$("form").submit(function(e){ //NEED TO FIX TO ACCESS ONLY THE MAIN FORM.

if(($("#firstname").val())==""){
		alert("Please enter your First Name");
		return false;
	}
if(($("#lastname").val())==""){
		alert("Please enter your Last Name");
		return false;
	}
if(($("#username").val())==""){
		alert("Please enter a Username");
		return false;
	}

if(($("#password").val())==""){
		alert("Please enter a Password");
		return false;
	} 
if(($("#authority").val())==""){
		alert("Please Select an Authority");
		return false;
	}

if($("#checkStatus").html()==1)
{
	alert("Username already exists. Please choose a different one.");
	return false;	
}

});
});
</script>