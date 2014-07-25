<?php
require_once "authorization_check.php";
require_once "objects/objects.php";
require_once "objects/aliases.php";
//connect to server and select database
require_once 'database_connection.php';
require_once "data_access_layer.php";

//value given from the page
$var = new Variable();
$varID=$_GET["varid"];
$var->VariableID = $varID;

$returnResult = "";
//#type $method Method
foreach (DAL::Get()->Methods($var) as $method)
{
	$returnResult .= "<option value='".$method->MethodID."'>".$method->MethodDescription."</option>";
}

if ($returnResult != "")
	echo $returnResult;
else
	echo "No ".$__Method->Plural." found for this ".$__Variable->Text."." ;

?>
