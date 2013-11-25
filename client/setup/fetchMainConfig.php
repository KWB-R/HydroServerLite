<?php
session_start();
if ($_SESSION['mainpath'])
{
echo $_SESSION['mainpath'];
require_once($_SESSION['mainpath']);
}
else
{
require_once("main_config.php");
}

?>