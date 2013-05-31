<?php
# The database configuration is loaded either from
# the main_config.php file in the client directory.
# for example (www.example.com/his_2/client/main_config.php).
# If main_config.php is not found then the file:
# services_config.php is used.
if (file_exists('../client/main_config.php')) {
    require_once '../client/main_config.php';
} else {
    require_once 'services_config.php';
}

$connection = mysql_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD) 
or
die("<p>Error connecting to database: ".mysql_error()."</p>");
$db = mysql_select_db(DATABASE_NAME,$connection) 
or 
die("<p>Error selecting the database ".DATABASE_NAME . mysql_error()."</p>");    
mysql_set_charset('utf8',$connection);