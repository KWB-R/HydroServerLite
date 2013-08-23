<?php
//check for required fields
if (!isset($_POST['firstname'])){
	echo "Firstname is missing!";
	exit;
	}else{
	$firstname = $_POST['firstname'];
	}
	
if (!isset($_POST['lastname'])){
  echo "Lastname is missing!";
  exit;
	}else{
	$lastname = $_POST['lastname'];
	}

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

if (!isset($_POST['authority'])){
  echo "Authority is missing!";
  exit;
	}else{
	$authority = $_POST['authority'];
	}

//check authority to be here
require_once 'authorization_check.php';

//connect to server and select database
require_once 'database_connection.php';

//add the user's data
$sql ="INSERT INTO moss_users(firstname, lastname, username, password, authority) VALUES ('$firstname', '$lastname', '$username', PASSWORD('$password'), '$authority')";

$result = @mysql_query($sql,$connection)or die(mysql_error());

//get a good message for display upon success
echo($result);

?>