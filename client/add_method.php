<?php

//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
require_once 'authorization_check.php';

//redirect anyone that is not an administrator
	if (!isAdmin()){
	header("Location: index.php?state=pass2");
	exit;	
	}
 require_once "_html_parts.php";


	HTML_Render_Head();
	
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
                $("#jqxWidget").jqxListBox({source: dataAdapter, theme: 'darkblue', multiple: true, width: '80%', height: 300, displayMember: "variablename", valueMember: "variableid"});

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
    <div class='col-md-9'>
<br /><p class="em" align="right"><span class="requiredInstructions"><?php echo $RequiredFieldsAsterisk; ?></span></p><div id="msg"><p class=em2><?php echo $MethodSuccessfully;?></p></div>
      <h1><?php echo $AddNewMethod;?></h1>
      <p>&nbsp;</p>
      <FORM METHOD="POST" class="form-horizontal" ACTION="" name="addmethod" id="addmethod">
      
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $MethodName; ?></label>
        <div class="col-sm-9">
     	   <input type="text" class="form-control" id="MethodDescription" name="MethodDescription" /><span class="required">*</span>
           <span class="help-block"><br/><?php echo $ExampleMethodName;?></span>
		</div>             
      </div>
      
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $MethodLinkColon;?></label>
        <div class="col-sm-9">
     	   <input type="text" class="form-control" id="MethodLink" name="MethodLink" maxlength="200"/>
           <span class="help-block"><br/><?php echo $ExMethodLink;?></span>
		</div>             
      </div>
      <?php echo $SelectVariablesBelow1;?><span class="required">*</span><br>
      <span class="help-block"><?php echo $SelectAllThatApply;?></span>
      <div id='jqxWidget'></div>
       <input type="SUBMIT" name="submit" value="<?php echo $AddMethodButton;?>" class="button" style="width: auto"/>
        </FORM>
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
