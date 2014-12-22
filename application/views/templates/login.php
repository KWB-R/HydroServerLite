<!-- #######################################################
 This file is the Login form. 
	NOTE: This will need to be updated to not use tables in the future.
	#######################################################	-->
<div id="loginHolder">
    <?php
	$attributes = array('name' => 'login', 'id' => 'login');
	echo form_open('auth/login',$attributes);
	?>
	<input type="hidden" name="redirect" value="<?php echo current_url(); ?>" />
	<h2><?php echo getText('Login');?></h2>
	<ul class="login">
		<li><label><?php echo getText('UserName');?></label><input type="text" id="username" name="username"></li>
		<li><label><?php echo getText('Password');?></label><input type="password" id="password" name="password"></li>
		<li><a href="#" onclick="showMessage('<?php echo getText('ForgotPW');?>','<?php echo getText('ForgotPassword1').getText('ForgotPassword2').getText('ForgotPassword3');?>',$(this).offset(),'error')"><?php echo getText('ForgotPW');?></a></li>
        <li><input type="submit" name="submit" value="Login"></input><button onclick="return showLogin(false);"><?php echo getText('Cancel');?></button></li>
    </ul>
</form>
</div>
