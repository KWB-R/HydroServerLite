<?php
HTML_Render_Head($js_vars,getTxt('ChangePassword'));
echo $CSS_Main;
echo $JS_JQuery;
HTML_Render_Body_Start(); 
genHeading('ChangeYourPassword',true);
$attributes = array('class' => 'form-horizontal', 'name' => 'change_yourpassword');
echo form_open('user/changeownpass', $attributes);
genInput('NewPassword','password1','password1',true);
genInput('NewPassword1','password','password',true);
genSubmit('ChangePassword');
HTML_Render_Body_End();?>
<script type= "text/javascript">
$(document).ready(function(){
	$("form").submit(function(e){
		if(($("#password1").val())==""){
		alert("Please enter a Password");
		return false;
		}
		if(($("#password").val())==""){
		alert("Please confirm your Password");
		return false;
		}
		var pass1 = document.getElementById('password'); 
		var pass2 = document.getElementById('password1')
		if(pass1.value !== pass2.value){
		alert ("The passwords don't match please try again.");
		return false;
		}
});
});
</script>