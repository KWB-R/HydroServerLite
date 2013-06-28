<?php

$connect = mysql_connect($_POST['databasehost'], $_POST['databaseusername'], $_POST['databasepassword'])
    or die("Error connecting to database: " . 
	       mysql_error() . "");
  
  $bool = mysql_select_db($_POST['databasename'],$connect)
    or die("Error selecting the database " . $_POST['databasename'] .
	  mysql_error() . "");

echo(1);