<?php

require_once("fetchMainConfig.php");

$sourceBanner ="images/WebClientBanner.png";

if (isset($topBannerCustom))
{
	$sourceBanner = $topBannerCustom;	
}

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


echo '<img src="'.$sourceBanner.'" width="960" height="90" alt="logo" />';

?>
