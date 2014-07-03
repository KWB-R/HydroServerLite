<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';
//All queries go through a translator. 
require_once 'DBTranslator.php';

$name="uploads/";
$name .=$_GET['name'];


$dtTable="-1";$methodsTable="-1";$varTable = "-1";$varMethTable = "-1";
$handle = fopen($name, "r");
$tempName = preg_replace('/\..*$/','',$name)."_CTF.csv"; 
$handle2 = fopen($tempName, "w");
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
if(($data[0]!="Variable")||($data[1]!="Type")||($data[2]!="Method")||($data[3]!="LocalDateTime")||($data[4]!="DataValue"))	
{
//$msg = "Invalid column headings. The headings should be in the following format: 'LocalDateTime,DataValue'";
$msg = $InvalidHeading;

$tracker=0;		
		break;
}
$flag=1;

$data[]="MethodID";
$data[]="VariableID";


}
else
{
//Now To Check for the date time parameter

//Check for characters in the date

$regex="(^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$)";

if (!preg_match($regex, $data[3])) {
   //$msg="Invalid characters present in LocalDateTime on row ".$row;
   $msg=$InvalidTime.$row;
	$tracker=0;		
    break;	
} 

//Check 1 : Date

$pieces = explode("-", $data[3]);

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
$iTimestamp = strtotime($data[3]);
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

if (!preg_match($regex, $data[4])) {
//   $msg="Invalid characters present in value on row ".$row;
   $msg=$InvalidChar.$row;
$tracker=0;		
break;	
} 

//Fetch DataTypeID

//Implementing a smart Verification system to verify only once for each ID rather than multiple times. Since there are not a large number of methods/DataTypes/Etc


//First Verifying MethodID

$methodDesc = $data[2];


if($methodsTable=="-1")
	{
		//The Table List is not defined. Need to run query to get it.
		$sql =" SELECT `MethodID`,`MethodDescription` FROM `methods`";
		$methodsTable = transQuery($sql,0,1);	
	
	}
	
//Search the table
$methodID="Not Found";
foreach($methodsTable as $row1)
{
	if ($row1['MethodDescription']==$methodDesc)
	{
		$methodID = $row1['MethodID'];
		break;
	}
}

if ($methodID == "Not Found")
{
$msg="Invalid Method found in row ".$row;
$tracker=0;		
break;	
}

$data[]=$methodID;

//Verifying DataType

$dtType = $data[1];

if($dtTable=="-1")
	{
		//The Table List is not defined. Need to run query to get it.
		$sql =" SELECT `Term` FROM `datatypecv`";
		$dtTable = transQuery($sql,0,1);	
	
	}
	
//Search the table
$dtStatus="Not Found";
foreach($dtTable as $row1)
{
	if ($row1['Term']==$dtType)
	{
		$dtStatus = "Found";
		break;
	}
}

if ($dtStatus == "Not Found")
{
$msg="Invalid DataType found in row ".$row;
$tracker=0;		
break;	
}


//Fetch the correct variable id and add it to the csv.

$varName = $data[0];

if($varTable=="-1")
	{
		//The Table List is not defined. Need to run query to get it.
		$sql =" SELECT `VariableID`,`VariableName`,`DataType` FROM `variables`";
		$varTable = transQuery($sql,0,1);	
	
	}
	
//Search the table
$varID="Not Found";
foreach($varTable as $row1)
{
	if ($row1['VariableName']==$varName && $row1['DataType']==$dtType )
	{
		$varID = $row1['VariableID'];
		break;
	}
}

if ($varID == "Not Found")
{
$msg="Invalid Variable found in row ".$row.". Either the variable does not exist or the DataType specified in that variable doesnt match. ";
$tracker=0;		
break;	
}




//Check for MethodID and variableID pair matching. 

if($varMethTable=="-1")
	{
		//The Table List is not defined. Need to run query to get it.
		$sql =" SELECT * FROM `varmeth`";
		$varMethTable = transQuery($sql,0,1);	
	
	}

$varMethResult="Not Found";
foreach($varMethTable as $row1)
{
	if ($row1['VariableID']==$varID && strpos($row1['MethodID'],$methodID) !== false)
	{
		$varMethResult = "Found";
		break;
	}
}

if ($varMethResult == "Not Found")
{
$msg="Invalid Variable found in row ".$row.". The variable - method pairing does not match any existing methods. ";
$tracker=0;		
break;	
}

$data[]=$varID;
   
$output .= "</br>";}
$row++;
fputcsv($handle2,$data);
}


if($tracker!=0)
{

fclose($handle);
fclose($handle2);
	
unlink($name);

if (rename($tempName,$name) != false)	
echo ("true");	
}
else
{
//echo $msg.". Please fix the error and reupload the CSV File";
echo $msg.$PleaseFix;	

}





?>