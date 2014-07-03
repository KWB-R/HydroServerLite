<?php

//All queries go through a translator. 
require_once 'DBTranslator.php';
$siteid=$_GET['sc'];
$query1 = "SELECT picname FROM sitepic";
$query1 .= " WHERE SiteID=".$siteid;

$result1 = transQuery($query1,0,0);

if(count($result1)<1)
{
echo("-1");	
}
else
{
$row1 = $result1[0];
echo("<img src='imagesite/small/".$row1['picname']."' width='100' height='100'>");
}