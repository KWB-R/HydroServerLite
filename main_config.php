<?php 

/*
Default Configuration file for Hydroserver-WebClient-PHP
Edit at your own risk
This file provides configuration for the database, for the default options on various pages.
Developed by : GIS LAB - CAES - ISU

This file will be populated while deployment
*/

//MySql Database Configuration Settings

define("DATABASE_HOST", "caedmdb.et.byu.edu"); //for example define("DATABASE_HOST", "your_database_host");
define("DATABASE_USERNAME", "wwol"); //for example define("DATABASE_USERNAME", "your_database_username");
define("DATABASE_NAME", "wwol");  //for example define("DATABASE_NAME", "your_database_name");
define("DATABASE_PASSWORD", "flaminghaggis"); //for example define("DATABASE_PASSWORD", "your_database_password");


//Cookie Settings - This is for Security!
$www = "worldwater.byu.edu"; // Please change this to your websites domain name. You may also use "localhost" for testing purposes on a local server.

//Default Variables for add_site.php
$default_datum="NAVD 88(US)";
$default_spatial="UTM, NAD 83";
$default_source="Brigham Young Studies";

//Establish default values for MOSS data variables when adding a data value to a site(add_data_value.php)
$UTCOffset = "-7"; 
$UTCOffset2 = "7"; // Actually it is -7
$CensorCode ="nc";
$QualityControlLevelID = "0";
$ValueAccuracy ="NULL"; 
$OffsetValue ="NULL";
$OffsetTypeID ="NULL";
$QualifierID ="1";
$SampleID ="NULL";
$DerivedFromID ="NULL";

//Establish default values for new MOSS site when adding a new site to the database (add_site.php)
$LocalX ="444719";
$LocalY ="4455708";
$LocalProjectionID ="12";
$PosAccuracy_m ="5";

//Establish default values for Variable Code when adding a new variable (add_variable.php)
$default_varcode="WWP"; //for example, for MOSS, it is IDCS- or IDCS-(somethinghere)-Avg


//Establish default values for source info when adding a new source to the database (add_source.php)
$ProfileVersion = "BYU"; 

//Name of your blog/Website homepage..(This affects the "Back to home button"
$homename="WWO";

//Link of your blog/Website homepage..(This affects the "Back to home button"
$homelink="http://wwol.groups.et.byu.net/descargar/";

//Name of your organization
$orgname="Brigham Young University";

//Name of your software version
$HSLversion="Versión 2.0";