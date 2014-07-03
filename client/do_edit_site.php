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
//All queries go through a translator. 
require_once 'DBTranslator.php';

$sql ="UPDATE `sites` SET `SiteCode`='$sitecode',`SiteName`='$sitename',`Latitude`='$lat',`Longitude`='$lng',`LatLongDatumID`='$llid',`SiteType`='$type',`Elevation_m`='$elev',`VerticalDatum`='$datum',`State`='$state',`County`='$county',`Comments`='$coms' WHERE `SiteID`='$siteid'";
$result = transQuery($sql,1,-1);

$sql4 = "UPDATE `seriescatalog` SET `SiteCode`='$sitecode',`SiteName`='$sitename',`SiteType`='$type' WHERE `SiteID`='$siteid'";
$result4 = transQuery($sql4,1,-1);
echo($result);

?>