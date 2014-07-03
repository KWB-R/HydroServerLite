<?php
//This is required to get the international text strings dictionary
	require_once 'internationalize.php';
	
//check authority to be here
require_once 'authorization_check.php';

//connect to server and select database
require_once 'database_connection.php';
require_once 'fetchMainConfig.php';

//add the SourceID's options
$sql ="Select * FROM sources";

$result = @mysql_query($sql,$connection)or die(mysql_error());

$num = @mysql_num_rows($result);
	if ($num < 1) {

	    //$msg = "<span class='em'>Sorry, there are no SourceID names.</span>";
		$msg = "<span class='em'> $NoSourceIDNames </span>";

	}else{

	while ($row = mysql_fetch_array ($result)) {

		$sourceid = $row["SourceID"];
		$sourcename = $row["Organization"];

		if ($sourcename==$default_source){
			$option_block .= "<option selected='selected' value=$sourceid>$sourcename</option>";

		}else{
			$option_block .= "<option value=$sourceid>$sourcename</option>";
		}
	}
}

//add the SiteType options
$sql2 ="Select * FROM sitetypecv";

$result2 = @mysql_query($sql2,$connection)or die(mysql_error());

$num2 = @mysql_num_rows($result2);
	if($num2 < 1){

	    //$msg = "<span class='em'>Sorry, there are no Site Types.</span>";
		$msg = "<span class='em'> $NoSiteTypes </span>";

	}else{

		while ($row2 = mysql_fetch_array ($result2)) {

			$sitetype = $row2["Term"];
			$option_block2 .= "<option value='$sitetype'>$sitetype</option>";
		}
	}

//add the VerticalDatum options
$sql3 ="Select * FROM verticaldatumcv";

$result3 = @mysql_query($sql3,$connection)or die(mysql_error());

$num3 = @mysql_num_rows($result3);
	if($num3 < 1){
		//$msg = "<span class='em'>Sorry, there are no Vertical Datums.</span>";
		$msg = "<span class='em'> $NoVerticalDatums </span>";
	}else{
		while ($row3 = mysql_fetch_array ($result3)){
			$vd = $row3["Term"];

			if($vd==$default_datum){
				$option_block3 .= "<option selected='selected' value='$vd'>$vd</option>";
			}else{
				$option_block3 .= "<option value=$vd>$vd</option>";
			}
		}
	}

//add the LatLongDatumID options
$sql4 ="Select * FROM spatialreferences";

$result4 = @mysql_query($sql4,$connection)or die(mysql_error());

$num4 = @mysql_num_rows($result4);
	if($num4 < 1){

	    //$msg = "<span class='em'>Sorry, there are no Vertical Datums.</span>";
		$msg = "<span class='em'> $NoVerticalDatums </span>";

	}else{

		while ($row4 = mysql_fetch_array ($result4)){

			$srid = $row4["SpatialReferenceID"];
			$srsname = $row4["SRSName"];

			if($srsname==$default_spatial){
	
				$option_block4 .= "<option selected='selected' value=$srid>$srsname</option>";

			}else{

				$option_block4 .= "<option value=$srid>$srsname</option>";
			}
		}
	}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<title>HydroServer Lite Web Client</title>-->
<title><?php echo $WebClient;?></title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="favicon.ico" >

<link rel="stylesheet" href="js/jqwidgets/styles/jqx.base.css" type="text/css" />
<link rel="stylesheet" href="js/jqwidgets/styles/jqx.darkblue.css" type="text/css" />
<script type="text/javascript" src="js/gettheme.js"></script>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
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
function show_answer(){
//alert("If you do not see your SITE listed here, please contact your supervisor and ask them to add it before entering data.");
alert(<?php echo "'".$SiteNotListedContact."'"; ?>);
}

function show_answer2(){
//alert("The current version of this software does not autmatically select the State and County. Please select them mannually.");
alert(<?php echo "'".$SelectStateCounty."'"; ?>);
}
</script>

<script type='text/javascript' src='js/drop_down.js'></script>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC3d042tZnUAA8256hCC2Y6QeTSREaxrY0&sensor=true"></script>

<script type="text/javascript">

$(document).ready(function(){
	$("#msg").hide();
	$("#msg2").hide();
	$("#msg3").hide();
	
	$('#window').hide();

	$('#window').jqxWindow({ height: 100, width: 200, theme: 'darkblue' });
    	$('#window').jqxWindow('hide');

	$("#Yeah").click(function(){
		deleteSite();	
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

</script>

<!-- Preload Images -->
<SCRIPT language="JavaScript">
<!--
pic1 = new Image(16, 16); 
pic1.src="images/loader.gif";
//-->
</SCRIPT>
<script type="text/javascript">

    // This will parse a delimited string into an array of
    // arrays. The default delimiter is the comma, but this
    // can be overriden in the second argument.
    function CSVToArray( strData, strDelimiter ){
        // Check to see if the delimiter is defined. If not,
        // then default to comma.
        strDelimiter = (strDelimiter || ",");

        // Create a regular expression to parse the CSV values.
        var objPattern = new RegExp(
                (
                        // Delimiters.
                        "(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +

                        // Quoted fields.
                        "(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +

                        // Standard fields.
                        "([^\"\\" + strDelimiter + "\\r\\n]*))"
                ),
                "gi"
                );


        // Create an array to hold our data. Give the array
        // a default empty first row.
        var arrData = [[]];

        // Create an array to hold our individual pattern
        // matching groups.
        var arrMatches = null;


        // Keep looping over the regular expression matches
        // until we can no longer find a match.
        while(arrMatches = objPattern.exec( strData )){

                // Get the delimiter that was found.
                var strMatchedDelimiter = arrMatches[ 1 ];

                // Check to see if the given delimiter has a length
                // (is not the start of string) and if it matches
                // field delimiter. If id does not, then we know
                // that this delimiter is a row delimiter.
                if(
                        strMatchedDelimiter.length &&
                        (strMatchedDelimiter != strDelimiter)
                        ){

                        // Since we have reached a new row of data,
                        // add an empty row to our data array.
                        arrData.push( [] );
                }


                // Now that we have our delimiter out of the way,
                // let's check to see which kind of value we
                // captured (quoted or unquoted).
                if(arrMatches[ 2 ]){

                        // We found a quoted value. When we capture
                        // this value, unescape any double quotes.
                        var strMatchedValue = arrMatches[ 2 ].replace(
                                new RegExp( "\"\"", "g" ),
                                "\""
                                );

                }else{

                        // We found a non-quoted value.
                        var strMatchedValue = arrMatches[ 3 ];

                }


                // Now that we have our value string, let's add
                // it to the data array.
                arrData[ arrData.length - 1 ].push( strMatchedValue );
        }

        // Return the parsed data.
        return( arrData );
    }

</script>
<script type="text/javascript">
     var map="-1";
	 var marker=null;
	 var elevator;

function initialize() {
	elevator = new google.maps.ElevationService();
	$("#file").hide();
	showSites($("#SourceID option:selected").val());
	
	//Make the Map
	GetSourceName();
	var myLatlng = new google.maps.LatLng(43.52764,-112.04951);
}


 
//Function to run on form submission to implement a validation and then run an ajax request to post the data to the server and display the message that the site has been added successfully

function TrainingAlert(){
	//alert("To automatically enter the latitude, longitude, and elevation, simply double click the location on the map. Once the marker is placed on the map, you may then click and drag it to the exact location you desire to adjust the results to be more accurate.");
	alert(<?php echo "'".$AutomaticallyEnterLongLatEle."'"; ?>);
} 




function showSites(str){

document.getElementById("txtHint").innerHTML="<select name='SiteID' id='SiteID' onChange='findSite()'><option value=''>Select....</option></select>&nbsp;<a href='#' onClick='show_answer()' border='0'><img src='images/questionmark.png' border='0'></a>";

if(str==""){
	document.getElementById("txtHint").innerHTML="";
	return;
	}
if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
}else{// code for IE6, IE5
	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange=function(){
	if(xmlhttp.readyState==4 && xmlhttp.status==200){
		document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
}
xmlhttp.open("GET","getsites2.php?q="+str,true);
xmlhttp.send();
}

</script>

<STYLE TYPE="text/css">
<!--
#county_drop_down, #no_county_drop_down, #loading_county_drop_down
{
display: none;
}
--> 
</STYLE>

<!-- Creating the Site Code automatically -->
<script type="text/javascript" src="js/create_site_code.js"></script>

</head>

<body background="images/bkgrdimage.jpg" onLoad="initialize()">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><?php include "topBanner.php" ; ?></td>
  </tr>
  <tr>
    <td colspan="2" align="right" valign="middle" bgcolor="#3c3c3c"><?php require_once 'header.php'; ?></td>
  </tr>

  <tr>
    <td width="240" valign="top" bgcolor="#f2e6d6"><?php echo "$nav"; ?></td>
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br /><?php //echo "$msg"; ?><p class="em" align="right"><!--Required fields are marked with an asterick (*).--><?php echo $RequiredFieldsAsterisk;?></p><div id="msg"><p class=em2><!--Site successfully deleted!--><?php echo $SiteSuccessfullyDeleted;?></p></div>
    <div id="msg2"><p class=em2><!--Site successfully edited!--><?php echo $SiteSuccessfullyEdited;?></p></div>
      <h1><!--Edit or Delete a Site--><?php echo $EditDeleteSite;?></h1>
      <p><!--Please select the Source and Site you would like to edit or delete from the drop down menu to proceed.--><?php echo $SelectSourceSiteMenu;?></p>
      <p>&nbsp;</p>
      <table width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <!--<td valign="top"><strong>Source:</strong></td>-->
          <td valign="top"><strong><?php echo $Source;?></strong></td>

          <td colspan="3" valign="top"><select name="SourceID" id="SourceID" onChange="showSites(this.value)"><option value="-1"><!--Select...--><?php echo $SelectEllipsis;?></option><?php echo "$option_block"; ?></select></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td width="78" valign="top"><strong><!--Site:--><?php echo $Site;?></strong></td>
          <td width="522" colspan="3" valign="top"><div id="txtHint"><select name="SiteID" id="SiteID" onChange="findSite()"><option value="-1"><!--Select....--><?php echo $SelectEllipsis;?></option></select>&nbsp;<a href="#" onClick="show_answer()" border="0"><img src="images/questionmark.png" border="0"></a></div></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
      </table>
	<p>&nbsp;</p>
      <div id="msg3"></div>
    <p>&nbsp;</p>
    </blockquote>
    <p></p></td>
  </tr>
  <tr>
    <script src="js/footer.js"></script>
  </tr>
</table>

<script>

//When a Site selection from the drop down menu is made, a query is used to fill in the form.
function findSite(){

marker=null;

		var siteid=$("#SiteID").val();
	
		$.ajax({
		type: "POST",
		url: "request_site_info.php?SiteID="+siteid}).done(function(data){
			
			if(data){
				
				$("#msg3").html(data);
				$("#msg3").show(500);
				
				//also to load the maps and perform other javascript operations
				//To Get the Sitepicture Details
				
				$.ajax({
  					type: "POST",
 					 url: "getsitepic.php?sc="+siteid
					  }).done(function( msg ){
  					if(msg!=-1){
	  
					$("#file").hide();
					//$("#sitepic").html(msg+"<br><div id='sitepicchange'><a href='#'>Click Here to Change the 		site picture</a></div>");
					("#sitepic").html(msg+"<br><div id='sitepicchange'><a href='#'>" + <?php echo "'".$ClickChangePicture."'"; ?> + "</a></div>");
						}else{
	 					 $("#file").hide();
	 					 //$("#sitepic").html("No Site Picture Defined.<br><div id='sitepicchange'><a href='#'>Click Here to Add a Site Picture</a></div>");
						 $("#sitepic").html(<?php echo "'".$NoSitePictureDefined."'"; ?> + "<div id='sitepicchange'><a href='#'>" + <?php echo "'".$ClickAddSitePicture."'"; ?> + "</a></div>");
 							 }
					});

						$("#sitepic").click(function(){
							$("#file").show();
						});

					//To Load the map
					

//Call the map
var initialLocation = new google.maps.LatLng($("#Latitude").val(),$("#Longitude").val());
 var myOptions = {
    zoom: 14,
    center: initialLocation,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
	disableDoubleClickZoom : true
  }
map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
placeMarker(initialLocation);
  google.maps.event.addListener(map, 'dblclick', function(event) {
    placeMarker(event.latLng);
  });

//Map Loading Complete

				
				return true;
			
			}else{
			
				//alert("Error during request! Please refresh the page and begin again.");
				alert(<?php echo "'".$ErrorDuringRequest."'"; ?>);
				return false;
				}
		});
};



//When the "Delete" button is clicked, validate the selected ID and submit the request
	function deleteSite(){

		if(($("#SiteID").val())==-1){
			//alert("Please select a Site to delete!");
			alert(<?php echo "'".$SelectSiteDelete."'"; ?>);
			return false;
		
		}else{
	
		//Validation is now complete, so send to the processing page
		var site_id = $("#SiteID").val();
	
			$.ajax({
			type: "POST",
			url: "delete_site.php?SiteID="+site_id}).done(function(data){

				if(data==1){
				
					$("#msg").show(1600);
					$("#SiteID").val("-1");
					$("#SiteName").val("");
					$("#SiteCode").val("");
					$("#SiteType").val("-1");
					$("#Latitude").val("");
					$("#Longitude").val("");
					$("#Elevation_m").val("");
					$("#state").val("-1");
					$("#county").val("");
					$("#newcounty").val("");
					$("#VerticalDatum").val("-1");
					$("#LatLongDatumID").val("-1");
					$("#Comments").val("");
					$("#msg").hide(2000);
					setTimeout(function() {
						window.open("edit_site.php","_self");
						}, 3800);
					return true;
	
				}else{
				
					//alert("Error during processing! You cannot delete a site that has data values associated with it. Please delete the values first, then delete the site.");
					alert(<?php echo "'".$ErrorDuringProcessingDeleteSite."'"; ?>);
					return false;
					}
			});		
		}
		return false;
	};



//When the "Cancel" button is clicked, clear the fields and reload the page
	function clearEverything(){

		$("#SiteID").val("-1");
		$("#SiteName").val("");
		$("#SiteCode").val("");
		$("#SiteType").val("-1");
		$("#Latitude").val("");
		$("#Longitude").val("");
		$("#Elevation_m").val("");
		$("#state").val("-1");
		$("#county").val("");
		$("#newcounty").val("");
		$("#VerticalDatum").val("-1");
		$("#LatLongDatumID").val("-1");
		$("#Comments").val("");
		setTimeout(function(){
			window.open("edit_site.php","_self");
			});
	}


	
//When the "Save Edits" button is clicked, validate the fields and then submit the request
function updateSite(){

$("form").submit(function(){

//Validate all fields

if(($("#SourceID option:selected").val())==-1){
	//alert("Please select a Source. If you do not find it in the list, please visit the 'Add a new source' page");
	alert(<?php echo "'".$SelectSourceAdd."'"; ?>);
	return false;
}

if(($("#SiteName").val())==""){
	//alert("Please enter a name for the site.");
	alert(<?php echo "'".$EnterSiteName."'"?>);
	return false;
}

if(($("#SiteCode").val())==""){
	//alert("Please enter a code for the site.");
	alert(<?php echo "'".$EnterSiteCode."'"; ?>);
	return false;
}

if(($("#SiteType option:selected").val())==-1){
	//alert("Please select a Site Type.");
	alert(<?php echo "'".$SelectSiteType."'"; ?>);
	return false;
}	  

if(($("#Latitude").val())==""){
	//alert("Please enter the latitude for the site or select a point from the map");
	alert(<?php echo "'".$EnterLatitude."'"; ?>);
	return false;
}

if(($("#Longitude").val())==""){
	//alert("Please enter the longitude for the site or select a point from the map");
	alert(<?php echo "'".$EnterLongitude."'"; ?>);
	return false;
}

if(($("#Elevation").val())==""){
	//alert("Please enter the elevation for the site or select a point from the map");
	alert(<?php echo "'".$EnterElevation."'"?>);
	return false;
}

var floatRegex = '[-+]?([0-9]*\.[0-9]+|[0-9]+)';
var myInt = $("#Latitude").val().match(floatRegex);

if(myInt==null){
	//alert("Invalid characters present in Latitude. Please correct it.");
	alert(<?php echo "'".$InvalidLatitude."'"; ?>);
    return false;
}

if(myInt[0]!=$("#Latitude").val()){
	//alert("Invalid characters present in latitude. Please correct it.");
	alert(<?php echo "'".$InvalidLatitude."'"; ?>);
    return false;
}

myInt = $("#Longitude").val().match(floatRegex);

if(myInt==null)
{
//alert("Invalid characters present in Longitude. Please correct it.");
alert(<?php echo "'".$InvalidLongitude."'"; ?>);
      return false;
}

if(myInt[0]!=$("#Longitude").val()){
	//alert("Invalid characters present in Longitude. Please correct it.");
	alert(<?php echo "'".$InvalidLongitude."'"; ?>);
    return false;
}

myInt = $("#Elevation").val().match(floatRegex);


if(myInt==null){
	//alert("Invalid characters present in Elevation. Please correct it.");
	alert(<?php echo "'".$InvalidElevation."'"?>);
    return false;
}


if(myInt[0]!=$("#Elevation").val()){
	//alert("Invalid characters present in Elevation. Please correct it.");
	alert(<?php echo "'".$InvalidElevation."'"?>);
    return false;
}

if(($("#state option:selected").val())==-1){
	//alert("Please select a State.");
	alert(<?php echo "'".$SelectState."'"; ?>);
	return false;
}

//Validation for the county
if((($("#county option:selected").val())=="") && (($("#newcounty option:selected").val())=="")){
		//alert("Please select a County.");
		alert(<?php echo "'".$SelectCounty."'"; ?>);
		return false;
	}

if($("#county").val()==""){
	 
	var county = $("#newcounty").val();
}

if($("#newcounty").val()==""){

	var county = $("#county").val();
}

if(county==undefined){
	//alert("County is undefined. Please reselect the County.");
	alert(<?php echo "'".$UndefinedCounty."'"; ?>);
	return false;
}


//Validation of county done

if(($("#VerticalDatum option:selected").val())==-1){
	//alert("Please select a Vertical Datum.");
	alert(<?php echo "'".$SelectVerticalDatum."'"; ?>);
	return false;
}

if(($("#LatLongDatumID option:selected").val())==-1){
	//alert("Please select a Spatial Reference.");
	alert(<?php echo "'".$SelectSpatialReference."'"; ?>);
	return false;
}

//All Validation Checks completed. Now add data to the database.

var sourceid = $("#SourceID option:selected").val();
var siteid = $("#SiteID option:selected").val();
var site_c = $("#SiteCode").val();
var site_n = $("#SiteName").val();
var site_lat = $("#Latitude").val();
var site_long = $("#Longitude").val();
var site_llid = $("#LatLongDatumID option:selected").val();
var site_type = $("#SiteType option:selected").val();
var site_elev = $("#Elevation").val();
var site_vd = $("#VerticalDatum option:selected").val()
var state = $("#state option:selected").val();
var site_com = $("#com").val();

	$.ajax({
	type: "POST",
	url: "do_edit_site.php?sc="+site_c+"&sn="+site_n+"&lat="+site_lat+"&lng="+site_long+"&llid="+site_llid+"&type="+site_type+"&elev="+site_elev+"&datum="+site_vd+"&state="+state+"&county="+county+"&com="+site_com+"&source="+sourceid+"&siteid="+siteid}).done(function(msg){

		if(msg==1){

	
			formdata = new FormData();	
			//document.getElementById("response").innerHTML = "Uploading . . ."
			document.getElementById("response").innerHTML = <?php echo "'".$UploadEllipsis."'" ;?>
			//Upload the image
			var input = document.getElementById("file");
			var file = input.files[0];
		
			if (file!=null){
				formdata.append("images[]", file);

				$.ajax({
					url: "do_add_site2.php?siteid="+siteid,
					type: "POST",
					data: formdata,
					processData: false,
					contentType: false,
					success: function(res){
			
						if(res==1){
					//			$("#msg2").show(2500);
			//$("#msg2").hide(3500);
			$("#SourceID").val("-1");
			$("#SiteID").val("");
			$("#SiteName").val("");
			$("#SiteCode").val("");
			$("#Latitude").val("");
			$("#Longitude").val("");
			$("#LatLongDatumID").val("-1");
			$("#SiteType").val("-1");
			$("#Elevation").val("");
			$("#VerticalDatum").val("-1");
			$("#state").val("-1");
			$("#county").val("");
			$("#newcounty").val("");
		
			$("#msg3").hide(1000);

							//alert("Site Edit Successful");
							alert(<?php echo "'".$SiteEditSuccessful."'"; ?>);
							window.location.href = "edit_site.php";
							return true;
						}else{
							document.getElementById("response").innerHTML = "" 
							alert(res);
							return false;
						}
					}
				});

			
			}else{
				//	$("#msg2").show(2500);
			//$("#msg2").hide(3500);
			$("#SourceID").val("-1");
			$("#SiteID").val("");
			$("#SiteName").val("");
			$("#SiteCode").val("");
			$("#Latitude").val("");
			$("#Longitude").val("");
			$("#LatLongDatumID").val("-1");
			$("#SiteType").val("-1");
			$("#Elevation").val("");
			$("#VerticalDatum").val("-1");
			$("#state").val("-1");
			$("#county").val("");
			$("#newcounty").val("");
		
			$("#msg3").hide(1000);

				//alert("Site Edit Successful");
				alert(<?php echo "'".$SiteEditSuccessful."'"; ?>);
				window.location.href = "edit_site.php";
				return true;
			}
	
			
		
		}else{
		  //alert("Error in database configuration");
		  alert(<?php echo "'".$DatabaseConfigurationError."'"; ?>);
		  return false;
		}
  
	});

	return false;
	});
}
function placeMarker(location){

 if(marker==null){
  marker = new google.maps.Marker({
      position: location,
      map: map,
	  draggable: true
  });
  
  google.maps.event.addListener(marker, 'dragend', function(event){
	 
//Again Update the Latitude longitude values
update(event.latLng)
//    placeMarker(event.latLng);
  });
  
  
  //Update values in the 
  update(location)
  
 }
 else
 {
	marker.setPosition(location); 
//Update Values into the form	
update(location)

 }
  map.setCenter(location);
}

function update(location)
{
	
	
	$("#Latitude").val(parseFloat(location.lat()).toFixed(5));
	$("#Longitude").val(parseFloat(location.lng()).toFixed(5));

//Update Elevation




  var locations = [];
  locations.push(location);

  // Create a LocationElevationRequest object using the array's one value
  var positionalRequest = {
    'locations': locations
  }

  // Initiate the location request
  elevator.getElevationForLocations(positionalRequest, function(results, status) {
    if (status == google.maps.ElevationStatus.OK) {

      // Retrieve the first result
      if (results[0]) {

        // Open an info window indicating the elevation at the clicked position
        $("#Elevation").val(parseFloat(results[0].elevation).toFixed(1));
	
        
      } else {
        //alert("No results found");
		alert(<?php echo "'".$NoResultsFound."'"; ?>);
      }
    } else {
      //alert("Elevation service failed due to: " + status);
	  alert(<?php echo "'".$ElevationServiceFailed."'"; ?>+ " " + status);
    }
  });

	

// Now to update the state
var latlng1 = new google.maps.LatLng(location.lat(), location.lng());
var geocoder = new google.maps.Geocoder();
geocoder.geocode({'latLng': latlng1}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
			
			//$("#locationtext").html("Your selected location according to us is: " + results[0].formatted_address + ". Please select the state and county accordingly.");
			$("#locationtext").html(<?php echo "'".$SelectedLocationIs."'"; ?> + " " + results[0].formatted_address + ". "+ <?php echo "'".$SelectStateCountyAccordingly."'"; ?>);
			        
          
        }
      } else {
        //alert("Geocoder failed due to: " + status);
		alert(<?php echo "'".$GeocoderFailed."'"; ?> + " " + status);
      }
    });

}	
	
</script>

<div id="window"><div id="windowHeader"><span><!--Confirmation Box--><?php echo $ConfirmationBox;?></span></div><div style="overflow: hidden;" id="windowContent"><center><strong><!--Are you sure?--><?php echo $AreYouSure;?></strong><br /><br /><input name="Yes" type="button" value="<?php echo $Yes;?>" id="Yeah"/>&nbsp;<input name="No" type="button" value="<?php echo $No;?>" id="No"/></center></div></div>
</body>
</html>
