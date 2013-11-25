<?php
//check authority to be here
require_once 'authorization_check.php';

$SiteID = $_GET['SiteID'];

//connect to server and select database
require_once 'database_connection.php';

//Delete the SiteID from the sites table

$sql_del_site ="DELETE FROM sites WHERE SiteID='$SiteID'";

$result_del_site = @mysql_query($sql_del_site,$connection)or die(mysql_error());

//DElete the Site ID from seriescatalog too
$sql_delete_site ="DELETE FROM seriescatalog WHERE SiteID='$SiteID'";

$result_delete_site = @mysql_query($sql_delete_site,$connection)or die(mysql_error());


echo ($result_del_site);

?>