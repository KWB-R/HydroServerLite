<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
require_once 'authorization_check.php';

//connect to server and select database
require_once 'database_connection.php';

//add the SourceID's
$sql ="Select * FROM sources";

$result = @mysql_query($sql,$connection)or die(mysql_error());

$num = @mysql_num_rows($result);
	if ($num < 1) {

    //$msg = "<P><em2>Sorry, there are no SourceID names.</em></p>";
    $msg = "<P><em2>$NoSourceIDNames</em></p>";

	} else {

	while ($row = mysql_fetch_array ($result)) {

		$sourceid = $row["SourceID"];
		$sourcename = $row["Organization"];

		$option_block .= "<option value=$sourceid>$sourcename</option>";

		}
	}


//add the Types
$sql3 ="Select * FROM variables ORDER BY VariableName ASC";

$result3 = @mysql_query($sql3,$connection)or die(mysql_error());

$num = @mysql_num_rows($result3);
	if ($num < 1) {

    //$msg3 = "<P><em2>Sorry, there are no Types.</em></p>";
	$msg3 = "<P><em2>$NoType</em></p>";

	} else {

	while ($row3 = mysql_fetch_array ($result3)) {

		$typeid = $row3["VariableID"];
		$typename = $row3["VariableName"];
		$datatype = $row3["DataType"];

		$option_block3 .= "<option value=$typeid>$typename ($datatype)</option>";

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

<link rel="stylesheet" href="styles/jqstyles/demos.css">
<script type="text/javascript" src="uploader/plupload.js"></script>
<script type="text/javascript" src="uploader/plupload.gears.js"></script>
<script type="text/javascript" src="uploader/plupload.silverlight.js"></script>
<script type="text/javascript" src="uploader/plupload.flash.js"></script>
<script type="text/javascript" src="uploader/plupload.browserplus.js"></script>
<script type="text/javascript" src="uploader/plupload.html4.js"></script>
<script type="text/javascript" src="uploader/plupload.html5.js"></script>

<script src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">



function show_answer(){
//alert("If you do not see your location (Site) listed here, please contact your supervisor and ask them to add it before entering data.");
alert(<?php echo "'".$NoLocation."'";?>);
}

function show_answer2(){
//alert("If you do not see your Method listed here, please contact your supervisor and ask them to add it before entering data.");
alert(<?php echo "'".$NoMethod."'";?>);
}

function showSites(str){

document.getElementById("txtHint").innerHTML="<select name='SiteID' id='SiteID'><option value=''>Select....</option></select>&nbsp;<a href='#' onClick='show_answer()' border='0'><img src='images/questionmark.png' border='0'></a>";

if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","getsites.php?q="+str,true);
xmlhttp.send();
}

function showMethods(str){

document.getElementById("txtHint2").innerHTML="&nbsp;<a href='#' onClick='show_answer()' border='0'><img src='images/questionmark.png' border='0'></a>";

if (str=="")
  {
  document.getElementById("txtHint2").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint2").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","getmethods1.php?m="+str,true);
xmlhttp.send();
}

</script>
</head>

<body background="images/bkgrdimage.jpg">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><img src="images/WebClientBanner.png" width="960" height="200" alt="Adventure Learning Banner" /></td>
  </tr>
  <tr>
    <td colspan="2" align="right" valign="middle" bgcolor="#3c3c3c"><?php include 'header.php'; ?></td>
  </tr>
  <tr>
    <!--<td width="240" valign="top" bgcolor="#f2e6d6"><//?php include 'fetchnav.php'; ?></td>-->
	<td width="240" valign="top" bgcolor="#f2e6d6"><?php echo "$nav"; ?></td>
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br />
      <!--<h1>Import a Data File</h1>-->
      <h1><?php echo $Import; ?></h1>
      <!--<p>In order to use this option, your CSV data file must conform to SQL database's requirements; therefore, your data should look like the following example before attempting to upload it:</p>-->
      <p><?php echo $MustConform; ?></p>
      <p>LocalDateTime,DataValue<br>
        2012-05-31 00:00:00,10.99<br>
        2012-05-31 00:10:00,11.01<br>
        2012-05-31 00:20:00,11.02<br>
        2012-05-31 00:30:00,11.04<br>
        <!--<a href="sample_csv.txt" target="_blank">view sample text file</a></p>--> 
        <a href="sample_csv.txt" target="_blank"><?php echo $ViewSample; ?></a></p>
      <!--<p>If your file is prepared correctly, you may use the process below:<div id="statusmsg">-->
      <p><?php echo $PreparedCorrectly; ?><div id="statusmsg">
        <!--<p class=em2>Data upload successfull.
          <input type="button" name="viewdata" id="viewdata" value="View data Uploaded" />
        <input type="button" name="viewdata2" id="viewdata2" value="Upload more data" />
        </p>-->
        <p class=em2><?php echo $Success;?>
          <input type="button" name="viewdata" id="viewdata" value="<?php echo $View; ?>" />
        <input type="button" name="viewdata2" id="viewdata2" value="<?php echo $More; ?>" />
        </p>
     </div>
     <FORM METHOD="POST" ACTION="" name="addvalue" id="addvalue">
       <table width="500" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <!--<td valign="top"><strong>Source:</strong></td>
          <td valign="top"><select name="SourceID" id="SourceID" onChange="showSites(this.value)"><option value="-1">Select....</option><?php echo "$option_block"; ?></select></td>-->
          <td valign="top"><strong><?php echo $Source; ?></strong></td>
          <td valign="top"><select name="SourceID" id="SourceID" onChange="showSites(this.value)"><option value="-1"><?php echo $SelectEllipsis; ?></option><?php echo "$option_block"; ?></select></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
        <tr>
          <!--<td valign="top"><strong>Site:</strong></td>
          <td valign="top"><div id="txtHint"><select name="SiteID" id="SiteID"><option value="-1">Select....</option></select>&nbsp;<a href="#" onClick="show_answer()" border="0"><img src="images/questionmark.png" border="0"></a></div></td>-->
          <td valign="top"><strong><?php echo $Site; ?></strong></td>
          <td valign="top"><div id="txtHint"><select name="SiteID" id="SiteID"><option value="-1"><?php echo $SelectEllipsis; ?></option></select>&nbsp;<a href="#" onClick="show_answer()" border="0"><img src="images/questionmark.png" border="0"></a></div></td>

          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
        <tr>
          <!--<td width="55" valign="top"><strong>Type:</strong></td>
          <td width="370" valign="top"><select name="VariableID" id="VariableID" onChange="showMethods(this.value)">
            <option value="-1">Select....</option><?php echo "$option_block3"; ?></select></td>-->
          <td width="55" valign="top"><strong><?php echo $Type; ?></strong></td>
          <td width="370" valign="top"><select name="VariableID" id="VariableID" onChange="showMethods(this.value)">
            <option value="-1"><?php echo $SelectEllipsis; ?></option><?php echo "$option_block3"; ?></select></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
        <tr>
          <!--<td valign="top"><strong>Method:</strong></td>
          <td valign="top"><div id="txtHint2"><select name="MethodID" id="MethodID"><option value="-1">Select....</option></select>&nbsp;<a href="#" onClick="show_answer2()" border="0"><img src="images/questionmark.png" border="0"></a></div></td>-->
          <td valign="top"><strong><?php echo $Method; ?></strong></td>
          <td valign="top"><div id="txtHint2"><select name="MethodID" id="MethodID"><option value="-1"><?php echo $SelectEllipsis; ?></option></select>&nbsp;<a href="#" onClick="show_answer2()" border="0"><img src="images/questionmark.png" border="0"></a></div></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td width="370" valign="top">&nbsp;</td>
          </tr>
        <tr>
          <!--<td colspan="2" valign="top"><strong>Upload CSV file containing dates, times, and values:</strong></td>-->
          <td colspan="2" valign="top"><strong><?php echo $UploadCSV;?></strong></td>
          </tr>
        <tr>
          <td colspan="2" valign="top"> <div id="container">
    <div id="filelist"></div>
   <br>
      <input name="filename" type="text" id="filename" size="36">
  <!--<input id="pickfiles" name="selfile" type="button" value="Select File">
  <input id="files" name="upfile" type="button" value="Upload">-->
  <input id="pickfiles" name="selfile" type="button" value="<?php echo $SelectFile; ?>">
  <input id="uploadfiles" name="upfile" type="button" value="<?php echo $Upload; ?>">
          </div></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td width="55" valign="top">&nbsp;</td>
          <td valign="top">
          <!--<input name="Button" type="button" class="button" id="submit1" value="Submit Your Data" style="width: 135px"/>-->
          <input name="Button" type="button" class="button" id="submit1" value="<?php echo $SubmitData; ?>" style="width: 135px"/>
          
          
         </td>
          </tr>
      </table>
    </FORM>
	<p>&nbsp;</p></blockquote>
    </td>
  </tr>
  <tr>
  <script src="js/footer.js"></script>
 
  </tr>
</table>

<script type="text/javascript">

function log(file) {
		var str = "";
var id2;
		plupload.each(arguments, function(arg) {
			var row = "";

			if (typeof(arg) != "string") {
				plupload.each(arg, function(value, key) {
					// Convert items in File objects to human readable form
				
					if ((typeof(value) != "function")&&(key=="name")) {
						row +=  value;
					}
					if ((typeof(value) != "function")&&(key=="id")) {
						id2 =  value;
					}
		
				});

				str += row + " ";
			} else { 
				str += arg + " ";
			}
		});

//Uploads the file and file name is stored in str
//Now to start processing this file and check if its a valid csv file
//lets process it on a server side php query and then get the result using an ajax request
//if invalid file delete it from the server and delete it from the list
//else pass the file name to a php script to add data

}



function $(id) {
	return document.getElementById(id);	
}


var uploader = new plupload.Uploader({
	runtimes : 'gears,html5,flash,silverlight,browserplus',
	browse_button : 'pickfiles',
	unique_names : true,
	container: 'container',
	max_file_size : '10mb',
	multi_selection:false,
	url : 'upload.php',
	resize : {width : 320, height : 240, quality : 90},
	flash_swf_url : 'uploader/plupload.flash.swf',
	silverlight_xap_url : 'uploader/plupload.silverlight.xap',
	filters : [
		{title : "CSV File", extensions : "csv"}	],
	rename: true,
	init : {
			
			FileUploaded: function(up, file, info) {
				// Called when a file has finished uploading
            log(file);
			check(file.name, file.id);
			},


			Error: function(up, args) {
				// Called when a error has occured
				//alert("Invalid File Type Selected");
				alert(<?php echo "'".$InvalidFile."'";?>);
				
			}
		}



});



uploader.bind('Init', function(up, params) {
	;
});

uploader.bind('FilesAdded', function(up, files) {

	
		
	for (var i in files) {
		var temp=files[i].name.split("."); 
	if(temp[1]=="csv")
	{ 
		$('filelist').innerHTML += '<div id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></div>';
	(function($){
		$("#filename").val(files[i].name);
	})(jQuery);
	files[i].name=files[i].id + ".csv";
	}
		
	}
});

uploader.bind('UploadProgress', function(up, file) {
	$(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
});

$('uploadfiles').onclick = function() {
	uploader.start();
	return false;
};



uploader.init();


function del(id2)
{
	

	var file1 = uploader.getFile(id2);
	//$(file1.id).getElementsByTagName('b')[0].innerHTML = '<span>' + "Invalid File! Make changes and re-upload" + "</span>";
	$(file1.id).getElementsByTagName('b')[0].innerHTML = '<span>' + <?php echo "'".$InvalidFileChanges."'";?> + "</span>";
	(function($){
		$("#filename").val("Invalid File");
	})(jQuery);
	

}

function check(name, id2)
{
	//alert(name);
	
	var url2 = 'upload_check.php?name='+ name;
	


if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    var msg=xmlhttp.responseText;
	
	if (msg="true")
//{alert("Upload successful. Click on submit data to proceed.");
{alert(<?php echo "'".$UploadSuccess."'";?>);
$filename=name;

	}
	else
{alert(msg);del(id2);}	
    }
  }
xmlhttp.open("GET",url2,true);
xmlhttp.send();
	
	
	}



(function($){
	


$(document).ready(function() {
	//$("#filename").val("Please select a file....");
	$("#filename").val(<?php echo "'".$SelectFileEllipsis."'";?>);
	<?php echo $jq;?>
  var glob_siteid=1;
  var $filename="";
  
  $("#viewdata").click(function() {
	window.location.href = "details.php?siteid="+glob_siteid;
	
});	


$("#viewdata2").click(function() {
	window.location.href = "import_data_file.php";
	
});	

				$("#statusmsg").hide();
  
});

$("#submit1").click(function() {

	if(($("#SourceID option:selected").val())==-1){
		//alert("Please select a Source!");
		alert(<?php echo "'".$SelectSourceMsg."'";?>);
		return false;
	}

	if(($("#SiteID option:selected").val())==-1){
		//alert("Please select a Site!");
		alert(<?php echo "'".$SelectSite."'";?>);
		return false;
	}
	
	glob_siteid=$("#SiteID option:selected").val();
	if(($("#VariableID option:selected").val())==-1){
		//alert("Please select a Type!");
		alert(<?php echo "'".$SelectType."'";?>);
		return false;
	}

	if(($("#MethodID option:selected").val())==-1){
		//alert("Please select a Method!");
		alert(<?php echo "'".$SelectMethodMsg."'";?>);
		return false;
	}
	
	//All Validation checks completed...time to open the file and start processing
	
	


var sourceid=$("#SourceID option:selected").val();
var siteid=$("#SiteID option:selected").val();
glob_siteid=siteid;
var variableid=$("#VariableID option:selected").val();
var methodid=$("#MethodID option:selected").val();


if (typeof $filename === "undefined") 
{
//alert("Please upload a file");
alert(<?php echo "'".$PleaseUpload."'";?>);
return false;
}


var file_final=$filename;

$.ajax({
  type: "POST",
  url: "do_import_data_file.php?SourceID="+sourceid+"&SiteID="+siteid+"&VariableID="+variableid+"&MethodID="+methodid+"&filename="+$filename
}).done(function( msg ) {
  if(msg==1)
  {
	   $("#statusmsg").show(1200);
	  return true;
 }
  else
  {alert(msg);
   //alert("Error in database configuration");
   alert(<?php echo "'".$DatabaseConfigurationError."'";?>);
  return false;
	  
  }
 });



return false;


});
})(jQuery);

</script>
</body>
</html>
