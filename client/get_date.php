<?php
//This is required to get the international text strings dictionary
//require_once 'internationalize.php';

//All queries go through a translator. 
require_once 'DBTranslator.php';

// Get parameters from URL
$siteid = $_GET["siteid"];
$varid = $_GET["varid"];
$methodid = $_GET["methodid"];


// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("datesall");
$parnode = $dom->appendChild($node);


// Search the rows in the markers table
$query = sprintf("SELECT BeginDateTime, EndDateTime, SiteName FROM seriescatalog WHERE SiteID='%s' and VariableID='%s'and MethodID='%s'",
  mysql_real_escape_string($siteid),
  mysql_real_escape_string($varid),
  mysql_real_escape_string($methodid));
$result = transQuery($query,0,0);

if (!$result) {
  die("Invalid query: " . mysql_error());
}

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
foreach ($result as $row) {
  $node = $dom->createElement("dates");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("date_from", $row['BeginDateTime']);
  $newnode->setAttribute("date_to", $row['EndDateTime']);
  $newnode->setAttribute("sitename", $row['SiteName']);
 }

//Output the XML DATA to be fed into the google maps api

echo $dom->saveXML();
?>