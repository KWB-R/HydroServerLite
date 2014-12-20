<!-- 
This script downloads and shows translated terms from the hydroserver_translation database
and then shows the english phrase if there is no translated term yet
 -->

<?php
	//Connects to the database
	$mysqlserver="worldwater.byu.edu";
	$mysqlusername="langreader";
	$mysqlpassword="readHSLlang@9";
	$error=0;
	$link=mysqli_connect($mysqlserver, $mysqlusername, $mysqlpassword) or $error=1;

	$dbname = 'hydroserver_translation';
	mysqli_select_db($link, $dbname) or $error=1;
	if(!$error)
	{//check which is the session language
	
	$language = $lang_code;
	//language file path
	$file_path = "languages/" .$language. ".php";
	//Check if file exists
	
	$file_exists = file_exists($file_path);
	if($file_exists){
	// The file exists. Now just check when it was last time created.
	$file_created_time =  filemtime($file_path);
	$timezone = date_default_timezone_set('UTC');
	$current_time = time();
	//Time lapse to check the difference between the current time and the last created time
	$time_lapse = (abs($current_time-$file_created_time)/60/60);
	//SQL statement to access the view from the database
	$sql = "SELECT * FROM hydroserver_translation.translations_by_language";
	$terms = mysqli_query($link, $sql);
	//Will create a new file if it has been more than four hours
	if($time_lapse >= '4.0'){
	//Deleting the existing file to avoid any parsing errors
	unlink($file_exists);
	//Writing the new language_file
	$lang_file= fopen("languages/" .$language. ".php","c+");
	//Loops through the query and shows the translated terms 
	//and english terms if there are no translations for the term
	$new_file = "<?php" . "\n "; 
	fwrite($lang_file, $new_file);
	while($row = mysqli_fetch_array($terms)) {
			if ($row[$language] != "")
				fwrite($lang_file,$row['php_variable']. " = " . '"' . addslashes($row[$language]) . '"' . ";" . "\n ");
			else
				fwrite($lang_file,$row['php_variable']. " = " . '"' . addslashes($row['english_phrase']) . '"' . ";" . "\n ");
		}
		$last_line = "?>";
	fwrite($lang_file, $last_line);
	fclose($lang_file);
	}	
	}
	else{
	// Creating a new file if the file doesn't exist
	$sql = "SELECT * FROM hydroserver_translation.translations_by_language";
	$terms = mysqli_query($link, $sql);
	$lang_file= fopen("languages/" .$language. ".php","c+");
	$new_file = "<?php" . "\n "; 
	fwrite($lang_file, $new_file);
	while($row = mysqli_fetch_array($terms)) {
			if ($row[$language] != "")
				fwrite($lang_file,$row['php_variable']. " = " . '"' . addslashes($row[$language]) . '"' . ";" . "\n ");
			else
				fwrite($lang_file,$row['php_variable']. " = " . '"' . addslashes($row['english_phrase']) . '"' . ";" . "\n ");
		}
		$last_line = "?>";
	fwrite($lang_file, $last_line);
	fclose($lang_file);
	//New language file succesfully created!!!!
	}
	}
	
?>