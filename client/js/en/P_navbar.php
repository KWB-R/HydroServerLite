<?php
header('Content-Type: text/javascript');
//This is required to get the international text strings dictionary
//require_once ('../internationalize.php');
include '_text.php';

?>

document.write("<div class='nav'><br /><div class='menuhead2'><?php echo $PublicNavigation; ?></div><a href=view_main.php class='button'><img src='images/icons/SearchData.png'>&nbsp;&nbsp;<?php echo $SearchData; ?></a><br /><a href=help.php class='button'><img src='images/icons/Help.png'>&nbsp;&nbsp;<?php echo $HelpCenter; ?></a><br /><a href=index.php class='button'><img src='images/icons/Home.png'>&nbsp;&nbsp;<?php echo $BackToHome; ?></a></p><p>&nbsp;</p></div>");