<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| HydroServer Lite Configuration\
|--------------------------------------------------------------------------
|
| This file is dynamically populated during installation. 
| It provides configuration for the database and default options for some pages
| Developed by Rohit Khattar, GIS LAB - CAES at ISU
| Further edits made while at GIS Lab - BYU, Provo, Utah
*/
/*
|--------------------------------------------------------------------------
| MySQL connection settings
|--------------------------------------------------------------------------
*/
$config['database_host']	= 'localhost';
$config['database_username']	= 'root';
$config['database_name']	= 'test122';
$config['database_password']	= '';
/*
|--------------------------------------------------------------------------
| Default Variables for Adding Site Controller
|--------------------------------------------------------------------------
*/
$config['default_datum']	= 'MSL';
$config['default_spatial']	= 'WGS1984';
$config['default_source']	= 'Source1';
$config['LocalX']	= '0';
$config['LocalY']	= '0';
$config['LocalProjectionID']	= '0';
$config['PosAccuracy_m']	= '0';
/*
|--------------------------------------------------------------------------
| Default Variables for Adding Data Values
|--------------------------------------------------------------------------
*/
$config['UTCOffset']	= '2';
$config['CensorCode']	= 'nc';
$config['QualityControlLevelID']	= '0';
$config['ValueAccuracy']	= 'NULL';
$config['OffsetValue']	= 'NULL';
$config['OffsetTypeID']	= 'NULL';
$config['QualifierID']	= '1';
$config['SampleID']	= 'NULL';
$config['DerivedFromID']	= 'NULL';
/*
|--------------------------------------------------------------------------
| Default Variables for Adding Variable
|--------------------------------------------------------------------------
*/
$config['default_varcode']	= '1';
/*
|--------------------------------------------------------------------------
| Default Variables for Adding Source
|--------------------------------------------------------------------------
*/
$config['ProfileVersion']	= 'ISO 15195';
/*
|--------------------------------------------------------------------------
| Configuration for Names and home links
|--------------------------------------------------------------------------
*/
$config['homename']	= 'HomeName'; //Name of your blog/Website homepage.
$config['homelink']	= 'http://www.google.com';//Link of your blog/Website homepage
$config['orgname']	= 'SuperCoolPeople'; //Name of your organization
$config['HSLversion']	= '3.0'; //Name of your software version
/*
|--------------------------------------------------------------------------
| Default Language Settings
|--------------------------------------------------------------------------
*/
$config['lang']	= 'English';

/* End of file hsl.php */
/* Location: ./application/config/hsl.php */
