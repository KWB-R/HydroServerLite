<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//value given from the page
$q=$_GET["q"];

//All queries go through a translator. 
require_once 'DBTranslator.php';

//filter the Site results after Source is selected 

$sql2 ="SELECT DISTINCT SiteID, SiteName FROM seriescatalog WHERE SourceID='".$q."' ORDER BY SiteName ASC";

$result2 = transQuery($sql2,0,0);

	if (count($result2) < 1) {
		echo "<span class='em'>".$NoSitesSource."</span>";
	} else {
	$option_block2 = "<select name='SiteID' id='SiteID' onChange='findSite()'><option value='-1'>".$SelectEllipsis."</option>";
	foreach ($result2 as $row2) {

		$siteid = $row2["SiteID"];
		$sitename = $row2["SiteName"];

		$option_block2 .= "<option value='".$siteid."'>".$sitename."</option>";

		}
	}
$option_block2 .= "</select>&nbsp;<a href='#' onClick='show_answer()' border='0'><img src='images/questionmark.png' border='0'></a>";
echo $option_block2;
mysql_close($connection);
?>
