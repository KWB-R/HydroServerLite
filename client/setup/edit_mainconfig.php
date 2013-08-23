<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HydroServer Lite Web Client: Install</title>
<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="../favicon.ico" >

<link href="../styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />

<!-- JQuery JS -->
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
function show_answerDH(){
alert("This may be either localhost or the server's IP address such as 8.23.154.5 if you are using a different server to host the database than the software.");
}

function show_answerVC(){
alert("An arbitrary code used by your organization to specify a specific variable record. For example, IDCS- could be used for International Data Collection System.");
}

function show_answerTS(){
alert("A numerical value that indicates the temporal footprint of the data values. 0 indicates instantaneous samples (samples taken at random or irregular intervals). Other values indicate the time over which data values are aggregated. For example, the value was collected every 10 minutes.");
}

function show_answerDN(){
alert("The name of the database when the tables of data will be stored.");
}

function show_answerPV(){
alert("The Profile Version field should be populated with the version of the ISO metadata profile that is being used. For example, ISO 19115 or ISO 8601. This field can be populated with Unknown if there is no profile version for the data.");
}

function show_answerLX(){
alert("This is the Local Projection X coordinate. For example, 456700. Or you simply put NULL if not known.");
}

function show_answerLY(){
alert("This is the Local Projection Y coordinate. For example, 232000. Or you simply put NULL if not known.");
}

function show_answerLPID(){
alert("An identifier that references the Spatial Reference System of the local coordinates in the SpatialReferences table. This field is required if local coordinates are given. For example, 7. Or you simply put NULL if not known.");
}

function show_answerPA(){
alert("Value giving the accuracy with which the positional information is specified in meters. For example, 100. Or you simply put NULL if not known.");
}

function show_answerVD(){
alert("Vertical datum of the elevation. Controlled Vocabulary from VerticalDatumCV. For example, MSL, which stands for Mean Sea Level.");
}

function show_answerSR(){
alert("SpatialReferences is for the purpose of recording the name and EPSG code of each Spatial Reference System used. For example, NAD83 / Idaho Central.");
}

function show_answerUTC1(){
alert("Unambiguous interpretation of date and time information requires specification of the time zone or offset from universal time (UTC). A UTCOffset field is included to ensure that local times recorded in the database can be referenced to standard time and to enable comparison of results across databases that may store data values collected in different time zones. For example, McCall Idaho is Mountain Standard Time (MST), and therefore the value is -7.");
}

function show_answerUTC2(){
alert("To automatically adjust Date and Time a second UTC value is needed for calculations in the software. The value of this UTC is the exact opposite of the first UTC. For example, McCall Idaho is Mountain Standard Time (MST), and therefore the value is 7.");
}

function show_answerCC(){
alert("The Censor Code is a controlled vocabulary used to define whether the data value is censored. 'nc' means that data is not censored. If not known, simply put nc. ");
}

function show_answerQCL(){
alert("A unique integer identifying the quality control level of the data values collected. For example, a quality control level code of 0 is suggested for data which is raw and unprocessed, and have not undergone quality control. Or a quality control level code of -9999 is suggested for data whose quality control level is unknown.");
}

function show_answerVA(){
alert("Value Accuracy is a numeric value that describes the measurement accuracy of the data value. If not known, simply put NULL.");
}

function show_answerOTID(){
alert("An integer identifier that references the measurement offset type in the OffsetTypes table. If not known, simply put NULL.");
}

function show_answerQ(){
alert("Integer identifier that references the Qualifiers table. In this environment, the Qualifier is 1 and refers to Citizen Science.");
}

function show_answerSID(){
alert("Integer identifier that references into the Samples table. This is required only if the data value resulted from a physical sample processed in a lab. If not known, simply put NULL.");
}

function show_answerDFID(){
alert("Integer identifier for the derived from group of data values that the current data value is derived from. This refers to a group of derived from records in the DerivedFrom table. If NULL, the data value is inferred to not be derived from another data value.");
}

</script>
</head>

<body background="../images/bkgrdimage.jpg">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><img src="../images/WebClientBanner.png" width="960" height="200" alt="Adventure Learning Banner" /></td>
  </tr>
  
  <tr>
    
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br />
      <form name="form1" id="form1" method="post" action="">
<table width="800" border="0" align="center" style="background-color: #FFFFFF;">
  <tr>
    <td><table width="600" border="0" align="center" style="background-color: #FFFFFF;">
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><center>
      <h2 class="config">HydroServer Lite: Editing your site's main configuration file</h2></center></td>
  </tr>
  <tr>
    <td colspan="3"><h3>Welcome, Administrator!</h3></td>
  </tr>
  <tr>
    <td colspan="3">Please take a few minutes to change all of the fields below to setup your application with the correct default settings needed for it to run properly. If you have any questions during the process, please click the information icon next to the field or refer to the example provided.</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td width="19">&nbsp;</td>
    <td><span class='confighead'>Current username:</span>&nbsp;</td>
    <td><input type="text" id="username" name="username" value="his_admin" disabled="disabled"/></td>
  </tr>
  <tr>
    <td width="19">&nbsp;</td>
    <td><span class='confighead'>New password:</span>&nbsp;</td>
    <td><input type="text" id="password" name="password" value="" />
      &nbsp;<span class='em'>(Must be entered  now.)</span></td>
  </tr>
  <tr>
    <td width="19">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="3"><h3>Please enter your default settings below....</h3></td>
  </tr>
  <tr>
    <td colspan="3"><span class='confighead'>Configuration settings for MySql Database </span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="19">&nbsp;</td>
    <td width="146">Database Host:      </td>
    <td width="416"><input type="text" id="Database Host" name="databasehost" value="" />&nbsp;<a href="#" onClick="show_answerDH()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
    </tr>
  <tr>
    <td width="19">&nbsp;</td>
    <td width="146">Database Username:      </td>
    <td><input type="text" id="Database User Name" name="databaseusername" value="" />&nbsp;</td>
  </tr>
  <tr>
    <td width="19">&nbsp;</td>
    <td width="146">Database Password:      </td>
    <td><input type="text" id="Database Password" name="databasepassword" value="" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Database Name: </td>
    <td><input type="text" id="Database Name" name="databasename" value="" />&nbsp;<a href="#" onClick="show_answerDN()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><span class="confighead">Configuration settings for website's look and functionality</span></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Organization's Name:</td>
    <td><input type="text" id="Organisation Name" name="orgname" value="" />
      &nbsp;<span class='em'>(Ex: McCall Outdoor Science School)</span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Parent Website's Name:</td>
    <td><input type="text" id="Parent Website's Name" name="parentname" value="" />
      &nbsp;<span class='em'>(Ex: MOSS blog)</span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Parent Website:</td>
    <td><input type="text" id="Parent Website Address" name="parentweb" value="" />
      &nbsp;<span class='em'>(Ex: adventurelearningat.com)</span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Software Version:</td>
    <td><input type="text" id="Software Version" name="sversion" value="Version 2.0" />
      &nbsp;<span class='em'>(Ex: Version 2.0)</span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><span class='confighead'>Configuration settings for security purposes</span></td>
    </tr>
  <tr>


    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="19">&nbsp;</td>
    <td>Website's Domain:&nbsp;</td>
    <td><input type="text" id="domain" name="domain" value="" />
      &nbsp;<span class='em'>(Ex: adventurelearningat.com)</span></td>
  </tr>
  <tr>
    <td width="19">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><span class="confighead">Configuration settings for adding a new Source</span></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Profile Version:</td>
    <td><input type="text" id="Profile Version" name="profilev" value="" />&nbsp;<a href="#" onClick="show_answerPV()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><span class='confighead'>Configuration settings for adding Sites </span></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Source: </td>
    <td><input type="text" id="source" name="source" value="" />&nbsp;<span class='em'>(Ex: McCall Outdoor Science School)</span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Local X:</td>
    <td><input type="text" id="localx" name="localx" value="" />&nbsp;<a href="#" onClick="show_answerLX()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Local Y:</td>
    <td><input type="text" id="localy" name="localy" value="" />&nbsp;<a href="#" onClick="show_answerLY()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Local Projection ID:</td>
    <td><input type="text" id="localpid" name="localpid" value="" />&nbsp;<a href="#" onClick="show_answerLPID()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>PosAccuracy_m:</td>
    <td><input type="text" id="posaccuracy" name="posaccuracy" value="" />&nbsp;<a href="#" onClick="show_answerPA()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td width="19">&nbsp;</td>
    <td>Vertical Datum:&nbsp;</td>
    <td><input type="text" id="Vertical datum" name="vdatum" value="" />&nbsp;<a href="#" onClick="show_answerVD()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td width="19">&nbsp;</td>
    <td> Spatial Reference: </td>
    <td><input type="text" id="Spatial Reference" name="spatialref" value="" />&nbsp;<a href="#" onClick="show_answerSR()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td width="19">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><span class='confighead'>Configuration settings for adding a new Variable</span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Variable Code: </td>
    <td><input type="text" id="Variable Code" name="varcode" value="" />&nbsp;<a href="#" onClick="show_answerVC()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td> Time Support: </td>
    <td><input type="text" id="Time Support" name="timesupport" value=""/>&nbsp;<a href="#" onClick="show_answerTS()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><span class='confighead'>Configuration settings for adding Data Values </span></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="19">&nbsp;</td>
    <td>UTCOffset: </td>
    <td><input type="text" id="UTC Offset" name="utcoffset1" value="" />&nbsp;<a href="#" onClick="show_answerUTC1()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>UTCOffset 2: </td>
    <td><input type="text" id="UTC Offset 2" name="utcoffset2" value="" />&nbsp;<a href="#" onClick="show_answerUTC2()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Censor Code: </td>
    <td><input type="text" id="Censor Code" name="censorcode" value="nc" />&nbsp;<a href="#" onClick="show_answerCC()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Quality Control Level: </td>
    <td><input type="text" id="Quality Control Level" name="qcl" value="0" />&nbsp;<a href="#" onClick="show_answerQCL()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Value Accuracy: </td>
    <td><input type="text" id="Value Accuracy" name="valueacc" value="NULL" />&nbsp;<a href="#" onClick="show_answerVA()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Offset Type ID:</td>
    <td><input type="text" id="Offset Type ID" name="offsettype" value="NULL" />&nbsp;<a href="#" onClick="show_answerOTID()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Qualifier ID: </td>
    <td><input type="text" id="Qualifier ID" name="qualifier" value="1" />&nbsp;<a href="#" onClick="show_answerQ()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Sample ID: </td>
    <td><input type="text" id="sampleid" name="sampleid" value="NULL" />&nbsp;<a href="#" onClick="show_answerSID()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Derived From ID: </td>
    <td><input type="text" id="Derived from ID" name="derived" value="NULL" />&nbsp;<a href="#" onClick="show_answerDFID()" border="0"><img src="../images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><input type="SUBMIT" id="submit" value="Save Settings" class="button" style="width: 115px" />&nbsp;&nbsp;<input type="reset" id="Reset" value="Cancel" class="button" style="width: 70px" /></td>
    <td width="1">&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</td>
  </tr>
</table>

</form></td>
  </tr>
  <tr>
    <script src="../js/footer.js"></script>
  </tr>
</table>

<script type="text/javascript">

//Validate installation form


$("form").submit(function(){
var fal=0;
//Iterate through each input field. 

	$("#form1 input[type=text]").each(function() {
		
	if ((($(this).val())=="")&&(($(this).attr('name'))!="databasepassword"))
	{ 
	
		$(this).focus();
		$(this).hide('slow',function(){$(this).show('slow');alert("Cannot Leave "+$(this).attr('id')+" Blank");});
		fal=1;
		return false;
		
	}
	
	});

if (fal==1)
return false;
//Check if the database settings are correct. 
$.post("db_check.php", $("#form1").serialize(),  function( data ) {
  
		 if(data!=1)
  {
	  alert(data);
  return false;
  }
  
		      });

//Database Check Completed. Now to run a script to insert the tables into the database. 

$.ajax({
  type: "POST",
  url: "do_add_table.php",
  data: $("#form1").serialize()
}).done(function( msg ) {
  if(msg==1)
  {
	//Database Successfully added. Now write the main_config file
	$.ajax({
  type: "POST",
  url: "creat_main.php",
  data: $("#form1").serialize()
  }).done(function( status ) {
   if(status==1)
  {
	  //redirect to final page
	  window.location.href = "install_final.php";
	  return false
  }
  else
  {
	  alert(status);
	  return false
  }
  });
  }
  else
  {
  alert("Error in database configuration.Could Not Add the base tables.");
  return false;
  }
  
});



	return false;
});

</script>

</body>
</html>
