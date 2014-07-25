<?php
/* #######################################################
This file handles the session object. THis file needs to
be called as soon as possible because the session start
must be called before any output is sent to the client.
####################################################### */
if (!isset($_SESSION)){
	// always start the session before doing anything else.
	// Session was started successfully.	
	session_start();
}
function isLoggedIn(){
	return isset($_SESSION['power']);
}

function getRequestedPage(){
	$requestedScript = $_SERVER['SCRIPT_NAME'];
	$lastSlashIndex = strripos($requestedScript,"/");
	return substr($requestedScript,$lastSlashIndex, strlen($requestedScript) - $lastSlashIndex);
}
function isPostBack(){
	return ($_SERVER["REQUEST_METHOD"] == "POST");	
}
$AnonymousPages = array("/home.php","/index.php","/view_main.php","/login_handler.php","/help.php");

if (!isLoggedIn()){
	// home.php handles login. This is probably not the best. It should he handled by a login page.
	$requestedPage = getRequestedPage();
	if(!in_array($requestedPage,$AnonymousPages)){ // ignore initial / anonymouse page requests.
		header("Location: index.php?state=pass2");
		exit;	
	}
}


function addError($mess){
	$errors = isset($_SESSION["Errors"])?$_SESSION["Errors"]: array();
	$errors[] = $mess;
	$_SESSION["Errors"] = $errors;
}

function addWarning($mess){
	$warnings = isset($_SESSION["Warnings"])?$_SESSION["Warnings"]: array();
	$warnings[] = $mess;
	$_SESSION["Warnings"] = $warnings;
}

function addSuccess($mess){
	$successes = isset($_SESSION["Successes"])?$_SESSION["Successes"]: array();
	$successes[] = $mess;
	$_SESSION["Successes"] = $successes;
}
