<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

$name="uploads/";
 $name .=$_GET['name'];



$handle = fopen($name, "r");
$msg="";
$output="";
$flag=0;
$row=0;
$tracker=1;
while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
	
//Checking for Header and preventing further processing if it is a header

if($flag==0)
{
//First Run
if(($data[0]!="LocalDateTime")||($data[1]!="DataValue"))	
{
//$msg = "Invalid column headings. The headings should be in the following format: 'LocalDateTime,DataValue'";
$msg = $InvalidHeading;

$tracker=0;		
		break;
}
$flag=1;
}
else
{
//Now To Check for the date time parameter

//Check for characters in the date

$regex="(^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$)";

if (!preg_match($regex, $data[0])) {
   //$msg="Invalid characters present in LocalDateTime on row ".$row;
   $msg=$InvalidTime.$row;

$tracker=0;		
break;	
} 

//Check 1 : Date

$pieces = explode("-", $data[0]);

$pieces2 = explode(" ", $pieces[2]);

$pieces3 = explode(":", $pieces2[1]);

if((intval($pieces[0],10)<1970)||(intval($pieces[0],10)>intval(date("Y"),10)))

{
//$msg="Invalid year for date on row ".$row;
$msg=$InvalidYear.$row;

$tracker=0;		
break;		
}

if((intval($pieces[1],10)<1)||(intval($pieces[1],10)>12))

{
//$msg="Invalid Month for date on row ".$row;
$msg=$InvalidMonth.$row;

$tracker=0;		
break;		
}

//Now to Check Day

if((intval($pieces2[0],10)<1)||(intval($pieces2[0],10)>31))

{
//$msg="Invalid Day for date on row ".$row;
$msg=$InvalidDay.$row;
$tracker=0;		
break;		
}

//The Below checks leap year and other date type stuff

$dateresult=checkdate($pieces[1],$pieces2[0],$pieces[0]);

/*
$output="[Date.UTC(".$pieces[0].",".$pieces[1].",".$pieces2[0].",".$pieces3[0].",".$pieces3[1].",".$pieces3[2]."),".$row['DataValue']."]";

*/
$iTimestamp = strtotime($data[0]);
if ($dateresult >= 0 && false !== $dateresult)
{
$output .= $data[0];
}
else
{
//$msg = "Error in date format on Row number".$row;
$msg = $ErrorDate.$row;

$tracker=0;		
break;	
}

// Now to begin time validation

if((intval($pieces3[0],10)<0)||(intval($pieces3[0],10)>23))

{
//$msg="Invalid hour for time on row ".$row;
$msg=$InvalidHour.$row;
$tracker=0;		
break;		
}

if((intval($pieces3[1],10)<0)||(intval($pieces3[1],10)>59))

{
//$msg="Invalid minute for time on row ".$row;
$msg=$InvalidMin.$row;

$tracker=0;		
break;		
}

//Now to Check seconds

if((intval($pieces3[2],10)<0)||(intval($pieces3[2],10)>59))

{
//$msg="Invalid seconds for time on row ".$row;
$msg=$InvalidSec.$row;

$tracker=0;		
break;		
}



//Date time Validation Complete

//To Validate Data now

$regex="/^[\-+]?[0-9]*\.?[0-9]+$/";

if (!preg_match($regex, $data[1])) {
//   $msg="Invalid characters present in value on row ".$row;
   $msg=$InvalidChar.$row;
$tracker=0;		
break;	
} 



   
$output .= "</br>";}
$row++;
}


if($tracker!=0)
{
echo ("true");	
}
else
{
//echo $msg.". Please fix the error and reupload the CSV File";
echo $msg.$PleaseFix;	

}


?>