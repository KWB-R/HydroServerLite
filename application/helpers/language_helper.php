<?php

//Language Helper. Will help with the required language functions on various pages. Will be loaded as a helper in the My class. 


function getTxt($key)
{
	$CI = &get_instance();
	return $CI->lang->line('hsl_'.$key);	
}

?>