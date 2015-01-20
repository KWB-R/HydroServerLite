<?php HTML_Render_Head($js_vars,getTxt('ChangeSite'));?>
<script type="text/javascript">
//For loading language variables that are required in javascript.
    phpVars = {};
    <?php  
		echo 'phpVars.NoSitesSource="' . getTxt('NoSitesSource') . '";';
		echo 'phpVars.SelectSite="' . getTxt('SelectSite2') . '";';
    ?>
</script>
<?php
echo $CSS_Main;
echo $JS_JQuery;
echo $JS_Forms;
echo $CSS_JQX;
echo $JS_GetTheme;
echo $JS_JQX;
echo $JS_DropDown;

?>


<script type="text/javascript">
function show_answer(){
//alert("If you do not see your SITE listed here, please contact your supervisor and ask them to add it before entering data.");
alert(<?php echo "'".getTxt('SiteNotListedContact')."'"; ?>);
}

function show_answer2(){
//alert("The current version of this software does not autmatically select the State and County. Please select them mannually.");
alert(<?php echo "'".getTxt('SelectStateCounty')."'"; ?>);
}
</script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC3d042tZnUAA8256hCC2Y6QeTSREaxrY0&sensor=true"></script>
<script type="text/javascript">

$(document).ready(function(){
	$("#msg").hide();
	$("#msg2").hide();
	$("#msg3").hide();
	$("#editsite").hide();
	$('#window').hide();

	$('#window').jqxWindow({ height: 150, width: 200, theme: 'darkblue' });
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

     var map="-1";
	 var marker=null;
	 var elevator;

function initialize() {
	
	$("#file").hide();
	showSites($("#SourceID option:selected").val());
	//Make the Map
	GetSourceName();
	var myLatlng = new google.maps.LatLng(43.52764,-112.04951);
}

</script>
<?php 
echo $JS_CreateUserName;
HTML_Render_Body_Start(); 
genHeading('EditDeleteSite',true);
?>
<p><?php echo getTxt('SelectSourceSiteMenu');?></p>
<?php
$attributes = array('class' => 'form-horizontal');
echo form_open_multipart('', $attributes);

genSelect('Source','SourceID','SourceID',$sourceOptions,'SelectEllipsis',true,' onChange="showSites(this.value)"');
genSelectH('SiteName',"SiteID","SiteID",'',getTxt('IfNoSeeSite1').' '.getTxt('ContactSupervisor').' '.getTxt('AddIt'),'SelectElipsis',true);
echo "</form>";
$attributes = array('class' => 'form-horizontal', 'name' => 'editsite', 'id' => 'editsite');
echo form_open_multipart('sites/change', $attributes);

genInput('Site','SiteID2','SiteID2', true,' readonly');
echo '<div class="form-group"><label class="col-sm-2 control-label">'.getTxt('SiteName').'</label><div class="col-sm-10">
<input type="type" class="form-control" id="SiteName" name="SiteName" onKeyUp="GetSiteName()">
<span class="required"></span>
<span class="em">'.getTxt('ExSiteName')." ".getTxt('NoApostrophe').'</span></div></div>';
//genInput('SiteName','SiteName','SiteName', true, ' onKeyUp="GetSiteName()"');
//echo '<span class="em">'.getTxt('ExSiteName')." ".getTxt('NoApostrophe').'</span>';
echo '<div class="form-group"><label class="col-sm-2 control-label">'.getTxt('SiteCode').'</label>
<div class="col-sm-10"><input type="type" class="form-control" id="SiteCode" name="SiteCode">
<span class="required"></span>
<span class="hint" title="'.getTxt('SiteCodeInfo').'">?</span>
<span class="em">'.getTxt('ExSiteCode').'</span></div></div>';
genSelect('SiteType','SiteType','SiteType',$typeOptions,'SelectEllipsis',true);

echo '<div class="form-group">
	<label class="col-sm-2 control-label">'.getTxt('SitePhoto').'</label>
	<div class="col-sm-10">
		<div id="sitepic"></div>
	   <input class="form-control" type="file" name="picture" id="picture" size="30">';	   
echo'</div>             
  </div><br>';	
echo getTxt('ExSitePhoto');
//Not altering map structure for now. As its responsive. In case it causes issues, the code can be altered here. 
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<div class ="row">
<?php echo getTxt('MapLatLongEle');?>
</div>
<div class="form-group">
<label for="Latitude" class="col-sm-2 control-label"><?php echo getTxt('Latitude');?></label>
<div class="col-sm-4">
<input class="form-control"  type="text" id="Latitude" name="Latitude" maxlength=20/>*
</div>
<label for="Longitude" class="col-sm-2 control-label"><?php echo getTxt('Longitude');?></label>
<div class="col-sm-4">
<input class="form-control" type="text" id="Longitude" name="Longitude" maxlength=20/>*
</div>
</div>
<tr>
  <div id="map_canvas" style="width:100%; height:450px"></div>
</tr>
</table>
<br />
<div class="form-group">
        <label class="col-sm-2 control-label"><?php echo getTxt('Elevation');?></label>
        <div class="col-sm-8">
        <input type="type" class="form-control" id="Elevation" name="Elevation"/>
        </div> 
        <div class="col-sm-2">
        	<?php echo getTxt('Meters');?>
            <span class="hint" title="<?php echo getTxt('ElevationInfo');?>">?</span><span class="required"/>
        </div>            
      </div> 
   <div id="locationtext"></div>   
 
<?php
genSelect('State','state','state',$stateOptions,'SelectEllipsis',true);
?>

<div class="form-group">
        <label class="col-sm-2 control-label"><?php echo getTxt('County');?></label>
        <div class="col-sm-10">
        <div id="county_drop_down"><select class="form-control" id="county" name="county"><option value=""><?php echo getTxt('CountyEllipsis');?></option></select>*</div>
	 <span id="loading_county_drop_down"><img src="<?php echo getImg('loader.gif'); ?>" width="16" height="16" align="absmiddle">&nbsp;<?php echo getTxt('SelectstateElipsis');?></span>
	 <div id="no_county_drop_down"><?php echo getTxt('StateNoCounties');?></div>
        </div> 
</div> 
<?php
genSelectH('VerticalDatum','VerticalDatum','VerticalDatum',$vdOptions,getTxt('VerticalDatumInfo'),'SelectEllipsis',true);
genSelectH('SpatialReference','LatLongDatumID','LatLongDatumID',$srOptions,getTxt('SpatialReferenceInfo'),'SelectEllipsis',true);
genInputT('Comments','com','value',false,'','Optional');

?>  

<input type='SUBMIT' name='submit' value='<?php echo getTxt('SaveEdits');?>' class='button' style='width: auto'/>&nbsp;&nbsp;
<input type='button' name='delete' value='<?php echo getTxt('Delete');?>' class='button' style='width: auto' onClick='confirmBox()'/>&nbsp;&nbsp;
<input type='button' name='Reset' value='<?php echo getTxt('Cancel');?>' class='button' style='width: auto' onClick='clearEverything()'/>
</form>
</div>

<div id="window"><div id="windowHeader"><span><?php echo getTxt('ConfirmationBox');?></span></div>
<div style="overflow: hidden;" id="windowContent"><center><strong><?php echo getTxt('AreYouSure');?></strong><br /><br />
<input name="Yes" type="button" value="<?php echo getTxt('Yes');?>" id="Yeah"/>&nbsp;
<input name="No" type="button" value="<?php echo getTxt('No');?>" id="No"/></center></div></div>
</body>
</html>
<?php HTML_Render_Body_End(); ?>
<script>

//When a Site selection from the drop down menu is made, a query is used to fill in the form.
$("#SiteID").change(function findSite(){

	if($(this).val()=="-1")
	{
		//Reset the form
		$("#editsite").hide(500);
		$("#editsite")[0].reset();
		return;	
	}
		
	marker=null;

	  var siteid=$("#SiteID").val();
 	  $("#editsite")[0].reset();
	  $.ajax({
	  dataType:'json',
	  url: base_url+"sites/getSiteJSON?siteid="+siteid}).done(function(data){
		  data=data[0];
		  if(data.SiteID){
			  
				//Add the fields to the form. 
				$("#SiteID2").val(data.SiteID);
				$("#SiteName").val(data.SiteName);
				$("#SiteCode").val(data.SiteCode);
				$("#SiteType").val(data.SiteType);
				$("#Latitude").val(data.Latitude);
				$("#Longitude").val(data.Longitude);
				$("#Elevation").val(data.Elevation_m);
				$("#state").val(data.State);
				//OLD versions saved it as the state name. 
				if($('#state').val()==-1)
				$('#state option').filter(function(index)
				{
				return $(this).text()==data.State;
				})[0].selected = true;
				new_drop_down_list(data.County)
				$("#county").val(data.County);
				$("#Citation").val(data.Citation);
				$("#VerticalDatum").val(data.VerticalDatum);
				$("#LatLongDatumID").val(data.LatLongDatumID);
				$("#com").val(data.Comments);
				
				var sitepic = data.picname;
				
				if(sitepic!=null)
				{
					$("#picture").hide();
					
					var imgURL = base_url.replace("index.php","uploads")+sitepic;
					var imgHTML = "<img src='"+imgURL+"' class='img-responsive' alt='Site image'>";
					$("#sitepic").html(imgHTML+"<br><div id='sitepicchange'><a href='#'>" + <?php echo "'".getTxt('ClickChangePicture')."'"; ?> + "</a></div>");		
				}
				else
				{
					$("#picture").hide();
					   //$("#sitepic").html("No Site Picture Defined.<br><div id='sitepicchange'><a href='#'>Click Here to Add a Site Picture</a></div>");
					   $("#sitepic").html(<?php echo "'".getTxt('NoSitePictureDefined')."'"; ?> + "<div id='sitepicchange' ><a href='#'>" + <?php echo "'".getTxt('ClickAddSitePicture')."'"; ?> + "</a></div>");
					   	
				}
				 $("#sitepicchange").click(function(){
						  $("#picture").show();
					  });
					  
			  setHintHandlers();
			
			//Call the map
			var initialLocation = new google.maps.LatLng($("#Latitude").val(),$("#Longitude").val());
			 var myOptions = {
				zoom: 14,
				center: initialLocation,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				disableDoubleClickZoom : true
			  }
			map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
			google.maps.event.addListenerOnce(map, 'idle', function(){
       		 google.maps.event.trigger(map, 'resize');
       			 map.setCenter(initialLocation);
   			 });
			placeMarker(initialLocation);
			  google.maps.event.addListener(map, 'dblclick', function(event) {
				placeMarker(event.latLng);
			  });
			//Map Loading Complete
			 $("#editsite").show(500);
			return true;
			}else{
				alert(<?php echo "'".getTxt('ErrorDuringRequest')."'"; ?>);
				return false;
			}
		});
});



//When the "Delete" button is clicked, validate the selected ID and submit the request
function deleteSite(){

if(($("#SiteID").val())==-1){
	alert(<?php echo "'".getTxt('SelectSiteDelete')."'"; ?>);
	return false;
}else{
	var site_id = $("#SiteID").val();
		$.ajax({
		dataType:'json',
		url: base_url+"sites/delete/"+site_id+"?ui=1"}).done(function(data){
			if(data.status=="success"){
					window.open(base_url+"sites/change","_self");	
			}else{
				alert(<?php echo "'".getTxt('ProcessingError')."'"; ?>);
				return false;
			}
		}).fail(function(data){alert(<?php echo "'".getTxt('ProcessingError')."'"; ?>);console.log(data);});		
	}
}

//When the "Cancel" button is clicked, clear the fields
function clearEverything(){

	$("#editsite")[0].reset();
	$("#editsite").hide(500);
	$("#SiteID").val("-1");
	$("html, body").animate({ scrollTop: 0 }, "slow");
	
}


	
//When the "Save Edits" button is clicked, validate the fields and then submit the request


$("#editsite").submit(function(){

//Validate all fields
if(($("#SourceID option:selected").val())==-1){
	//alert("Please select a Source. If you do not find it in the list, please visit the 'Add a new source' page");
	alert(<?php echo "'".getTxt('SelectSourceAdd')."'"; ?>);
	return false;
}

if(($("#SiteName").val())==""){
	//alert("Please enter a name for the site.");
	alert(<?php echo "'".getTxt('EnterSiteName')."'";?>);
	return false;
}

if(($("#SiteCode").val())==""){
	//alert("Please enter a code for the site.");
	alert(<?php echo "'".getTxt('EnterSiteCode')."'"; ?>);
	return false;
}

if(($("#SiteType option:selected").val())==-1){
	//alert("Please select a Site Type.");
	alert(<?php echo "'".getTxt('SelectSiteType')."'"; ?>);
	return false;
}	  

if(($("#Latitude").val())==""){
	//alert("Please enter the latitude for the site or select a point from the map");
	alert(<?php echo "'".getTxt('EnterLatitude')."'"; ?>);
	return false;
}

if(($("#Longitude").val())==""){
	//alert("Please enter the longitude for the site or select a point from the map");
	alert(<?php echo "'".getTxt('EnterLongitude')."'"; ?>);
	return false;
}

if(($("#Elevation").val())==""){
	//alert("Please enter the elevation for the site or select a point from the map");
	alert(<?php echo "'".getTxt('EnterElevation')."'";?>);
	return false;
}

var floatRegex = '[-+]?([0-9]*\.[0-9]+|[0-9]+)';
var myInt = $("#Latitude").val().match(floatRegex);

if(myInt==null){
	//alert("Invalid characters present in Latitude. Please correct it.");
	alert(<?php echo "'".getTxt('InvalidLatitude')."'"; ?>);
    return false;
}

if(myInt[0]!=$("#Latitude").val()){
	//alert("Invalid characters present in latitude. Please correct it.");
	alert(<?php echo "'".getTxt('InvalidLatitude')."'"; ?>);
    return false;
}

myInt = $("#Longitude").val().match(floatRegex);

if(myInt==null)
{
//alert("Invalid characters present in Longitude. Please correct it.");
alert(<?php echo "'".getTxt('InvalidLongitude')."'"; ?>);
      return false;
}

if(myInt[0]!=$("#Longitude").val()){
	//alert("Invalid characters present in Longitude. Please correct it.");
	alert(<?php echo "'".getTxt('InvalidLongitude')."'"; ?>);
    return false;
}

myInt = $("#Elevation").val().match(floatRegex);


if(myInt==null){
	//alert("Invalid characters present in Elevation. Please correct it.");
	alert(<?php echo "'".getTxt('InvalidElevation')."'";?>);
    return false;
}


if(myInt[0]!=$("#Elevation").val()){
	//alert("Invalid characters present in Elevation. Please correct it.");
	alert(<?php echo "'".getTxt('InvalidElevation')."'";?>);
    return false;
}

if(($("#state option:selected").val())==-1){
	alert(<?php echo "'".getTxt('SelectState')."'"; ?>);
	return false;
}

//Validation for the county
if(($("#county option:selected").val())==""){
		alert(<?php echo "'".getTxt('SelectCounty')."'"; ?>);
		return false;
	}

var county = $("#county").val();

if(county==undefined){
	//alert("County is undefined. Please reselect the County.");
	alert(<?php echo "'".getTxt('UndefinedCounty')."'"; ?>);
	return false;
}



if(($("#VerticalDatum option:selected").val())==-1){
	//alert("Please select a Vertical Datum.");
	alert(<?php echo "'".getTxt('SelectVerticalDatum')."'"; ?>);
	return false;
}

if(($("#LatLongDatumID option:selected").val())==-1){
	//alert("Please select a Spatial Reference.");
	alert(<?php echo "'".getTxt('SelectSpatialReference')."'"; ?>);
	return false;
}

//All Validation Checks completed. Now add data to the database.
//Add this to the form. 
		
var selectedItem = $('#SiteID').jqxDropDownList('getSelectedItem');
	$('<input>').attr({
	type: 'hidden',
	id: 'SiteID',
	name: 'SiteID',
	value: $("#SiteID").val()
}).appendTo('#editsite');



return true;
});

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
	elevator = new google.maps.ElevationService();
  // Initiate the location request
  elevator.getElevationForLocations(positionalRequest, function(results, status) {
    if (status == google.maps.ElevationStatus.OK) {

      // Retrieve the first result
      if (results[0]) {

        // Open an info window indicating the elevation at the clicked position
        $("#Elevation").val(parseFloat(results[0].elevation).toFixed(1));
	
        
      } else {
        //alert("No results found");
		alert(<?php echo "'".getTxt('NoResultsFound')."'"; ?>);
      }
    } else {
      //alert("Elevation service failed due to: " + status);
	  alert(<?php echo "'".getTxt('ElevationServiceFailed')."'"; ?>+ " " + status);
    }
  });

	

// Now to update the state
var latlng1 = new google.maps.LatLng(location.lat(), location.lng());
var geocoder = new google.maps.Geocoder();
geocoder.geocode({'latLng': latlng1}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
			
			//$("#locationtext").html("Your selected location according to us is: " + results[0].formatted_address + ". Please select the state and county accordingly.");
			$("#locationtext").html(<?php echo "'".getTxt('SelectedLocationIs')."'"; ?> + " " + results[0].formatted_address + ". "+ <?php echo "'".getTxt('SelectStateCountyAccordingly')."'"; ?>);
			        
          
        }
      } else {
        //alert("Geocoder failed due to: " + status);
		alert(<?php echo "'".getTxt('GeocoderFailed')."'"; ?> + " " + status);
      }
    });

}	
	
</script>

