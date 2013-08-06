<?php

require_once 'db_config.php';

function XML2Array(SimpleXMLElement $parent)
{
    $array = array();

    foreach ($parent as $name => $element) {
        ($node = & $array[$name])
            && (1 === count($node) ? $node = array($node) : 1)
            && $node = & $node[];

        $node = $element->count() ? XML2Array($element) : trim($element);
    }

    return $array;
}

function updateSourceData($post) {
	$sql = "SELECT MetadataID FROM sources WHERE SourceID = '".$_POST["source"]."'";

	$result = @mysql_query($sql,$GLOBALS['connection'])or die(mysql_error());
	
	$num = @mysql_num_rows($result);
	
	$metadataID = 0;

	if ($num > 0) {
		if ($row = mysql_fetch_array ($result)) {
			$metadataID = $row['MetadataID'];
		}
	}

	$sql1 = "UPDATE `sources` SET `Organization`='".$_POST['title']."',`SourceDescription`='".$_POST['organization_name']."',`SourceLink`='".$_POST['organization_url']."',`ContactName`='".$_POST['contact_name']."',`Email`='".$_POST['contact_email']."',`Phone`='".$_POST['contact_phone']."',`Citation`='".$_POST['citation']."' WHERE `SourceID`='".$_POST['source']."'";

	@mysql_query($sql1,$GLOBALS['connection'])or die(mysql_error());

	$sql2 = "UPDATE `isometadata` SET `Abstract`='".$_POST['abstract']."' WHERE `MetadataID`='".$metadataID."'";

	@mysql_query($sql2,$GLOBALS['connection'])or die(mysql_error());
}

$params = "";
foreach($_POST as $k=>$param) {
	if ($k != 'process') {
		$params .= "$k=$param&";
	}
}

if (strlen($params) > 0) {
	$params = substr($params,0,-1);
}

if ($_POST['process'] == 'CHECK') {
	$url = 'http://register.hydrodata.org/check.py';
	$cUrl = curl_init();
	curl_setopt($cUrl, CURLOPT_URL, $url);
	curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($cUrl, CURLOPT_POST, 1);
	curl_setopt($cUrl, CURLOPT_POSTFIELDS, $params);
	curl_setopt($cUrl, CURLOPT_TIMEOUT, 30);

	$answer	= curl_exec($cUrl);
	$ret = "0";
	$info = curl_getinfo($cUrl, CURLINFO_HTTP_CODE);
	if ($info == 200) {
		$xml   = simplexml_load_string($answer);
		$array = XML2Array($xml);
		$answer = array($xml->getName() => $array);

		$status = $answer["response"]["status"];
		if (strtoupper($status) == "OK") {
			$public_page = $answer["response"]["info"]["public_page"];
			$ret = array('status'=>strtoupper($status),'is_registered'=>$answer["response"]['info']["is_registered"],'result_link'=>'<a href="'.$public_page.'" target="_blank">'.$public_page.'</a>');
		} else {
			$ret = array('status'=>strtoupper($status),'error'=>$answer["response"]["errors"]['error']);
		}
		curl_close($cUrl);
		exit(json_encode($ret));
	}
	curl_close($cUrl);
	exit(json_encode($ret));
} else if ($_POST['process'] == "REGISTER" || $_POST['process'] == "UPDATE" || $_POST['process'] == "UNREGISTER") {
	if (!isset($_POST['is_public'])) {
		$params .= "&is_public=false";
	}
	$url = 'http://register.hydrodata.org/register.py';
	$cUrl = curl_init();
	curl_setopt($cUrl, CURLOPT_URL, $url);
	curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($cUrl, CURLOPT_POST, 1);
	curl_setopt($cUrl, CURLOPT_ENCODING, "UTF-8");
	curl_setopt($cUrl, CURLOPT_POSTFIELDS, utf8_encode($params));
	curl_setopt($cUrl, CURLOPT_TIMEOUT, 30);

	$answer	= curl_exec($cUrl);
	$ret = "0";
	$info = curl_getinfo($cUrl, CURLINFO_HTTP_CODE);
	$error = curl_error($cUrl);
	if ($info == 200) {
		$xml   = simplexml_load_string($answer);
		$array = XML2Array($xml);
		$answer = array($xml->getName() => $array);

		$status = $answer["response"]["status"];
		if (strtoupper($status) == "OK") {
			if ($_POST['process'] == "UPDATE") {
				updateSourceData($_POST);
			}
			$ret = array('status'=>strtoupper($status));
		} else {
			$ret = array('status'=>strtoupper($status),'error'=>$answer["response"]["errors"]['error']);
		}
		curl_close($cUrl);
		exit(json_encode($ret));
	}
	curl_close($cUrl);
	exit(json_encode($ret));
}else if ($_POST['process'] == "HARVEST") {
	$url = 'http://register.hydrodata.org/harvest.py';
	$cUrl = curl_init();
	curl_setopt($cUrl, CURLOPT_URL, $url);
	curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($cUrl, CURLOPT_POST, 1);
	curl_setopt($cUrl, CURLOPT_ENCODING, "UTF-8");
	curl_setopt($cUrl, CURLOPT_POSTFIELDS, utf8_encode($params));
	curl_setopt($cUrl, CURLOPT_TIMEOUT, 30);

	$answer	= curl_exec($cUrl);
	$ret = "0";
	$info = curl_getinfo($cUrl, CURLINFO_HTTP_CODE);
	$error = curl_error($cUrl);
	if ($info == 200) {
		$xml   = simplexml_load_string($answer);
		$array = XML2Array($xml);
		$answer = array($xml->getName() => $array);

		$status = $answer["response"]["status"];
		if (strtoupper($status) == "OK") {
			$public_page = $answer["response"]["message"];
			$ret = array('status'=>strtoupper($status),'result_link'=>'<a href="'.$public_page.'" target="_blank">'.$public_page.'</a>');
		} else {
			$ret = array('status'=>strtoupper($status),'error'=>$answer["response"]["errors"]['error']);
		}
		curl_close($cUrl);
		exit(json_encode($ret));
	}
	curl_close($cUrl);
	exit(json_encode($ret));
}
?>