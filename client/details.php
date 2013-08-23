<?php
//check authority to be here
require_once 'authorization_check2.php';
require_once 'db_config.php';

// get data and store in a json array
$query = "SELECT DISTINCT SiteName, SiteType, Latitude, Longitude FROM sites";
$siteid = $_GET['siteid'];
$query .= " WHERE SiteID=".$siteid;

$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$row = mysql_fetch_array($result, MYSQL_ASSOC);
?>
<html>
<head>
<title>IDAH2O Web App</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="favicon.ico" >

<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jquery-migrate-1.1.0.min.js"></script>


<link href="styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />
<script>
var did;

    $(document).ready(function () {
		

$("#editBut").click(function(){

	//Get the DataID and redirect to the editAdaptor for further processing
	window.location.replace("editAdaptor.php?did="+did);
	
});

	$("#printBut").click(function(){
var prtContent = document.getElementById("dataResult");
var searchText='<tr><td colspan="2"></td></tr>';
var replaceText="<tr><td width=20%><strong>Site: </strong></td><td width=70%><?php echo $row['SiteName']; ?></td></tr>";
var tempTxt='<tr><td colspan="2" align="center"><strong>Data Report View</strong></td></tr><tr><td colspan="2"></td></tr><tr><td colspan="2"></td></tr>';
var dtTxt=$("#dataType").html().replace('<a href="javascript:history.go(0)" class="button">Change</a>',"").replace("<b>Data Type: </b>","");
replaceText = replaceText + "<tr><td width=20%><strong>Data Type: </strong></td><td width=70%>"+dtTxt+"</td></tr>";
var WinPrint = window.open('', '', 'letf=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
prtContent=$("#dataResult").html();
prtContent=prtContent.replace(tempTxt,replaceText);
WinPrint.document.write(prtContent+"<br>This Data report has been generated from The Master Water Stewards Web Application(www.idah2o.host22.com).");
WinPrint.document.close();
WinPrint.focus();
WinPrint.print();
WinPrint.close();
		});
		
		$("#pdfBut").click(function(){
		
		//Show Pdf Fetcher Loader. 
		
		$('#loadingPDF').fadeIn(500);
		$('html, body').animate({scrollTop:$(document).height()}, 'slow');
		var prtContent = document.getElementById("dataResult");
		var searchText='<tr><td colspan="2"></td></tr>';
		var replaceText="<tr><td width=20%><strong>Site: </strong></td><td width=70%><?php echo $row['SiteName']; ?></td></tr>";
		var tempTxt='<tr><td colspan="2" align="center"><strong>Data Report View</strong></td></tr><tr><td colspan="2"></td></tr><tr><td colspan="2"></td></tr>';
		var dtTxt=$("#dataType").html().replace('<a href="javascript:history.go(0)" class="button">Change</a>',"").replace("<b>Data Type: </b>","");
		replaceText = replaceText + "<tr><td width=20%><strong>Data Type: </strong></td><td width=70%>"+dtTxt+"</td></tr>";
		prtContent=$("#dataResult").html();
		prtContent=prtContent.replace(tempTxt,replaceText);
		prtContent=prtContent+"<br>This Data report has been generated from The Master Water Stewards Web Application(www.idah2o.host22.com).";
		
		$('#dataPDF').val(prtContent);
		$('#invisible_form').submit();
		$('#loadingPDF').fadeOut(500);
		});	
	});
	
		function goto2(id)
		{
		//Hide All  Buttons
		var siteid=<?php echo $_GET['siteid'];?>;
		 $('#buttontable').fadeOut(600, function() {
			 //Show the type of data
			 var text="";
			 switch(id)
			 {
				case 131:text="<p id='dataType' align='center'><b>Data Type: </b>Habitat Assessment Data <a href='javascript:history.go(0)' class='button'>Change</a></p>";break;
				case 53:text="<p id='dataType' align='center'><b>Data Type: </b>Biological Assessment Data <a href='javascript:history.go(0)' class='button'>Change</a></p>";break;
				case 56:text="<p id='dataType' align='center'><b>Data Type: </b>Physical/Chemical Assessment Data <a href='javascript:history.go(0)' class='button'>Change</a></p>";break;
				case 182:text="<p id='dataType' align='center'><b>Data Type: </b>Standing Water Assessment Data <a href='javascript:history.go(0)' class='button'>Change</a></p>";break;
				 
			 }
			 $("#datatype").html(text);
			  $("#datatype").fadeIn(400);
			 $('#loading').fadeIn(500);
			//Get the Date Range Available
			
			var url="get_date2.php?siteid="+siteid+"&varid="+id;
			
$.ajax({
        type: "GET",
	url: url,
	dataType: "xml",
	success: function(xml) {

//Displaying the Available Dates	
$(xml).find("dates").each(function()
{

//Displaying the Available Dates
sitename=String($(this).attr("sitename"));	
date_from=String($(this).attr("date_from"));
date_to=String($(this).attr("date_to"));		
//Call the next function to display the data

$('#daterange').html("");  
$('#daterange').prepend('<p align="center"><strong>Dates Available:</strong> ' + date_from + ' <strong>to</strong> ' + date_to +'</p>');
$('#daterange').show();  

})
}
});


			//Send out an AJAX request to fetch the dates. 
			
			
			
			
			 $.ajax({
 				 url: "db_get_viewdates.php?sid="+siteid+"&vid="+id
				 }).done(function(data) {
				
				$("#datesdata").hide();
				$("#datesdata").html(data);
			
  				 $('#loading').fadeOut(500, function (){
				 
				  $("#datesdata").fadeIn(300);
			 
				//Keep checking if date selected
				$("#seldate").show();
			
				$("#show").click(function(){
					$("#dataResult").fadeOut(500);
					$("#dataButtons").hide();
					flag=0;
					
				});
				var flag=0;
				did=-1;
				setInterval(function(){
					
					if ($('#dateval').val()!="")
					{
						if (did!=$('#dateval').val())
						{
						did=$('#dateval').val();
						
						$("#dataResult").html("");
						$("#final").hide();		
						$("#viewfinal").click();
						}
					}
					else
					{
						$("#final").hide();						
					}
					
				},500);
				
				$("#viewfinal").click(function(){
						
							//Show Loader...
							flag=1;
							$("#final").hide();
							//$("#seldate2").hide();
							$('#loading').fadeIn(500);
							//Send out an ajax request to get the contents for the result. 
							//Pass the data value id 'did'
							 $.ajax({
 				 url: "db_get_details.php?did="+did
				 }).done(function(data) {
							
							$("#dataResult").html(data);
							
				
							$('#loading').fadeOut(500,function(){$("#dataResult").fadeIn(500);
							
							$("#dataButtons").show();
							});
							
							
				 });
							});
				});
			 
			 //Once Ajax Request is complete
			
				 
			 });
			
		 });
       //Show the loader
	   
	   
	
		return false;
		}
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
    <td valign="middle" bgcolor="#FFFFFF" width="720"><blockquote>
      <p>&nbsp;</p>
      <table width="630" border="0">
        <tr>
          <td colspan="4"><?php  


echo("<p align='center'><b>Site: </b>".$row['SiteName']." <a class='button' href='javascript:window.location.replace(".'"view_main.php"'.");'>Change</a><a class='button' href='javascript:window.location.replace(".'"detailsg.php?siteid='.$siteid.'"'.");'>Graph View</a></p>
</td>
          </tr>
     
		<tr>
           <td id='datatype' hidden='true' colspan='4'>
");
?>

</td></tr>
 <tr>
           <td width='50%'>&nbsp;</td>
          <td width='50%'>&nbsp;</td>
        </tr>
<tr>
           <td id='daterange' hidden='true' colspan='4'>

</td></tr>

  <tr>
           <td width='50%'>&nbsp;</td>
          <td width='50%'>&nbsp;</td>
        </tr>
   <tr>
           <td id="seldate" style="width:30%;" hidden="true" align="right"><strong>Selected Date:</strong></td>
          <td style="width:70%;"><div id="datesdata"></div></td>
        </tr>
        
 
            <tr>
           <td width='50%'>&nbsp;</td>
          <td width='50%'>&nbsp;</td>
        </tr>
          <tr>
           <td colspan='4' hidden="true"  id="final"><p align='middle'><a href="#" id="viewfinal" class="button">View Data</a></p></td>
           </tr>
</table>
  <table id="buttontable" width="630" border="0">

<?php
//To determine types of data available for this site. 

//Habitat Assessment Present? Check based on Var Embeddeddness:131

$query = "SELECT * FROM seriescatalog";
$query .= " WHERE SiteID='$siteid' AND VariableID='131'";
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$row = mysql_fetch_array($result, MYSQL_ASSOC);


echo '<tr>
          <td width="50%">';

if($row)
{
echo	'<div onclick="goto2(131)" class="button_details" style=" padding-left:56px;  padding-right:56px;">Habitat Assessment Data</div>';
}
else
{
echo	'<div class="button_details2" style=" padding-left:56px;  padding-right:56px;">Habitat Assessment Data</div>';
}

echo '</td>';

echo '<td width="50%">';

//Bio Assessment Present? Check based on Fish!:53

$query = "SELECT * FROM seriescatalog";
$query .= " WHERE SiteID='$siteid' AND VariableID='53'";
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$row = mysql_fetch_array($result, MYSQL_ASSOC);

if($row)
{
echo	'<a href="#" onclick="goto2(53)" class="button_details">Biological Assessment Data</a>';
}
else

{
echo	'<div class="button_details2">Biological Assessment Data</div>';
}

echo '</td>';
echo '</tr>';

//Psychem Assessment Present? Check based on Var stream depth:56

$query = "SELECT * FROM seriescatalog";
$query .= " WHERE SiteID='$siteid' AND VariableID='56'";
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$row = mysql_fetch_array($result, MYSQL_ASSOC);


echo '<tr>
          <td width="50%">';

if($row)
{
echo	'<a href="#" onclick="goto2(56)" style=" padding-left:35px;  padding-right:35px;" class="button_details">Physical/Chemical Assessment</a>';
}
else

{
echo	'<div class="button_details2" style=" padding-left:35px;  padding-right:35px;">Physical/Chemical Assessment</div>';
}

echo '</td>';

echo '<td width="50%">';

//SW Assessment Present? Check based on Site location!:182

$query = "SELECT * FROM seriescatalog";
$query .= " WHERE SiteID='$siteid' AND VariableID='182'";
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$row = mysql_fetch_array($result, MYSQL_ASSOC);

if($row)
{
echo	'<a href="#" onclick="goto2(182)" class="button_details">Standing Water Assessment</a>';
}
else

{
echo	'<div class="button_details2">Standing Water Assessment</div>';
}

echo '</td>';
echo '</tr>';


?>

       
       
</table>






<div id="loading" hidden="true" style="padding-left:25%;">
<img src="images/loader.gif" width="32" height="32" style="margin-left:120px;" alt="loading" /><br>
Please wait...Fetching data from the server!
</div>
</blockquote>
<div id="dataResult" hidden="true">
</div>
<div style="margin-left:35%" id="dataButtons" hidden="true">
<a id='editBut' style="cursor: hand;" class='button'>Edit</a>     
<a id='printBut' style="cursor: hand;" class='button'>Print</a>     <a id='pdfBut' style="cursor: hand;" class='button'>Download as PDF</a>
</div>
<div id="loadingPDF" hidden="true" style="padding-left:25%;">
<img src="images/loader.gif" width="32" height="32" style="margin-left:120px;" alt="loading" /><br>
Please wait...Generating your PDF!
</div>
</td>
</tr>
    <tr>
    <script src="js/footer.js"></script>
    </tr>
</table>

<form id="invisible_form" action="getpdf.php" method="post" target="_blank">
  <input id="dataPDF" name="data" type="hidden" value="default">
</form>
  
</body>
</html>