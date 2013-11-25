<?php

if (!isset($_POST['Organization'])){
	//echo "Organization is missing!";
	echo $MissOrganization;
	exit;
	}else{
	$Organization = $_POST['Organization'];
	}
	
if (!isset($_POST['SourceDescription'])){
  //echo "SourceDescription is missing!";
  echo $MissSourceDescription;
  exit;
	}else{
	$SourceDescription = $_POST['SourceDescription'];
	}

$SourceL = $_POST["SourceLink"];
if ($SourceL ==''){
	$SourceL = 'NULL';
	}else{
	$SourceL = $_POST['SourceLink'];
	}
	
if (!isset($_POST['ContactName'])){
	//echo "ContactName is missing!";
	echo $MissContactName;

	exit;
	}else{
	$ContactName = $_POST['ContactName'];
	}
	
if (!isset($_POST['Phone'])){
	//echo "Phone is missing!";
	echo $MissPhone;
	exit;
	}else{
	$Phone = $_POST['Phone'];
	}
	
if (!isset($_POST['Email'])){
	//echo "Email is missing!";
	echo $MissEmail;
	exit;
	}else{
	$Email = $_POST['Email'];
	}
	
if (!isset($_POST['Address'])){
	//echo "Address is missing!";
	echo $MissAddress;
	exit;
	}else{
	$Address = $_POST['Address'];
	}
	
if (!isset($_POST['City'])){
	//echo "City is missing!";
	echo $MissCity;
	exit;
	}else{
	$City = $_POST['City'];
	}
	
if (!isset($_POST['state'])){
	//echo "State is missing!";
	echo $MissState;
	exit;
	}else{
	$State = $_POST['state'];
	}
	
if (!isset($_POST['ZipCode'])){
	//echo "Zip Code is missing!";
	echo $MissZip;
	exit;
	}else{
	$ZipCode = $_POST['ZipCode'];
	}
if (!isset($_POST['SourceLink'])){
	//echo "SourceLink is missing!";
	echo $MissSourceLink;
	exit;
	}else{
	$SourceLink = $_POST['SourceLink'];
	}
	
if (!isset($_POST['Citation'])){
	//echo "Citation is missing!";
	echo $MissCitation;
	exit;
	}else{
	$Citation = $_POST['Citation'];
	}
	
//get hidden default values
require_once 'source_hidden_values.php';

//connect to server and select database
require_once 'database_connection.php';

//Create a new Metadata
if (!isset($_POST['TopicCategory'])){
	//echo "Topic Category is missing!";
	echo $MissTopicCategory;
	exit;
	}

if (!isset($_POST['Title'])){
	//echo "Title is missing!";
	echo $MissTitle;
	exit;
	}
	
if (!isset($_POST['Abstract'])){
	//echo "Abstract is missing!";
	echo $MissAbstract;
	exit;
	}

$TopicCategory = $_POST['TopicCategory'];
$Title = $_POST['Title'];
$Abstract = $_POST['Abstract'];
	
$MetadataLink = $_POST['MetadataLink'];	
	if($MetadataLink ==''){
		$MetadataLink = 'NULL';
	}
	
	$MetadataID = '';

	//Get the next MetadataID # available in the table
	$next_increment ="0";

//Enter the provided data into the ISO Metadata table
$sql2 ="INSERT INTO  `isometadata`(`TopicCategory`, `Title`, `Abstract`, `ProfileVersion`, `MetadataLink`) VALUES ('$TopicCategory', '$Title', '$Abstract', '$ProfileVersion', '$MetadataLink')";

$result2 = @mysql_query($sql2,$connection)or die(mysql_error());

//Now get the # of the MetadataID, so we can add it to the Source info when it is posted
$sql3 ="SELECT `MetadataID` FROM `isometadata` WHERE `MetadataLink`='$MetadataLink' and `ProfileVersion`='$ProfileVersion' and `Abstract`='$Abstract' and `Title`='$Title' and `TopicCategory`='$TopicCategory'";

$result3 = @mysql_query($sql3,$connection)or die(mysql_error());

$row3 = mysql_fetch_array($result3, MYSQL_ASSOC);

	$MetadataID = $row3['MetadataID'];


//Enter the provided data into the Sources table
$sql3 ="INSERT INTO `sources`(`SourceID`, `Organization`, `SourceDescription`, `SourceLink`, `ContactName`, `Phone`, `Email`, `Address`, `City`, `State`, `ZipCode`, `Citation`, `MetadataID`) VALUES ('$SourceID', '$Organization', '$SourceDescription', '$SourceLink', '$ContactName', '$Phone', '$Email', '$Address', '$City', '$State', '$ZipCode', '$Citation', '$MetadataID')";

$result3 = @mysql_query($sql3,$connection)or die(mysql_error());

echo($result3);

?>
