<?php 

/*
Default Configuration file for Hydroserver-WebClient-PHP
Edit at your own risk
This file provides configuration for the database, for the default options on various pages.
Developed by : GIS LAB - CAES - ISU

This file will be populated while deployment
*/

//MySql Database Configuration Settings

define("DATABASE_HOST", "localhost"); //for example define("DATABASE_HOST", "your_database_host");
define("DATABASE_USERNAME", "root"); //for example define("DATABASE_USERNAME", "your_database_username");
define("DATABASE_NAME", "idaho");  //for example define("DATABASE_NAME", "your_database_name");
define("DATABASE_PASSWORD", ""); //for example define("DATABASE_PASSWORD", "your_database_password");


//Cookie Settings - This is for Security!
$www = "idah2o-dev.nkn.uidaho.edu"; // Please change this to your websites domain name. You may also use "localhost" for testing purposes on a local server.

//Default Variables for add_site.php
$default_datum="MSL";
$default_spatial="WGS84";
$default_source="IDAH2O";

//Establish default values for MOSS data variables when adding a data value to a site(add_data_value.php)
$UTCOffset = "-8"; 
$UTCOffset2 = "8"; // Actually it is -7
$CensorCode ="nc";
$QualityControlLevelID = "1";
$ValueAccuracy ="NULL"; 
$OffsetValue ="NULL";
$OffsetTypeID ="NULL";
$QualifierID ="1";
$SampleID ="NULL";
$DerivedFromID ="NULL";

//Establish default values for new MOSS site when adding a new site to the database (add_site.php)
$LocalX ="NULL";
$LocalY ="NULL";
$LocalProjectionID ="NULL";
$PosAccuracy_m ="NULL";

//Establish default values for Variable Code when adding a new variable (add_variable.php)
$default_varcode="IDAH2O-"; //for example, for MOSS, it is IDCS- or IDCS-(somethinghere)-Avg


//Establish default values for source info when adding a new source to the database (add_source.php)
$ProfileVersion = "Unknown"; 

//Name of your blog/Website homepage..(This affects the "Back to home button"
$homename="IDAH2O Website";

//Link of your blog/Website homepage..(This affects the "Back to home button"
$homelink="http://idah2o-dev.nkn.uidaho.edu";

//Name of your organization
$orgname="IDAH2O";

//Name of your software version
$version="Version 1.0";