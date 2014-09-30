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
 	echo "<span class='em'>". $NoSitesSource ."</span>";
	} else {
	// ShowTypes does not appear to be anywhere in the code set. Not sure what this is doing.
		// This appears to be the only reference to this script anywhere.	
$option_block2 = "<select class=\"form-control\" name='SiteID' id='SiteID' onChange='showTypes(this.value)'><option value='-1'>".$SelectEllipsis."</option>";
	foreach ($result2 as $row2) {

		$siteid = $row2["SiteID"];
		$sitename = utf8_encode($row2["SiteName"]);

		$option_block2 .= "<option value='".$siteid."'>".$sitename."</option>";

		}
	}
$option_block2 .= "</select><span class=\"required\">*</span><span class=\"hint\" title=\"If you do not see your site listed here, please contact your supervisor and ask them to add it before entering data.\">?</span>";
echo $option_block2;
mysql_close($connection);
?>
