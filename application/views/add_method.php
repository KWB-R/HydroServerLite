<?php


/*
//redirect anyone that is not an administrator
	if (!isAdmin()){
	header("Location: index.php?state=pass2");
	exit;	
	}
*/


	HTML_Render_Head($js_vars);
	
	echo $JS_JQuery; // only JQuerey needed.
	
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
          	{ name: 'variablename' },
	        { name: 'variableid' },
       	],
           	url: 'db_get_types.php'
	};			
	
	var dataAdapter = new $.jqx.dataAdapter(source);
                // Create a jqxListBox
                $("#jqxWidget").jqxListBox({source: dataAdapter, theme: 'darkblue', multiple: true, width: 400, height: 300, displayMember: "variablename", valueMember: "variableid"});

	 $("#jqxWidget").bind('change', function () {
					var items = $("#jqxWidget").jqxListBox('getItems');
					// get selected indexes.
var selectedIndexes = $("#jqxWidget").jqxListBox('selectedIndexes');
var selectedItems = [];
varmeth="";
// get selected items.
for (var index in selectedIndexes) {
if (selectedIndexes[index] != -1) {
selectedItems[index] = items[index];
varmeth+=selectedItems[index].value;
if(index!=(selectedIndexes.length-1))
{
varmeth+=",";	
}
}
}
	
                });
	});
</script>
	<?php HTML_Render_Body_Start(); ?>
<br /><p class="em" align="right"><span class="requiredInstructions"><?php echo getTxt('RequiredFieldsAsterisk'); ?></span></p><div id="msg"><p class=em2><?php echo getTxt('MethodSuccessfully');?></p></div>
      <h1><?php echo getTxt('AddNewMethod');?></h1>
      <p>&nbsp;</p>
      <FORM METHOD="POST" ACTION="" name="addmethod" id="addmethod">
        <table width="620" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="80" valign="top"><strong><?php echo getTxt('MethodName'); ?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="MethodDescription" name="MethodDescription" maxlength=100"/><span class="required">*</span><span class="em"><?php echo getTxt('ExampleMethodName');?></span></td>
        </tr>
        <tr>
          <td width="80" valign="top">&nbsp;</td>
          <td width="260" valign="top">&nbsp;</td>
          <td width="280" valign="top">&nbsp;</td>
        </tr>
        <tr>

          <td valign="top"><strong><?php echo getTxt('MethodLinkColon');?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="MethodLink" name="MethodLink" maxlength=200"/>&nbsp;<span class="em"><?php echo getTxt('ExMethodLink');?></span></td>
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
          <td colspan="3" valign="top"><input type="SUBMIT" name="submit" value="<?php echo getTxt('AddMethodButton');?>" class="button" style="width: auto"/><input type="reset" name="Reset" value="<?php echo getTxt('Cancel'); ?>" class="button" style="width: auto" /></td>
          </tr>
      </table></FORM>
      <p>&nbsp;</p>
    
	<?php HTML_Render_Body_End(); ?>

<script>
//Calls a function to validate all fields when the submit button is hit.
$("form").submit(function(){

	if(($("#MethodDescription").val())==''){
		alert("Please enter a Method Name!");
		return false;
	}

	
	if(varmeth=="")
	{alert("Please select at least one variable!");
		return false;
	}

$.post("do_add_method.php?varmeth="+varmeth, $("#addmethod").serialize(),  function( data ){
			if(data==1){
				$("#msg").show(1600);
				$("#MethodDescription").val("");
				$("#MethodLink").val("");
				$("#jqxWidget").jqxListBox('clearSelection');
				$("#msg").hide(1000);
				return true;
			}else{
				alert("Error in database configuration!");
				return false;
				}
		});


return false;
});

</script>



</body>
</html>
