<?php
//Display the correct navigation or redirect them to the unauthorized user page
if ($_COOKIE['power']=="admin"){
	$nav ="<script src=A_navbar.php></script>";
	}
elseif ($_COOKIE['power']=="teacher"){
	$nav ="<script src=T_navbar.php></script>";
	}
elseif ($_COOKIE['power']=="student"){
	$nav ="<script src=S_navbar.php></script>";
	} 
else {
	$nav ="<script src=P_navbar.php></script>";
	}