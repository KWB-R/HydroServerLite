<?php

require_once("fetchMainConfig.php");

$sourceBanner ="images/WebClientBanner.png";

//Changed to "re" include the script. 
$configPath = $_SESSION['mainpath'];
$configPath = str_replace("main_config","headerConfig",$configPath);
$configPath = str_replace("\\","/",$configPath);
if (file_exists($configPath))
{
	require($configPath);
	$sourceBanner = $topBannerCustom;	
}
if(isset($lpManagerMode)):
if ($lpManagerMode)
{
	if ($sourceBanner =="images/WebClientBanner.png")
	{
		//Fetch the banner from the main directory. 
			$sourceBanner=$mainDir.$sourceBanner;
	}
	else
	{
		//Custom Banner..Is stored in the same directory. Strip the custom name to get the file name
		$pieces = explode("/", $sourceBanner);
		$sourceBanner="..\/".array_pop($pieces);
	}
}
endif;

echo '<img src="'.$sourceBanner.'" width="960" height="90" alt="logo" />';

?>
