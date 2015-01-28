<?php
HTML_Render_Head($js_vars,getTxt('AddSiteButton'));
echo $CSS_Main;
echo $JS_JQuery;
echo $JS_Forms;
echo $JS_DropDown;
echo $JS_SiteCreate;
?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC3d042tZnUAA8256hCC2Y6QeTSREaxrY0&sensor=true"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/site_maps.js')?>"></script>
<script>
function setDefaults()
{
	//Set Default Datum
	var defaultDatum = "<?php echo $this->config->item('default_datum');?>";
	if(defaultDatum=="")
	defaultDatum="MSL";
	$('#VerticalDatum').val(defaultDatum);
	//Check if it was an invalid datum, if so reset to MSL
	if($('#VerticalDatum').val()==-1)
	$('#VerticalDatum').val('MSL');
	
	//Set Default Spatial Ref
	var defaultDatum = "<?php echo $this->config->item('default_spatial');?>";
	if(defaultDatum=="")
	defaultDatum="WGS84";
	var temp = $('#LatLongDatumID option').filter(function(index)
		{
		return $(this).text()==defaultDatum;
		})
	if(temp.length>0)
	{
		temp[0].selected=true;
	}
	//Check if it was an invalid datum, if so reset to MSL
	if($('#LatLongDatumID').val()==-1)
		$('#LatLongDatumID option').filter(function(index)
		{
		return $(this).text()=="WGS84";
		})[0].selected = true;
}
$( document ).ready(function() {
	
	setDefaults();
  initialize();
});
</script>
<?php 
HTML_Render_Body_Start(); 
genHeading('AddNewSite',true);
$attributes = array('class' => 'form-horizontal', 'name' => 'addsite', 'id' => 'addsite');
echo form_open_multipart('sites/add', $attributes);

genSelect('Source','SourceID','SourceID',$sourceOptions,'SelectEllipsis',true,' onChange="GetSourceName()"');
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
//genInputH('SiteCode','SiteCode', 'SiteCode',getTxt('SiteCodeInfo'), true);
//echo '<span class="em">'.getTxt('ExSiteCode').'</span>';
genSelect('SiteType','SiteType','SiteType',$typeOptions,'SelectEllipsis',true);

echo '<div class="form-group">
	<label class="col-sm-2 control-label">'.getTxt('SitePhoto').'</label>
	<div class="col-sm-10">
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
genInputT('Comments','com','value',false,$extra="",'Optional');

?>  
<div class="col-md-5 col-md-offset-5">
<input type="SUBMIT" name="submit" value="<?php echo getTxt('AddSiteButton');?>" class="button" width="auto"/>
<input id="resetButton" type="button" name="resetButton" value="<?php echo getTxt('Cancel'); ?>" class="button" style="width: auto" />
</FORM>
</div>
</div>
<?php HTML_Render_Body_End(); ?>
<script>
$("#resetButton").click(function() {
	$("#addsite")[0].reset();
	setDefaults();
	 $("html, body").animate({ scrollTop: 0 }, "slow");
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
if(($("#state option:selected").val())=="NULL")
{
	$("#county option:selected").val())=="1234"
}
if(($("#county option:selected").val())=="")
{
//alert("Please select a county.");
alert(<?php echo "'".getTxt('SelectCounty')."'"; ?>);
return false;
}
if(($("#VerticalDatum option:selected").val())==-1)
{
//alert("Please select a vertical datum.");
alert(<?php echo "'".getTxt('SelectVerticalDatum')."'"; ?>);
return false;
}
if(($("#LatLongDatumID option:selected").val())==-1)
{
//alert("Please select a spatial reference.");
alert(<?php echo "'".getTxt('SelectSpatialReference')."'"; ?>);
return false;
}
//All Validation Checks completed.Now add data to the database
return true;
});
</script>