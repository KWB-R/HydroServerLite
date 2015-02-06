<?php
HTML_Render_Head($js_vars,getTxt('ChangePassword'));
echo $CSS_Main;
echo $JS_JQuery;
HTML_Render_Body_Start();
genHeading('ChangeUserPassword',true);
$attributes = array('class' => 'form-horizontal');
echo form_open('user/changepass', $attributes);
genSelect('UserName',"username","username",$option_block,'SelectUsernameEllipisis',true);
genInput('NewPassword','password','password',true);
genSubmit('ChangePassword');
HTML_Render_Body_End();?>