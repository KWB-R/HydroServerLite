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
?>
        <table width="620" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="80" valign="top"><strong><?php echo getTxt('MethodName'); ?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="MethodDescription" name="MethodDescription" maxlength="100"/><span class="required">*</span><span class="em"><?php echo getTxt('ExampleMethodName');?></span></td>
        </tr>
        <tr>
          <td width="80" valign="top">&nbsp;</td>
          <td width="260" valign="top">&nbsp;</td>
          <td width="280" valign="top">&nbsp;</td>
        </tr>
        <tr>

          <td valign="top"><strong><?php echo getTxt('MethodLinkColon');?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="MethodLink" name="MethodLink" maxlength="200"/>&nbsp;<span class="em"><?php echo getTxt('ExMethodLink');?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
        
            <td colspan="3" valign="top"><strong><?php echo getTxt('SelectVariablesBelow1');?><br>
            <?php echo getTxt('SelectAllThatApply');?></td>
          </tr>
        <tr>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" valign="top"><div id='jqxWidget'></div></td>
          <td valign="top"><span class="required">*</span></td>
          </tr>
        <tr>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top">
          <input type="SUBMIT" name="submit" value="<?php echo getTxt('AddMethodButton');?>" class="button" style="width: auto"/><input type="reset" name="Reset" value="<?php echo getTxt('Cancel'); ?>" class="button" style="width: auto" /></td>
          </tr>
      </table></FORM>
      <p>&nbsp;</p>
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

