<?php
//create next increment ValueID in the table
$next_increment ="0";
//All queries go through a translator. 
require_once 'DBTranslator.php';

//Get the auto increment value
$sql ="SHOW TABLE STATUS LIKE 'datavalues'";
$result = transQuery($sql,0,0);
$row = $result[0];
$ValueID = $row['Auto_increment'];
?>