<?php
//All queries go through a translator. 
require_once 'DBTranslator.php';

// Get parameters from URL
$siteid = $_GET["siteid"];

// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("sources");
$parnode = $dom->appendChild($node);
header("Content-type: text/xml");


//Search the Data Table for SourceIDs

$query = sprintf("SELECT DISTINCT SourceID, SiteID FROM seriescatalog WHERE SiteID ='%s'",
  mysql_real_escape_string($siteid));
$result = transQuery($query,0,0);

foreach ($result as $row) {
//Search Details of Each Source ID Returned

$sourceid=$row['SourceID'];
$query1 = sprintf("SELECT SourceID, Organization, SourceLink FROM sources WHERE SourceID ='%s'",
  mysql_real_escape_string($sourceid));
$result1 = transQuery($query1,0,0);

$row1 = $result1[0];

$node = $dom->createElement("source");
$newnode = $parnode->appendChild($node);
$newnode->setAttribute("sourcename", utf8_encode($row1['Organization']));
$newnode->setAttribute("sourcecode", utf8_encode($row1['SourceID']));
$newnode->setAttribute("sourcelink", utf8_encode($row1['SourceLink']));
}

//Output the XML DATA to be fed into the google maps api

echo $dom->saveXML();
mysql_close($connect);
?>