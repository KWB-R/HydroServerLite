<?php

//This is required to get the international text strings dictionary
require_once 'internationalize.php';
//check authority to be here
require_once 'authorization_check.php';

//All queries go through a translator. 
require_once 'DBTranslator.php';
require_once "_html_parts.php";

$option_block = "";
$option_block3 = "";
$msg = "";
$msg3 ="";
$msg4 = "";

//add the SourceID's
$sql ="Select distinct SourceID, Organization FROM seriescatalog";

$result = transQuery($sql,0,0);

$num = count($result);
	if ($num < 1) {

	$msg = "<P><em2> $SorryNoSource </em></p>";

	} else {

	foreach ($result as $row) {

		$sourceid = $row["SourceID"];
		$sourcename = $row["Organization"];

		$option_block .= "<option value=$sourceid>$sourcename</option>";

		}
	}

//add the Variables
$sql3 ="Select * FROM variables ORDER BY VariableName ASC";

$data = transQuery($sql3,0,1);

$num = count($data);
	if ($num < 1) {

	$msg3 = "<P><em2>$SorryNoVariable</em></p>";

	} else {

	foreach ($data as $row3) {

		$typeid = $row3["VariableID"];
		$typename = $row3["VariableName"];
		$datatype = $row3["DataType"];

		$option_block3 .= "<option value=$typeid>$typename ($datatype)</option>";

		}
	}

HTML_Render_Head(); 

echo $JS_JQuery;

echo $JS_JQueryUI;

echo $JS_FormValidation;

echo $CSS_JQuery_UI;

echo $JS_Forms;

echo $CSS_JQStyles;

echo $CSS_Main;
?>

<script type="text/javascript">

	$(document).ready(function(){
		$("#msg").hide();
	});

	$(function() {
		

	$( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd", constrainInput: false, showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true, monthNames: [<?php echo "'".$Jan."'"; ?>, <?php echo "'".$Feb."'"; ?>, <?php echo "'".$Mar."'"; ?>, <?php echo "'".$Apr."'"; ?>, <?php echo "'".$May."'"; ?>, <?php echo "'".$Jun."'"; ?>, <?php echo "'".$Jul."'"; ?>, <?php echo "'".$Aug."'"; ?>, <?php echo "'".$Sep."'"; ?>, <?php echo "'".$Oct."'"; ?>, <?php echo "'".$Nov."'"; ?>, <?php echo "'".$Dec."'"; ?>], dayNamesMin: [<?php echo "'".$Su."'"; ?>, <?php echo "'".$Mo."'"; ?>, <?php echo "'".$Tu."'"; ?>, <?php echo "'".$We."'"; ?>, <?php echo "'".$Th."'"; ?>, <?php echo "'".$Fr."'"; ?>, <?php echo "'".$Sa."'"; ?>]});
	$( "#timepicker" ).timepicker({ showOn: "focus", showPeriodLabels: false, hourText: <?php echo "'".$Hour."'";?>, minuteText: <?php echo "'".$Minute."'"; ?>, closeButtonText: <?php echo "'".$Done."'"; ?>, nowButtonText: <?php echo "'".$Now."'"; ?>, deselectButtonText: <?php echo "'".$Deselect."'"; ?> });
		
	});
</script>

<?php HTML_Render_Body_Start(); ?>
<br /><p class="em" align="right"><span class="requiredInstructions"><?php echo $RequiredFieldsAsterisk; ?> </span></p><?php echo "$msg"; ?>&nbsp;<?php echo "$msg3"; ?>&nbsp;<?php echo "$msg4"; ?>
        <div id="msg"><p class=em2><?php echo $ValueSuccessfully; ?></p></div>
        <h1><?php echo $EnterSingleDataValue; ?></h1>
        <p>&nbsp;</p>
      <FORM METHOD="POST" ACTION="" name="addvalue" id="addvalue">
        <table width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><strong><!--Source:--> <?php echo $Source; ?></strong></td>
          <td valign="top"><select name="SourceID" id="SourceID" onChange="showSites(this.value)"><option value="-1"><!--Select....--><?php echo $SelectEllipsis; ?></option><?php echo "$option_block"; ?></select><span class="required">*</span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td valign="top"><strong><?php echo $Site; ?></strong></td>
          <td valign="top"><div id="txtHint"><select name="SiteID" id="SiteID"><option value="-1"><?php echo $SelectElipsis; ?></option></select><span class="required">*</span><span class="hint" title="If you do not see your <?php echo $__Site->Capitalized; ?> listed here, please contact your supervisor and ask them to add it before entering data.">?</span></div> 
</td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td width="55" valign="top"><strong><?php echo $Variable; ?></strong></td>
          <td width="370" valign="top"><select name="VariableID" id="VariableID" onChange="showMethods(this.value)"><option value="-1"><?php echo $SelectElipsis; ?></option><?php echo "$option_block3"; ?></select><span class="required">*</span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td valign="top"><strong><?php echo $Method; ?>:</strong></td>
          <td valign="top"><div id="txtHint2"><select name="MethodID" id="MethodID"><option value="-1"><?php echo $SelectElipsis; ?></option></select><span class="required">*</span><span class="hint" title="<?php echo "'". $IfNoSeeMethod1."'";?> + '\n' + <?php echo "'". $ContactSupervisor."'";?> + '\n' + <?php echo "'". $AddIt."'";?>"></span></div></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td width="370" valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td width="55" valign="top"><strong><!--Date:--><?php echo $Date; ?></strong></td>
          <td valign="top"><input type="text" id="datepicker" name="datepicker" onChange="return validateDate()"><span class="required">*</span><span class="em"><?php echo $DateFormatExample; ?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td width="55" valign="top"><strong><!--Time:--><?php echo $Time; ?></strong></td>
          <td valign="top"><input type="text" id="timepicker" name="timepicker" onChange="return validateTime()" class="short"><span class="required">*</span><span class="em"><?php echo $TimeFormatExample; ?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td width="55" valign="top"><strong><?php echo $Value; ?></strong></td>
          <td valign="top"><input type="text" id="value" name="value" class="short" maxlength=20 onBlur="return validateNum()"/><span class="required">*</span><span class="em"><?php echo $NumberNoCommas; ?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td width="55" valign="top">&nbsp;</td>
          <td valign="top"><input type="SUBMIT" name="submit" value= "<?php echo $SubmitData; ?>" class="button" style="width: auto" />&nbsp;&nbsp;<input type="reset" name="Reset" value="<?php echo $Cancel; ?>" class="button" style="width: auto" /></td>
          </tr>
      </table>
    </FORM></p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </blockquote>
    <p></p>
	<?php HTML_Render_Body_End(); ?>
<script>

//Calls a function to validate all fields when the submit button is hit.
$("form").submit(function(){

	if(($("#SourceID option:selected").val())==-1){

		alert(<?php echo "'".$SelectSource ."'";?>);
		return false;
	}

	if(($("#SiteID option:selected").val())==-1){

		alert(<?php echo "'".$SelectSite."'";?>);
		return false;
	}

	if(($("#VariableID option:selected").val())==-1){
		alert(<?php echo "'".$SelectVariableMsg."'";?>);
		return false;
	}

	if(($("#MethodID option:selected").val())==-1){
		alert(<?php echo "'".$SelectMethodMsg."'";?>);
		return false;
	}
	
	//Date checking
	var checkid='datepicker';
	var result=validatedate(checkid);

	if(result==false){
		return false;
	}

	//Time checking
	checkid='timepicker';
	var result=validatetime(checkid);

	if(result==false){
		return false;
	}

	//Value checking
	checkid='value';

	if(validatenum(checkid)==false){
		return false;
	}
	
	
	//Value validation
	
	var vt = $('#' + checkid).val();
	

var tv=$("#VariableID option:selected").val();

switch(tv)
{
case "19":
if((vt<0)||(vt>100))
{
alert(<?php echo "'".$ValueBetweenZeroAndHundred."'";?>);
		return false;
}
break;
case "13":
case "22":
if((vt<0)||(vt>14))
{
alert(<?php echo "'".$ValueBetweenZeroAndFourteen."'";?>);
		return false;
}
break;
case "7":
case "24":break;
default:
if(vt<0)
{
alert(<?php echo "'".$ValueLessThanZero."'";?>);
		return false;
}
  break;
}


//Validation is now complete, so send to the processing page
$.post("do_add_data_value.php", $("#addvalue").serialize(),  function( data ){

		if(data.search("1")!=-1){
			$("#msg").show(1600);
			$("#SourceID").val(-1);
			$("#SiteID").val(-1);
			$("#VariableID").val(-1);
			$("#MethodID").val(-1);
			$("#datepicker").val("");
			$("#timepicker").val("");
			$("#value").val("");
			$("#msg").hide(1000);
			return true;
		}else{
			alert(data);
			alert(<?php echo "'".$ProcessingError."'";?>);
			return false;
			}
	});

return false;
});
	
</script>
