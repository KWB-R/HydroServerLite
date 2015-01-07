<?php 

/*
Default Configuration file for Hydroserver-WebClient-PHP
Edit at your own risk
This file provides configuration for the database, for the default options on various pages.
Developed by : GIS LAB - CAES - ISU

This file will be populated while deployment
*/

//MySql Database Configuration Settings

if (!(defined("DATABASE_HOST"))):

define("DATABASE_HOST", "localhost"); //for example define("DATABASE_HOST", "your_database_host");
define("DATABASE_USERNAME", "root"); //for example define("DATABASE_USERNAME", "your_database_username");
define("DATABASE_NAME", "english");  //for example define("DATABASE_NAME", "your_database_name");
define("DATABASE_PASSWORD", ""); //for example define("DATABASE_PASSWORD", "your_database_password");
endif;
//Cookie Settings - This is for Security!
$www = "127.0.0.1"; // Please change this to your websites domain name. You may also use "localhost" for testing purposes on a local server.

//Default Variables for add_site.php
$default_datum="MSL";
$default_spatial="WGS1984";
$default_source="Lahden kaupunkilaboratorio";

//Establish default values for MOSS data variables when adding a data value to a site(add_data_value.php)
$UTCOffset = "2"; 
$UTCOffset2 = "-2"; // Actually it is -7
$CensorCode ="nc";
$QualityControlLevelID = "0";
$ValueAccuracy ="NULL"; 
$OffsetValue ="NULL";
$OffsetTypeID ="NULL";
$QualifierID ="1";
$SampleID ="NULL";
$DerivedFromID ="NULL";

//Establish default values for new MOSS site when adding a new site to the database (add_site.php)
$LocalX ="0";
$LocalY ="0";
$LocalProjectionID ="0";
$PosAccuracy_m ="0";

//Establish default values for Variable Code when adding a new variable (add_variable.php)
$default_varcode="1"; //for example, for MOSS, it is IDCS- or IDCS-(somethinghere)-Avg


//Establish default values for source info when adding a new source to the database (add_source.php)
$ProfileVersion = "ISO 15195"; 

//Name of your blog/Website homepage..(This affects the "Back to home button"
$homename="lahti";

//Link of your blog/Website homepage..(This affects the "Back to home button"
$homelink="geoinformatics.aalto.fi";

//Name of your organization
$orgname="AAlto";

//Name of your software version
$HSLversion="Version 2.0";

$lang='en';

?>