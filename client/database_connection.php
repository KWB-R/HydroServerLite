<?php
require_once 'fetchMainConfig.php';
  
  $connection = mysql_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD)
    or die("<p>Error connecting to database: " . 
	       mysql_error() . "</p>");
mysql_set_charset ("utf8");
  //echo "<p>Connected to MySQL!</p>";
  
  $db = mysql_select_db(DATABASE_NAME,$connection)
    or die("<p>Error selecting the database " . DATABASE_NAME .
	  mysql_error() . "</p>");