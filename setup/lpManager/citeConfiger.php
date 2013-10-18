<?php

$string = '<?php

/*
Citation configuration file for Landing Page for World Water
Edit at your own risk
This file provides config file for the landing page. 
Developed by : Rohit Khattar @ BYU Grad Lab Provo Utah

This file will be populated while deployment
*/
	Require(\'LpConfig.php\');

	$AuthorFN = "'.$_POST['auth1fn'].'";
	$AuthorFN2 = "'.$_POST['auth2fn'].'";
	$AuthorFN3 = "'.$_POST['auth3fn'].'";
	
	$AuthorF = $AuthorFN[0];
	$AuthorL = "'.$_POST['auth1ln'].'";
	$AuthorF2 = $AuthorFN2[0];
	$AuthorL2 = "'.$_POST['auth2ln'].'";
	$AuthorF3 = $AuthorFN3[0];
	$AuthorL3 = "'.$_POST['auth3ln'].'";
	$Year = "'.$_POST['citeYear'].'";
	
	$Etal = "'.$_POST['etAl'].'";
	$url = "www.worldwater.byu.edu/interactive/" . $dir . "/";
	
	?>
';

//Create the landing page file
umask(0000);
$fp = fopen("citation.php", "w") or die("can't open file");
fwrite($fp, $string);
fclose($fp);

?>