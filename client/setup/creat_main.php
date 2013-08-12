<?php

$string = '<?php 

/*
Default Configuration file for Hydroserver-WebClient-PHP
Edit at your own risk
This file provides configuration for the database, for the default options on various pages.
Developed by : GIS LAB - CAES - ISU

This file will be populated while deployment
*/

//MySql Database Configuration Settings

if (!defined(\'DATABASE_HOST\'))
{
define("DATABASE_HOST", "'.$_POST['databasehost'].'"); //for example define("DATABASE_HOST", "your_database_host");
define("DATABASE_USERNAME", "'.$_POST['databaseusername'].'"); //for example define("DATABASE_USERNAME", "your_database_username");
define("DATABASE_NAME", "'.$_POST['databasename'].'");  //for example define("DATABASE_NAME", "your_database_name");
define("DATABASE_PASSWORD", "'.$_POST['databasepassword'].'"); //for example define("DATABASE_PASSWORD", "your_database_password");
}

$lang="'.$_POST['lang'].'";

//Cookie Settings - This is for Security!
$www = "'.$_POST['domain'].'"; // Please change this to your websites domain name. You may also use "localhost" for testing purposes on a local server.

//Default Variables for add_site.php
$default_datum="'.$_POST['vdatum'].'";
$default_spatial="'.$_POST['spatialref'].'";
$default_source="'.$_POST['source'].'";

//Establish default values for MOSS data variables when adding a data value to a site(add_data_value.php)
$UTCOffset = "'.$_POST['utcoffset1'].'"; 
$UTCOffset2 = "'.$_POST['utcoffset2'].'"; // Actually it is -7
$CensorCode ="'.$_POST['censorcode'].'";
$QualityControlLevelID = "'.$_POST['qcl'].'";
$ValueAccuracy ="'.$_POST['valueacc'].'"; 
$OffsetValue ="NULL";
$OffsetTypeID ="'.$_POST['offsettype'].'";
$QualifierID ="'.$_POST['qualifier'].'";
$SampleID ="'.$_POST['sampleid'].'";
$DerivedFromID ="'.$_POST['derived'].'";

//Establish default values for new MOSS site when adding a new site to the database (add_site.php)
$LocalX ="'.$_POST['localx'].'";
$LocalY ="'.$_POST['localy'].'";
$LocalProjectionID ="'.$_POST['localpid'].'";
$PosAccuracy_m ="'.$_POST['posaccuracy'].'";

//Establish default values for Variable Code when adding a new variable (add_variable.php)
$default_varcode="'.$_POST['varcode'].'"; //for example, for MOSS, it is IDCS- or IDCS-(somethinghere)-Avg


//Establish default values for source info when adding a new source to the database (add_source.php)
$ProfileVersion = "'.$_POST['profilev'].'"; 

//Name of your blog/Website homepage..(This affects the "Back to home button"
$homename="'.$_POST['parentname'].'";

//Link of your blog/Website homepage..(This affects the "Back to home button"
$homelink="'.$_POST['parentweb'].'";

//Name of your organization
$orgname="'.$_POST['orgname'].'";

//Name of your software version
$HSLversion="'.$_POST['sversion'].'";';



$fp = fopen("../main_config.php", "w") or die("can't open file");

fwrite($fp, $string);

fclose($fp);

//Create the admin username and password. 

$connect = mysql_connect($_POST['databasehost'], $_POST['databaseusername'], $_POST['databasepassword'])
    or die("Error connecting to database: " . 
	       mysql_error() . "");
  
  $bool = mysql_select_db($_POST['databasename'],$connect)
    or die("Error selecting the database " . $_POST['databasename'] .
	  mysql_error() . "");
	  
sleep(1);

$sql ="INSERT INTO `moss_users`(`firstname`, `lastname`, `username`, `password`, `authority`) VALUES ('admin', 'admin', 'his_admin', PASSWORD('".$_POST['password']."'), 'admin')";

$result = @mysql_query($sql,$connect)or die(mysql_error());

echo($result);

?>

