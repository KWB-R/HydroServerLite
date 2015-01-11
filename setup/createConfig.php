<?php
    
    include("decipher.php");
    //check if we have access to the install directory. 
    $path="../application/config/installations/";
    $filepath = $path.$_POST['ConfigName'].".php";
    $myfile = fopen($filepath, "w") or die("Unable to open file!");


    $fileContents = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
    $"."config"."['database_host']    = '".$dbhost."';
    $"."config"."['database_username']    = '".$dbUname."';
    $"."config"."['database_name']    = '".$dbName."';
    $"."config"."['database_password']    = '".$dbPassword."';
    /*
    |--------------------------------------------------------------------------
    | Default Variables for Adding Site Controller
    |--------------------------------------------------------------------------
    */
    $"."config"."['default_datum']    = '".addslashes($_POST['vdatum'])."';
    $"."config"."['default_spatial']  = '".addslashes($_POST['spatialref'])."';
    $"."config"."['default_source']   = '".addslashes($_POST['source'])."';
    $"."config"."['LocalX']   = '".addslashes($_POST['localx'])."';
    $"."config"."['LocalY']   = '".addslashes($_POST['localy'])."';
    $"."config"."['LocalProjectionID']    = '".addslashes($_POST['localpid'])."';
    $"."config"."['PosAccuracy_m']    = '".addslashes($_POST['posaccuracy'])."';
    /*
    |--------------------------------------------------------------------------
    | Default Variables for Adding Data Values
    |--------------------------------------------------------------------------
    */
    $"."config"."['UTCOffset']    = '".addslashes($_POST['utcoffset1'])."';
    $"."config"."['CensorCode']   = '".addslashes($_POST['censorcode'])."';
    $"."config"."['QualityControlLevelID']    = '".addslashes($_POST['qcl'])."';
    $"."config"."['ValueAccuracy']    = '".addslashes($_POST['valueacc'])."';
    $"."config"."['OffsetValue']  = 'NULL';
    $"."config"."['OffsetTypeID'] = '".addslashes($_POST['offsettype'])."';
    $"."config"."['QualifierID']  = '".addslashes($_POST['qualifier'])."';
    $"."config"."['SampleID'] = '".addslashes($_POST['sampleid'])."';
    $"."config"."['DerivedFromID']    = '".addslashes($_POST['derived'])."';
    /*
    |--------------------------------------------------------------------------
    | Default Variables for Adding Variable
    |--------------------------------------------------------------------------
    */
    $"."config"."['default_varcode']  = '".addslashes($_POST['varcode'])."';
    $"."config"."['time_support'] = '".addslashes($_POST['timesupport'])."';
    /*
    |--------------------------------------------------------------------------
    | Default Variables for Adding Source
    |--------------------------------------------------------------------------
    */
    $"."config"."['ProfileVersion']   = '".addslashes($_POST['profilev'])."';
    /*
    |--------------------------------------------------------------------------
    | Configuration for Names and home links
    |--------------------------------------------------------------------------
    */
    $"."config"."['homename'] = '".addslashes($_POST['parentname'])."'; //Name of your blog/Website homepage.
    $"."config"."['homelink'] = '".addslashes($_POST['parentweb'])."';//Link of your blog/Website homepage
    $"."config"."['orgname']  = '".addslashes($_POST['orgname'])."'; //Name of your organization
    $"."config"."['HSLversion']   = '3.0'; //Name of your software version
    /*
    |--------------------------------------------------------------------------
    | Default Language Settings
    |--------------------------------------------------------------------------
    */
    $"."config"."['lang'] = '".addslashes($_POST['lang'])."';
    /*
    |--------------------------------------------------------------------------
    | WaterOneFlow Services Settings
    |--------------------------------------------------------------------------
    */
    //Service code Settings
    $"."config"."['auth_token'] = '';
    $"."config"."['service_code'] = '".addslashes($_POST['ConfigName'])."';
    $"."config"."['odm_service'] = 'http://his.cuahsi.org/ODMCV_1_1/ODMCV_1_1.asmx?wsdl';
    /*
    |--------------------------------------------------------------------------
    | Enterprise Settings
    |--------------------------------------------------------------------------
    */
    ";

    if(isset($_POST['multi']))
    {
        $multi='true';
        if($_POST['multi']=="-1")
            $multi='false';
        $fileContents.="$"."config"."['multiInstall'] = ".$multi.";";
    }

    $fileContents.="
    /* End of file ".$_POST['ConfigName'].".php */
    /* Location: ./application/config/installations/".$_POST['ConfigName'].".php */";

    fwrite($myfile, $fileContents);
    fclose($myfile);
    echo "success";
?>