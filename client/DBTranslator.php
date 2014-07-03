<?php

//Change made on 7/3/2014 - Rohit Khattar : Adding a debugging mode, if this variable is set to 1, all the queries made will be sent to the screen!

$debugMode = 0;

require_once 'db_config.php';

function transQuery($inputquery,$sendTrans = 1 ,$returnTrans = 1)
{

//Main Check required to see if main_config language is actually not english. 
global $lang;
global $connect;

if (!isset($_SESSION))
{
	session_start();	
}

if (isset($_SESSION['lang']))
{
$lang=$_SESSION['lang'];	
}
if ($lang == "en")
{
	$sendTrans = 0;
	if($returnTrans==1)
	{		
	$returnTrans =0;
	}
}



//Check if the database is in spanish and the language set is not spanish: This means reverse translation needs to happen! This should be exciting :P

$sql2="SELECT Term FROM datatypecv LIMIT 0,1";
global $debugMode;
if ($debugMode==1 ) {echo $sql2;}
$result2 = @mysql_query($sql2,$connect)or die("Error" .mysql_error());
$row = mysql_fetch_assoc($result2);
if ($row['Term'] == "Acumulativo"):
//Spanish Database Detected
if ($lang !='es'):
//Non Spanish Lnaguage is selected by user. Translate all queries going to the database. And also translate the retruning ones, unless the selection is -1
$translatedQuery  = translateQueryRev($inputquery);
$result =  makeRequest($translatedQuery);
if ($returnTrans == -1):
$translatedResult  = translateResult($result,$returnTrans);
else:
global $forceTrans;
$forceTrans = 1;
$translatedResult  = translateResult($result,1,1);
endif;
return $translatedResult;
else:

$sendTrans=0;
if ($returnTrans==1)
{
$returnTrans=0;	
}

endif;
endif;

if ($sendTrans==1){
	$translatedQuery  = translateQuery($inputquery);
	}
	else
	{
	$translatedQuery = $inputquery;
	}


$result =  makeRequest($translatedQuery);

$translatedResult  = translateResult($result,$returnTrans);

return $translatedResult;
}

function makeRequest($query)
{
global $debugMode;
global $connect;
if ($debugMode==1 ) {echo $query;}
$finalResult = @mysql_query($query,$connect)or die(mysql_error());	
return $finalResult;
	
}

function translateQuery($inputquery)
{
global $debugMode;
global $connect;
//Split query into pieces

preg_match_all("/[^']+(\w+)/",$inputquery,$keywords);
$keywords = $keywords[0];

//Remove quotes
$data = array();
$ignore = array ("","*");

foreach ($keywords as &$key):
$key = str_replace("'","",$key);
$key = str_replace("`","",$key);

//Ignore Keywords/empty spaces/etc LATER

if (in_array($key,$ignore))
{
	continue;
}
$key1=utf8_encode($key);
$sql2="SELECT EngText FROM  `spanish` WHERE  `SpanishText` =  '$key1' LIMIT 0 , 1";
if ($debugMode==1 ) {echo $sql2;}
$result2 = @mysql_query($sql2,$connect)or die("Error" .mysql_error());
if (mysql_num_rows($result2) > 0 ):
$row = mysql_fetch_assoc($result2);
$data[$key]=$row['EngText'];
//Do Replacement
$inputquery = str_replace($key,$row['EngText'],$inputquery);
endif;
endforeach;

//Final Query to be sent. 

return $inputquery;

}

function translateQueryRev($inputquery)
{
global $debugMode;
global $connect;
//Split query into pieces

preg_match_all("/[^']+(\w+)/",$inputquery,$keywords);
$keywords = $keywords[0];

//Remove quotes
$data = array();
$ignore = array ("","*");

foreach ($keywords as &$key):
$key = str_replace("'","",$key);
$key = str_replace("`","",$key);

//Ignore Keywords/empty spaces/etc LATER

if (in_array($key,$ignore))
{
	continue;
}
$key1=utf8_encode($key);
$sql2="SELECT SpanishText FROM  `spanish` WHERE  `EngText` =  '$key1' LIMIT 0 , 1";
if ($debugMode==1 ) {echo $sql2;}
$result2 = @mysql_query($sql2,$connect)or die("Error" .mysql_error());
if (mysql_num_rows($result2) > 0 ):
$row = mysql_fetch_assoc($result2);
$data[$key]=$row['SpanishText'];
//Do Replacement
$inputquery = str_replace($key,$row['SpanishText'],$inputquery);
endif;
endforeach;

//Final Query to be sent. 

return $inputquery;

}


function translateResult($result,$returnTrans=1,$rev=0)
{
global $debugMode;

global $connect;
//Translate the Response

if($returnTrans==-1)
{
return $result;	
}

$outputData =array();
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

		$row1=array();
		foreach ($row as $key => $value):

			if($returnTrans == 1)
			{
				$row1[$key]=translateWord($value,$rev);
			}
			else
			{

				$row1[$key]=$value;
			}
		endforeach;
		$outputData[]=$row1;
	
}

return $outputData;

}

function translateWord($value,$rev=0)
{
global $debugMode;
global $forceTrans;

if ($forceTrans != 1):
if (!isset($_SESSION))
{
	session_start();	
}

if (isset($_SESSION['lang']))
{
$lang=$_SESSION['lang'];	
}
if ($lang == "en")
{
return $value;
}
endif;
global $connect;
$value = addslashes($value); //To escape the special characters that might come up during translation. 
if ($rev==1 )
{
	$sql2="SELECT EngText FROM  `spanish` WHERE  `SpanishText` =  '$value' LIMIT 0 , 1";
}
else
{
	$sql2="SELECT SpanishText FROM  `spanish` WHERE  `EngText` =  '$value' LIMIT 0 , 1";	
}
if ($debugMode==1 ) {echo $sql2;}
$result2 = @mysql_query($sql2,$connect)or die(mysql_error());
if (mysql_num_rows($result2) > 0 )
{$row = mysql_fetch_assoc($result2);


if ($rev ==1 )
{
	return $row['EngText'];
}
else
{
	return $row['SpanishText'];
}

}
else
{return $value;
}
}

?>
