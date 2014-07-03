<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';
//check authority to be here
require_once 'authorization_check.php';
//All queries go through a translator. 
require_once 'DBTranslator.php';

//redirect anyone that is not an administrator
if ($_COOKIE[power] !="admin"){
	header("Location: index.php?state=pass2");
	exit;	
	}



//filter the Site results after Source is selected
$sql ="SELECT * FROM sources";
$result = transQuery($sql,0,0);

	if (count($result) < 1){
		$msg = "<p class=em2>$NoSoureces</em></p>";
	} else {
		foreach ($result as $row) {
			$s_id = $row["SourceID"];
			$s_org = $row["Organization"];
			$option_block_s .= "<option value=$s_id>$s_org</option>";
		}
	}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<title>HydroServer Lite Web Client</title>-->
<title><?php echo $WebClient; ?></title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="favicon.ico" >

<link rel="stylesheet" href="js/jqwidgets/styles/jqx.base.css" type="text/css" />
<link rel="stylesheet" href="js/jqwidgets/styles/jqx.darkblue.css" type="text/css" />
<script type="text/javascript" src="js/gettheme.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script> 
<script type="text/javascript" src="js/jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxdata.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxbuttons.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxscrollbar.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxwindow.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxpanel.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxtabs.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxcheckbox.js"></script>

<link href="styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />

<script type="text/javascript">

$(document).ready(function(){
	$("#msg").hide();
	$("#msg2").hide();
	$("#msg3").hide();
	
	$('#window').hide();

	$('#window').jqxWindow({ height: 100, width: 200, theme: 'darkblue' });
    	$('#window').jqxWindow('hide');

	$("#Yessir").click(function(){
		deleteSource();	
		$('#window').jqxWindow('hide');
	 });
	 
	$("#No").click(function(){
    	$('#window').jqxWindow('hide');
	});
});	

function confirmBox(){
	$("html, body").animate({ scrollTop: 0 }, "slow");
$('#window').show();
    $('#window').jqxWindow('show');
}

function show_answerProf(){
//alert("The Profile Version is a label for the standard of compliance used, such as ISO 19115 or ISO 8601,  which provides a complete set of ISO compliant metadata in the database.");
alert(<?php echo "'".$ProfileVersionLabel."'"; ?>);
}

</script>

</head>

<body background="images/bkgrdimage.jpg">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><?php include "topBanner.php" ; ?></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#3c3c3c" align="right" valign="middle" ><?php require_once 'header.php'; ?></td>
  </tr>
  <tr>
    <td width="240" valign="top" bgcolor="#f2e6d6"><?php echo "$nav"; ?></td>
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br /><?php echo "$msg"; ?><p class="em" align="right"><!--Required fields are marked with an asterick (*).--><?php echo $RequiredFieldsAsterisk;?></p><div id="msg">
      <p class=em2><!--Source and MetadataID successfully deleted!--><?php echo $SourceMetadataDeleted;?></p></div>
    <div id="msg2">
      <p class=em2><!--Source successfully edited!--><?php echo $SourceEdited;?></p></div>
      <h1><!--Edit or Delete a Source--><?php echo $EditDeleteSource;?></h1>
      <!--<p>Please select the Source you would like to edit or delete from the drop down menu to proceed.</p>-->
      <p><?php echo $SelectSource;?></p>

        <table width="620" border="0" cellspacing="0" cellpadding="0">

      	<tr>
         <!-- <td><strong>Source:</strong></td> -->
          <td><strong><?php echo $Source;?></strong></td>
          <td colspan="2" valign="top"><select name="SourceID" id="SourceID" onChange="editSource()">
            <option value="-1"><!--Select....--><?php echo $SelectEllipsis;?></option>
            <?php echo "$option_block_s"; ?></select></td>
        </tr>
        <tr>
          <td width="60" valign="top">&nbsp;</td>
          <td width="280" valign="top">&nbsp;</td>
          <td width="280" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <!-- <<td colspan="3" valign="top"><strong class="em2"NOTE:</strong> <span class="em">You cannot delete a Source or MetadataID without first deleting all data values associated with them.</span></td>-->
          <td colspan="3" valign="top"><strong class="em2"><?php echo $NoteColon;?></strong> <span class="em"><?php echo $NoteText;?></span></td>
          </tr>
        <tr>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
         <tr>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        </table>
      
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
		<span><!--Confirmation Box--><?php echo $ConfirmationBox;?></span>
	</div>
	<!--<div style="overflow: hidden;" id="windowContent"><center><strong>Are you sure?</strong><br /><br /><input name="Yes" type="button" value="Yes" id="Yessir"/>&nbsp;<input name="No" type="button" value="No" id="No"/></center></div>-->
    <div style="overflow: hidden;" id="windowContent"><center><strong><?php echo $AreYouSure;?></strong><br /><br /><input name="Yes" type="button" value="<?php echo $Yes;?>" id="Yessir"/>&nbsp;<input name="No" type="button" value="<?php echo $No;?>" id="No"/></center></div>
</div>
</body>
</html>

<script>
//When a selection from the Source dropdown menu, a query is used to fill in the form.
function editSource(){
	
		var sourceid=$("#SourceID").val();
	
		$.ajax({
		type: "POST",
		url: "request_source_info.php?SourceID="+sourceid}).done(function(data){
			
			if(data){
				
				$("#msg3").html(data);
				$("#msg3").show(500);
				return true;
			
			}else{
			
				//alert("Error during request! Please refresh the page and begin again.");
				alert(<?php echo "'".$ErrorDuringRequest."'";?>);
				return false;
				}
		});
};


//When the "Delete" button is clicked, validate the method selected and then submit the request
function deleteSource(){

		if(($("#SourceID").val())==-1){
			//alert("Please select a Source to delete!");
			alert(<?php echo "'".$SelectSourceDelete."'"; ?>);
			return false;
		}
	
		if(($("#MetadataID2").val())==""){
			//alert("Your Source doesn't appear to have a MetadataID! Please talk to your supervisor.");
			alert(<?php echo "'".$SourceWithoutMetadataID."'"; ?>);
			return false;
		}
	
	//Validation is now complete, so send to the processing page
	var sourceid = $("#SourceID").val();
	var metadataid = $("#MetadataID2").val();
	
		$.ajax({
		type: "POST",
		url: "delete_source.php?SourceID="+sourceid+"&MetadataID2="+metadataid}).done(function(data){
		
			if(data==1){
				
				$("#msg").show(1600);
				$("#SourceID").val("-1");
				$("#msg3").hide(1500);
				$("#msg").hide(2000);
				setTimeout(function() {
					window.open("change_source.php","_self");
					}, 3800);
				return true;
	
			}else{
				
				//alert("Error during processing! Please refresh the page and begin again.");
				alert(<?php echo "'".$ProcessingError."'"; ?>);
				return false;
			}
		});		
return false;
};



//When the "Save Edits" button is clicked, validate the fields and then submit the request
function updateSource(){

	$("form").submit(function(){

		//Validate all fields
		if(($("#SourceID2").val())==""){
			//alert("Please select a Source to edit.");
			alert(<?php echo "'".$SelectSourceEdit."'"; ?>);
			return false;
		}

		if(($("#Organization2").val())==""){
			//alert("Please enter an organization for the source.");
			alert(<?php echo "'".$EnterOrganization."'"; ?>);
			return false;
		}

		if(($("#SourceDescription2").val())==""){
			//alert("Please enter a description for the source.");
			alert(<?php echo "'".$EnterDescription."'"; ?>);
			return false;
		}

		//SourceLink Validation
		if(($("#SourceLink2").val())!=""){
			var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
			if(!($("#SourceLink2").val().match(regexp))){
				//alert("Invalid url for sourcelink");
				alert(<?php echo "'".$InvalidSourceLinkURL."'"; ?>);
				return false;
			}
		}

		//Contact Name Validation
		if(($("#ContactName2").val())==""){
			//alert("Please enter a contact name for the source.");
			alert(<?php echo "'".$EnterContactNameSource."'"; ?>);
			return false;
		}

		//Phone Validation
		if(($("#Phone2").val())==""){
			//alert("Please enter a phone number for the contact person.");
			alert(<?php echo "'".$EnterPhoneNumber."'"; ?>);
			return false;
		}
		var regex = /^((\+?1-)?\d\d\d-)?\d\d\d-\d\d\d\d$/;
		if(!($("#Phone2").val().match(regex))){
			//alert("Invalid phone number");
			alert(<?php echo "'".$InvalidPhoneNumber."'"; ?>);
			return false;
		}

		//Email Validation
		if(($("#Email2").val())==""){
			//alert("Please enter an email address for the source.");
			alert(<?php echo "'".$EnterEmailAddress."'"; ?>);
			return false;
		}
		var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
		if(!($("#Email2").val().match(pattern))){
			//alert("Invalid email address");
			alert(<?php echo "'".$InvalidEmailAddress."'"; ?>);
			return false;
		}

		//Address Validation
		if(($("#Address2").val())==""){
			//alert("Please enter an address for the source.");
			alert(<?php echo "'".$EnterAddress."'"; ?>);
			return false;
		}

		//City Validation
		if(($("#City2").val())==""){
			//alert("Please enter a city for the source.");
			alert(<?php echo "'".$EnterCity."'"; ?>);
			return false;
		}
	
		//State Validation
		if(($("#State2 option:selected").val())==-1){
			//alert("Please select a state for the source.");
			alert(<?php echo "'".$SelectSourceState."'"; ?>);
			return false;
		}

		//Zip Code Validation
		if(($("#ZipCode2").val())==""){
			//alert("Please enter a zip code for the source.");
			alert(<?php echo "'".$EnterZipCode."'"; ?>);
			return false;
		}
		if(!($("#ZipCode2").val().match(/^\d{5}(-\d{4})?$/))){
			//alert("Invalid zip code");
			alert(<?php echo "'".$InvalidZipCode."'"; ?>);
			return false;
		}

		//MetadataID Validation
		if(($("#MetadataID2").val())==""){
			//alert("Your Source doesn't appear to have a MetadataID! Please talk to your supervisor.");
			alert(<?php echo "'".$SourceWithoutMetadataID."'"; ?>);
			return false;
		}
	
		//Topic Category Validation
		if(($("#TopicCategory2").val())==""){
			//alert("Please select a Topic Category for the Metadata.");
			alert(<?php echo "'".$SelectTopicCategory."'"; ?>);
			return false;
		}

		//Title Validation
		if(($("#Title2").val())==""){
			//alert("Please enter a Title for the Metadata.");
			alert(<?php echo "'".$EnterMetadataTitle."'"; ?>);
			return false;
		}

		//Abstract Validation
		if(($("#Abstract2").val())==""){
			//alert("Please enter an Abstract for the Metadata.");
			alert(<?php echo "'".$EnterMetadataAbstract."'"; ?>);
			return false;
		}

		//Profile Version Validation
		if(($("#ProfileVersion2").val())==""){
			//alert("Please enter a Profile Version for the contact person.");
			alert(<?php echo "'".$EnterProfileVersionContact."'"; ?>);
			return false;
		}

		//MetadataLink Validation
		if(($("#MetadataLink2").val())!=""){
			var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
			if(!($("#MetadataLink2").val().match(regexp))){
				//alert("Invalid url for Metadata Link");
				alert(<?php echo "'".$InvalidURLMetadata."'"; ?>);
				return false;
			}
		}
//alert("Passed all validation!");
//alert(<?php echo "'".$PassedValidation."'"; ?>);
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
				//Validation is now complete, so send info to the processing page
				var source_ID=$("#SourceID2").val();
				var source_org=$("#Organization2").val();
				var source_d=$("#SourceDescription2").val();
				var source_l=$("#SourceLink2").val();
				var source_cn=$("#ContactName2").val();
				var source_p=$("#Phone2").val();
				var source_e=$("#Email2").val();
				var source_a=$("#Address2").val();
				var source_city=$("#City2").val();
				var source_st=$("#State2").val();
				var source_zc=$("#ZipCode2").val();
				var source_c=$("#Citation2").val();
				var source_md=$("#MetadataID2").val();
				
				var urlForUpdate1 = "update_source.php?SourceID2="+source_ID+"&Organization2="+source_org+"&SourceDescription2="+source_d+"&SourceLink2="+source_l+"&ContactName2="+source_cn+"&Phone2="+source_p+"&Email2="+source_e+"&Address2="+source_a+"&City2="+source_city+"&State2="+source_st+"&ZipCode2="+source_zc+"&Citation2="+source_c+"&MetadataID2="+source_md;
				
				var urlForUpdate = "update_source.php";
				
				var dataForUpdate = {
				SourceID2:source_ID,
				Organization2:source_org,
				SourceDescription2:source_d,
				SourceLink2:source_l,
				ContactName2:source_cn,
				Phone2:source_p,
				Email2:source_e,
				Address2:source_a,
				City2:source_city,
				State2:source_st,
				ZipCode2:source_zc,
				Citation2:source_c,
				MetadataID2:source_md
				};
				
				$.ajax({
				type: "POST",
				url: urlForUpdate,
				data:dataForUpdate}).done(function(data2){
					if(data2==1){
						$("#msg2").show(2000);
						$("#msg2").hide(3000);
						$("#SourceID2").val("");
						$("#Organization2").val("");
						$("#SourceDescription2").val("");
						$("#SourceLink2").val("");
						$("#ContactName2").val("");
						$("#Phone2").val("");
						$("#Email2").val("");
						$("#Address2").val("");
						$("#City2").val("");
						$("#State2").val("-1");
						$("#ZipCode2").val("");
						$("#Citation2").val("");
						$("#MetadataID2").val("");
						$("#msg3").hide(1500);
						setTimeout(function(){
							window.open("change_source.php","_self");
							}, 5000);
						return true;

					}else{
						alert(<?php echo "'".$ProcessingError."'"; ?> + data2);
						return false;
					}
				});

			}else{
				alert(<?php echo "'".$ProcessingError."'"; ?> + data);
				return false;
			}
		});
	return false;
	});
}

//When the "Cancel" button is clicked, clear the fields and reload the page
function clearEverything(){

	$("#SourceID").val("-1");
	$("#SourceID2").val("");
	$("#Organization2").val("");
	$("#SourceDescription2").val("");
	$("#SourceLink2").val("");
	$("#ContactName2").val("");
	$("#Phone2").val("");
	$("#Email2").val("");
	$("#Address2").val("");
	$("#City2").val("");
	$("#State2").val("-1");
	$("#ZipCode2").val("");
	$("#Citation2").val("");
	$("#MetadataID2").val("");
	setTimeout(function(){
		window.open("change_source.php","_self");
		});
}

</script>