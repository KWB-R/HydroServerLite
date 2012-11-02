<?php

if (!isset($_POST['Organization'])) {
    echo "Organization is missing!";
    exit;
} else {
    $Organization = $_POST['Organization'];
} if (!isset($_POST['SourceDescription'])) {
    echo "SourceDescription is missing!";
    exit;
} else {
    $SourceDescription = $_POST['SourceDescription'];
}$SourceL = $_POST["SourceLink"];
if ($SourceL == '') {
    $SourceL = 'NULL';
} else {
    $SourceL = $_POST['SourceLink'];
} if (!isset($_POST['ContactName'])) {
    echo "ContactName is missing!";
    exit;
} else {
    $ContactName = $_POST['ContactName'];
} if (!isset($_POST['Phone'])) {
    echo "Phone is missing!";
    exit;
} else {
    $Phone = $_POST['Phone'];
} if (!isset($_POST['Email'])) {
    echo "Email is missing!";
    exit;
} else {
    $Email = $_POST['Email'];
} if (!isset($_POST['Address'])) {
    echo "Address is missing!";
    exit;
} else {
    $Address = $_POST['Address'];
} if (!isset($_POST['City'])) {
    echo "City is missing!";
    exit;
} else {
    $City = $_POST['City'];
} if (!isset($_POST['country'])) {
    echo "country is missing!";
    exit;
} else {
    $country = $_POST['country'];
}if ($country == 'US') {
    if (!isset($_POST['state'])) {
        echo "State is missing!";
        exit;
    } else {
        $State = $_POST['state'];
    }
} else {
    $State = ' ';
} if (!isset($_POST['ZipCode'])) {
    echo "Zip Code is missing!";
    exit;
} else {
    $ZipCode = $_POST['ZipCode'];
}if (!isset($_POST['SourceLink'])) {
    echo "SourceLink is missing!";
    exit;
} else {
    $SourceLink = $_POST['SourceLink'];
} if (!isset($_POST['Citation'])) {
    echo "Citation is missing!";
    exit;
} else {
    $Citation = $_POST['Citation'];
}
//get hidden default values
require_once 'source_hidden_values.php';
require_once 'database_connection.php';
if (!isset($_POST['TopicCategory'])) {
    echo "Topic Category is missing!";
    exit;
}if (!isset($_POST['Title'])) {
    echo "Title is missing!";
    exit;
} if (!isset($_POST['Abstract'])) {
    echo "Abstract is missing!";
    exit;
}$TopicCategory = $_POST['TopicCategory'];
$Title = $_POST['Title'];
$Abstract = $_POST['Abstract'];
$MetadataLink = $_POST['MetadataLink'];
if ($MetadataLink == '') {
    $MetadataLink = 'NULL';
} $MetadataID = '';
$next_increment = "0";
$sql2 = "INSERT INTO  `isometadata`(`TopicCategory`, `Title`, `Abstract`, `ProfileVersion`, `MetadataLink`) VALUES ('$TopicCategory', '$Title', '$Abstract', '$ProfileVersion', '$MetadataLink')";
$result2 = @mysql_query($sql2, $connection) or die(mysql_error());
$sql3 = "SELECT `MetadataID` FROM `isometadata` WHERE `MetadataLink`='$MetadataLink' and `ProfileVersion`='$ProfileVersion' and `Abstract`='$Abstract' and `Title`='$Title' and `TopicCategory`='$TopicCategory'";
$result3 = @mysql_query($sql3, $connection) or die(mysql_error());
$row3 = mysql_fetch_array($result3, MYSQL_ASSOC);
$MetadataID = $row3['MetadataID'];

// add the 'Country' field if required
$result5 = @mysql_query("SHOW COLUMNS FROM sources") or die(mysql_error());
$found_country_column = false;
if ($result5) {
  while($row5 = mysql_fetch_array($result5, MYSQL_ASSOC)){
    if ($row5['Field'] == 'country') {
	   $found_country_column = true;
	}
  }
}
if (!$found_country_column) {
  $result6 = mysql_query('ALTER TABLE `sources` ADD `country` NVARCHAR(64)') or die(mysql_error());
}

$sql3 = "INSERT INTO `sources`(`SourceID`, `Organization`, `SourceDescription`, `SourceLink`, `ContactName`, `Phone`, `Email`, `Address`, `City`, `country`, `State`, `ZipCode`, `Citation`, `MetadataID`) VALUES ('$SourceID', '$Organization', '$SourceDescription', '$SourceLink', '$ContactName', '$Phone', '$Email', '$Address', '$City', '$country' ,'$State', '$ZipCode', '$Citation', '$MetadataID')";
$result3 = @mysql_query($sql3, $connection) or die(mysql_error());
echo($result3);