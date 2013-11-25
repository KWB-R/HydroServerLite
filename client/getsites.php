<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//value given from the page
$q=$_GET["q"];

//connect to server and select database
require_once 'database_connection.php';

//filter the Site results after Source is selected
$sql2 ="SELECT DISTINCT SiteID, SiteName FROM seriescatalog WHERE SourceID='".$q."' ORDER BY SiteName ASC";

$result2 = @mysql_query($sql2,$connection)or die(mysql_error());

$num = @mysql_num_rows($result2);
	if ($num < 1) {

    //echo "<span class='em'>No Sites for this Source.</span>";
	 echo "<span class='em'>". $NoSitesSource ."</span>";

	} else {
//$option_block2 = "<select name='SiteID' id='SiteID' onChange='showTypes(this.value)'><option value='-1'>Select....</option>";
$option_block2 = "<select name='SiteID' id='SiteID' onChange='showTypes(this.value)'><option value='-1'>".$SelectEllipsis."</option>";
	while ($row2 = mysql_fetch_array ($result2)) {

		$siteid = $row2["SiteID"];
		$sitename = utf8_encode($row2["SiteName"]);

		$option_block2 .= "<option value='".$siteid."'>".$sitename."</option>";

		}
	}
$option_block2 .= "</select>*&nbsp;<a href='#' onClick='show_answer()' border='0'><img src='images/questionmark.png' border='0'></a>";
echo $option_block2;
mysql_close($connection);
?>
