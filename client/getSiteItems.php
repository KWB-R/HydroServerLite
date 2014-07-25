<?php
require_once "authorization_check.php";
require_once "objects/objects.php";
require_once "objects/aliases.php";
//connect to server and select database
require_once 'database_connection.php';
require_once "data_access_layer.php";

//value given from the page
$src = new Source();
$srcID=$_GET["srcid"];
$src->SourceID = $srcID;

$returnResult = "";
//#type $site Site
foreach (DAL::Get()->Sites($src) as $site)
{
	$returnResult .= "<option value='".$site->SiteID."'>".$site->SiteName."</option>";
}

if ($returnResult != "")
	echo $returnResult;
else
	echo "No ".$__Site->Plural." found for this ".$__Source->Text."." ;

?>
