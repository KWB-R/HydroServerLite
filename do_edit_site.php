<?php
//check authority to be here
require_once 'authorization_check.php';


$siteid=$_GET['siteid'];
$sitecode= $_GET['sc'];
$source= $_GET['source'];
$sitename= $_GET['sn'];
$lat=$_GET['lat'];
$lng=$_GET['lng'];
$llid=$_GET['llid'];
$type=$_GET['type'];
$elev=$_GET['elev'];
$datum=$_GET['datum'];
$state=$_GET['state'];
$county=$_GET['county'];
$coms=$_GET['com'];
require_once 'db_config.php';

$sql ="UPDATE `sites` SET `SiteCode`='$sitecode',`SiteName`='$sitename',`Latitude`='$lat',`Longitude`='$lng',`LatLongDatumID`='$llid',`SiteType`='$type',`Elevation_m`='$elev',`VerticalDatum`='$datum',`State`='$state',`County`='$county',`Comments`='$coms' WHERE `SiteID`='$siteid'";
$result = @mysql_query($sql,$connect)or die(mysql_error());

$sql4 = "UPDATE `seriescatalog` SET `SiteCode`='$sitecode',`SiteName`='$sitename',`SiteType`='$type' WHERE `SiteID`='$siteid'";
$result4 = @mysql_query($sql4,$connect)or die(mysql_error());
echo($result);

?>