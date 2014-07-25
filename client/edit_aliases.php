<?php
require_once('main_config.php');
//connect to server and select database
require_once("authorization_check.php");
require_once('data_access_layer.php');

$ExistingAliases = array("User","Source","Site","Variable","Method","Value","Unit");
$aliasFilePath = "objects/aliases.php";
$showForm = !isset($_POST["editAliases"]);
$editSuccessful = true;

if($showForm){
	$aliasFile = fopen($aliasFilePath,"r");
	$lines = array();
	while(!feof($aliasFile)) { 
		$tempLine = fgets($aliasFile, 4096); 
		$AliasName = getValueFromString($tempLine,'$__',' = ');
		$AliasValue = getValueFromString($tempLine,'Alias("','");');
		if ($AliasName != "")
			$lines[$AliasName] = $AliasValue;	
	} 
	fclose ($aliasFile); 
}else{
	// update ALias file from form
	$aliasFile = fopen($aliasFilePath,"w");
	if($aliasFile !== false){
		$editSuccessful = true && fwrite($aliasFile,"<?php\n") !== false;
		$editSuccessful = true && fwrite($aliasFile,"// Using double underscore to designate an alias;\n") !== false;
		foreach ($ExistingAliases as $Alias){
			$editSuccessful = true && fwrite($aliasFile,'$__'.$Alias.' = new Alias("'
				.strtolower($_POST["Alias_".$Alias])."\");\n") !== false;
		}
		$editSuccessful = true && fwrite($aliasFile,"?>\n");
		fclose($aliasFile);	
		if (!$editSuccessful)
		{
			addError("Could not write to file. Restoring aliases from AliasMaster file found at: objects/aliasesMaster.php.");
			if (copy("objects/aliasesMaster.php",$aliasFilePath) ===false)
				addError("Could not restore aliases from Master. Please contact and administrator.");
			
		}else{
			addSuccess("Aliases successfully updated.");
		}
	}else{
		addError("Alias file does not have write permissions.
		 You will not be allowed to edit aliases until the file ($aliasFilePath)
			has write permission granted to the application. 
				Please check the file permissions that file.");
	}
}
require_once "_html_parts.php";

HTML_Render_Head();

echo $CSS_Main;

function getValueFromString($strToParse,$startVal,$endVal){
	$startIdx = strpos($strToParse,$startVal);
	if($startIdx !== false){
		$startIdx += strlen($startVal);
		$endIdx = strpos($strToParse,$endVal,$startIdx);
		return substr($strToParse,$startIdx,$endIdx-$startIdx);
	}else{
		return "";
	}
}

HTML_Render_Body_Start(); 
if ($showForm){
?>
	<p class="instructions">Aliases are words that are used throughout the site that can be changed if needed.
	Enter aliases in as the singular uncapitalized version of the word and the site will try to guess the plural versions when needed.
	i.e. for Users of the site enter the alias "user".</p>
	<form action="" method="POST">
	<ul class="form">
	<?php
	foreach ($ExistingAliases as $Alias){
		echo "<li><label for=\"Alias_$Alias\">$Alias</label>".
			"<input type=\"text\" id=\"Alias_$Alias\" name=\"Alias_$Alias\"".
			" value=\"".$lines[$Alias]."\" /></li>";
	}
	?>
<li><input type="submit" id="editAliases" name="editAliases" value="Save" />
<input type="reset" id="stopEditAliases" name="stopEditAliases" value="Cancel" /></li>
</ul>
</form>
	<?php
}else{
	// not showing form
	echo "<p>The following are the known aliases for this site:</p><ul>";
	foreach ($ExistingAliases as $Alias)
		echo "<li>$Alias = ".strtolower($_POST["Alias_".$Alias])."</li>";
	echo "</ul>";
}
HTML_Render_Body_End();
?>
