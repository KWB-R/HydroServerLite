<?php
require_once 'main_config.php';

//connect to server and select database
require_once 'database_connection.php';

//get all source
$sql ="SELECT s.*, m.Abstract FROM sources s JOIN isometadata m ON s.MetadataID = m.MetadataID WHERE SourceID = '".$_GET["SourceID"]."'";

$result = @mysql_query($sql,$connection)or die(mysql_error());

$num = @mysql_num_rows($result);

$proto = "http".((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")?"s":"")."://";
$server = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:$_SERVER['SERVER_NAME'];

$data = array();

if ($num > 0) {
	if ($row = mysql_fetch_array ($result)) {
		$row["Network"] = $default_varcode;
		$row["WSDL"] = $proto.$server."/services/cuahsi_1_1.asmx?wsdl";
		$data = $row;
	}
}

echo json_encode($data);
?>