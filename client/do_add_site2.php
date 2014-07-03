<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }


$errors=0;
$name = $_FILES["images"]["name"][0];

$filename = stripslashes($name);
$extension = getExtension($filename);
$extension = strtolower($extension);

if (($extension != "jpg") && ($extension != "jpeg") && ($extension !=
 "png") && ($extension != "gif")) 
 		{
		//print error message
 			echo $InvalidPhotoExtension;
			$errors=1;
 		}
		
if($errors==0)
{		
$size=filesize($_FILES["images"]["tmp_name"][0]);
if ($size > 1024*1024)
{
	echo $ImageTooLarge;
	$errors=1;
}


}

if($errors==0)
{	
$uploadedfile = $_FILES["images"]["tmp_name"][0];
if($extension=="jpg" || $extension=="jpeg" )
{
$uploadedfile = $_FILES["images"]["tmp_name"][0];
$src = imagecreatefromjpeg($uploadedfile);
}
else if($extension=="png")
{
$uploadedfile = $_FILES["images"]["tmp_name"][0];
$src = imagecreatefrompng($uploadedfile);
}
else 
{
$src = imagecreatefromgif($uploadedfile);
}
 
list($width,$height)=getimagesize($uploadedfile);

$tmp=imagecreatetruecolor(368,250);

$tmp1=imagecreatetruecolor(100,100);

imagecopyresampled($tmp,$src,0,0,0,0,368,250,$width,$height);

imagecopyresampled($tmp1,$src,0,0,0,0,100,100,$width,$height);
$tempname="siteimg".time().rand(0, 1000).".".$extension;	
$filename = "imagesite/". $tempname;
$filename1 = "imagesite/small/". $tempname;

imagejpeg($tmp,$filename,100);
imagejpeg($tmp1,$filename1,100);

imagedestroy($src);
imagedestroy($tmp);
imagedestroy($tmp1);


//Update the sitepic table

//Fetch the siteid


//All queries go through a translator. 
require_once 'DBTranslator.php';


$siteid=$_GET['siteid'];


//Before adding the entry, delete all previous entries

$query4="DELETE FROM `sitepic` WHERE `siteid`='$siteid'";
$result4 = transQuery($query4,0,-1);

$query3 = "INSERT INTO `sitepic`(`siteid`, `picname`) VALUES ('$siteid','$tempname')";
$result3 = transQuery($query3,0,-1);

echo($result3);

}




?>
