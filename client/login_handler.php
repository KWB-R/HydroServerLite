<?php
	/* #######################################################
	 This file handles all login and logout requests.
	 Anything to do with logging in or out of this site
	  should be done on this page.
	 This page is intended to be a stand alone page,
	  not intended to be included anywhere.
	####################################################### */
	
	require_once 'fetchMainConfig.php';
	require_once "session_handler.php";
	require_once('database_connection.php');
	if (!isset($_SESSION['power'])){
		//Check to see if the person is an authorized user and display their first name
		$sql ="SELECT * FROM moss_users WHERE username='$_POST[username]' AND password = password('$_POST[password]')";

		$result = @mysql_query($sql,$connection) or die(mysql_error());

		//get the number of rows in the result set
		$num = mysql_num_rows($result);
		if ($num != 0) {
			//get the person's first name and authority
			while ($row = mysql_fetch_assoc($result)) {
				$firstname = $row['firstname'];
				$auth = $row['authority'];
			}
			$_SESSION['username'] =$firstname;
			$_SESSION['power'] =$auth;
			header("Location: home.php");
			exit;
		} else {
			header("Location: index.php?state=pass");
			exit;
		}
	}else{ // Already logged in.
		if (isset( $_GET["logout"])){ 
			session_destroy();
			header("Location: index.php?");
		}
	}
	
?>