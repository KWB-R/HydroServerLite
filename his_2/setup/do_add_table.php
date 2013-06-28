<?php
$host = $_POST['databasehost'];
   $user = $_POST['databaseusername'];
   $pass = $_POST['databasepassword'];
  
$mysqli = new mysqli($host,$user,$pass,$_POST['databasename']);
 
if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}
 
 
$sql = file_get_contents('create_database_tables.sql');
if (!$sql){
	die ('Error opening file');
}
 

mysqli_multi_query($mysqli,$sql);
$mysqli->close();
echo (1);