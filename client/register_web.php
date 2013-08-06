<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
require_once 'authorization_check.php';

//connect to server and select database
require_once 'database_connection.php';

//get all source
$sql ="Select SourceID, Organization FROM sources";

$result = @mysql_query($sql,$connection)or die(mysql_error());

$num = @mysql_num_rows($result);

if ($num < 1) {
	//$msg2 = "<P><em2>Sorry, no data available.</em></p>";
	$msg = "<P><em2> $NoData </em></p>";
} else {
	$option_source = "";
	while ($row = mysql_fetch_array ($result)) {
		$SourceID = $row["SourceID"];
		$Organization = $row["Organization"];
		$option_source .= "<option value=$SourceID>$Organization</option>";
	}
	$msg = "";
}

$proto = "http".((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")?"s":"")."://";
$server = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:$_SERVER['SERVER_NAME'];
$server .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);

$base_url = $proto.$server;

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

	$("#source").change(function() {
		$.ajax({
           	type: "GET",
           	url: "<?php echo $base_url;?>get_source_detail.php/?SourceID="+$(this).val(),
           	success: function(data) {
				data = $.parseJSON(data);
			    $("#title").val(data.SourceDescription);
			    $("#wsdl").val(data.WSDL);
			    $("#network").val(data.Network);
			    $("#organization_name").val(data.Organization);
			    $("#organization_url").val(data.SourceLink);
			    $("#contact_name").val(data.ContactName);
			    $("#contact_email").val(data.Email);
			    $("#contact_phone").val(data.Phone);
			    $("#citation").val(data.Citation);
			    $("#abstract").val(data.Abstract);
           	}
		});
	});

});

</script>

</head>
<body background="images/bkgrdimage.jpg">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><img src="images/WebClientBanner.png" width="960" height="200" alt="logo" /></td>
  </tr>
  <tr>
    <td colspan="2" align="right" valign="middle" bgcolor="#3c3c3c"><?php require_once 'header.php'; ?></td>
  </tr>
  <tr>
    <td width="240" valign="top" bgcolor="#f2e6d6"><?php echo "$nav"; ?></td>
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br /><div id="msg"><p class=em2><?php echo $WebSuccessfullyRegistered;?></p></div>
      <h1><?php echo $EnterInputText;?></h1>
      <p>&nbsp;</p>
      <form method="POST" action="" name="registerweb" id="registerweb">
        <table width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="108" valign="top"><strong><?php echo $Source;?></strong></td>
          <td colspan="2" valign="top"><select name="source" id="source">
            <option value="-1"><?php echo $SelectSource;?></option>
            <?php echo "$option_source"; ?>
          </select>&nbsp;<?php echo "$msg"; ?>&nbsp;<div id="status" style="display:none;"><img src="<?php echo $base_url.'images/loader.gif';?>"/></div></td>
        </tr>
        <tr>
          <td width="108" valign="top">&nbsp;</td>
          <td width="22" valign="top">&nbsp;</td>
          <td width="470" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td width="108" valign="top"><strong><?php echo $Title;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="title" name="title" size="35" maxlength="100" style="background-color:#999;" readonly="true"/></td>
        </tr>
        <tr>
          <td width="108" valign="top">&nbsp;</td>
          <td width="22" valign="top">&nbsp;</td>
          <td width="470" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><?php echo $WSDL;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="wsdl" name="wsdl" size="35" maxlength="200" style="background-color:#999;" readonly="true"/></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td width="22" valign="top">&nbsp;</td>
          <td width="470" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><?php echo $Network;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="network" name="network" size="35" maxlength="200" style="background-color:#999;" readonly="true"/></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><?php echo $OrganizationName;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="organization_name" name="organization_name" size="25" maxlength="200" style="background-color:#999;" readonly="true"/></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><?php echo $OrganizationURL;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="organization_url" name="organization_url" size="12" maxlength="15" style="background-color:#999;" readonly="true"/></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><?php echo $ContactName;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="contact_name" name="contact_name" size="12" maxlength="50" style="background-color:#999;" readonly="true"/></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><?php echo $ContactEmail;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="contact_email" name="contact_email" size="35" maxlength="100" style="background-color:#999;" readonly="true"/></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><?php echo $ContactPhone;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="contact_phone" name="contact_phone" size="25" maxlength="100" style="background-color:#999;" readonly="true"/></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><?php echo $Citation;?></strong></td>
          <td colspan="2" valign="top"><input type="text" id="citation" name="citation" size="35" maxlength="100" style="background-color:#999;" readonly="true"/></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><?php echo $Abstract;?></strong></td>
          <td colspan="2" valign="top"><span class="em">
            <input type="text" id="abstract" name="abstract" size="5" maxlength="8" style="background-color:#999;" readonly="true"/></td>
        </tr>
        <tr id="param_is_public" style="display:none;">
          <td valign="top"><strong><?php echo $IsPublic;?></strong></td>
          <td colspan="2" valign="top"><span class="em">
            <input type="checkbox" id="is_public" name="is_public" checked="true" /></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr id="process_result" style="display:none;">
          <td valign="top"><strong><?php echo $TheLink;?></strong></td>
          <td colspan="2" valign="top"><div id="link_result"></div></td>
        </tr>
        <tr>
          <td width="108" valign="top">&nbsp;</td>
          <td width="22" valign="top">&nbsp;</td>
          <td width="470" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top">
		  	  <input type="hidden" id="process" name="process" value="CHECK" />
			  <input type="button" id="btnSubmit" name="submit" value="<?php echo $CheckRegisterWebButton;?>" class="button" />
			  &nbsp;
			  <input type="button" id="btnHarvest" name="btnHarvest" value="<?php echo $HarvestWebButton;?>" class="button" style="display:none;" />
			  &nbsp;
			  <input type="button" id="btnUnregister" name="btnUnregister" value="<?php echo $UnregisterWebButton;?>" class="button" style="display:none;" />
			 </td>
          </tr>
      </table></form>
    <p>&nbsp;</p>
    </blockquote></td>
  </tr>
  <tr>
    <script src="js/footer.js"></script>
  </tr>
</table>

<script>

$("#btnSubmit").click(function(){
	$('#status').show();
	if ($("#is_public").attr('checked') != 'undefined') {
		$("#is_public").val("true");
	}
	//Validate all fields
	if(($("#source").val())==""){
		alert(<?php echo "'".$SelectSourceFirst."'"; ?>);
		return false;
	}

	//Validation is all complete, so now process it

	processRegistration();
	return false;
});

$("#btnUnregister").click(function() {
	$('#status').show();
	$("#is_public").val("");
	$("#process").val("UNREGISTER");
	processRegistration();
	return false;
});

$("#btnHarvest").click(function() {
	$('#status').show();
	$("#is_public").val("");
	$("#process").val("HARVEST");
	processRegistration();
	return false;
});

function processRegistration() {
	$.post("process_register_web.php", $("#registerweb").serialize(), function(data){
		var resp = $.parseJSON(data);
		 if (resp.status == "OK") {
			$('#status').hide();
		 	if ($('#process').val() == "CHECK") {
			 	if (resp.is_registered == "no") {
			 		$('#process').val("REGISTER");
			 		$("#param_is_public").show();
				 	$('#btnSubmit').val("<?=$RegisterWebButton;?>");
				 	alert('<?=$NotRegistered;?>');
				} else {
				    $("#process_result").show();
					$("#link_result").html(resp.result_link);
			 		$('#process').val("UPDATE");
			 		$('#btnSubmit').val("<?=$UpdateRegisterWebButton;?>");		 		
			 		$('#btnHarvest').show();		 		
			 		$('#btnUnregister').show();		 		
			 		$("#param_is_public").show();

					$("#title").css("background-color","white");
				    $("#wsdl").css("background-color","white");
				    $("#network").css("background-color","white");
				    $("#organization_name").css("background-color","white");
				    $("#organization_url").css("background-color","white");
				    $("#contact_name").css("background-color","white");
				    $("#contact_email").css("background-color","white");
				    $("#contact_phone").css("background-color","white");
				    $("#citation").css("background-color","white");
				    $("#abstract").css("background-color","white");

					$("#title").removeAttr("readonly");
				    $("#wsdl").removeAttr("readonly");
				    $("#network").removeAttr("readonly");
				    $("#organization_name").removeAttr("readonly");
				    $("#organization_url").removeAttr("readonly");
				    $("#contact_name").removeAttr("readonly");
				    $("#contact_email").removeAttr("readonly");
				    $("#contact_phone").removeAttr("readonly");
				    $("#citation").removeAttr("readonly");
				    $("#abstract").removeAttr("readonly");
					alert('<?=$Registered;?>');
				}
			} else if ($('#process').val() == "REGISTER" || $('#process').val() == "UNREGISTER") {
		 		$('#process').val("CHECK");
		 		$("#param_is_public").hide();
			 	$('#btnSubmit').val("<?=$CheckRegisterWebButton;?>");		 		
				$("#msg").show(5000);
				$("#msg").hide(3500);
			    $("#source").val("-1");
			    $("#title").val("");
			    $("#wsdl").val("");
			    $("#network").val("");
			    $("#organization_name").val("");
			    $("#organization_url").val("");
			    $("#contact_name").val("");
			    $("#contact_email").val("");
			    $("#contact_phone").val("");
			    $("#citation").val("");
			    $("#abstract").val("");
				setTimeout(function(){
					window.open("register_web.php","_self");
					}, 5000);
			} else if ($('#process').val() == "UPDATE") {
				alert('<?=$UpdateRegistered;?>');
			} else if ($('#process').val() == "HARVEST") {
			    $("#process_result").show();
				$("#link_result").html(resp.result_link);
				alert('<?=$HarvestRegistered;?>');
			}
		}else{
			alert(resp.error);
		}
		
	});
}
</script>
</body>
</html>
