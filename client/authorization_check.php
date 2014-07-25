<?php
	/* #######################################################
	This file has been changed to use sessions instead of cookies to control the 
	  login status. Cookies are extremely vulernerable because they are stored
	  on the client machine. Anything stored on a client machine can be altered.
	 ####################################################### */
	
	
require_once("session_handler.php");
//Display the correct navigation or redirect them to the unauthorized user page

function isAdmin(){
	return isset($_SESSION['power']) && $_SESSION['power'] == 'admin';
	
}
function isTeacher(){
	return isset($_SESSION['power']) && $_SESSION['power'] == 'teacher';
}
function isStudent(){
	return isset($_SESSION['power']) && $_SESSION['power'] == 'student';
}


