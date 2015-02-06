<?php

//We need to also check if the configname provided isn't already taken. 
$filename = $_POST['ConfigName'].".php";
$path="../application/config/installations/";
$files1 = scandir($path);

if(in_array($filename,$files1))
{
	echo "Error: The Website Path provided is already taken.Please enter a different value.";
	return;
}
include("decipher.php");
$link = mysqli_connect($dbhost,$dbUname,$dbPassword) or die("Error Connecting to the database " . mysqli_error($link)); 

$dbQuery = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".$dbName."'";

$result = $link->query($dbQuery); 

$result = mysqli_fetch_array($result);
$return = array();
if(count($result)<=0)
{
	$dbQuery = "CREATE DATABASE IF NOT EXISTS ".$dbName."";
	$result = $link->query($dbQuery) or die("Error creation of database" . mysqli_error($link));

}
echo "success";
?>