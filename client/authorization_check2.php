<?php
//Display the correct navigation or redirect them to the unauthorized user page

	if(!isset($_COOKIE['idaho']))
	{
		$nav ="<script src=js/P_navbar.js></script>";
	
	}
	else
	{
	
	$some_array = unserialize(base64_decode($_COOKIE['idaho']));
	$power1= $some_array['power'];

if ($power1=="admin"){
	$nav ="<script src=js/A_navbar.js></script>";
	}
elseif ($power1=="teacher"){
	$nav ="<script src=js/T_navbar.js></script>";
	}
elseif ($power1=="student"){
	$nav ="<script src=js/S_navbar.js></script>";
	} 
else {
	$nav ="<script src=js/P_navbar.js></script>";
	}
	}