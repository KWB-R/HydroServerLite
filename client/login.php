<!-- #######################################################
 This file is the Login form. 
	NOTE: This will need to be updated to not use tables in the future.
	#######################################################	-->
	<?php global$__User; ?>
	<div id="loginHolder">
<form method="POST" action="login_handler.php" name="login" id="login">
		<h2>Login</h2>
	<ul class="login">
		<li><label>Username</label><input type="text" id="username" name="username"></li>
		<li><label>Password</label><input type="password" id="password" name="password"></li>
		<li><a href="#" onclick="showMessage('Forgotten Password?','If you have forgotten your password, please contact your direct supervisor and they can reset it for you. Thank You!',$(this).offset(),'error')">Forgot your password?</a></li>
        <li><input type="submit" name="submit" value="Login"></input><button onclick="return showLogin(false);">Cancel</button></li>
    </ul>
</form>
</div>