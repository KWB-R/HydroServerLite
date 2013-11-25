<?php
session_start();
if ($_SESSION['mainpath'])
{
$str = str_replace('\\', '/', $_SESSION['mainpath']);
if (isset($urlExtra))
{
require_once($urlExtra.$str);
}
else
{
require_once($str);
}
}
else
{
require_once("main_config.php");
}

?>