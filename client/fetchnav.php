<?php

if (isAdmin()){
	$nav ="js/A_navbar.js";
	}
elseif (isTeacher()){
	$nav ="js/T_navbar.js";
	}
elseif (isStudent()){
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