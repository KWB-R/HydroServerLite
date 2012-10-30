<?php

if ($_COOKIE[power] ==admin){
	$nav ="js/A_navbar.js";
	}
elseif ($_COOKIE[power] ==teacher){
	$nav ="js/T_navbar.js";
	}
elseif ($_COOKIE[power] ==student){
	$nav ="js/S_navbar.js";
	} 
else {
	header("Location: index.php?state=pass2");
	exit;	
	}

$text=file_get_contents($nav, NULL, NULL, 16); 

$req=str_split($text,stripos($text,"<script>"));

echo $req[0];

$jq=substr_replace($req[1],'',0,8);
$jq=substr_replace($jq,'',stripos($jq,"</script>"),12);

?>