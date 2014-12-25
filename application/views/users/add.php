<?php
echo HTML_Render_Head($js_vars);
echo $CSS_Main;
echo $JS_JQuery;
echo $JS_CreateUserName;
HTML_Render_Body_Start();
genHeading('AddNewUser',true);
$attributes = array('class' => 'form-horizontal', 'id' => 'newuser');
echo form_open('user/doadd', $attributes);
genInput('FirstName','firstname','firstname',true);
genInput('LastName','lastname','lastname',true);
genInput('UserName','password','password',true);
echo '<span id="user-result"></span>
<span class="help-block">'.getTxt('FirstLastNameExample').'</span>';
genInput('Password','password','password',true);
echo '<span class="help-block">'.getTxt('CaseSensitive').'</span>';
genSelect('Authority',"authority","authority",$selection,'SelectLevel',true);
?>
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
	if(data == '<img src="<?php echo getImg('not-available.png')?>" />'){
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
	if(data == '<img src="<?php echo getImg('not-available.png')?>" />'){
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