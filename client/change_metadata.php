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

//All queries go through a translator. 
require_once 'DBTranslator.php';

//filter the Site results after Source is selected
$sql ="SELECT * FROM isometadata";
$result = transQuery($sql,0,1);

	if (count($result) < 1){
   	$msg = "<p class=em2> $NoMetadataIDs </em></p>";
	} else {
		foreach ($result as $row) {
			$md_id = $row["MetadataID"];
			$md_title = $row["Title"];
			$option_block_md .= "<option value=$md_id>$md_title</option>";
		}
	}
require_once "_html_parts.php";
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

	$("#Yesplz").click(function(){
		deleteMD();	
		$('#window').jqxWindow('hide');
	 });
	 
	$("#No").click(function(){
    	$('#window').jqxWindow('hide');
	});
});	

function confirmBox(){
	
$('#window').show();
    $('#window').jqxWindow('show');
}

function show_answer(){
alert(<?php echo "'".$ProfileVersion1. "'"; ?>+ '\n' + <?php echo "'".$ProfileVersion2. "'"; ?> + '\n' + <?php echo "'".$ProfileVersion3. "'"; ?>);
}

</script>

	<?php HTML_Render_Body_Start(); ?>
      <p><br />
        <?php echo "$msg"; ?></p>
      <p class="em" align="right"><!--Required fields are marked with an asterick (*).--><?php echo $RequiredFieldsAsterisk;?></p>
      <div id="msg">
      <p class=em2><!--Metadata successfully deleted!--><?php echo $MetadataDeleted;?></p></div>
    <div id="msg2">
      <p class=em2><!--Metadata successfully edited!--><?php echo $MetadataEdited;?></p></div>
      <h1><!--Edit or Delete a Metadata ID--><?php echo $EditDeleteMetadataID;?></h1>
      <p><!--Please select the Metadata ID you would like to edit or delete from the drop down menu to proceed.--><?php echo $SelectMetadataID;?></p>
      <p>&nbsp;</p>
      <FORM METHOD="POST" ACTION="" name="changemethod" id="changemethod">
        <table width="620" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><strong><!--Metadata Title:--><?php echo $MetadataTitleColon;?></strong></td>
          <td colspan="2" valign="top"><select name="MetadataID" id="MetadataID" onChange="findMD()">
            <option value="-1"><!--Select....--><?php echo $SelectEllipsis;?></option>
            <?php echo "$option_block_md"; ?></select></td>
        </tr>
        <tr>
          <td width="102" valign="top">&nbsp;</td>
          <td width="247" valign="top">&nbsp;</td>
          <td width="271" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top"><strong class="em2"><!--NOTE:--><?php echo $NoteColon;?></strong> <span class="em"><!--You cannot delete a Metadata ID without first deleting all Sources which use the specific Metadata ID--><?php echo $MetadataNoteText;?>.</span></td>
          </tr>
        <tr>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
         <tr>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        </table>
      </FORM>
      
    <div id="msg3"></div>
    <p>&nbsp;</p>
    </blockquote>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <script src="js/footer.js"></script>
  </tr>
</table>
<div id="window">
	<div id="windowHeader">
		<span><?php echo $ConfirmationBox;?></span>
	</div>
    <div style="overflow: hidden;" id="windowContent"><center><strong><?php echo $AreYouSure;?></strong><br /><br /><input name="Yes" type="button" value="<?php echo $Yes;?>" id="Yesplz"/>&nbsp;<input name="No" type="button" value="<?php echo $No;?>" id="No"/></center></div>
</div>
	<?php HTML_Render_Body_End(); ?>

<script>
//When the "Delete" button is clicked, validate the Metadata ID selected and then submit the request
function deleteMD(){

	if(($("#MetadataID").val())==-1){
		alert(<?php echo "'".$SelectMetaDataTitle."'"; ?>);
		return false;
		
	}else{
	
	//Validation is now complete, so send to the processing page
	var md_id = $("#MetadataID2").val();
	
		$.ajax({
		type: "POST",
		url: "delete_metadata.php?MetadataID="+md_id}).done(function(data){
			
			if(data==1){
				
				$("#msg").show(1600);
				$("#MetadataID").val("-1");
				$("#msg").hide(2000);
				setTimeout(function() {
					window.open("change_metadata.php","_self");
					}, 3800);
				return true;
	
			}else{
				alert(<?php echo "'".$ProcessingError."'"; ?>);
				return false;
				}
		});		
	}
return false;
};

//When a selection from the drop down menu, a query is used to fill in the form.
function findMD(){
	
		var md_id=$("#MetadataID").val();
	
		$.ajax({
		type: "POST",
		url: "request_metadata_info.php?MetadataID="+md_id}).done(function(data){
			
			if(data){
				
				$("#msg3").html(data);
				$("#msg3").show(500);
				return true;
			
			}else{
			
				alert(<?php echo "'".$ErrorDuringRequest."'"; ?>);
				return false;
				}
		});
};

//When the "Save Edits" button is clicked, validate the fields and then submit the request
function updateMD(){

//Validate all fields
if(($("#MetadataID2").val())==""){
	alert(<?php echo "'".$SelectMetadataIDMsg."'"; ?>);
	return false;
}

//Topic Category Validation
if(($("#TopicCategory2").val())==""){
	alert(<?php echo "'".$SelectMetadataTopicCategory."'"; ?>);
	return false;
}

//Title Validation
if(($("#Title2").val())==""){
	alert(<?php echo "'".$EnterMetadataTitle."'"; ?>);
	return false;
}

//Abstract Validation
if(($("#Abstract2").val())==""){
	alert(<?php echo "'".$EnterMetadataAbstract."'"; ?>);
	return false;
}

//Profile Version Validation
if(($("#ProfileVersion2").val())==""){
	alert(<?php echo "'".$EnterProfileVersionContact."'"; ?>);
	return false;
}

//MetadataLink Validation
if(($("#MetadataLink2").val())!=""){
	var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
	if(!($("#MetadataLink2").val().match(regexp))){
		alert(<?php echo "'".$InvalidURLMetadata."'"; ?>);
		return false;
	}
	
//Validation is now complete, so send to the processing page
	var md_id=$("#MetadataID2").val();
	var md_tc=$("#TopicCategory2").val();
	var md_title=$("#Title2").val();
	var md_ab=$("#Abstract2").val();
	var md_pv=$("#ProfileVersion2").val();
	var md_link=$("#MetadataLink2").val();

		$.ajax({
		type: "POST",
		url: "update_metadata.php?MetadataID2="+md_id+"&TopicCategory2="+md_tc+"&Title2="+md_title+"&Abstract2="+md_ab+"&ProfileVersion2="+md_pv+"&MetadataLink2="+md_link}).done(function(data){

			if(data==1){

				$("#msg2").show(1600);
				$("#msg2").hide(2000);
				$("#MetadataID2").val("");
				$("#TopicCategory2").val("-1");
				$("#Title2").val("");
				$("#Abstract2").val("");
				$("#ProfileVersion2").val("");
				$("#MetadataLink2").val("");
				$("#MetadataID").val("-1");
				$("#msg3").hide(4000);
				setTimeout(function(){
					window.open("change_metadata.php","_self");
					}, 5000);
				return true;

			}else{

				alert(ErrorProcessing);
				return false;
				}
		});

	return false;
	};
}

//When the "Cancel" button is clicked, clear the fields and reload the page
function clearEverything(){

	$("#MetadataID").val("-1");
	$("#MetadataID2").val("");
	$("#TopicCategory2").val("-1");
	$("#Title2").val("");
	$("#Abstract2").val("");
	$("#ProfileVersion2").val("");
	$("#MetadataLink2").val("");
	setTimeout(function(){
		window.open("change_metadata.php","_self");
		});
}

</script>