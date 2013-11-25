<?php
//check authority to be here
require_once 'authorization_check.php';

$SID = $_GET['SourceID2'];
$source_org = $_GET['Organization2'];
$source_d = $_GET['SourceDescription2'];
$source_l = $_GET['SourceLink2'];
$source_cn = $_GET['ContactName2'];
$source_p = $_GET['Phone2'];
$source_e = $_GET['Email2'];
$source_a = $_GET['Address2'];
$source_city = $_GET['City2'];
$source_st = $_GET['State2'];
$source_zc = $_GET['ZipCode2'];
$source_c = $_GET['Citation2'];
$source_md = $_GET['MetadataID2'];

//connect to server and select database
require_once 'database_connection.php';

//Update the fields for the SourceID # provided
if ($source_l=='' && $source_c==''){
	$sql_upd ="UPDATE sources SET Organization='$source_org',SourceDescription='$source_d',SourceLink=NULL,ContactName='$source_cn',Phone='$source_p',Email='$source_e',Address='$source_a',City='$source_city',State='$source_st',ZipCode='$source_zc',Citation=NULL,MetadataID='$source_md' WHERE SourceID='$SID'";
	
}elseif($source_l!='' && $source_c==''){
	$sql_upd ="UPDATE sources SET Organization='$source_org',SourceDescription='$source_d',SourceLink='$source_l',
ContactName='$source_cn',Phone='$source_p',Email='$source_e',Address='$source_a',City='$source_city',State='$source_st',ZipCode='$source_zc',Citation=NULL,MetadataID='$source_md' WHERE SourceID='$SID'";

}elseif($source_l=='' && $source_c!=''){
	$sql_upd ="UPDATE sources SET Organization='$source_org',SourceDescription='$source_d',SourceLink=NULL,ContactName='$source_cn',Phone='$source_p',Email='$source_e',Address='$source_a',City='$source_city',State='$source_st',ZipCode='$source_zc',Citation='$source_c',MetadataID='$source_md' WHERE SourceID='$SID'";

}else{
	$sql_upd ="UPDATE sources SET Organization='$source_org',SourceDescription='$source_d',SourceLink='$source_l',
ContactName='$source_cn',Phone='$source_p',Email='$source_e',Address='$source_a',City='$source_city',State='$source_st',ZipCode='$source_zc',Citation='$source_c',MetadataID='$source_md' WHERE SourceID='$SID'";
};

$result_upd = @mysql_query($sql_upd,$connection)or die(mysql_error());

echo ($result_upd);

?>