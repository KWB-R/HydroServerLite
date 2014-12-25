<?php

//Language Helper. Will help with the required language functions on various pages. Will be loaded as a helper in the My class. 


function getTxt($key)
{
	$CI = &get_instance();
	$text = $CI->lang->line('hsl_'.$key);
	return stripslashes($text);
}

?>