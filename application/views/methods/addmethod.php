<?php
HTML_Render_Head($js_vars);
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
	$("#jqxWidget").jqxListBox({source: dataAdapter, theme: 'darkblue', multiple: true, width: 400, height: 300, displayMember: "VarNameMod", valueMember: "VariableID"});
});
</script>
<?php HTML_Render_Body_Start();
genHeading('AddNewMethod',true);
$attributes = array('class' => 'form-horizontal', 'name' => 'addmethod', 'id' => 'addmethod');
echo form_open('methods/add', $attributes);
genInput('MethodName','MethodDescription','MethodDescription',true);echo '<span class="em">'.getTxt('ExampleMethodName').'</span>';
genInput('MethodLinkColon','MethodLink','MethodLink',true);echo '<span class="em">'.getTxt('ExMethodLink').'</span>';
?>
<div class="form-group">
  <div class = "col-md-12">
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

