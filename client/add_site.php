<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';
//check authority to be here
require_once 'authorization_check.php';
//All queries go through a translator. 
require_once 'DBTranslator.php';

	$option_block = "";
	$option_block2 = "";
	$option_block3 = "";
	$option_block4 = "";
//add the SourceID's options
$sql ="Select * FROM sources";

$result = transQuery($sql,0,0);
$num = count($result);
	if ($num < 1) {
	$msg = "<P><em2> $NoSourceIDNames </em></p>";
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


require_once "_html_parts.php";
	
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
<br /><?php //echo "$msg"; ?><p class="em" align="right"><?php echo $RequiredFieldsAsterisk;?></p>
      <h1><?php echo $AddNewSite;?></h1>      
      <p>&nbsp;</p><FORM METHOD="POST" ACTION="" name="addsite" id="addsite">
      <table width="650" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="93"><strong><!--Source:--><?php echo $Source;?></strong></td>
          <td width="557"><select name="SourceID" id="SourceID" onChange="GetSourceName()">
            <option value="-1"><!--Select....--><?php echo $SelectEllipsis;?></option>
            <?php echo "$option_block"; ?>
          </select></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong><!--Site Name:--><?php echo $SiteName;?></strong></td>
          <td><input type="text" id="SiteName" name="SiteName" size=20 maxlength="200" onKeyUp="GetSiteName()"/>*&nbsp;<span class="em"><!--(Ex: Boulder Creek at Jug Mountain Ranch)--><?php echo $ExSiteName." ".$NoApostrophe;?></span></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong><!--Site Code:--><?php echo $SiteCode;?></strong></td>
          <td><input type="text" id="SiteCode" name="SiteCode" size=20 maxlength="200"/>*&nbsp;<a href="#" onClick="show_answerSC()" border="0"><img src="images/questionmark.png" border="0"></a>&nbsp;<span class="em"><!--(You may adjust this if needed)--><?php echo $ExSiteCode;?></span></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong><!--Site Type:--><?php echo $SiteType;?></strong></td>
          <td><select name="SiteType" id="SiteType" onChange="TrainingAlert()">
            <option value="-1"><!--Select....--><?php echo $SelectEllipsis;?></option>
            <?php echo "$option_block2"; ?>
            </select>*</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Site Photo:--><?php echo $SitePhoto;?></strong></td>
          <td><input type="file" name="file" id="file" size="30">
            <br>
            <!--(Photo must be in .JPG format; File will be uploaded upon submit below.)--><?php echo $ExSitePhoto;?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
        <table width="650" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" valign="top"><strong><!--You may either enter the latitude/longitude/elevation manually or simply double click the location on the map. Once the marker is placed on the map, you may then click and drag it to the exact location you desire to adjust the results to be more accurate.--><?php echo $MapLatLongEle;?></strong></td>
        </tr>
        <tr>
          <td width="100" valign="top">&nbsp;</td>
          <td width="155" valign="top">&nbsp;</td>
          <td width="86" valign="top">&nbsp;</td>
          <td width="309" valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td width="100" align="right" valign="top"><strong><!--Latitude:--><?php echo $Latitude;?>&nbsp;</strong></td>
          <td width="155" valign="top"><input type="text" id="Latitude" name="Latitude" size=20 maxlength=20/>*</td>
          <td width="86" align="right" valign="top"><strong><!--Longitude:--><?php echo $Longitude;?>&nbsp;</strong></td>
          <td width="309" valign="top"><input type="text" id="Longitude" name="Longitude" size=20 maxlength=20/>*</td>
          </tr>
        <tr>
          <td width="100" valign="top">&nbsp;</td>
          <td width="155" valign="top">&nbsp;</td>
          <td width="86" valign="top">&nbsp;</td>
          <td width="309" valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="4" valign="top"><div id="map_canvas" style="width:650px; height:450px"></div></td>
        </tr>
      </table><table width="650" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="130">&nbsp;</td>
    <td width="520">&nbsp;</td>
  </tr>
  <tr>
    <td><strong><!--Elevation:--><?php echo $Elevation;?></strong></td>
    <td><input type="text" id="Elevation" name="Elevation" size=20 maxlength=20/>
    <!--* Meters-->* <?php echo $Meters;?>&nbsp;<a href="#" onClick="show_answerE()" border="0"><img src="images/questionmark.png" border="0"></a></td>
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
    <td><strong><!--State:--><?php echo $State;?></strong></td>
    <td><select name="state" id="state">
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
    </select>*&nbsp;<a href="#" onClick="show_answerState()" border="0"><img src="images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong><!--County:--><?php echo $County;?></strong></td>
    <td><div id="county_drop_down"><select id="county" name="county"><option value=""><!--County...--><?php echo $CountyEllipsis;?></option></select>*</div>
	 <span id="loading_county_drop_down"><img src="images/loader.gif" width="16" height="16" align="absmiddle">&nbsp;<!--Select state first...--><?php echo $SelectstateElipsis;?></span>
	 <div id="no_county_drop_down"><!--This state has no counties.--><?php echo $StateNoCounties;?></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong><!--Vertical Datum:--><?php echo $VerticalDatum;?></strong></td>
    <td><select name="VerticalDatum" id="VerticalDatum">
      <option value="-1"><!--Select....--><?php echo $SelectEllipsis;?></option>
      <?php echo "$option_block3"; ?>
    </select>*&nbsp;<a href="#" onClick="show_answerVD()" border="0"><img src="images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong><!--Spatial Reference:--><?php echo $SpatialReferenceColon;?></strong></td>
    <td><select name="LatLongDatumID" id="LatLongDatumID">
      <option value="-1"><!--Select....--><?php echo $SelectEllipsis;?></option>
      <?php echo "$option_block4"; ?>
    </select>*&nbsp;<a href="#" onClick="show_answerSR()" border="0"><img src="images/questionmark.png" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong><!--Comments:--><?php echo $Comments;?></strong></td>
    <td><input type="text" id="com" name="value" size=50 maxlength=500/>
      <span class="em">&nbsp;<!--(Optional)--><?php echo $Optional;?></span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <!--<td><input type="SUBMIT" name="submit" value="Add Site" class="button"/></td>-->
    <td><input type="SUBMIT" name="submit" value="<?php echo $AddSiteButton;?>" class="button" width="auto"/></td>
    <td><div id='response'>
      <input type="reset" name="Reset" value="<?php echo $Cancel; ?>" class="button" style="width: auto" />
    </div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
      </table>
</FORM>
     
    </blockquote>
    <p></p></td>
  </tr>
  <tr>
    <script src="js/footer.js"></script>
  </tr>
</table>

<script>

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
