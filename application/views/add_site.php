<?php
/*
	$option_block = "";
	$option_block2 = "";
	$option_block3 = "";
	$option_block4 = "";
//add the SourceID's options
$sql ="Select * FROM sources";

$result = transQuery($sql,0,0);
$num = count($result);
	if ($num < 1) {
	$msg = "<P><em2>".getTxt('NoSourceIDNames')."</em></p>";
	} else {
	foreach ($result as $row) {
		$sourceid = $row["SourceID"];
		$sourcename = $row["Organization"];
if ($sourcename==$default_source)
{
	$option_block .= "<option selected='selected' value=$sourceid>$sourcename</option>";
}
else
{
		$option_block .= "<option value=$sourceid>$sourcename</option>";
}}}

//add the SiteType options
$sql2 ="Select Term FROM sitetypecv";
$result2 = transQuery($sql2,0,1);

	if (count($result2) < 1) {
	$msg = "<P><em2>$NoSiteTypes</em></p>";
	} else {
	foreach ($result2 as $row2) {
		$sitetype = $row2["Term"];
		$option_block2 .= "<option value=$sitetype>$sitetype</option>";
		}
	}

//add the VerticalDatum options
$sql3 ="Select Term FROM verticaldatumcv";
$result3 = transQuery($sql3,0,1);
	//MSL default selection
	if ((!isset($default_datum))){
	$default_datum = "MSL";
	}
	else {
	if (empty($default_datum) || $default_datum==""){
	
	$default_datum = "MSL";
	}
	}
	
	if (count($result3) < 1) {
	$msg = "<P><em2>$NoVerticalDatums</em></p>";
	} else {
	foreach ($result3 as $row3) {
		$vd = $row3["Term"];
if ($vd==$default_datum)
{
	$option_block3 .= "<option selected='selected' value=$vd>$vd</option>";
}
else
{
		$option_block3 .= "<option value=$vd>$vd</option>";
}
		}
	}

//add the LatLongDatumID options
$sql4 ="Select SpatialReferenceID,SRSName FROM spatialreferences";
$result4 = transQuery($sql4,0,1);
	//WGS84 Default Selection
	if ((!isset($default_spatial))){
	$default_spatial = "WGS84";
	}
	else{
	if (empty($default_spatial) || $default_spatial==""){
	$default_spatial = "WGS84";
	}
	}
	if (count($result4) < 1) {
	$msg = "<P><em2>$NoVerticalDatums</em></p>";
	} else {
	foreach ($result4 as $row4) {

		$srid = $row4["SpatialReferenceID"];
		$srsname = $row4["SRSName"];
		
		
		if ($srsname==$default_spatial)
		{
			$option_block4 .= "<option selected='selected' value=$srid>$srsname</option>";
		}
		else
		{
			$option_block4 .= "<option value=$srid>$srsname</option>";
		}
	}
	}

*/

	HTML_Render_Head();

	echo $CSS_Main;
	
	echo $JS_JQuery;

	echo $JS_Forms;
	
	echo $JS_DropDown;
?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC3d042tZnUAA8256hCC2Y6QeTSREaxrY0&sensor=true"></script>
<script type="text/javascript" src="js/site_maps.js"></script>
<script>
$( document ).ready(function() {
  initialize();
});
</script>
<?php 
	echo $JS_SiteCreate;
HTML_Render_Body_Start(); ?>
<div class='col-md-9'>
<br /><?php //echo "$msg"; ?><p class="em" align="right"><?php echo getTxt('RequiredFieldsAsterisk');?></p>
      <h1><?php echo getTxt('AddNewSite');?></h1>      
      <p>&nbsp;</p><FORM class="form-horizontal" METHOD="POST" ACTION="" name="addsite" id="addsite">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><strong><?php echo getTxt('Source');?></strong></td>
          <td><select class="form-control" name="SourceID" id="SourceID" onChange="GetSourceName()">
            <option value="-1"><?php echo getTxt('SelectEllipsis;')?></option>
            <?php echo "$option_block"; ?>
          </select></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong><?php echo getTxt('SiteName');?></strong></td>
          <td><input class="form-control"type="text" id="SiteName" name="SiteName" size=20 maxlength="200" onKeyUp="GetSiteName()"/>*&nbsp;<span class="em"><?php echo getTxt('ExSiteName')." ".getTxt('NoApostrophe');?></span></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong><?php echo getTxt('SiteCode');?></strong></td>
          <td><input class="form-control" type="text" id="SiteCode" name="SiteCode" size=20 maxlength="200"/>*&nbsp;<a href="#" onClick="show_answerSC()" border="0"><img src="<?php echo getImg('questionmark.png'); ?>" border="0"></a>&nbsp;<span class="em"><?php echo getTxt('ExSiteCode');?></span></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong><?php echo getTxt('SiteType');?></strong></td>
          <td><select class="form-control" name="SiteType" id="SiteType" onChange="TrainingAlert()">
            <option value="-1"><?php echo getTxt('SelectEllipsis');?></option>
            <?php echo "$option_block2"; ?>
            </select>*</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><?php echo getTxt('SitePhoto');?></strong></td>
          <td><input class="form-control" type="file" name="file" id="file" size="30">
            <br>
            <?php echo getTxt('ExSitePhoto');?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
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
      </table><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="130">&nbsp;</td>
    <td width="520">&nbsp;</td>
  </tr>
  <tr>
    <td><strong><?php echo getTxt('Elevation');?></strong></td>
    <td><input class="form-control" type="text" id="Elevation" name="Elevation" size=20 maxlength=20/>
    * <?php echo getTxt('Meters');?>&nbsp;<a href="#" onClick="show_answerE()" border="0"><img src="<?php echo getImg('questionmark.png'); ?>" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><div id="locationtext"></div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong><?php echo getTxt('State');?></strong></td>
    <td><select class="form-control" name="state" id="state">
      <option value="-1"><?php echo getTxt('SelectEllipsis');?></option>
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
		<option value="NULL"><?php echo getTxt('International');?></option>
    </select>*&nbsp;<a href="#" onClick="show_answerState()" border="0"><img src="<?php echo getImg('questionmark.png'); ?>" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong><?php echo getTxt('County');?></strong></td>
    <td><div id="county_drop_down"><select class="form-control" id="county" name="county"><option value=""><?php echo getTxt('CountyEllipsis');?></option></select>*</div>
	 <span id="loading_county_drop_down"><img src="<?php echo getImg('loader.gif'); ?>" width="16" height="16" align="absmiddle">&nbsp;<?php echo getTxt('SelectstateElipsis');?></span>
	 <div id="no_county_drop_down"><?php echo getTxt('StateNoCounties');?></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong><?php echo getTxt('VerticalDatum');?></strong></td>
    <td><select class="form-control" name="VerticalDatum" id="VerticalDatum">
      <option value="-1"><?php echo getTxt('SelectEllipsis');?></option>
      <?php echo "$option_block3"; ?>
    </select>*&nbsp;<a href="#" onClick="show_answerVD()" border="0"><img src="<?php echo getImg('questionmark.png');?>" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong><?php echo getTxt('SpatialReferenceColon');?></strong></td>
    <td><select class="form-control" name="LatLongDatumID" id="LatLongDatumID">
      <option value="-1"><?php echo getTxt('SelectEllipsis');?></option>
      <?php echo "$option_block4"; ?>
    </select>*&nbsp;<a href="#" onClick="show_answerSR()" border="0"><img src="<?php echo getImg('questionmark.png');?>" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong><?php echo getTxt('Comments');?></strong></td>
    <td><input class="form-control" type="text" id="com" name="value" size=50 maxlength=500/>
      <span class="em">&nbsp;<?php echo getTxt('Optional');?></span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    
    <td><input type="SUBMIT" name="submit" value="<?php echo getTxt('AddSiteButton');?>" class="button" width="auto"/></td>
    <td><div id='response'>
      <input id="resetButton" type="button" name="resetButton" value="<?php echo getTxt('Cancel'); ?>" class="button" style="width: auto" />
    </div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
      </table>
</FORM>
     
 </div>
   	<?php HTML_Render_Body_End(); ?>

<script>


$("#resetButton").click(function() {
	$("form")[0].reset();
	 $("html, body").animate({ scrollTop: 0 }, "slow");
  return false;
});

    $("form").submit(function() {
      //Validate all fields
	  
if(($("#SourceID option:selected").val())==-1)
{
//alert("Please select a Source. If you do not find it in the list, please visit the 'Add a new source' page");
alert(<?php echo "'".$SelectSourceAdd."'"; ?>);
return false;
}

if(($("#SiteName").val())=="")
{
//alert("Please enter a name for the site.");
alert(<?php echo "'".$EnterSiteName."'"?>);
return false;
}

if(($("#SiteCode").val())=="")
{
//alert("Please enter a code for the site.");
alert(<?php echo "'".$Enter."'"; ?>);
return false;
}

if(($("#SiteType option:selected").val())==-1)
{
//alert("Please select a Site Type.");
alert(<?php echo "'".$SelectSiteType."'"; ?>);
return false;
}	  

if(($("#Latitude").val())=="")
{
//alert("Please enter the latitude for the site or select a point from the map");
alert(<?php echo "'".$EnterLatitude."'"; ?>);
return false;
}

if(($("#Longitude").val())=="")
{
//alert("Please enter the longitude for the site or select a point from the map");
alert(<?php echo "'".$EnterLongitude."'"; ?>);
return false;
}

if(($("#Elevation").val())=="")
{
//alert("Please enter the elevation for the site or select a point from the map");
alert(<?php echo "'".$EnterElevation."'"?>);
return false;
}


var floatRegex = '[-+]?([0-9]*\.[0-9]+|[0-9]+)';
var myInt = $("#Latitude").val().match(floatRegex);


if(myInt==null)
//{alert("Invalid characters present in latitude. Please correct it.");
{alert(<?php echo "'".$InvalidLatitude."'"; ?>);
      return false;
}


if(myInt[0]!=$("#Latitude").val())
//{alert("Invalid characters present in latitude. Please correct it.");
{alert(<?php echo "'".$InvalidLatitude."'"; ?>);
      return false;
}


myInt = $("#Longitude").val().match(floatRegex);


if(myInt==null)
//{alert("Invalid characters present in longitude. Please correct it.");
{alert(<?php echo "'".$InvalidLongitude."'"; ?>);
      return false;
}


if(myInt[0]!=$("#Longitude").val())
//{alert("Invalid characters present in longitude. Please correct it.");
{alert(<?php echo "'".$InvalidLongitude."'"; ?>);
      return false;
}

myInt = $("#Elevation").val().match(floatRegex);


if(myInt==null)
//{alert("Invalid characters present in elevation. Please correct it.");
{alert(<?php echo "'".$InvalidElevation."'"?>);
      return false;
}


if(myInt[0]!=$("#Elevation").val())
//{alert("Invalid characters present in elevation. Please correct it.");
{alert(<?php echo "'".$InvalidElevation."'"?>);
      return false;
}

if(($("#state option:selected").val())==-1)
{
//alert("Please select a state.");
alert(<?php echo "'".$SelectState."'"; ?>);
return false;
}
if(($("#VerticalDatum option:selected").val())==-1)
{
//alert("Please select a vertical datum.");
alert(<?php echo "'".$SelectVerticalDatum."'"; ?>);
return false;
}
if(($("#LatLongDatumID option:selected").val())==-1)
{
//alert("Please select a spatial reference.");
alert(<?php echo "'".$SelectSpatialReference."'"; ?>);
return false;
}

//All Validation Checks completed.Now add data to the database

$.ajax({
  type: "POST",
  url: "do_add_site.php?sc="+$("#SiteCode").val()+"&sn="+$("#SiteName").val()+"&lat="+$("#Latitude").val()+"&lng="+$("#Longitude").val()+"&llid="+$("#LatLongDatumID option:selected").val()+"&type="+$("#SiteType option:selected").text()+"&elev="+$("#Elevation").val()+"&datum="+$("#VerticalDatum option:selected").text()+"&state="+$("#state option:selected").text()+"&county="+$("#county option:selected").text()+"&com="+$("#com").val()+"&source="+$("#SourceID").val()
}).done(function( msg ) {
  if(msg==1)
  {

formdata = new FormData();	
document.getElementById("response").innerHTML = "Uploading . . ." 
	//Upload the image
var input = document.getElementById("file");
var file = input.files[0];
if (file!=null)
{


formdata.append("images[]", file);

	$.ajax({
		url: "do_add_site2.php",
		type: "POST",
		data: formdata,
		processData: false,
		contentType: false,
		success: function (res) {
			
			if(res==1)
			{
//alert("Site successfully added");
alert(<?php echo "'".$SiteAddedSuccessfully."'"; ?>);
window.location.href = "add_site.php";
	  return true;
			}
			else
			{document.getElementById("response").innerHTML = "" 
				alert(res);
				return false;}
			
			
		}
	});

}
else
{

alert(<?php echo "'".$SiteAddedSuccessfully."'"; ?>);
window.location.href = "add_site.php";
	  return true;
	
}
  }
  else
  {
  //alert("Error in database configuration");
  alert(<?php echo "'".$DatabaseConfigurationError."'"; ?> + msg);
  return false;
  }
  
});


      return false;
    });
</script>


</body>
</html>
