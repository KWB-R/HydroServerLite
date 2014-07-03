<?php



require_once 'fetchMainConfig.php';

//create next increment SiteID in the table
$next_increment ="0";

//All queries go through a translator. 
require_once 'DBTranslator.php';

//add the SourceID's

$sql ="SHOW TABLE STATUS LIKE 'sites'";

$result = transQuery($sql,0,0);

$row =$result[0];

$SiteID = $row['Auto_increment'];

?>