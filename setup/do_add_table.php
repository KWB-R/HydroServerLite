<?php
include("decipher.php");

$mysqli = new mysqli($dbhost,$dbUname,$dbPassword,$dbName);

if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}
//Sets the char set for the query
$mysqli->set_charset("utf8");
$tableExists = $mysqli->query("SHOW TABLES LIKE 'moss_users'")->num_rows > 0;
//Only add files. 
if(!$tableExists)
{
	$sql = file_get_contents('en_create_database_tables.sql');
if (!$sql){
	die ('Error opening file');}

/*
JUST some testing code for error reporting! Uncomment this if errors are happening here. 
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
//Since the above command is async, wait until its done. 
do {
    if($result = mysqli_store_result($mysqli)){
        mysqli_free_result($result);
    }
} while(@mysqli_next_result($mysqli)); //Suppress error msg here. 

if(mysqli_error($mysqli)) {
    die(mysqli_error($mysqli));
}
}


//If previous admin account exist. 
$adminExists = $mysqli->query("SELECT * FROM moss_users WHERE username='his_admin'") or die("couldn't query");
$adminExists = $adminExists->num_rows > 0;
//Add the user details to the table.
if(!$adminExists)
{
$sql ="INSERT INTO `moss_users`(`firstname`, `lastname`, `username`, `password`, `authority`) VALUES ('admin', 'admin', 'his_admin', PASSWORD('".$_POST['password1']."'), 'admin')";
$mysqli->query($sql);
}

$mysqli->close();
echo "success";

?>