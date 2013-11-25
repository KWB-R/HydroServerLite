<?php




$connect = mysql_connect($_POST['databasehost'], $_POST['databaseusername'], $_POST['databasepassword'])
    or die("Error connecting to database: " . 
	       mysql_error() . "");

// Make my_db the current database
$db_selected = mysql_select_db($_POST['databasename'], $connect);

if (!$db_selected) {
  // If we couldn't, then it either doesn't exist, or we can't see it.
  $sql = 'CREATE DATABASE '.$_POST['databasename'];

  if (mysql_query($sql, $connect)) {
  } else {
      echo 'Error creating database: ' . mysql_error() . "\n";
  }
}


$bool = mysql_select_db($_POST['databasename'],$connect)
    or die("Error selecting the database " . $_POST['databasename'] .
	  mysql_error() . "");

echo(1);

?>