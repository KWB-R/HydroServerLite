<?php
HTML_Render_Head($js_vars);
echo $CSS_Main;
echo $JS_JQuery;
HTML_Render_Body_Start();
genHeading('ChangeUserAuthority',true); 
$attributes = array('class' => 'form-horizontal');
echo form_open('user/edit', $attributes);
genSelect('UserName',"username","username",$option_block,'SelectUsernameEllipisis',true);
genSelect('NewAuthority',"authority","authority",$selection,'SelectLevel',true);
genSubmit('ChangeAuthorityButton');
HTML_Render_Body_End(); ?>