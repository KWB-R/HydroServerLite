<?php

require_once 'db_config.php';
$siteid=$_GET['sc'];
$query1 = "SELECT picname FROM sitepic";
$query1 .= " WHERE SiteID=".$siteid;

$result1 = mysql_query($query1) or die("SQL Error 1: " . mysql_error());


if(mysql_num_rows($result1)<1)
{


echo("-1");
	
}

else
{

$row1 = mysql_fetch_array($result1, MYSQL_ASSOC);
echo("<img src='imagesite/small/".$row1['picname']."' width='100' height='100'>");
}