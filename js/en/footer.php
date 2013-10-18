<?php
header('Content-Type: text/javascript');
//This is required to get the international text strings dictionary
//require_once ('../internationalize.php');
include '_text.php';

?>

document.write("<td colspan=2 bgcolor=#0971B3><center><font color=#FFFFFF face=Arial, Helvetica, sans-serif size=2><i><?php echo $Copyright; ?> &copy; 2013. <a href='http://hydroserverlite.codeplex.com/' target='_blank' class='reversed'><?php echo $HydroserverTitle; ?></a>. <?php echo $RightsReserved; ?> <a href='http://hydroserverlite.codeplex.com/team/view' target='_blank' class='reversed'><?php echo $MeetUs; ?></a></i></font></center></td>");