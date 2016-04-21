<?php
HTML_Render_Head($js_vars,getTxt('AddData')); 
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
echo $JS_FormValidation;
echo $JS_Forms;
echo $CSS_JQStyles;
echo $CSS_Main;

HTML_Render_Body_Start();

genHeading('Import',true);
echo "<p>".getTxt('MustConform')."</p>";
?>
<p>LocalDateTime,DataValue<br>
        2012-05-31 00:00:00,10.99<br>
        2012-05-31 00:10:00,11.01<br>
        2012-05-31 00:20:00,11.02<br>
        2012-05-31 00:30:00,11.04<br></p>
<?php
echo "<p>".getTxt('ImportInstructionsNew')."<a href='".base_url("assets/samples/sample1.csv")."'>Sample 1</a> 
<a href='".base_url("assets/samples/sample2.csv")."'>Sample 2</a> 
<a href='".base_url("assets/samples/sample3.csv")."'>Sample 3</a> 
<a href='".base_url("assets/samples/sample4.csv")."'>Sample 4</a>
</p>";
$attributes = array('class' => 'form-horizontal', 'name' => 'importfile', 'id' => 'importfile');
echo form_open_multipart('datapoint/importfile', $attributes);
?>

<div class="form-group">
<label class="col-sm-3 control-label"><?php echo getTxt('optionsFile');?></label>
<div class="col-sm-9">
<input type="checkbox" id="valueSpec" name="valueSpec" value="1">
</div></div>
<div id="mainContent">
<?php
genSelect('Source',"SourceID","SourceID",$sourcesOptions,'SelectEllipsis',true,'onChange="showSites(this.value)"');
genSelectH('Site',"SiteID","SiteID",'',getTxt('IfNoSeeSite1').' '.getTxt('ContactSupervisor').' '.getTxt('AddIt'),'SelectElipsis',true);
genSelect('Variable',"VariableID","VariableID",$variableOptions,'SelectEllipsis',true,'onChange="showMethods(this.value)"');
genSelectH('Method',"MethodID","MethodID",'',getTxt('IfNoSeeMethod1').' '.getTxt('ContactSupervisor').' '.getTxt('AddIt'),'SelectElipsis',true);
?>
</div>
<?php
echo "<p>".getTxt('UploadCSV')."</p>";
echo '<div class="form-group">
        <label class="col-sm-3 control-label">'.getTxt('SelectFile').'s:</label>
        <div class="col-sm-9">
     	   <input type="file" class="form-control" id="files" name="files[]" multiple>';	   
		  echo '<span class="required"/>';	  
	echo'</div>             
      </div>';	
genSubmit('SubmitData');
HTML_Render_Body_End(); ?>
<script type="text/javascript">

$('#valueSpec').click(function () {
    if($('#valueSpec').is(':checked'))
	{
		$("#mainContent").hide(200);
	}
	else
	{
		$("#mainContent").show(200);
	}
});

$("#importfile").submit(function() {
	
	if(!$('#valueSpec').is(':checked'))
	{
		if(($("#SourceID option:selected").val())==-1){
			alert(<?php echo "'".getTxt('SelectSource')."'";?>);
			return false;
		}
	
		if(($("#SiteID option:selected").val())==-1){
			alert(<?php echo "'".getTxt('SelectSite')."'";?>);
			return false;
		}
		
		if(($("#VariableID option:selected").val())==-1){
			alert(<?php echo "'".getTxt('SelectType')."'";?>);
			return false;
		}
	
		if(($("#MethodID option:selected").val())==-1){
			alert(<?php echo "'".getTxt('SelectMethodMsg')."'";?>);
			return false;
		}
	}
	var files = $('#files').prop("files")
	if(files.length<1){
		alert(<?php echo "'".getTxt('SelectFileEllipsis')."'";?>);
		return false;
	}
	var names = $.map(files, function(val) { return val.name; });
	flag=1;
	names.forEach(function(name)
	{
		var ext = name.split('.').pop().toLowerCase();
		if (ext !== "csv" && ext !== "xls" && ext !== "xlsx") {
			flag=0;
		}
	});
	if(!flag)
	{
		alert(<?php echo "'".getTxt('InvalidFormat')."'";?>);
		return false;
	}
});
</script>
