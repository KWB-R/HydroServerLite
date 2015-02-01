<?php
HTML_Render_Head($js_vars,getTxt('AddNewBanner'));
echo $CSS_Main;
echo $JS_JQuery;
echo $JS_Forms;
echo $JS_DropDown;
echo $JS_SiteCreate;

HTML_Render_Body_Start(); 
genHeading('AddNewBanner',true);
$attributes = array('class' => 'form-horizontal', 'name' => 'banner', 'id' => 'banner');
echo form_open_multipart('banner/add', $attributes);

echo '<div class="form-group">
	<label class="col-sm-2 control-label">'.getTxt('banner').'</label>
	<div class="col-sm-10">
	   <input class="form-control" type="file" name="banner" id="banner" size="30">';	   
echo'</div>             
  </div><br>';	
echo getTxt('ExSitePhoto');
//Not altering map structure for now. As its responsive. In case it causes issues, the code can be altered here. 
?>
<div class="col-md-5 col-md-offset-5">
<input type="SUBMIT" name="submit" value="<?php echo getTxt('Submit');?>" class="button" width="auto"/>
<input id="resetButton" type="button" name="resetButton" value="<?php echo getTxt('Cancel'); ?>" class="button" style="width: auto" />
</FORM>
</div>
</div>
<?php HTML_Render_Body_End(); ?>
<script>
$("#resetButton").click(function() {
	$("#banner")[0].reset();
	return false;
});

$("#addsite").submit(function() {
      //Validate all fields
	  
if(($("#SourceID option:selected").val())==-1)
{
//alert("Please select a Source. If you do not find it in the list, please visit the 'Add a new source' page");
alert(<?php echo "'".getTxt('SelectSourceAdd')."'"; ?>);
return false;
}

if(($("#SiteName").val())=="")
{
//alert("Please enter a name for the site.");
alert(<?php echo "'".getTxt('EnterSiteName')."'";?>);
return false;
}

if(($("#SiteCode").val())=="")
{
//alert("Please enter a code for the site.");
alert(<?php echo "'".getTxt('Enter')."'"; ?>);
return false;
}

if(($("#SiteType option:selected").val())==-1)
{
//alert("Please select a Site Type.");
alert(<?php echo "'".getTxt('SelectSiteType')."'"; ?>);
return false;
}	  

if(($("#Latitude").val())=="")
{
//alert("Please enter the latitude for the site or select a point from the map");
alert(<?php echo "'".getTxt('EnterLatitude')."'"; ?>);
return false;
}

if(($("#Longitude").val())=="")
{
//alert("Please enter the longitude for the site or select a point from the map");
alert(<?php echo "'".getTxt('EnterLongitude')."'"; ?>);
return false;
}

if(($("#Elevation").val())=="")
{
//alert("Please enter the elevation for the site or select a point from the map");
alert(<?php echo "'".getTxt('EnterElevation')."'";?>);
return false;
}


var floatRegex = '[-+]?([0-9]*\.[0-9]+|[0-9]+)';
var myInt = $("#Latitude").val().match(floatRegex);


if(myInt==null)
//{alert("Invalid characters present in latitude. Please correct it.");
{alert(<?php echo "'".getTxt('InvalidLatitude')."'"; ?>);
      return false;
}


if(myInt[0]!=$("#Latitude").val())
//{alert("Invalid characters present in latitude. Please correct it.");
{alert(<?php echo "'".getTxt('InvalidLatitude')."'"; ?>);
      return false;
}


myInt = $("#Longitude").val().match(floatRegex);


if(myInt==null)
//{alert("Invalid characters present in longitude. Please correct it.");
{alert(<?php echo "'".getTxt('InvalidLongitude')."'"; ?>);
      return false;
}


if(myInt[0]!=$("#Longitude").val())
//{alert("Invalid characters present in longitude. Please correct it.");
{alert(<?php echo "'".getTxt('InvalidLongitude')."'"; ?>);
      return false;
}

myInt = $("#Elevation").val().match(floatRegex);


if(myInt==null)
//{alert("Invalid characters present in elevation. Please correct it.");
{alert(<?php echo "'".getTxt('InvalidElevation')."'";?>);
      return false;
}


if(myInt[0]!=$("#Elevation").val())
//{alert("Invalid characters present in elevation. Please correct it.");
{alert(<?php echo "'".getTxt('InvalidElevation')."'";?>);
      return false;
}

if(($("#state option:selected").val())==-1)
{
//alert("Please select a state.");
alert(<?php echo "'".getTxt('SelectState')."'"; ?>);
return false;
}

if(($("#county option:selected").val())=="" && (($("#state option:selected").val())!="NULL")){
{
alert(<?php echo "'".getTxt('SelectCounty')."'"; ?>);
return false;
}
if(($("#VerticalDatum option:selected").val())==-1)
{
alert(<?php echo "'".getTxt('SelectVerticalDatum')."'"; ?>);
return false;
}
if(($("#LatLongDatumID option:selected").val())==-1)
{
alert(<?php echo "'".getTxt('SelectSpatialReference')."'"; ?>);
return false;
}
//All Validation Checks completed.Now add data to the database
return true;
});
</script>