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
    $"."config"."['default_datum']    = '".$_POST['vdatum']."';
    $"."config"."['default_spatial']  = '".$_POST['spatialref']."';
    $"."config"."['default_source']   = '".$_POST['source']."';
    $"."config"."['LocalX']   = '".$_POST['localx']."';
    $"."config"."['LocalY']   = '".$_POST['localy']."';
    $"."config"."['LocalProjectionID']    = '".$_POST['localpid']."';
    $"."config"."['PosAccuracy_m']    = '".$_POST['posaccuracy']."';
    /*
    |--------------------------------------------------------------------------
    | Default Variables for Adding Data Values
    |--------------------------------------------------------------------------
    */
    $"."config"."['UTCOffset']    = '".$_POST['utcoffset1']."';
    $"."config"."['CensorCode']   = '".$_POST['censorcode']."';
    $"."config"."['QualityControlLevelID']    = '".$_POST['qcl']."';
    $"."config"."['ValueAccuracy']    = '".$_POST['valueacc']."';
    $"."config"."['OffsetValue']  = 'NULL';
    $"."config"."['OffsetTypeID'] = '".$_POST['offsettype']."';
    $"."config"."['QualifierID']  = '".$_POST['qualifier']."';
    $"."config"."['SampleID'] = '".$_POST['sampleid']."';
    $"."config"."['DerivedFromID']    = '".$_POST['derived']."';
    /*
    |--------------------------------------------------------------------------
    | Default Variables for Adding Variable
    |--------------------------------------------------------------------------
    */
    $"."config"."['default_varcode']  = '".$_POST['varcode']."';
    $"."config"."['time_support'] = '".$_POST['timesupport']."';
    /*
    |--------------------------------------------------------------------------
    | Default Variables for Adding Source
    |--------------------------------------------------------------------------
    */
    $"."config"."['ProfileVersion']   = '".$_POST['profilev']."';
    /*
    |--------------------------------------------------------------------------
    | Configuration for Names and home links
    |--------------------------------------------------------------------------
    */
    $"."config"."['homename'] = '".$_POST['parentname']."'; //Name of your blog/Website homepage.
    $"."config"."['homelink'] = '".$_POST['parentweb']."';//Link of your blog/Website homepage
    $"."config"."['orgname']  = '".$_POST['orgname']."'; //Name of your organization
    $"."config"."['HSLversion']   = '3.0'; //Name of your software version
    /*
    |--------------------------------------------------------------------------
    | Default Language Settings
    |--------------------------------------------------------------------------
    */
    $"."config"."['lang'] = '".$_POST['lang']."';
    /*
    |--------------------------------------------------------------------------
    | WaterOneFlow Services Settings
    |--------------------------------------------------------------------------
    */
    //Service code Settings
    $"."config"."['auth_token'] = '';
    $"."config"."['service_code'] = '".$_POST['ConfigName']."';
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