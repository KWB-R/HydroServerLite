<?php
HTML_Render_Head($js_vars); 
?>
<script type="text/javascript">
//For loading language variables that are required in javascript.
    phpVars = {};
    <?php  
        echo 'phpVars.NoMethodsVariable="' . getTxt('NoMethodsVariable') . '";';
		echo 'phpVars.NoSitesSource="' . getTxt('NoSitesSource') . '";';
		echo 'phpVars.SelectVariable="' . getTxt('SelectVariable') . '";';
		echo 'phpVars.SelectSite="' . getTxt('SelectSite2') . '";';
		
    ?>
</script>
<?php
echo $JS_JQuery;
echo $JS_JQueryUI;
echo $JS_FormValidation;
echo $CSS_JQuery_UI;
echo $JS_Forms;
echo $CSS_JQStyles;
echo $CSS_Main;
?>
<script type="text/javascript">
$(function() {
	$( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd", constrainInput: false, showOn: "button", buttonImage: "<?php echo getImg('calendar.gif')?>", buttonImageOnly: true, monthNames: [<?php echo "'".getTxt('Jan')."'"; ?>, <?php echo "'".getTxt('Feb')."'"; ?>, <?php echo "'".getTxt('Mar')."'"; ?>, <?php echo "'".getTxt('Apr')."'"; ?>, <?php echo "'".getTxt('May')."'"; ?>, <?php echo "'".getTxt('Jun')."'"; ?>, <?php echo "'".getTxt('Jul')."'"; ?>, <?php echo "'".getTxt('Aug')."'"; ?>, <?php echo "'".getTxt('Sep')."'"; ?>, <?php echo "'".getTxt('Oct')."'"; ?>, <?php echo "'".getTxt('Nov')."'"; ?>, <?php echo "'".getTxt('Dec')."'"; ?>], dayNamesMin: [<?php echo "'".getTxt('Su')."'"; ?>, <?php echo "'".getTxt('Mo')."'"; ?>, <?php echo "'".getTxt('Tu')."'"; ?>, <?php echo "'".getTxt('We')."'"; ?>, <?php echo "'".getTxt('Th')."'"; ?>, <?php echo "'".getTxt('Fr')."'"; ?>, <?php echo "'".getTxt('Sa')."'"; ?>]});
	$( "#timepicker" ).timepicker({ showOn: "focus", showPeriodLabels: false, hourText: <?php echo "'".getTxt('Hour')."'";?>, minuteText: <?php echo "'".getTxt('Minute')."'"; ?>, closeButtonText: <?php echo "'".getTxt('Done')."'"; ?>, nowButtonText: <?php echo "'".getTxt('Now')."'"; ?>, deselectButtonText: <?php echo "'".getTxt('Deselect')."'"; ?> });
		
	});
</script>
<?php HTML_Render_Body_Start();
genHeading('EnterSingleDataValue',true);
$attributes = array('class' => 'form-horizontal', 'name' => 'addvalue', 'id' => 'addvalue');
echo form_open('datapoint/addvalue', $attributes);
genSelect('Source',"SourceID","SourceID",$sourcesOptions,'SelectEllipsis',true,'onChange="showSites(this.value)"');
genSelectH('Site',"SiteID","SiteID",'',getTxt('IfNoSeeSite1').' '.getTxt('ContactSupervisor').' '.getTxt('AddIt'),'SelectElipsis',true);
genSelect('Variable',"VariableID","VariableID",$variableOptions,'SelectEllipsis',true,'onChange="showMethods(this.value)"');
genSelectH('Method',"MethodID","MethodID",'',getTxt('IfNoSeeMethod1').' '.getTxt('ContactSupervisor').' '.getTxt('AddIt'),'SelectElipsis',true);
genInputT('Date','datepicker','datepicker',true,'onChange="return validateDate()"','DateFormatExample');
//echo '<span class="help-block"><br />'.getTxt('DateFormatExample').'</span>';
genInputT('Time','timepicker','timepicker',true,'onChange="return validateTime()" class="short"','TimeFormatExample');
//echo '<span class="help-block"><br />'.getTxt('TimeFormatExample').'</span>';
genInputT('Value','value','value',true,'class="short" maxlength=20 onBlur="return validateNum()"','NumberNoCommas');
//echo '<span class="badge"><br />'.getTxt('NumberNoCommas').'</span>';
?>
<div class="col-md-5 col-md-offset-5">
<input type="SUBMIT" name="submit" value= "<?php echo getTxt('SubmitData'); ?>" class="button" style="width: auto" />
<input type="reset" name="Reset" value="<?php echo getTxt('Cancel'); ?>" class="button" style="width: auto" />
</FORM>
</div>
</div>
<?php HTML_Render_Body_End(); ?>
<script>
//Calls a function to validate all fields when the submit button is hit.
$("form").submit(function(){

	if(($("#SourceID option:selected").val())==-1){

		alert(<?php echo "'".getTxt('SelectSource')."'";?>);
		return false;
	}

	if(($("#SiteID option:selected").val())==-1){

		alert(<?php echo "'".getTxt('SelectSite')."'";?>);
		return false;
	}

	if(($("#VariableID option:selected").val())==-1){
		alert(<?php echo "'".getTxt('SelectVariableMsg')."'";?>);
		return false;
	}

	if(($("#MethodID option:selected").val())==-1){
		alert(<?php echo "'".getTxt('SelectMethodMsg')."'";?>);
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
return true;
});
	
</script>
