<?php
//check authority to be here
require_once 'authorization_check.php';
//All queries go through a translator. 
require_once 'DBTranslator.php';

$SiteID = $_GET['SiteID'];

//Delete the SiteID from the sites table
$sql_del_site ="DELETE FROM sites WHERE SiteID='$SiteID'";
$result_del_site = transQuery($sql_del_site,0,-1);

//DElete the Site ID from seriescatalog too
$sql_delete_site ="DELETE FROM seriescatalog WHERE SiteID='$SiteID'";
$result_delete_site =transQuery($sql_delete_site,0,-1);

echo ($result_del_site);

?>