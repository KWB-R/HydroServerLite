<?php
//check for required fields
if (!isset($_POST['username'])){
	echo "Username is missing!";
	exit;
	}else{
	$username = $_POST['username'];
	}

//check authority to be here
require_once 'authorization_check.php';

//connect to server and select database
require_once 'database_connection.php';

//add the user's data
$sql ="DELETE FROM moss_users WHERE username='$username'";

$result = @mysql_query($sql,$connection)or die(mysql_error());

//get a good message for display upon success
echo($result);

?>