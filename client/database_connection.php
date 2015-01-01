<?php
require_once 'fetchMainConfig.php';
require_once 'objects/objects.php';
  
  $connection = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD,DATABASE_NAME)
    or die("<p>Error connecting to database: " . 
	       mysqli_error() . "</p>");
mysqli_set_charset ($connection,"utf8");
  //echo "<p>Connected to MySQL!</p>";
  /*
  $db = mysql_select_db(DATABASE_NAME,$connection)
    or die("<p>Error selecting the database " . DATABASE_NAME .
	  mysql_error() . "</p>");
	  */
?>