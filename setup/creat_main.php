<?php

//Check if this a single install or not? If Single then proceed differently
$singleInstall = 0;
if ($_POST['single']=="Yes")
	{
		$singleInstall = 1;
	}

$worldInstall = 0;
if ($_POST['worldwater']=="Yes")
	{
		$worldInstall = 1;
	}

if (!$singleInstall)
{
//Set the Session into the new directory
session_start();
$_SESSION["mainpath"]="..\\".$_POST['dir']."\main_config.php";
$_SESSION["dir"]=$_POST['dir'];
}
function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 



$string = '<?php 

/*
Default Configuration file for Hydroserver-WebClient-PHP
Edit at your own risk
This file provides configuration for the database, for the default options on various pages.
Developed by : GIS LAB - CAES - ISU

Further Edits made at BYU

This file will be populated while deployment
*/

//MySql Database Configuration Settings

define("DATABASE_HOST", "'.$_POST['databasehost'].'"); //for example define("DATABASE_HOST", "your_database_host");
define("DATABASE_USERNAME", "'.$_POST['databaseusername'].'"); //for example define("DATABASE_USERNAME", "your_database_username");
define("DATABASE_NAME", "'.$_POST['databasename'].'");  //for example define("DATABASE_NAME", "your_database_name");
define("DATABASE_PASSWORD", "'.$_POST['databasepassword'].'"); //for example define("DATABASE_PASSWORD", "your_database_password");


//Cookie Settings - This is for Security!
$www = "'.$_POST['domain'].'"; // Please change this to your websites domain name. You may also use "localhost" for testing purposes on a local server.

//Default Variables for add_site.php
$default_datum="'.$_POST['vdatum'].'";
$default_spatial="'.$_POST['spatialref'].'";
$default_source="'.$_POST['source'].'";
$lang="'.$_POST['lang'].'";

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
$HSLversion="'.$_POST['sversion'].'";
';


if (!$singleInstall)
{
	
$string.= '
if(file_exists("../'.$_POST['dir'].'/headerConfig.php"))
{
	include("../'.$_POST['dir'].'/headerConfig.php");
}

//Type of Installation Being Run!
$singleInstall = "No"; //Please Specify either "Yes" or "No". By default this is set to "Yes" unless this is an install on worldwater servers. 
';
}
else
{
	$string.= '
//Type of Installation Being Run!
$singleInstall = "Yes"; //Please Specify either "Yes" or "No". By default this is set to "Yes" unless this is an install on worldwater servers. 
';
}

//Create the main_config file in the mentioned directory


if(!$singleInstall)
{
//first create the dir. 
umask(0000);
mkdir("../../".$_POST['dir'], 0777) or die ("Unable to Create the directory. Contact Ken Clark and bug him until he fixes it!");

$fp = fopen("../../".$_POST['dir']."/main_config.php", "w") or die("can't open file");

fwrite($fp, $string);

fclose($fp);

//Copy the Security gaurd
$file = 'security/index.php';
$newfile = "../../".$_POST['dir']."/index.php";
umask(0000);
if (!copy($file, $newfile)) {
    echo "failed to copy $file...\n";
}


//Copy the new files for the LP Manager
umask(0000);
recurse_copy('lpManager', '../../'.$_POST['dir'].'/manageLP');
}
else
{
	
$fp = fopen("../main_config.php", "w") or die("can't open file");
fwrite($fp, $string);
fclose($fp);
	
}

if ((!$singleInstall)&&($worldInstall))
{
//Run an update for the names.php file. 
umask(0000);
$namesHandler = fopen("../../names.php", "a") or die ("Cant open names.php for editing");

$contentToWrite = "\n<?php echo '<li><a href=\"/interactive/" .$_POST['dir']."\">".$_POST['orgname']."</a></li>';?>";

fwrite($namesHandler, $contentToWrite) or die ("Could not write to the names file");
fclose($namesHandler);

}
//Create the admin username and password. 

$connect = mysql_connect($_POST['databasehost'], $_POST['databaseusername'], $_POST['databasepassword'])
    or die("Error connecting to database: " . 
	       mysql_error() . "");
  
  $bool = mysql_select_db($_POST['databasename'],$connect)
    or die("Error selecting the database " . $_POST['databasename'] .
	  mysql_error() . "");

//While loop checks if the table has been created or not. If created then good else keeps looping. 
while(mysql_num_rows(mysql_query("SHOW TABLES LIKE 'moss_users'"))!=1) 
{
}

$sql ="INSERT INTO `moss_users`(`firstname`, `lastname`, `username`, `password`, `authority`) VALUES ('admin', 'admin', 'his_admin', PASSWORD('".$_POST['password']."'), 'admin')";

$result = @mysql_query($sql,$connect)or die(mysql_error());

echo($result);

?>

