<?php
require_once "session_handler.php";
require_once "data_access_layer.php";
require_once "_html_parts.php";
require_once "date_functions.php";

// verify form data from import data file

// page variables
$redirect = false;
$fileUploadPath= "uploads/"; // path to save uploaded files to.
$fileName; // name of current file.
$filePath; // relative path to the current file.
$SiteToUse; // site that data will be saved to.
$SourceToUse; // source used in saving.
$openedFile; // handle for the opened file.
$timeOffsetCodes = array("e","I","Z","O","P","T"); // known GMT offset codes for Times

// get/set Site
if(isset($_POST["SiteID"])){ 
	$sID =  $_POST["SiteID"];
	$SiteToUse = (object) DAL::Get()->Site($sID);
	$_SESSION["SiteForUpload"] = serialize($SiteToUse);
}elseif(isset($_SESSION["SiteForUpload"])){
	$SiteToUse = unserialize($_SESSION["SiteForUpload"]);
}
	
// get/set Source
if(isset($_POST["SourceID"])){
	$sID = $_POST["SourceID"];
	$SourceToUse = (object) DAL::Get()->Source($sID);
	$_SESSION["SourceForUpload"] = serialize($SourceToUse);
}elseif(isset($_SESSION["SourceForUpload"])){
	$SourceToUse = unserialize($_SESSION["SourceForUpload"]);
}
	
// get/set File
if (isset($_FILES["fileToUse"])){
	// save file with unique name. 
	//  This way the files will not be overwritten in 
	//     multiple users upload files with the same 
	//     file name while using the site at the same time.
	$filePath = "";
	$tmpName =  $_FILES["fileToUse"]["tmp_name"];
	$impFileName = basename($_FILES["fileToUse"]["name"]);
	$uniqueHandler = session_id() ."-". time(). "-";
	if ($tmpName == "")
	{
		 addError("No file was selected. Please include a file to import.");
		$redirect= true;
	}
	if (move_uploaded_file($tmpName,$fileUploadPath.$uniqueHandler.$impFileName)){
		// file moved
		$_SESSION["importStarted"]= true;
		$filePath = $fileUploadPath.$uniqueHandler.$impFileName;
		$_SESSION["importFilePath"]= $filePath;
	}else{
		// file could not be moved
		addError("Uploaded file could not be moved to the $fileUploadPath folder. Please contact an adminstrator.");				
		$redirect = true;
	}
	// get latest file if not found in session.	
}elseif(isset($_SESSION["importFilePath"])){
	$filePath= $_SESSION["importFilePath"];
}else{
	$filePath= getLatestUploadedFile(session_id(),$fileUploadPath);
}

if (isset($_POST["delimeterPicker"]))
{
	$_SESSION["importDelimeter"] = $_POST["delimeterPicker"];
}

if(!isset($_SESSION["importFilePath"])){
	// get current file name from full unique name.	
	if ($filePath == null || $filePath == "")
	{
		addError("No file was specified to upload.");
		$redirect = true;
	}else
	{
		$openedFile = fopen($filePath,"r");
	}
}else{
	$fpath = $_SESSION["importFilePath"];
	$fileName = getFileNameParts($fpath);
	$openedFile = fopen($fpath,"r");
}

$delimeter = $_SESSION["importDelimeter"];
	if ($openedFile != null){
		if ($delimeter == '\t'){
			// Make sure there are actual tabs in this file.
			// some files appear to be tab delimited by they are really
			//   fixed width space columns.
			$tabCount = 0;	
			$spaceCount = 0;
			while (($fileContent = fgets($openedFile,4096))!== false)
			{
				$tabCount += substr_count($fileContent,"\t");
				$spaceCount += substr_count($fileContent," ");
			}
	
			if (!rewind($openedFile)){
				addError("Oops, Could not rewind file.");
			}
			
			// no tabs appear in line
			if ($tabCount == 0){// no tabs found, though selected.
				$delimeter = "fws";
				$_SESSION["importDelimeter"] = $delimeter;
				addWarning("You selected a TAB delimeter, however, I found no tabs in this file. I have changed the delimeter to a Fixed Width (Space) delimeter.");	
			}
		}
		$columnUnits = null;
		$fileMatrix = getMatrixFromFile($openedFile,$columnUnits,$delimeter);
		fclose($openedFile);
	}
// Make sure required variables are set.
if(!isset($SiteToUse) || (is_scalar($SiteToUse) && $SiteToUse->scalar == false)){ 
	addError("No ".$__Site->Capitalized." Set");
	$redirect = true;
}
if(!isset($SourceToUse) || (is_scalar($SourceToUse) && $SourceToUse->scalar == false)){
	addError("No ".$__Source->Capitalized." Set");
	$redirect = true;
}

// if we need to redirect, we must do it before anything is written to the page.
//   Make sure this is called before any echos but after all neccesary checks
//   have been made.				
if ($redirect){
	Redirect("import_data_file.php");
}else{
	// all is well and we can continue with import.
	HTML_Render_Head("Import Wizard");
	echo $CSS_Main;
	echo $JS_JQuery;
	echo $JS_Forms;
	HTML_Render_Body_Start();
	if (!isset($_SESSION["importStarted"])){
		echo "Import process has not been started. ";
		echo "Please start the import process from the ";
		echo "<a href=\"import_data_file.php\">Import Data</a>";
		echo " page.";
	}else{
		echo "<div class=\"instructions\">";
		if(!isset($_POST["fileToUse"])){
		echo "<p>This form is used to set which ".$__Variable->Text
				." applies to which column in the the file being imported."
				." The ".$__Variable->Text
				." has tried to be guessed from the import file but may not be correct."
				." For each column, pick the proper ".$__Variable->Text
				." from the drop down list found in the respective column."
				." After picking the appropriate".$__Variable->Text
				.", a list will appear with the ".$__Method->Plural
				." related to the selected ".$__Variable->Text
				.". Please pick the appropriate ".$__Method->Text
				.".</p>";
				
		echo "<p class=\"warning\"><em>Please note: a date column must be set to continue."
			." If a date column is not set, you will have to return to this page"
			." and all column settings will be lost."
				."</p>";
				
				}else
{
	echo "Use this table to select which values you will actually import into the system."
		." Any row with a check on it will be imported. Rows that are not checked will not "
		." be imported into the system and will be completely skipped.";
}
echo "</div>";
		echo "<form method=\"POST\">";
		if(!isset($_POST["fileToUse"])){
			// we have not yet set columns. This form element exists on the set columns page.
			if ($fileMatrix != null){
				// we have a file to parse
				renderForm($SiteToUse,$SourceToUse,$fileName[2]);
				displayFileTable($fileMatrix,$columnUnits,true);
				echo "<div class=\"tableButtons\">";
				echo "<input type=\"submit\" value=\"Save Column Settings >\" onclick=\"return showSpinningWheelOnMe(this);\"></input>";
				echo "<input type=\"hidden\" name=\"fileToUse\" value=\"".$_SESSION["importFilePath"]."\"></input>";
				echo "</div>";
			}
		}elseif(!isset($_POST["saveData"])){
			// Columns have been set, now we need to take this file and commit it to the database.
			//var_dump($_POST);	
			$columnSettings = getColumnSettings($fileMatrix);
			$dateColumnIndex = -1;
			$timeColumnIndex = -1;
			//#type $colSet ColumnSetting
			foreach ($columnSettings as $colSet)
			{
				if ($colSet->IsDate || $colSet->IsDateTime)
					$dateColumnIndex = $colSet->Index;
				if ($colSet->IsTime)
					$timeColumnIndex = $colSet->Index;
			}
			if ($dateColumnIndex < 0){ 
				// no date column was set, this will fail. THere must be a date column set.
				echo "<p class=\"error\">";
				echo "No date column was set. A date is required to save the data. ";
				echo "Please return and set a data column on the <a href=\"importWizard.php\">Import File page</a>.";
				echo "<em>Please note: returning to the import page resets all columns, please check your column setttings.";
				echo "</p>";
			}else{
				// date column was set; continue.
				displayFileTable($fileMatrix,$columnUnits,false,$columnSettings);
				if ($dateColumnIndex >= 0){
					// date is set
					if ($timeColumnIndex >= 0 ){
						// time is set too
						echo "<input type=\"hidden\" id=\"dateFormat\"  name=\"formatDateOnly\" value=\"".
							$columnSettings[$dateColumnIndex]->Format."\"></input>"
							."<input type=\"hidden\" id=\"timeFormat\"  name=\"formatTimeOnly\" value=\"".
							$columnSettings[$timeColumnIndex]->Format."\"></input>";
					}else{
						// time is not set, so it must be a date time.
						echo "<input type=\"hidden\" id=\"dateTimeFormat\"  name=\"formatDateTime\" value=\"".
							$columnSettings[$dateColumnIndex]->Format."\"></input>";
					}
					echo "<input type=\"hidden\" id=\"timeOffset\" name=\"timeOffset\" value=\"".
								$columnSettings[$timeColumnIndex]->TimeOffset."\"></input>";
				}
				echo "<div class=\"tableButtons\">";
				echo "<input type=\"hidden\" name=\"fileToUse\" value=\"".$_SESSION["importFilePath"]."\"></input>";
				echo "<input type=\"submit\" name=\"saveData\" value=\"Save data to System >\" onclick=\"return showSpinningWheelOnMe(this);\"></input>";
				echo "</div>";
				
			}
		}else{
			saveDataValues($fileMatrix,$columnUnits,$SiteToUse,$SourceToUse);
			}
		
	echo "</form>";
		HTML_Render_Body_End();
	}
	echo "<script type=\"text/javascript\" src=\"js/importWizard.js\"></script>";
}


/* =======================================================
Functions for page start here
======================================================= */
function renderForm($SiteToUse,$SourceToUse,$fileName){
	global $__Site;
	global $__Source;
	global $__DateTimeFormats;
	global $__DateFormats;
	global $__TimeFormats;
	global $__TimeZones;
	
	$tempDate = new DateTime();
	?>
<dl>
<dt>File</dt>
		<dd><?php echo $fileName; ?></dd>
	<dt><?php echo $__Site->Capitalized; ?></dt><dd><?php echo $SiteToUse->SiteName; ?>
			<input name="SiteID" type="hidden" value="<?php echo $SiteToUse->SiteID; ?>"></input></dd>
	<dt><?php echo $__Source->Capitalized; ?></dt><dd><?php echo $SourceToUse->SourceDescription; ?>
			<input name="SourceID" type="hidden" value="<?php echo $SourceToUse->SourceID; ?>"></input></dd>
			<dt>Formats</dt>
<dd>
<dl>
<dt id="formatDateTimeSetterTitle"><label for="formatPickerDateTime">DateTime</label></dt>
<dd id="formatDateTimeSetter">
<select name="formatPickerDateTime" onchange="showFieldIfAdd(this,'formatDateTimeGroup',{delay:500,after:showTimeFormatter,param:this})">
<optgroup label="Common">
<?php
foreach($__DateTimeFormats as $name => $format)
	echo "<option value=\"$format\">$name [".$tempDate->format($format)."]</option>";	
?>
</optgroup>
<optgroup label="User Define">
<option value="-1">Pick your own</option>
</optgroup>
</select>
<div id="formatDateTimeGroup" class="hiddenField"> 
<label for="dateTimeFormat">Custom DateTime Format</label>
<input id="dateTimeFormat"  name="formatDateTime" type="text" value=""></input>
<span class="hint" title="Use the format rules for PHP dates, found at: goto:http://www.php.net/manual/en/function.date.php#refsect1-function.date-parameters||PHP Manual||_new">?</span>
</div>
</dd>
<dt id="formatDateSetterTitle"><label for="formatPickerDateTime">Date Only</label></dt>
<dd id="formatDateSetter">
<select name="formatPickerDateOnly" onchange="showFieldIfAdd(this,'formatDateGroup')">
<optgroup label="Common">
<?php
foreach($__DateFormats as $name => $format)
	echo "<option value=\"$format\">$name [".$tempDate->format($format)."]</option>";	
?>
</optgroup>
<optgroup label="User Define">
<option value="-1">Pick your own</option>
</optgroup>
</select>
<div id="formatDateGroup" class="hiddenField"> 
<label for="dateFormat">Custom Date Format</label>
<input id="dateFormat"  name="formatDateOnly" type="text" value=""></input>
<span class="hint" title="Use the format rules for PHP dates, found at: goto:http://www.php.net/manual/en/function.date.php#refsect1-function.date-parameters||PHP Manual||_new">?</span>
</div>
</dd>
<dt id="formatTimeSetterTitle"><label for="formatPickerDateTime">Time Only</label></dt>
<dd id="formatTimeSetter">
<select id="formatPickerTimeOnly" name="formatPickerTimeOnly" 
		onchange="showFieldIfAdd(this,'formatTimeGroup',{delay:500,after:showTimeFormatter,param:this})">
<optgroup label="Common">
<?php
foreach($__TimeFormats as $name => $format)
	echo "<option value=\"$format\">$name (".$tempDate->format($format).")</option>";	
?>
</optgroup>
<optgroup label="User Define">
<option value="-1">Pick your own</option>
</optgroup>
</select>
<div id="formatTimeGroup" class="hiddenField"> 
<label for="timeFormat">Custom Time Format</label>
<input id="timeFormat"  name="formatTimeOnly" type="text" value=""></input>
<span class="hint" title="Use the format rules for PHP dates, found at: goto:http://www.php.net/manual/en/function.date.php#refsect1-function.date-parameters||PHP Manual||_new">?</span>
</div>
</dd>
<dt id="formatTimeOffsetSetterTitle">Time Offset</dt>
<dd id="formatTimeOffsetSetter">
<div id="formatTimeOffset" class="hiddenField">
<label for="timeOffset">Custom Time Offset</label>
<select name="timeOffset" id="timeOffset">
	<?php
	$tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
	foreach ($__TimeZones as $text => $offset)
	{
		echo "<option value=\"$offset\">$text</option>";
	}
	/*for ($i = -11; $i < 12; $i++)
	{
		echo sprintf("<option value=\"%d\">%03d:00</option>",$i,$i);
	}*/
	?>
</select>
<?php
// The code will set DST correctly if the timezone is set properly and the dates fall within DST rules.
//<label for="timeInDST">Time are based on DST:</label> <input type="checkbox" id="timeInDST" name="timeInDST" onChange="guessTimeZone()" /> 
//<span class="hint" title="DST is Daylight Saving Time. If times on this data need to be adjusted for DST, please check the checkbox. Otherwise DST will not be used." >?</span>
?>
</div>
</dd>
</dl>

</dd>

</dl>
<?php
}


function getLatestUploadedFile($sessID, $dirPath){
	$matchingFiles = array();
	if (is_dir($dirPath) && $openDir = opendir($dirPath)){
		while( false !== ($file = readdir($openDir))){
			if ($file != "." && $file != ".."){
				$fileParts = getFileNameParts($file);
				if (count($fileParts) == 3){
					// has correct format
					// check to see if session matches.
					if ($sessID == $fileParts[0]){
						$matchingFiles[$fileParts[1] * 1] = $file;
					}
				}
			}
		}
		//var_dump($matchingFiles);
		krsort($matchingFiles);
		//var_dump($matchingFiles);
		//var_dump(reset($matchingFiles));
		//return fopen($dirPath.reset($matchingFiles),"r");
		return $dirPath.reset($matchingFiles);
	}
}
function getDateWithUTC($val,$format){
	$tempDate = DateTime::createFromFormat($format,$val);
	if($tempDate){
		//$tempDate->setTimezone( new DateTimeZone("UTC"));
	}else
	{
		$tempDate = new DateTime();
	}
	return $tempDate;
}
function updateVariablesWithDate($arr,$date){}
function getFileNameParts($uploadedName){
	return  explode("-",$uploadedName);
}
function getVariableOptions($varList,$units,$aUnit,&$out_SelectedVariable){
	$retStr = "<option class=\"ignored\" unit=\"0\" value=\"-10\">--Ignore--</option>"
		."<option class=\"highlighted\" unit=\"0\" value=\"-1\">DateTime Field</option>"
		."<option class=\"highlighted\" unit=\"0\" value=\"-2\">Date Field</option>"
		."<option class=\"highlighted\" unit=\"0\" value=\"-3\">Time Field</option>";
	//#type $var Variable
	foreach ($varList as $var){
		$retStr .= "<option value=\"".$var->VariableID
			."\" title=\"".getVariableDetailsInString($var,$units[$var->VariableunitsID])
			."\" unit=\"".$var->VariableunitsID;
		if (isset($aUnit) 
			&& $aUnit != false 
			&& $var->VariableunitsID == $aUnit->unitsID) {
				$retStr .= "\" selected=\"selected";
				$out_SelectedVariable = $var;
			}
			$retStr .="\" onmouseenter=\"showDetailsFromTitle(this)\">".$var->VariableName
			." (".$var->DataType.")[".
			$units[$var->VariableunitsID]->unitsAbbreviation."]</option>";
		}
		return $retStr;
	}
	function getVariableDetailsInString($var,$aUnit){
		return $var->VariableName
		." : ".$var->DataType
		." : ".$var->ValueType
		." : ".$var->VariableCode;
	}
	/*function getUnitOptions($aUnit){
		$retStr = "";
		//#type $var Unit
		foreach (DAL::Get()->AllUnits() as $var){
			$retStr .= "<option value=\"".$var->unitsID."\"";
			$retStr .= " title=\"".$var->unitsName." ("
			.$var->unitsAbbreviation.")\"";
			//#type $aUnit Unit
			if (isset($aUnit) 
				&& $aUnit != false 
				&& $var->unitsID == $aUnit->unitsID) {
				$retStr .= " selected=\"selected\"";
			}
			$retStr .= ">".$var->unitsAbbreviation."</option>";
		}
		return $retStr;
	}*/
	function getMethodOptions($var){
		$retStr = "";
		//#type $var Method
		if (isset($var) && $var != null)
		{
			foreach (DAL::Get()->Methods($var) as $meth)
				$retStr .= "<option value=\"".$meth->MethodID
				."\">".$meth->MethodDescription."</option>";
		}
		return $retStr;
	}
	function getIndexesFromFile($handle, $spacesStartColumn = true){
		$columnIndexesInFile;
		$indexesFound = false;
		if ($spacesStartColumn){
			while (($fileContent = fgets($handle,4096))!== false)
			{
				$thisSpaceCount = substr_count($fileContent," ");
				if (!$indexesFound && $thisSpaceCount > 0)
				{
					$inColumn = false;
					$lastChar = '';
					$colStartIndex = 0;
					$fileLine = str_split($fileContent);
					foreach ($fileLine as $chr){
						if(!$inColumn){
							$columnIndexesInFile[] = $colStartIndex;	
							$inColumn = true;
						}else{
							if ($chr == ' ' && $lastChar != ' ')
							{
								$inColumn = false;
							}
						}
						$lastChar = $chr;
						$colStartIndex++;
					}
					$indexesFound = true;
				}		
				if ($indexesFound)
				{
					break;
				}
			}
		}
		// no tabs appear in line
		// set file pointer back to start.
		if (!rewind($handle)){
			addError("Oops, Could not rewind file.");
		}
		return $columnIndexesInFile;			
	}
	function getArrayUsingIndexes($str, $arrOfIndexes){
		$retArr;
		$numberOfIndexes = count($arrOfIndexes);
		for($idx = 0; $idx < $numberOfIndexes; $idx++){
			if ($idx < $numberOfIndexes-1){
				$retArr[] = substr($str,$arrOfIndexes[$idx],$arrOfIndexes[$idx+1] - $arrOfIndexes[$idx]);	
			}else{
				$retArr[] = substr($str,$arrOfIndexes[$idx]);	
			}
		}
		return $retArr;
	}
	function tryGetUnit($colStr,$varTypes){
		//#type $var Unit
		foreach ($varTypes as $var)
		{
			if($var->unitsAbbreviation == $colStr)
				return $var;
			if (strtolower($var->unitsName) == strtolower($colStr))
				return $var;
		}	
		return false;
	}
	function getMatrixFromFile($handle,&$columnUnits,$delimeter){
		$columnIndexes = null; // keeps track of the columns in the file.
		$isFixedWidth =  false;
		$varTypes = DAL::Get()->AllUnits();
		$fileMatrix;
		$colIndex = 0;
		if($delimeter == 'fws'){
			if($columnIndexes == null){
				$columnIndexes = getIndexesFromFile($handle);
			}
			$delimeter = chr(255);
			$isFixedWidth = true;
		}
		$rowIndex = 0;
		while(($csvArray = fgetcsv($handle,0,$delimeter)) !== false){
			$rowArray;
			$colArray = $csvArray;
			if ($isFixedWidth){
				// make sure that if line somehow got split it is put back together.
				$line;
				if (count($csvArray) > 1){
					$line = implode($delimeter,$csvArray);
				}else{
					$line = $csvArray[0];
				}
				$colArray = getArrayUsingIndexes($line,$columnIndexes);
			}
			foreach($colArray as $col){
				$rowArray[$colIndex] = $col;
				if (!isset($columnUnits[$colIndex])||!$columnUnits[$colIndex])
				{
					$columnUnits[$colIndex] = tryGetUnit(trim($col),$varTypes);
				}
				$colIndex++;
			}
			$fileMatrix[$rowIndex] = $rowArray;
			$rowIndex++;
			$colIndex = 0;
		}
		return $fileMatrix;
	}
	function displayFileTable($fileMatrix,$columnUnits,$editHeaders,$existingColumnSettings = null){
		global $__Variable;
		global $__Unit;
		global $__Method;
		
		// $editheader defines the step.
		// if true, then we are on step 1.
		// otherwise we are on step 2.
		
		$columnDate = new ColumnDateTime();
		
		$className = "partialList";
		if (!$editHeaders) $className = "list";
		
		echo "<table id=\"importWizardData\" class=\"$className\">";
		if (!$editHeaders) 
			echo "<caption>Ignored Fields/Columns are not displayed. Please select which rows of data you want to import."
			."<p class=\"warning\">If there are errors with these columns, please return to the <a href=\"importWizard.php\">Import wizard page</a></p>"
			."</caption>";
		$columnIndexes = null; // keeps track of the columns in the file.
		//$columnUnits = null; // keeps track of the columns variables types in the file.
		$varTypes = DAL::Get()->AllVariables(ListOrder::Asc);
		$units = DAL::Get()->AllUnits();
		//$variableOptions = getVariableOptions($varTypes);
		//$unitOptions = getUnitOptions();
		//$methodOptions = getMethodOptions();
		
		echo "<thead><tr>";
		if ($editHeaders){
			echo "<th>Use as Headers</th>";
			echo "<th>Data Starts</th>";
		}else{
			echo "<th>Use Data
							<a href=\"#\" onclick=\"setAllCheckboxesTo('#importWizardData',true)\">Check All</a>
							<a href=\"#\" onclick=\"setAllCheckboxesTo('#importWizardData',false)\">Uncheck All</a>
						</th>";
		}
		$columnSettings;
		$rowIndexHeader = isset($_POST["radHeaderRow"])? (int)$_POST["radHeaderRow"]: 0;
		$rowIndexStartData = isset($_POST["radStartData"])? (int)$_POST["radStartData"]: 0;
		
		// set column headings
		foreach($fileMatrix[0] as $colI => $colData){
			if ($editHeaders)
			{
				$selectedVariable = null;
				// Step 1: define columns
				echo "<th>";
				echo "<fieldset>";
				echo "<legend>$colData</legend>";
				echo "<dl id=\"col_".$colI."_list\">";
				echo "<dt id=\"variable_col_".$colI."_title\">"
				."<label for=\"variable_col_$colI\" >".$__Variable->Capitalized."</label></dt>";
				echo "<dd id=\"variable_col_".$colI."_container\">"
				."<select name=\"variable_col_$colI\" id=\"variable_col_$colI\" "
				."class=\"selected\" onchange=\"javascript:disableOtherListsIfDate(this);setUnit(this);getMethods(this);\">"
				.getVariableOptions($varTypes,$units,$columnUnits[$colI],$selectedVariable)."</select></dd>";
				/*echo "<dt id=\"unit_col_".$colI."_title\">"
				."<label for=\"unit_col_$colI\" >".$__Unit->Capitalized."</label></dt>";
				echo "<dd id=\"unit_col_".$colI."_container\">"
				."<select name=\"unit_col_$colI\" id=\"unit_col_$colI\">"
				.getUnitOptions($columnUnits[$colI])."</select></dd>";*/
				echo "<dt id=\"method_col_".$colI."_title\">"
				."<label for=\"method_col_$colI\" >".$__Method->Capitalized."</label></dt>";
				echo "<dd id=\"method_col_".$colI."_container\">"
				."<select name=\"method_col_$colI\" id=\"method_col_$colI\">".
				getMethodOptions($selectedVariable)."</select></dd>";// $methodOptions
				echo "</dl>";
				echo "</fieldset>";
				echo "</th>";
				
			}else
			{
				// Step2: Define rows to use.
				//#type $colSet ColumnSetting
				$colSet;
				if ($existingColumnSettings == null)
					$colSet= getColumnSettingsFromPOST($colI);
				else
					$colSet = $existingColumnSettings[$colI];
				// do not use columns that were marked as ignored
				fillColumnDate($columnDate,$colSet);
				if (!$colSet->IsIgnored){
					echo "<th>";
					echo "<fieldset>";
					$colName = $fileMatrix[$rowIndexHeader][$colI];
					if ($colName == null || $colName == "")
						$colName = $colI;	
					echo "<legend>".$colName."</legend>";
					// set values to pass to next step.
					echo "<input type=\"hidden\" name=\"variable_col_$colI\" "
					."id=\"variable_col_$colI\" value =\"".
					$_POST["variable_col_$colI"]."\"></input>";
					if ($colSet->IsDate || $colSet->IsDateTime || $colSet->IsTime)
					{
						echo "<p>".$colSet->Format;
						//TODO: show offset for column.
						if (isset($colSet->TimeOffset) && $colSet->TimeOffset != null)
							echo " TZ:".$colSet->TimeOffset;
						echo "</p>";
					}else{
						//#type $colSet Variable
						$var = $colSet->Variable;
						//#type $colSet Unit
						$unt = $colSet->Unit;
						//#type $colSet Method
						$met = $colSet->Method;
						
						echo "<p>".$var->VariableName."</p>"
						."<p>".$unt->unitsName." (".$unt->unitsAbbreviation.")</p>"
						."<p>".$met->MethodDescription."</p>";
						
						if (isset($_POST["method_col_$colI"]))
						{
							echo "<input type=\"hidden\" name=\"method_col_$colI\" "
							."id=\"method_col_$colI\" value =\"".
							$_POST["method_col_$colI"]."\"></input>";
						}

					}	
					echo "</fieldset>";
					echo "</th>";
					
				}
				$columnSettings[$colSet->Index] = $colSet;
			}
		}
		echo "</tr>";
		echo "</thead>";
		// start filling table data
		echo "<tbody>";
		// set flag to get first row withdata.
		$firstGoodRow = true;
		foreach($fileMatrix as $rowI => $rowData){
			if ($rowI >= $rowIndexStartData)
			{
				// start row
				echo "<tr id=\"row_$rowI\"";
				// create radio buttons for step 1, and checkboxes for step 2.
				
				$rowText = "";
				$goodRowData= true;
				
				// start going across data columns.
				foreach($rowData as $colI => $colData){
					//#type $colSet ColumnSetting
					$colSet = null;				
					if (isset($columnSettings))
					{
						$colSet = $columnSettings[$colI];
					}
					if ($colSet == null || !$colSet->IsIgnored){
						$rowText .=  "<td class=\"";
						$cellValue="";
						if (!$editHeaders)
						{
							//step 2:
							// set class based on paramenters.
							if($colSet->IsDateTime ||$colSet->IsDate||$colSet->IsTime){
								$rowText .=   "highlighted";
								$tempDate = getDateFromColumns($rowData,$columnDate);
								if ($tempDate)
								{
									$cellValue = $tempDate->format('Y-m-d H:i:s O').
									" | ".$tempDate->format('T');
								}else
								{
									$cellValue = "Unknown Date";
									$goodRowData = false;
								}
							}elseif($colSet->IsIgnored){
								// this should never be called on step 2.
								$rowText .=   "ignored";
							}else{
								// no class
								$cellValue = $colData;
							}
						}else{
							$cellValue = $colData;
						}
						// include data
						$rowText .=   "\" title=\"$colData\">$cellValue";
						// include unit on step2.
						if (!$editHeaders && $colSet->Unit){
							//#type $uni Unit
							$uni = $colSet->Unit;
							$rowText .=   " ".$uni->unitsAbbreviation;
						}
						
						$rowText .=   "</td>";
					}
				}
				
				if ($editHeaders)
				{
					// step 1 - define the header row and starting data row.
					echo " class=\"selected\">";
					echo "<td title=\"This row is column Headers.\"><input type=\"radio\""
					." name=\"radHeaderRow\" class=\"rowMarker\" value=\"$rowI\"></input></td>";
					echo "<td title=\"Start using data from this row, down.\"><input type=\"radio\"";
					if ($goodRowData && $firstGoodRow) {
						echo " checked=\"checked\"";
						$firstGoodRow = false;
					}
					echo " name=\"radStartData\"class=\"useData\" value=\"$rowI\"></input></td>";
				}else{
					// step 2 -- define which rows to import.
					echo ">";
					echo "<td title=\"Use data in this row.\"><input type=\"checkbox\""
					." name=\"chk_useData_$rowI\" ";
					if ($goodRowData) {
						// do not check rows with known bad data.
						echo "checked=\"checked\"";
					}
					echo " class=\"useData\"></input></td>";
				}
				echo $rowText;
				echo "</tr>";
				if ($editHeaders && $rowI > 10)
				{
					break;
				}
				
			}
		}
		if($editHeaders){
			echo "<tr><td class=\"message\" colspan=\"".(count($fileMatrix[0])+2)."\">This is only the first 12 lines of the ".
			count($fileMatrix)." lines of data to help identify each column of data that will be imported.</td></tr>";
		}
		echo "</tbody></table>";
	}
	//#type $colDate ColumnDateTime
	function getDateFromColumns($rowData,$colDate){
		//$colDate ){
		$retDate = new DateTime();
		//$colDate = new ColumnDateTime();
		$format ="";
		$dateStr ="";
		if( $colDate->DateIndex== $colDate->TimeIndex){
			// datetime column
			$format =$colDate->DateFormat;
			$dateStr = $rowData[$colDate->DateIndex];
		}else{
			// date and time are separate
			$format =	$colDate->DateFormat." ".$colDate->TimeFormat;
			$dateStr = $rowData[$colDate->DateIndex]." ".$rowData[$colDate->TimeIndex];
		}
		
		// cleanup issues of extra spaces if they exist.
		// this will cause the date to fail.
		$dateStr = str_replace("  "," ",$dateStr);
		$dateStr = trim($dateStr);
		
		$retDate =	date_create_from_format ($format,$dateStr);
		if ($retDate){
			if(isset($colDate->Offset)){
				try
				{	        
					$newTimeZone = new DateTimeZone($colDate->Offset);
					if ($newTimeZone)
					{
						$retDate->setTimezone($newTimeZone);	
					}
				}
				catch (exception $e)
				{
					echo $e->getMessage();
				}
				
			}
		}else{
			// error with date. Probably could not be parsed.
		}
		return $retDate;
	}
	class RowDate{
		public $DateString = null;	
		public $DateFormat = null;
		public $TimeString = null;
		public $TimeFormat = null;
		public $DateTime = null;
	}
	/**
	 Class ColumnDate
	*/
	class ColumnDateTime
	{
		public $DateIndex;
		public $TimeIndex;
		public $DateFormat;
		public $TimeFormat;
		public $Offset;
	}
	function saveDataValues($fileMatrix,$columnUnits,$SiteToUse,$SourceToUse){
		global $_SITE_ValueAccuracy;
		global $_SITE_OffsetValue;
		global $_SITE_OffsetTypeID;
		global $_SITE_UTCOffset;
		global $_SITE_CensorCode;
		global $_SITE_QualifierID;
		global $_SITE_SampleID;
		global $_SITE_DerivedFromID;
		global $_SITE_QualityControlLevelID;
		
		
		$DataValuesToSave = array();
		$columnDate = new ColumnDateTime();
		$columnIndexes = null; // keeps track of the columns in the file.
		$varTypes = DAL::Get()->AllVariables();
		$columnSettings;
		getColumnSettingsWithDateColumn($fileMatrix,$columnSettings,$columnDate);

		foreach($fileMatrix as $rowI => $rowData){
			$dateForRow = getDateFromColumns($rowData,$columnDate);
			$UTC_Date;
			if (!$dateForRow) 
			{// error parsing date. Will not have a valid date for this row.
			}else{
				$UTC_Date = clone $dateForRow;//DateTime::createFromFormat('U',$dateForRow->format("U"));
				$UTC_Date->setTimezone(new DateTimeZone("UTC"));
			}
			if (isset($_POST["chk_useData_$rowI"])){	
				foreach($rowData as $colI => $colData){
					//#type $colSet ColumnSetting
					$colSet = null;				
					if (isset($columnSettings))
						$colSet = $columnSettings[$colI];
					if ($colSet != null && !$colSet->IsIgnored){
						if(!$colSet->IsDateTime && !$colSet->IsDate && !$colSet->IsTime){
							$DataValue = new DataValue();
							$DataValue->DataValue = (double)$colData;
							$DataValue->ValueAccuracy = dbTools::checkForNULL($_SITE_ValueAccuracy);
							$DataValue->LocalDateTime = dbTools::checkForNULL($dateForRow->format("Y-m-d H:i:s")); // get data time as string
							$DataValue->UTCOffset = dbTools::checkForNULL($dateForRow->getOffset()/3600);  // $_SITE_UTCOffset;  // This offset really needs to be based on the time.
							$DataValue->DateTimeUTC = dbTools::checkForNULL($UTC_Date->format("Y-m-d H:i:s"));
							$DataValue->SiteID = dbTools::checkForNULL($SiteToUse->SiteID); 
							$DataValue->VariableID = dbTools::checkForNULL($colSet->Variable->VariableID); 
							$DataValue->OffsetValue = dbTools::checkForNULL($_SITE_OffsetValue); 
							$DataValue->OffsetTypeID = dbTools::checkForNULL($_SITE_OffsetTypeID); 
							$DataValue->CensorCode = dbTools::checkForNULL($_SITE_CensorCode); 
							$DataValue->QualifierID = dbTools::checkForNULL($_SITE_QualifierID);
							$DataValue->MethodID = dbTools::checkForNULL($colSet->Method->MethodID);
							$DataValue->SourceID = dbTools::checkForNULL($SourceToUse->SourceID); 
							$DataValue->SampleID = dbTools::checkForNULL($_SITE_SampleID); 
							$DataValue->DerivedFromID = dbTools::checkForNULL($_SITE_DerivedFromID); 
							$DataValue->QualityControlLevelID = dbTools::checkForNULL($_SITE_QualityControlLevelID); 
							$DataValuesToSave[$rowI][$colI] =$DataValue;
						}
					}
				}
			}
		}
		//var_dump($DataValuesToSave);
		//#type $dValue DataValue
		$errorCount = 0;
		$rowSuccessCount = 0;
		$dataValueSuccessCount = 0;
		foreach ($DataValuesToSave as $rowI => $dValueRow){
			$firstCell = true;
			foreach ($dValueRow as $colI => $dValue){
				if(DAL::Get()->AddDataValue($dValue)){
					$dataValueSuccessCount++;
				}else{
					$errorCount++;
					echo "<p class=\"";
					echo "error\">There was an error trying to add ";
					echo $columnSettings[$colI]->Variable->VariableName;
					echo  " value ";
					echo $dValue->DataValue;
					echo " ".$columnSettings[$colI]->Unit->unitsAbbreviation;
					echo "</p>";
				}
				if($firstCell){
					$rowSuccessCount++;
					$firstCell = false;
				}
			}
		}
		echo "<p class=\"";
		echo "success\">Successfully saved ";
		echo $rowSuccessCount." rows, containing ";
		echo $dataValueSuccessCount." data values";
		echo "</p>";
	}
	function getColumnSettingsWithDateColumn($fileMatrix,&$columnSettings,&$columnDate){
		foreach($fileMatrix[0] as $colI => $colData){
			//#type $colSet ColumnSetting
			$colSet = getColumnSettingsFromPOST($colI);
			fillColumnDate($columnDate,$colSet);
			$columnSettings[$colSet->Index] = $colSet;
		}
	}
	function fillColumnDate(&$columnDate,$colSet){
		if (isset($columnDate->DateFormat)&&isset($columnDate->TimeFormat))
		{
			return;
		}
		if($colSet->IsDateTime){
			$columnDate->DateIndex = $colSet->Index;
			$columnDate->TimeIndex = $colSet->Index;
			if(isset($_POST["formatPickerDateTime"]))
				$columnDate->DateFormat = $_POST["formatPickerDateTime"];
			else
				$columnDate->DateFormat = $_POST["formatDateTime"];
			$columnDate->TimeFormat = "";
		}elseif($colSet->IsDate){
			$columnDate->DateIndex = $colSet->Index;
			if(isset($_POST["formatPickerDateOnly"]))
				$columnDate->DateFormat = $_POST["formatPickerDateOnly"];
			else
				$columnDate->DateFormat = $_POST["formatDateOnly"];
		}elseif($colSet->IsTime){
			$columnDate->TimeIndex = $colSet->Index;
			if(isset($_POST["formatPickerTimeOnly"]))
				$columnDate->TimeFormat = $_POST["formatPickerTimeOnly"];
			else
				$columnDate->TimeFormat = $_POST["formatTimeOnly"];
		}
		$columnDate->Offset = $_POST["timeOffset"];
	}
	function getColumnSettings($fileMatrix){
		$columnSets;
		foreach ($fileMatrix[0] as $colI => $colData){
			$columnSets[$colI] = getColumnSettingsFromPOST($colI);
		}
		return $columnSets;
	}
	
	function getColumnSettingsFromPOST($colIndex){
		$retSetting = new ColumnSetting();
		$retSetting->Index = $colIndex;
		// assume ignored. because at the end stage ignored columns will not appear
		$colVariableID = -10; 
		if (isset($_POST["variable_col_$colIndex"]))
			$colVariableID = (int)$_POST["variable_col_$colIndex"];
		$retSetting->IsIgnored = $colVariableID <= -10;
		$retSetting->IsDateTime = $colVariableID == -1;
		$retSetting->IsDate = $colVariableID == -2;
		$retSetting->IsTime = $colVariableID == -3;
		
		if (!$retSetting->IsIgnored){
			if($colVariableID < 0){
				// is date field
				$selFormat = null;
				if ($retSetting->IsDateTime){
					if (isset($_POST["formatPickerDateTime"]))
						$selFormat = $_POST["formatPickerDateTime"];
					if ($selFormat == null || $selFormat == "-1")
						$selFormat = $_POST["formatDateTime"];
					$retSetting->TimeOffset = $_POST["timeOffset"];// getTimeOffset($selFormat);
				}elseif($retSetting->IsDate){
					if (isset($_POST["formatPickerDateOnly"]))
						$selFormat = $_POST["formatPickerDateOnly"];
					if ($selFormat == null || $selFormat == "-1")
						$selFormat = $_POST["formatDateOnly"];
				}elseif($retSetting->IsTime){
					if (isset($_POST["formatPickerTimeOnly"]))
						$selFormat = $_POST["formatPickerTimeOnly"];
					if ($selFormat == null || $selFormat == "-1")
						$selFormat = $_POST["formatTimeOnly"];
					$retSetting->TimeOffset = $_POST["timeOffset"];// getTimeOffset($selFormat);
				}
				
				$retSetting->Format = $selFormat;
			}else{	
				// do not bother filling ignored columns
				// is other type of field
				$retSetting->Variable = DAL::Get()->Variable($colVariableID);
				$retSetting->Unit = DAL::Get()->Unit($retSetting->Variable->VariableunitsID);//($_POST["unit_col_$colIndex"]);
				$retSetting->Method = DAL::Get()->Method($_POST["method_col_$colIndex"]);
			}
			
		}
		return $retSetting;
	}
	function containsTimeOffset($format){
		global $timeOffsetCodes;
		$timeformat = trim($format);
		$lastChar = substr($timeformat,-1);
		return in_array($lastChar,$timeOffsetCodes);
	}
	/*function getTimeOffset($format){
		if (!containsTimeOffset($format)){
			$offsetValue;
			if(isset($_POST["fileToUse"])){	
				$tempDate = new DateTime();
				$tempDate->setTimezone(new DateTimeZone($_POST["timeOffset"]));
				$offsetValue = $tempDate->getOffset();
			}else{
				$offsetValue = $_POST["timeOffset"];
			}
			return $offsetValue;
	}}*/
	/**
	 Class ColumnSetting
	*/
	class ColumnSetting
	{
		public $Index;
		public $IsIgnored; // true if column was set to be ignored.
		public $IsDateTime;// true if datetime field, there can be only one of these.
		public $IsDate;// true if date only field, there can be only one of these.
		public $IsTime;// true if time only field, there can be only one of these.
		public $Format; // field format if date or time or datetime;
		public $TimeOffset; // amount of GMT offset if time needs it (not included in Format)
		
		public $Variable; // Variable Object
		public $Unit; // Unit Object
		public $Method; // Method Object
	}
?>
