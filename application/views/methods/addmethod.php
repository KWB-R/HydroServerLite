<?php
HTML_Render_Head($js_vars,getTxt('AddMethodButton'));
echo $JS_JQuery; // only Jquery needed.
echo $CSS_JQX;
echo $JS_JQX;
echo $JS_GetTheme;
echo $CSS_Main;
?>
<script type="text/javascript">
	var varmeth="";
	$(document).ready(function(){
	$("#msg").hide();
	var source =
	{
       	datatype: "json",
	   	datafields: [
          	{ name: 'VarNameMod' },
	        { name: 'VariableID' },
       	],
           	url: base_url+'variable/getAllJSON2'
	};			
	
	var dataAdapter = new $.jqx.dataAdapter(source);
	// Create a jqxListBox
	$("#jqxWidget").jqxListBox({source: dataAdapter, theme: 'darkblue', multiple: true, width: '94%', height: 300, displayMember: "VarNameMod", valueMember: "VariableID"});
});
</script>
<?php HTML_Render_Body_Start();
genHeading('AddNewMethod',true);
$attributes = array('class' => 'form-horizontal', 'name' => 'addmethod', 'id' => 'addmethod');
echo form_open('methods/add', $attributes);
genInputT('MethodName','MethodDescription','MethodDescription',true,$extra="",'ExampleMethodName');
//echo '<span class="em">'.getTxt('ExampleMethodName').'</span>';
genInputT('MethodLinkColon','MethodLink','MethodLink',false,$extra="",'ExMethodLink');
//echo '<span class="em">'.getTxt('ExMethodLink').'</span>';
?>
<div class="form-group">
	<label class="col-sm-2 control-label"></label>
  <div class = "col-md-8">
  <label><?php echo getTxt('SelectVariablesBelow1');?></label>
  <label><?php echo getTxt('SelectAllThatApply');?></label>
  <div id="jqxWidget"></div><span class="required">*</span>
  </div>
</div>

<div class="col-md-5 col-md-offset-5">
       <input type="SUBMIT" name="submit" value="<?php echo getTxt('AddMethodButton');?>" class="button"/>
       <input type="reset" name="Reset" value="<?php echo getTxt('Cancel'); ?>" class="button" style="width: auto" />
</div>
</div>
<?php HTML_Render_Body_End(); ?>
<script>
//Calls a function to validate all fields when the submit button is hit.
$("form").submit(function(){
	if(($("#MethodDescription").val())==''){
		alert("Please enter a Method Name!");
		return false;
	}
	if($("#jqxWidget").val()=="")
	{alert("Please select at least one variable!");
		return false;
	}
return true;
});
</script>

