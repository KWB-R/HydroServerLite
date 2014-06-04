<?php

if (file_exists("setup/index.php"))
{
	if (!($_GET['state']=="setup"))
	{
		header("Location: index.php?state=setup");
	}
	
}

?>