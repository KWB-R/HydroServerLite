<?php



$host = $_POST['databasehost'];
   $user = $_POST['databaseusername'];
   $pass = $_POST['databasepassword'];
  
$mysqli = new mysqli($host,$user,$pass,$_POST['databasename']);

 
 
if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}
//Sets the char set for the query
  $mysqli->set_charset("utf8");
 
//Change the File name below for setting up in different languages
$sql = file_get_contents($_POST['lang'].'_create_database_tables.sql');
if (!$sql){
	die ('Error opening file');
}

/*

JUST some testing code for error reporting!

$statements = $sql;

if ($mysqli->multi_query($statements)) { 
    $i = 0; 
    do { 
        $i++; 
    } while ($mysqli->next_result()); 
} 
if ($mysqli->errno) { 
    echo "Batch execution prematurely ended on statement $i.\n"; 
    var_dump($statements[$i], $mysqli->error); 
} 
 */
 
 
mysqli_multi_query($mysqli,$sql);

$mysqli->close();

echo 1;

?>