<?php
//check authority to be here
require_once 'authorization_check.php';

//check for required fields
if (!isset($_POST['username'])){
	echo "Username is missing!";
	exit;
	}else{
	$username = $_POST['username'];
	}

if (!isset($_POST['password'])){
	echo "Password is missing!";
	exit;
	}else{
	$password = $_POST['password'];
	}

//connect to server and select database
require_once 'database_connection.php';

//add the user's data
$sql ="UPDATE moss_users SET password=PASSWORD('$password') WHERE username='$username'";

$result = @mysql_query($sql,$connection)or die(mysql_error());

//get a good message for display upon success
echo($result);

?>