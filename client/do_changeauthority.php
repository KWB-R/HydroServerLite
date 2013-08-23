<?php
//check authority to be here
require_once 'authorization_check.php';

//redirect anyone that is not an administrator
if ($power1 !="admin"){
	header("Location: index.php?state=pass2");
	exit;	
	}

//check for required fields

if (!isset($_POST['authority'])){
  echo "Authority is missing!";
  exit;
	}else{
	$authority = $_POST['authority'];
	}

if (!isset($_POST['username'])){
  echo "Username is missing!";
  exit;
	}else{
	$username = $_POST['username'];
	}

//connect to server and select database
require_once 'database_connection.php';

//add the user's data
$sql ="UPDATE moss_users SET authority='$authority' WHERE username='$username'";

$result = @mysql_query($sql,$connection)or die(mysql_error());

//get a good message for display upon success
echo($result);

?>