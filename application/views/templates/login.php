<!-- #######################################################
 This file is the Login form. 
	NOTE: This will need to be updated to not use tables in the future.
	#######################################################	--><head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
</head>

<div id="loginHolder">
    <?php
	$attributes = array('name' => 'login', 'id' => 'login');
	echo form_open('auth/login',$attributes);
	?>
	<input type="hidden" name="redirect" value="<?php echo site_url('home'); ?>" />
    <div class="container-fluid">
    <div class= "row">
    <div class="col-sm-12">
    <div class = "form-login">
    <div class="col-sm-9 col-sm-offset-4"><p class="h3"><strong><?php echo getTxt('LoginButton');?></strong></p></div>
	<!--<ul class="login">-->
		<!--<li><label><?php echo getTxt('UserName');?></label><input type="text" id="username" name="username"></li>-->
        <div class="form-group"><div class="col-sm-12"><input type="text" id="username" name="username" class="form-control input-md chat-input" placeholder="<?php echo getTxt('UserName');?>" /></div></div>
        </br>
		<!--<li><label><?php echo getTxt('Password');?></label><input type="password" id="password" name="password"></li>-->
        <div class="form-group"><div class="col-sm-12"><input type="password" id="password" name="password" class="form-control input-md chat-input" placeholder="<?php echo getTxt('Password');?>" /></div></div>
        </br>
        <div class="col-sm-9"><a class="bg-primary" href="#" onclick="showMessage('<?php echo getTxt('ForgotPW');?>','<?php echo getTxt('ForgotPassword1').getTxt('ForgotPassword2').getTxt('ForgotPassword3');?>',$(this).offset(),'error')"><?php echo getTxt('ForgotPW');?></a></div>
        </br>
        <div class="col-sm-9 col-sm-offset-2">
        <input type="submit" name="submit" value="Login" ></input>
        <button onclick="return showLogin(false);"><?php echo getTxt('Cancel');?></button>
        </div>
        
        
  	<!--<li><a href="#" onclick="showMessage('<?php echo getTxt('ForgotPW');?>','<?php echo getTxt('ForgotPassword1').getTxt('ForgotPassword2').getTxt('ForgotPassword3');?>',$(this).offset(),'error')"><?php echo getTxt('ForgotPW');?></a></li>-->
        <!--<li><input type="submit" name="submit" value="Login"></input><button onclick="return showLogin(false);"><?php echo getTxt('Cancel');?></button></li>
    </ul>-->
</form>
</div>
</div>
</div>
</div>



