<?php
HTML_Render_Head($js_vars,getTxt('RemoveUser'));
echo $CSS_Main;
echo $JS_JQuery;
HTML_Render_Body_Start(); 
genHeading('RemoveExistingUser',true);
$attributes = array('class' => 'form-horizontal');
echo form_open('user/delete', $attributes);
genSelect('UserName',"username","username",$option_block,'SelectUsernameEllipisis',true);
genSubmit('RemoveUser');
HTML_Render_Body_End();	
?>
