<?php

include "citeConfiger.php";

$string = '<?php 

/*
Default Configuration file for Landing Page for World Water
Edit at your own risk
This file provides config file for the landing page. 
Developed by : Rohit Khattar @ BYU Grad Lab Provo Utah

This file will be populated while deployment
*/

//UserName Password settings

$UserNameLP = "'.$_POST['username'].'";  //Username for the LP Manager
$PasswordLP = "'.$_POST['password'].'";  //Password for the LP Manager

//LP Stuff

$link = "../main/index.php"; //file link to the database main page
$title = "'.addslashes($_POST['databasetitle']).'"; //title for the database
$group = "'.addslashes($_POST['groupname']).'"; //name of the group collecting/managing the data
$description = "'.addslashes($_POST['description']).'"; //description of the data contained in the database

';

//Create the landing page file

$fp = fopen("LpConfig.php", "w") or die("can't open file");
fwrite($fp, $string);
fclose($fp);
echo(1);
?>