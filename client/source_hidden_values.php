<?php

require 'fetchMainConfig.php';

//create next increment SourceID in the table
$next_increment ="0";

//All queries go through a translator. 
require_once 'DBTranslator.php';

//add the next SourceID's

$sql ="SHOW TABLE STATUS LIKE 'sources'";

$result = transQuery($sql,0,0);

$row = $result[0];

$SourceID = $row['Auto_increment'];

?>