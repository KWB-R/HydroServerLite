<?php

/*Restore Banner Script
* Made for custom banner functions
* It simply deletes the headerConfig File from the folder
*
**/

	
	

if (unlink("../headerConfig.php"))
{
echo "Banner restored. You may now close this tab and continue editing";	
}
else
echo "Unable to restore. Please contact your system admin.";


?>