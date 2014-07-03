<?php
//All queries go through a translator. 
require_once 'DBTranslator.php';

// Start XML file, create parent node

$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// Select all the rows in the markers table
$dist=0;
$query = "SELECT * FROM sites WHERE 1";
$result = transQuery($query,0,0);
if (!$result) {
  die('Invalid query: ' . mysql_error());
}

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
foreach ($result as $row) {
	
$query1 = "SELECT * FROM seriescatalog WHERE SiteID=".$row['SiteID']." and VariableID IS NULL";
$result1 = transQuery($query1,0,0);
$rows=count($result1);

$query2 = "SELECT * FROM seriescatalog WHERE SiteID=".$row['SiteID'];
$result2 = transQuery($query2,0,0);
$rows2=count($result2);

	if ((($rows==1)&&($rows==$rows2))||($rows2==0)) {
	}
	else
	{
	 $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("name", $row['SiteName']);
  $newnode->setAttribute("siteid", $row['SiteID']);
   $newnode->setAttribute("sitecode", $row['SiteCode']);
  $newnode->setAttribute("lat", $row['Latitude']);
  $newnode->setAttribute("lng", $row['Longitude']);
  $newnode->setAttribute("sitetype", translateWord($row['SiteType']));
  $newnode->setAttribute("distance", $dist);	
		
	}
	
}

echo $dom->saveXML();
mysql_close($connect);
?>