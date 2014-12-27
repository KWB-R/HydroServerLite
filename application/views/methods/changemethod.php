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

	$('#window').jqxWindow({ height: 150, width: 200, theme: 'darkblue' });
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
$attributes = array('class' => 'form-horizontal');
echo form_open('methods/change', $attributes);
genSelect('Method',"MethodID","MethodID",$methodOptions,'SelectEllipsis',true,'onChange="editMethod()"');
?>
<strong class="em2"><?php echo getTxt('NoteColon');?></strong>
<span class="em"><?php echo getTxt('MethodNote');?></span>
</FORM>
<div id="msg3"></div>
<div id="window">
<div id="windowHeader">
    <span><?php echo getTxt('ConfirmationBox');?></span>
</div>
    <div style="overflow: hidden;" id="windowContent"><center><strong><?php echo getTxt('AreYouSure');?></strong><br /><br /><input name="Yes" type="button" value="<?php echo getTxt('Yes');?>" id="Yup"/>&nbsp;<input name="No" type="button" value="<?php echo getTxt('No');?>" id="No"/></center></div>
</div>
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
		dataType: "json",
		url: base_url+"methods/delete/"+methodid+"?ui=1"}).done(function(data){
			
			if(data.status=="success"){
				window.open(base_url+"methods/change/","_self");
				return true;
			}else{
				alert(<?php echo "'".getTxt('ProcessingError')."'"; ?>);
				return false;
				}
		}).fail(function(data){alert(<?php echo "'".getTxt('ProcessingError')."'"; ?>);console.log(data);});		
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
$("#editmethod").submit(function (){

	if(($("#MethodDescription2").val())==''){
		
		alert(<?php echo "'".getTxt('MethodNameRequired')."'"; ?>);
		return false;
	
	}else{
		return true;
	}
});

//When the "Cancel" button is clicked, clear the fields and reload the page
function clearEverything(){
	window.open(base_url+"methods/change/","_self");
}

</script>