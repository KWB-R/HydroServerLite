<?php
//check authority to be here
require_once 'authorization_check.php';

$SID = $_POST['SourceID2'];
$source_org = $_POST['Organization2'];
$source_d = $_POST['SourceDescription2'];
$source_l = $_POST['SourceLink2'];
$source_cn = $_POST['ContactName2'];
$source_p = $_POST['Phone2'];
$source_e = $_POST['Email2'];
$source_a = $_POST['Address2'];
$source_city = $_POST['City2'];
$source_st = $_POST['State2'];
$source_zc = $_POST['ZipCode2'];
$source_c = $_POST['Citation2'];
$source_md = $_POST['MetadataID2'];

//All queries go through a translator. 
require_once 'DBTranslator.php';

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

$result_upd = transQuery($sql_upd,1,-1);

echo ($result_upd);

?>