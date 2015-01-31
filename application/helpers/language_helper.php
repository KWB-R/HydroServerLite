<?php

//Language Helper. Will help with the required language functions on various pages. Will be loaded as a helper in the My class. 

function fetch_langSession()
{	
//Going against CI sessions for now. Ease of use and existing code in PHP sessions is the reason. 
if (!isset($_SESSION)){
	// always start the session before doing anything else.
	session_start();
	//nset($_SESSION['power']);
	//$_SESSION['power']="admin";
}
}

function getTxt($key)
{
	$CI = &get_instance();
	$text = $CI->lang->line('hsl_'.$key);
	return stripslashes($text);
}

function translateTerm($term)
{
	$CI = &get_instance();
	$text = $CI->lang->line('db_'.$term);
	if($text=="")
	{
		return $term;	
	}
	return $text;
}

function getCurrentLang()
{
fetch_langSession();
if(isset($_SESSION['lang']))
return $_SESSION['lang'];
else
{
	$CI = &get_instance();
	return $CI->config->item('lang');
}
return "English";
}

function getCurrentDisplay()
{
fetch_langSession();
if(isset($_SESSION['display']))
return $_SESSION['display'];
return "English";
}

function changeLang($lang,$disp)
{
fetch_langSession();
$lang1 = "English";
$display = "English";
if(!empty($lang))
{
	$lang1=$lang;	
	$display = $disp;
};
$_SESSION['display']=$display;
$_SESSION['lang']=$lang1;
echo ("langChanged");	
}

function processLang()
{
	//Check if language file exists. 
	$language = (getCurrentLang());
	//language file path
	$file_path ="./application/language/" .$language. "/hsl_lang.php";
	$file_exists = is_file($file_path);
	if($file_exists){
		// The file exists. Now just check when it was last time created.
		$file_created_time =  filemtime($file_path);
		$timezone = date_default_timezone_set('UTC');
		$current_time = time();
		//Time lapse to check the difference between the current time and the last created time
		$time_lapse = (abs($current_time-$file_created_time)/60/60);
		//if(1){
		if($time_lapse >= '4.0'){
			createNew($language);	
		}
	}
	else
	{
		createNew($language);		
	}
	
	return $language;
}


function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function createNew($language)
{	
	$file_path ="./application/language/" .$language. "/hsl_lang.php";
	if(is_file($file_path)){
		unlink($file_path);	
	}
	else
	{
		if(!file_exists("./application/language/" .$language))
		{
			//create dir
			mkdir("./application/language/" .$language);
		}
	}
	
	$CI=&get_instance();
	$CI->load->model('language');
	$CI->load->helper('text');
	$langTerms = $CI->language->getTerms($language);
	$lang_file= fopen($file_path,"c+");
	$new_file = "<?php" . "\n "; 
	fwrite($lang_file, $new_file);
	while($term = mysqli_fetch_array($langTerms))
	{
		$phpTerm = str_replace("$","",$term['php_variable']);
		if(startsWith($phpTerm,"dbText"))
		{
			if (isset($term[$language]))
				{if($term[$language]!= "")
				fwrite($lang_file,'$lang["db_'.addslashes($term['english_phrase']). "\"] = " . '"' . addslashes($term[$language]) . '"' . ";" . "\n ");
				}else
				fwrite($lang_file,'$lang["db_'.addslashes($term['english_phrase']). "\"] = " . '"' . addslashes($term['english_phrase']) . '"' . ";" . "\n ");
		}
		else
		{
		if (isset($term[$language]))
				{if($term[$language]!= "")
				fwrite($lang_file,'$lang[\'hsl_'.$phpTerm. "'] = " . '"' . addslashes($term[$language]) . '"' . ";" . "\n ");
				}else
				fwrite($lang_file,'$lang[\'hsl_'.$phpTerm. "'] = " . '"' . addslashes($term['english_phrase']) . '"' . ";" . "\n ");	
		}
	}
	fwrite($lang_file, "?>");
	fclose($lang_file);
}

?>