<?php

$AnonymousPages = array("/home.php","/index.php","/view_main.php","/login_handler.php","/help.php","/details.php");

function fetch_session()
{	
//Going against CI sessions for now. Ease of use and existing code in PHP sessions is the reason. 
if (!isset($_SESSION)){
	// always start the session before doing anything else.
	session_start();
	//nset($_SESSION['power']);
	//$_SESSION['power']="admin";
}
}
function isLoggedIn(){
	fetch_session();
	return isset($_SESSION['user_auth']);
}
function getSessionUser()
{
	fetch_session();
	if(isLoggedIn())
	{
		return $_SESSION['username'];
	}
	else
	{
		return false;	
	}
}

function isAdmin()
{
	fetch_session();
	return isset($_SESSION['user_auth']) && $_SESSION['user_auth'] == 'admin';
}
function isTeacher()
{
	fetch_session();
	return isset($_SESSION['user_auth']) && $_SESSION['user_auth'] == 'teacher';
}
function isStudent()
{
	fetch_session();
	return isset($_SESSION['user_auth']) && $_SESSION['user_auth'] == 'student';
}

function session_clear()
{
	//Only clearing login related stuff. Just in case its being used somewhere else. 
	unset($_SESSION['user_auth']);
	unset($_SESSION['username']);
}

//Putting these here as its just better

function addError($mess){
	fetch_session();
	$errors = isset($_SESSION["Errors"])?$_SESSION["Errors"]: array();
	$errors[] = $mess;
	$_SESSION["Errors"] = $errors;
}

function addWarning($mess){
	fetch_session();
	$warnings = isset($_SESSION["Warnings"])?$_SESSION["Warnings"]: array();
	$warnings[] = $mess;
	$_SESSION["Warnings"] = $warnings;
}

function addSuccess($mess){
	fetch_session();
	$successes = isset($_SESSION["Successes"])?$_SESSION["Successes"]: array();
	$successes[] = $mess;
	$_SESSION["Successes"] = $successes;
}


?>