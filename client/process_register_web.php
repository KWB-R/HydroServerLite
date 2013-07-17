<?php

function parseControlledVocabularyObject($xmlObj) {
	$xml   = simplexml_load_string($xmlObj);
	$array = json_decode(json_encode((array) $xml), 1);
	$array = array($xml->getName() => $array);
	return $array;
}

$params = "";
foreach($_POST as $k=>$param) {
	$params .= "$k=$param&";
}

if (strlen($params) > 0) {
	$params = substr($params,0,-1);
}

$url = 'http://register.hydrodata.org/register.py';
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
	$answer = parseControlledVocabularyObject($answer);
	$status = $answer["response"]["status"];
	$ret = strtoupper($status) == "OK"? "1":"0";
}
curl_close($cUrl);
exit($ret);
?>