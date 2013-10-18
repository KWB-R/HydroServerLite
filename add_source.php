<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
require_once 'authorization_check.php';

//connect to server and select database
require_once 'database_connection.php';

//get list of TopicCategories to choose from
$sql2 ="Select Term FROM topiccategorycv";

$result2 = @mysql_query($sql2,$connection)or die(mysql_error());

$num2 = @mysql_num_rows($result2);
	if ($num2 < 1) {

    //$msg2 = "<P><em2>Sorry, no data available.</em></p>";
	$msg2 = "<P><em2> $NoData </em></p>";

	} else {

	while ($row2 = mysql_fetch_array ($result2)) {

		$metaTerm = $row2["Term"];
		
		$option_block2 .= "<option value=$metaTerm>$metaTerm</option>";

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

<link href="styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>

<script type="text/javascript">

$(document).ready(function(){

	$("#msg").hide();

});

</script>

</head>
<body background="images/bkgrdimage.jpg">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><?php include "topBanner.php" ; ?></td>
  </tr>
  <tr>
    <td colspan="2" align="right" valign="middle" bgcolor="#3c3c3c"><?php require_once 'header.php'; ?></td>
  </tr>
  <tr>
    <td width="240" valign="top" bgcolor="#f2e6d6"><?php echo "$nav"; ?></td>
    <!--<td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br /><p class="em" align="right">Required fields are marked with an asterick (*).</p><div id="msg"><p class=em2>Source successfully added!</p></div>
      <h1>Add a New Source</h1>-->
      <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br /><p class="em" align="right"><?php echo $RequiredFieldsAsterisk;?></p><div id="msg"><p class=em2><?php echo $SourceSuccessfullyAdded;?></p></div>
      <h1><?php echo $AddNewSource;?></h1>
      <p>&nbsp;</p>
      <FORM METHOD="POST" ACTION="" name="addsource" id="addsource">
        <table width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="108" valign="top"><strong><!--Organization:--><?php echo $Organization;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="Organization" name="Organization" size="35" maxlength="100"/>*&nbsp;<span class="em"><!--(Ex: McCall Outdoor Science School)--><?php echo $ExTitle1;?></span></td>
        </tr>
        <tr>
          <td width="108" valign="top">&nbsp;</td>
          <td width="22" valign="top">&nbsp;</td>
          <td width="470" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Description:--><?php echo $Description;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="SourceDescription" name="SourceDescription" size="35" maxlength="200"/>*&nbsp;<span class="em"><!--(Ex: The mission of the MOSS is....)--><?php echo $ExDescript;?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td width="22" valign="top">&nbsp;</td>
          <td width="470" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Link to Org:--><?php echo $Link;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="SourceLink" name="SourceLink" size="35" maxlength="200"/>
          &nbsp;<span class="em"><!--(Optional, Ex: http://www.mossidaho.org)--><?php echo $ExMetaLink;?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Contact Name:--><?php echo $ContactName;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="ContactName" name="ContactName" size="25" maxlength="200"/>*&nbsp;<span class="em"><!--(Full Name)--><?php echo $ExName;?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Phone:--><?php echo $Phone;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="Phone" name="Phone" size="12" maxlength="15"/>*&nbsp;<span class="em"><!--(Ex: XXX-XXX-XXXX)--><?php echo $ExPhone;?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Email:--><?php echo $Email;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="Email" name="Email" size="12" maxlength="50"/>*&nbsp;<span class="em"><!--(Ex: info@moss.org)--><?php echo $ExEmail;?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Address:--><?php echo $Address;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="Address" name="Address" size="35" maxlength="100"/>*</td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--City:--><?php echo $City;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="City" name="City" size="25" maxlength="100"/>*</td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--State:--><?php echo $State;?></strong></td>
          <td colspan="2" valign="top"><select name="state" id="state">
            <option value="-1"><!--Select....--><?php echo $SelectEllipsis;?></option>
            <option value="AL">Alabama</option>
            <option value="AK">Alaska</option>
            <option value="AZ">Arizona</option>
            <option value="AR">Arkansas</option>
            <option value="CA">California</option>
            <option value="CO">Colorado</option>
            <option value="CT">Connecticut</option>
            <option value="DE">Delaware</option>
            <option value="DC">District of Columbia</option>
            <option value="FL">Florida</option>
            <option value="GA">Georgia</option>
            <option value="HI">Hawaii</option>
            <option value="ID">Idaho</option>
            <option value="IL">Illinois</option>
            <option value="IN">Indiana</option>
            <option value="IA">Iowa</option>
            <option value="KS">Kansas</option>
            <option value="KY">Kentucky</option>
            <option value="LA">Louisiana</option>
            <option value="ME">Maine</option>
            <option value="MD">Maryland</option>
            <option value="MA">Massachusetts</option>
            <option value="MI">Michigan</option>
            <option value="MN">Minnesota</option>
            <option value="MS">Mississippi</option>
            <option value="MO">Missouri</option>
            <option value="MT">Montana</option>
            <option value="NE">Nebraska</option>
            <option value="NV">Nevada</option>
            <option value="NH">New Hampshire</option>
            <option value="NJ">New Jersey</option>
            <option value="NM">New Mexico</option>
            <option value="NY">New York</option>
            <option value="NC">North Carolina</option>
            <option value="ND">North Dakota</option>
            <option value="OH">Ohio</option>
            <option value="OK">Oklahoma</option>
            <option value="OR">Oregon</option>
            <option value="PA">Pennsylvania</option>
            <option value="RI">Rhode Island</option>
            <option value="SC">South Carolina</option>
            <option value="SD">South Dakota</option>
            <option value="TN">Tennessee</option>
            <option value="TX">Texas</option>
            <option value="UT">Utah</option>
            <option value="VT">Vermont</option>
            <option value="VA">Virginia</option>
            <option value="WA">Washington</option>
            <option value="WV">West Virginia</option>
            <option value="WI">Wisconsin</option>
            <option value="WY">Wyoming</option>
            <option value="NULL"><!--International--><?php echo $International;?></option>
          </select>*</td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Zip Code:--><?php echo $Zip;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="ZipCode" name="ZipCode" size="5" maxlength="8"/>*</td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Citation:--><?php echo $Citation;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="Citation" name="Citation" size="35" maxlength="100"/>&nbsp;<span class="em"><!--(Optional, Ex: Data collected by MOSS scientists and citizen scie...)--><?php echo $ExCitation;?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--MetadataID:--><?php echo $MetadataIDSemicolon;?></strong></td>
          <td colspan="2" valign="top"><span class="em">
            <input type="text" id="MetadataID" name="MetadataID" size="5" maxlength="8" style="background-color:#999;" disabled/>&nbsp;<!--(This will be auto-generated for you upon submission.)--><?php echo $MetadataAutoGenerated;?></span></td>
        </tr>
        <tr>
          <td width="108" valign="top">&nbsp;</td>
          <td width="22" valign="top">&nbsp;</td>
          <td width="470" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Topic Category:--><?php echo $TopicCategory;?></strong></td>
          <td colspan="2" valign="top"><select name="TopicCategory" id="TopicCategory">
            <option value="-1"><!--Select....--><?php echo $SelectEllipsis;?></option>
            <?php echo "$option_block2"; ?>
          </select>*&nbsp;<?php echo "$msg2"; ?></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Title:--><?php echo $Title;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="Title" name="Title" size="35" maxlength="100"/>*&nbsp;<span class="em"><!--(Ex: Twin Falls High School)--><?php echo $ExTitle2;?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Abstract:--><?php echo $Abstract;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="Abstract" name="Abstract" size="35" maxlength="250"/>*&nbsp;<span class="em"><!--(Ex: High school students/citizen scientists collecting...)--><?php echo $ExAbstract1;?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Metadata Link:--><?php echo $MetaLink;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="MetadataLink" name="MetadataLink" size="12" maxlength="15"/>
&nbsp;<span class="em"><!--(Optional)--><?php echo $Optional;?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td width="108" valign="top">&nbsp;</td>
          <td width="22" valign="top">&nbsp;</td>
          <td width="470" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <!--<td colspan="3" valign="top"><input type="SUBMIT" name="submit" value="Add Source" class="button" /></td>-->
          <td colspan="3" valign="top"><input type="SUBMIT" name="submit" value="<?php echo $AddSourceButton;?>" class="button" /></td>
          </tr>
      </table></FORM>
    <p>&nbsp;</p>
    </blockquote></td>
  </tr>
  <tr>
    <script src="js/footer.js"></script>
  </tr>
</table>

<script>

$("#addsource").submit(function(){

	//Validate all fields
	if(($("#Organization").val())==""){
		//alert("Please enter an organization for the source.");
		alert(<?php echo "'".$EnterOrganization."'"; ?>);
		return false;
	}

	if(($("#SourceDescription").val())==""){
		//alert("Please enter a description for the source.");
		alert(<?php echo "'".$EnterDescription."'"; ?>);
		return false;
	}

	if(($("#SourceLink").val())!=""){
		var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
		if(!($("#SourceLink").val().match(regexp))){
			//alert("Invalid url for sourcelink");
			alert(<?php echo "'".$InvalidSourceLinkURL."'"; ?>);
			return false;
		}
	}

	if(($("#ContactName").val())==""){
		//alert("Please enter a contact name for the source.");
		alert(<?php echo "'".$EnterContactName."'"; ?>);
		return false;
	}

	if(($("#Phone").val())==""){
		//alert("Please enter a phone number for the contact person.");
		alert(<?php echo "'".$EnterPhoneNumber."'"; ?>);
		return false;
	}

	//Phone Validation
	var regex = /^((\+?1-)?\d\d\d-)?\d\d\d-\d\d\d\d$/;
	if(!($("#Phone").val().match(regex))){
		//alert("Invalid phone number");
		alert(<?php echo "'".$InvalidPhoneNumber."'"; ?>);
		return false;
	}

	if(($("#Email").val())==""){
		//alert("Please enter an email address for the source.");
		alert(<?php echo "'".$EnterEmailAddress."'"; ?>);
		return false;
	}

	var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;

	if(!($("#Email").val().match(pattern))){
		//alert("Invalid email address");
		alert(<?php echo "'".$InvalidEmailAddress."'"; ?>);
		return false;
	}

	if(($("#Address").val())==""){
		//alert("Please enter an address for the source.");
		alert(<?php echo "'".$EnterAddress."'"; ?>);
		return false;
	}
	
	if(($("#City").val())==""){
		//alert("Please enter a city for the source.");
		alert(<?php echo "'".$EnterCity."'"; ?>);
		return false;
	}

	if(($("#state option:selected").val())==-1){
		//alert("Please select a state for the source.");
		alert(<?php echo "'".$SelectSourceState."'"; ?>);
		return false;
	}

	if(($("#ZipCode").val())==""){
		//alert("Please enter a zip code for the source.");
		alert(<?php echo "'".$EnterZipCode."'"; ?>);
		return false;
	}

	if(!($("#ZipCode").val().match(/^\d{5}(-\d{4})?$/))){
		//alert("Invalid zip code");
		alert(<?php echo "'".$InvalidZipCode."'"; ?>);
		return false;
	}

	//Validate MetadataID info
	if(($("#TopicCategory option:selected").val())==-1){
		//alert("Please select a topic category for the Metadata.");
		alert(<?php echo "'".$SelectTopicCategory."'"; ?>);
		return false;
	}

	if(($("#Title").val())==""){
		//alert("Please enter a title for the Metadata.");
		alert(<?php echo "'".$EnterMetadataTitle."'"; ?>);
		return false;
	}

	if(($("#Abstract").val())==""){
		//alert("Please enter an Abstract for the Metadata.");
		alert(<?php echo "'".$EnterMetadataAbstract."'"; ?>);
		return false;
	}

	if(($("#MetadataLink").val())!=""){
		var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
			if(!($("#ContactName").val().match(regexp))){
				//alert("Invalid url for Metadata Link");
				alert(<?php echo "'".$InvalidURLMetadata."'"; ?>);
				return false;
			}
	}

//Validation is all complete, so now process it

	$.post("do_add_source.php", $("#addsource").serialize(), function(data){
  
		 if(data==1){
			$("#msg").show(2000);
			$("#msg").hide(3500);
			$("#Organization").val("");
			$("#SourceDescription").val("");
			$("#SourceLink").val("");
			$("#ContactName").val("");
			$("#Phone").val("");
			$("#Email").val("");
			$("#Address").val("");
			$("#City").val("");
			$("#state").val("-1");
			$("#ZipCode").val("");
			$("#Citation").val("");
			$("#TopicCategory").val("-1");
			$("#Title").val("");
			$("#Abstract").val("");
			$("#MetadataLink").val("");
			setTimeout(function(){
				window.open("add_source.php","_self");
				}, 5000);
			return true;
		}else{
			//alert("Error during processing! Please refresh the page and try again.");
			alert(<?php echo "'".$ProcessingError."'";?>);
			return false;
		}
		
	});
return false;
});
</script>
</body>
</html>
