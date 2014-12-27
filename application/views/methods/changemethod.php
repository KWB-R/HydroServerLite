<?php
HTML_Render_Head($js_vars);
echo $CSS_JQX;
echo $JS_GetTheme;
echo $JS_JQuery;
echo $JS_JQX;
echo $CSS_Main;
?>
<script type="text/javascript">
$(document).ready(function(){
	$('#window').hide();

	$('#window').jqxWindow({ height: 100, width: 200, theme: 'darkblue' });
    	$('#window').jqxWindow('hide');

	$("#Yup").click(function(){
		deleteMethod();	
		$("#window").jqxWindow('hide');
	 });
	 
	$("#No").click(function(){
    	$('#window').jqxWindow('hide');
	});
});	

function confirmBox(){
	
$('#window').show();
    $('#window').jqxWindow('show');
}

</script>

<?php HTML_Render_Body_Start(); 
genHeading('EditDeleteMethod',true);
echo '<p>'.getTxt('SelectMethod').'</p>';
$attributes = array('class' => 'form-horizontal', 'name' => 'addvalue', 'id' => 'addvalue');
echo form_open('datapoint/addvalue', $attributes);
genSelect('Source',"SourceID","SourceID",$sourcesOptions,'SelectEllipsis',true,'onChange="showSites(this.value)"');
genSelectH('Site',"SiteID","SiteID",'',getTxt('IfNoSeeSite1').' '.getTxt('ContactSupervisor').' '.getTxt('AddIt'),'SelectElipsis',true);
genSelect('Variable',"VariableID","VariableID",$variableOptions,'SelectEllipsis',true,'onChange="showMethods(this.value)"');
genSelectH('Method',"MethodID","MethodID",'',getTxt('IfNoSeeMethod1').' '.getTxt('ContactSupervisor').' '.getTxt('AddIt'),'SelectElipsis',true);
genInput('Date','datepicker','datepicker',true,'onChange="return validateDate()"');
?>
<br /><p class="em" align="right"><?php echo getTxt('RequiredFieldsAsterisk');?></p><?php echo getTxt('msg_1'); ?><div id="msg"><p class=em2><!--Method successfully deleted!--><?php echo getTxt('MethodDeleted');?></p></div>
    <div id="msg2"><p class=em2><?php echo getTxt('MethodEdited');?></p></div>
      <h1><?php echo getTxt('EditDeleteMethod');?></h1>
      <p><?php echo getTxt('SelectMethod');?></p>
      <FORM METHOD="POST" ACTION="" name="changemethod" id="changemethod">
        <table width="620" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><strong><!--Method:--><?php echo getTxt('Method');?></strong></td>
          <td colspan="2" valign="top"><select name="MethodID" id="MethodID" onChange="editMethod()">
            <option value="-1"><!--Select....--><?php echo getTxt('SelectEllipsis');?></option>
            <?php echo "$option_block"; ?></select></td>
        </tr>
        <tr>
          <td width="60" valign="top">&nbsp;</td>
          <td width="280" valign="top">&nbsp;</td>
          <td width="280" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top"><strong class="em2"><!--NOTE:--><?php echo getTxt('NoteColon');?></strong> <span class="em"><!--You cannot delete a Method without first deleting all data values which use the specific Method.--><?php echo getTxt('MethodNote');?></span></td>
          </tr>
        </table>
      </FORM>
      
    <div id="msg3"></div>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
<div id="window">
	<div id="windowHeader">
		<span><!--Confirmation Box--><?php echo getTxt('ConfirmationBox');?></span>
	</div>
    <div style="overflow: hidden;" id="windowContent"><center><strong><?php echo getTxt('AreYouSure');?></strong><br /><br /><input name="Yes" type="button" value="<?php echo getTxt('Yes');?>" id="Yup"/>&nbsp;<input name="No" type="button" value="<?php echo getTxt('No');?>" id="No"/></center></div>
</div>
<?php HTML_Render_Body_End(); ?>

<script>
//When the "Delete" button is clicked, validate the method selected and then submit the request
function deleteMethod(){

	if(($("#MethodID").val())==-1){
		alert(<?php echo "'".getTxt('SelectMethodDelete')."'"; ?>);
		return false;
		
	}else{
	
	//Validation is now complete, so send to the processing page
	var methodid = $("#MethodID").val();
	
		$.ajax({
		type: "POST",
		url: "delete_method.php?MethodID="+methodid}).done(function(data){
			
			if(data==1){
				
				$("#msg").show(1600);
				$("#MethodID").val("-1");
				$("#msg").hide(2000);
				setTimeout( function() {
					window.open("change_method.php","_self");
					}, 3800 );
				return true;
	
			}else{
				
				alert(<?php echo "'".getTxt('ProcessingError')."'"; ?>);
				return false;
				}
		});		
	}
return false;
};

//When the "Edit" button is clicked, validate the field and do a query to fill in the form for changes.
function editMethod(){
	
	if(($("#MethodID").val())==-1){
		alert(<?php echo "'".getTxt('SelectMethodEdit')."'"; ?>);
		return false;
		
	}else{

		//Validation is now complete, so go get the info needed for this method
		var methodid=$("#MethodID").val();
	
		$.ajax({
		url: base_url+"methods/methodinfo/"+methodid}).done(function(data){
			if(data){
				
				$("#msg3").html(data);
				$("#msg3").show(500);
				return true;
			
			}else{
			
				alert(<?php echo "'".getTxt('ErrorDuringRequest')."'"; ?>);
				return false;
				}
		});
	}
return false;
}

//When the "Save Edits" button is clicked, validate the fields and then submit the request
function updateMethod(){

	if(($("#MethodDescription2").val())==''){
		
		alert(<?php echo "'".getTxt('MethodNameRequired')."'"; ?>);
		return false;
	
	}else{
	
		//Validation is now complete, so send to the processing page
		var method_ID=$("#MethodID2").val();
		var method_D=$("#MethodDescription2").val();
		var method_L=$("#MethodLink2").val();

		$.ajax({
		type: "POST",
		url: "update_method.php?MethodID2="+method_ID+"&MethodDescription2="+method_D+"&MethodLink2="+method_L}).done(function(data){
			
			if(data==1){

				$("#msg2").show(1600);
				$("#msg2").hide(2000);
				$("#MethodID2").val("");
				$("#MethodDescription2").val("");
				$("#MethodLink2").val("");
				$("#msg3").hide(4000);
				setTimeout( function() {
					window.open("change_method.php","_self");
					}, 5000 );
				return true;

			}else{

				alert(<?php echo "'".getTxt('ProcessingError')."'"; ?>);
				return false;
				}
		});

	return false;
	};
}

//When the "Cancel" button is clicked, clear the fields and reload the page
function clearEverything(){

	$("#MethodID").val("-1");
	$("#MethodID2").val("");
	$("#MethodDescription2").val("");
	$("#MethodLink2").val("");
				
	setTimeout( function() {
		window.open("change_method.php","_self");
		});
}

</script>