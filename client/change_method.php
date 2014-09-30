<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';
//check authority to be here
require_once 'authorization_check.php';
//All queries go through a translator. 
require_once 'DBTranslator.php';

//redirect anyone that is not an administrator
	if (!isAdmin()){
	header("Location: index.php?state=pass2");
	exit;	
	}
require_once "_html_parts.php";

$sql ="SELECT * FROM methods WHERE MethodID >= 2";
$result = transQuery($sql,0,1);

	if (count($result) < 1){
	$msg_1 = "<p class=em2> $NoMethods </em></p>";
	} else {
		foreach ($result as $row) {
			$m_id = $row["MethodID"];
			$m_desc = $row["MethodDescription"];
			$option_block .= "<option value=$m_id>$m_desc</option>";
		}
	}
	HTML_Render_Head();

	echo $CSS_JQX;
	
	echo $JS_GetTheme;

	echo $JS_JQuery;
	
	echo $JS_JQX;
	
	echo $CSS_Main;
?>


<script type="text/javascript">

$(document).ready(function(){
	$("#msg").hide();
	$("#msg2").hide();
	$("#msg3").hide();
	
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

	<?php HTML_Render_Body_Start(); ?>
<div class='col-md-9'>
<br /><p class="em" align="right"><?php echo $RequiredFieldsAsterisk;?></p><?php echo "$msg_1"; ?><div id="msg"><p class=em2><!--Method successfully deleted!--><?php echo $MethodDeleted;?></p></div>
    <div id="msg2"><p class=em2><?php echo $MethodEdited;?></p></div>
      <h1><?php echo $EditDeleteMethod;?></h1>
      <p><?php echo $SelectMethod;?></p>
      <FORM METHOD="POST" class="form-horizontal" ACTION="" name="changemethod" id="changemethod">
      
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo $Method; ?></label>
        <div class="col-sm-9">
     	  <select name="MethodID" class="form-control" id="MethodID" onChange="editMethod()">
          <option value="-1"><?php echo $SelectEllipsis;?></option>
            <?php echo "$option_block"; ?></select>
           <span class="help-block"><br/><?php echo $NoteColon;?><?php echo $MethodNote;?></span>
		</div>             
      </div>
      </FORM>
     
    <div id="msg3"></div>
     </div>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
<div id="window">
	<div id="windowHeader">
		<span><?php echo $ConfirmationBox;?></span>
	</div>
    <div style="overflow: hidden;" id="windowContent"><center><strong><?php echo $AreYouSure;?></strong><br /><br /><input name="Yes" type="button" value="<?php echo $Yes;?>" id="Yup"/>&nbsp;<input name="No" type="button" value="<?php echo $No;?>" id="No"/></center></div>
</div>
	<?php HTML_Render_Body_End(); ?>

<script>
//When the "Delete" button is clicked, validate the method selected and then submit the request
function deleteMethod(){

	if(($("#MethodID").val())==-1){
		alert(<?php echo "'".$SelectMethodDelete."'"; ?>);
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
				
				alert(<?php echo "'".$ProcessingError."'"; ?>);
				return false;
				}
		});		
	}
return false;
};

//When the "Edit" button is clicked, validate the field and do a query to fill in the form for changes.
function editMethod(){
	
	if(($("#MethodID").val())==-1){
		alert(<?php echo "'".$SelectMethodEdit."'"; ?>);
		return false;
		
	}else{

		//Validation is now complete, so go get the info needed for this method
		var methodid=$("#MethodID").val();
	
		$.ajax({
		type: "POST",
		url: "request_method_info.php?MethodID="+methodid}).done(function(data){
			
			if(data){
				
				$("#msg3").html(data);
				$("#msg3").show(500);
				return true;
			
			}else{
			
				alert(<?php echo "'".$ErrorDuringRequest."'"; ?>);
				return false;
				}
		});
	}
return false;
}

//When the "Save Edits" button is clicked, validate the fields and then submit the request
function updateMethod(){

	if(($("#MethodDescription2").val())==''){
		
		alert(<?php echo "'".$MethodNameRequired."'"; ?>);
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

				alert(<?php echo "'".$ProcessingError."'"; ?>);
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