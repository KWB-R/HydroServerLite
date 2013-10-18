<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
require_once 'authorization_check.php';

$SiteID = $_GET['SiteID'];

//connect to server and select database
require_once 'database_connection.php';

//start of the main option block for the table
$option_block_esite = "<FORM METHOD='POST' ACTION='' name='editsite' id='editsite'><table width='600' border='0' cellspacing='0' cellpadding='0'>";

//Pull the data from the appropriate table using the MetadataID # provided
$sql_esite ="SELECT * FROM sites WHERE SiteID='$SiteID'";

$result_esite = @mysql_query($sql_esite,$connection)or die(mysql_error());

	while ($row = mysql_fetch_array($result_esite)){
			
// 		$SiteID = $row["SiteID"];
	//Some of these variables have been changed to not coinside with /_common_text.php as part of internationalization
       	//$SiteCode = $row["SiteCode"];
		$SiteCodeSQL = $row["SiteCode"];
		//$SiteName = $row["SiteName"];
		$SiteNameSQL = $row["SiteName"];
		//$Latitude = $row["Latitude"];
		$LatitudeSQL = $row["Latitude"];
		//$Longitude = $row["Longitude"];
		$LongitudeSQL = $row["Longitude"];
		$LatLongDatumID = $row["LatLongDatumID"];
		//$SiteType = $row["SiteType"];
		$SiteTypeSQL = $row["SiteType"];
		$Elevation_m = $row["Elevation_m"];
		//$VerticalDatum = $row["VerticalDatum"];
		$VerticalDatumSQL = $row["VerticalDatum"];
//		$LocalX = $row["LocalX"];
//		$LocalY = $row["LocalY"];
//		$LocalProjectionID = $row["LocalProjectionID"];
//		$PosAccuracy_m = $row["PosAccuracy_m"];
		//$State = $row["State"];
		$StateSQL = $row["State"];
		//$County = $row["County"];
		$CountySQL = $row["County"];
		//$Comments = $row["Comments"];
		$CommentsSQL = $row["Comments"];
	};

$option_block_esite .= "<tr>
          <!--<td width='93'><strong>Site Name:</strong></td>
          <td width='557'><input type='text' id='SiteName' name='SiteName' value='$SiteName' size='20' maxlength='200' onKeyUp='GetSiteName()'/>*&nbsp;<span class='em'>(Ex: Boulder Creek at Jug Mountain Ranch)</span></td>-->
		  <td width='93'><strong>$SiteName</strong></td>
          <td width='557'><input type='text' id='SiteName' name='SiteName' value='$SiteNameSQL' size='20' maxlength='200' onKeyUp='GetSiteName()'/>*&nbsp;<span class='em'>$ExSiteName</span></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <!--<td><strong>Site Code:</strong></td>
          <td><input type='text' id='SiteCode' name='SiteCode' value='$SiteCode' size=20 maxlength='200'/>*&nbsp;<span class='em'>(You may adjust this if needed)</span></td>-->
		  <td><strong>$SiteCode</strong></td>
          <td><input type='text' id='SiteCode' name='SiteCode' value='$SiteCodeSQL' size=20 maxlength='200'/>*&nbsp;<span class='em'>$ExSiteCode</span></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <!--<td valign='top'><strong>Site Photo:</strong></td>-->
		  <td valign='top'><strong>$SitePhoto</strong></td>
          <td><div id='sitepic'></div><input type='file' name='file' id='file' size='30'>
            <br>
            <!--<span class='em'>(Photo must be in .JPG format; File will be uploaded upon submit below.)</span></td>-->
			<span class='em'>$ExSitePhoto</span></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong>$SiteType</strong></td>";
		 
		  
//Pull the Site Type data for the dropdown menu
	$option_block_st = "<select name='SiteType' id='SiteType' value='$SiteTypeSQL' onChange='TrainingAlert()'>
	<option value='$SiteTypeSQL'>$SiteTypeSQL</option>
	<option value='-1'>$SelectEllipsis</option>";
	

	$sql_st ="SELECT * FROM sitetypecv";

	$result_st = @mysql_query($sql_st,$connection)or die(mysql_error());

	while ($row = mysql_fetch_array ($result_st)){

		$Term = $row["Term"];
       	//$Definition = $row["Definition"];

		$option_block_st .= "<option value=$Term>$Term</option>";
	};

	$option_block_st .= "</select>";

//End of SR dropdown

$option_block_esite .= "<td>$option_block_st*</td></tr><tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
        <table width='600' border='0' cellspacing='0' cellpadding='0'>
        <tr>
          <!--<td colspan='4' valign='top'><strong>To change you may either enter the latitude/longitude manually or simply double click the location on the map. Once the marker is placed on the map, you may then click and drag it to the exact location you desire to adjust the results to be more accurate.</strong></td>-->
		  <td colspan='4' valign='top'><strong>$ToChange</strong></td>

        </tr>
        <tr>
          <td width='100' valign='top'>&nbsp;</td>
          <td width='145' valign='top'>&nbsp;</td>
          <td width='95' valign='top'>&nbsp;</td>
          <td width='260' valign='top'>&nbsp;</td>
          </tr>
        <tr>
         <!--<td width='100' align='right' valign='top'><strong>Latitude:&nbsp;</strong></td>
          <td width='145' valign='top'><input type='text' id='Latitude' name='Latitude' value='$Latitude' size='15' maxlength='20'/>*</td>
          <td width='95' align='right' valign='top'><strong>Longitude:&nbsp;</strong></td>
          <td width='260' valign='top'><input type='text' id='Longitude' name='Longitude' value='$Longitude' size='15' maxlength='20'/>*</td>-->
		  <td width='100' align='right' valign='top'><strong>$Latitude&nbsp;</strong></td>
          <td width='145' valign='top'><input type='text' id='Latitude' name='Latitude' value='$LatitudeSQL' size='15' maxlength='20'/>*</td>
          <td width='95' align='right' valign='top'><strong>$Longitude&nbsp;</strong></td>
          <td width='260' valign='top'><input type='text' id='Longitude' name='Longitude' value='$LongitudeSQL' size='15' maxlength='20'/>*</td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          </tr>
        <tr>
          <td colspan='4' valign='top'><div id='map_canvas' style='width:600px; height:450px'></div></td>
        </tr>
      </table><table width='600' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width='130'>&nbsp;</td>
    <td width='470'>&nbsp;</td>
  </tr>
  <tr>
    <!--<td><strong>Elevation:</strong></td>
    <td><input type='text' id='Elevation' name='Elevation' value='$Elevation_m' size='20' maxlength='20'/>* meter</td>-->
	<td><strong>$Elevation</strong></td>
    <td><input type='text' id='Elevation' name='Elevation' value='$Elevation_m' size='20' maxlength='20'/>* $Meters</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan='2'><div id='locationtext'></div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <!--<td><strong>State:</strong></td>-->
	<td><strong>$State</strong></td>
	<td><select name='state' id='state' onclick='final_drop_down_list()'>
      	<option value='$StateSQL'>$StateSQL</option>
		//<option value='-1'>Select....</option>
		<option value='-1'>$SelectEllipsis</option>
        <option value='AL'>Alabama</option>
        <option value='AK'>Alaska</option>
        <option value='AZ'>Arizona</option>
        <option value='AR'>Arkansas</option>
        <option value='CA'>California</option>
        <option value='CO'>Colorado</option>
        <option value='CT'>Connecticut</option>
        <option value='DE'>Delaware</option>
        <option value='DC'>District of Columbia</option>
        <option value='FL'>Florida</option>
        <option value='GA'>Georgia</option>
        <option value='HI'>Hawaii</option>
        <option value='ID'>Idaho</option>
        <option value='IL'>Illinois</option>
        <option value='IN'>Indiana</option>
        <option value='IA'>Iowa</option>
        <option value='KS'>Kansas</option>
        <option value='KY'>Kentucky</option>
        <option value='LA'>Louisiana</option>
        <option value='ME'>Maine</option>
        <option value='MD'>Maryland</option>
        <option value='MA'>Massachusetts</option>
        <option value='MI'>Michigan</option>
        <option value='MN'>Minnesota</option>
        <option value='MS'>Mississippi</option>
        <option value='MO'>Missouri</option>
        <option value='MT'>Montana</option>
        <option value='NE'>Nebraska</option>
        <option value='NV'>Nevada</option>
        <option value='NH'>New Hampshire</option>
        <option value='NJ'>New Jersey</option>
        <option value='NM'>New Mexico</option>
        <option value='NY'>New York</option>
        <option value='NC'>North Carolina</option>
        <option value='ND'>North Dakota</option>
        <option value='OH'>Ohio</option>
        <option value='OK'>Oklahoma</option>
        <option value='OR'>Oregon</option>
        <option value='PA'>Pennsylvania</option>
        <option value='RI'>Rhode Island</option>
        <option value='SC'>South Carolina</option>
        <option value='SD'>South Dakota</option>
        <option value='TN'>Tennessee</option>
        <option value='TX'>Texas</option>
        <option value='UT'>Utah</option>
        <option value='VT'>Vermont</option>
        <option value='VA'>Virginia</option>
        <option value='WA'>Washington</option>
        <option value='WV'>West Virginia</option>
        <option value='WI'>Wisconsin</option>
        <option value='WY'>Wyoming</option>
		<option value='NULL'>$International</option>
    </select>*&nbsp;<a href='#' onClick='show_answer2()' border='0'><img src='images/questionmark.png' border='0'></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <!--<td><strong>County:</strong></td>-->
	<td><strong>$County</strong></td>
    <!--<td><div id='county_original'><select id='county' name='county' onclick='new_drop_down_list()'><option value='$County'>$County</option><option value=''>County...</option></select>*</div>-->
	<td><div id='county_original'><select id='county' name='county' onclick='new_drop_down_list()'><option value='$CountySQL'>$CountySQL</option><option value=''>$CountyEllipsis</option></select>*</div>
	
	<!--<div id='county_drop_down'><select id='newcounty' name='newcounty'><option value=''>County...</option></select>*</div>-->
	<div id='county_drop_down'><select id='newcounty' name='newcounty'><option value=''>$CountyEllipsis</option></select>*</div>
	 <!--<span id='loading_county_drop_down'><img src='images/loader.gif' width='16' height='16' align='absmiddle'>&nbsp;Select state first...</span>
	 <div id='no_county_drop_down'>This state has no counties.</div></td>-->
	 <span id='loading_county_drop_down'><img src='images/loader.gif' width='16' height='16' align='absmiddle'>&nbsp;$SelectstateElipsis</span>
	 <div id='no_county_drop_down'>$StateNoCounties</div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>$VerticalDatum</strong></td>";
    


//Pull the Vertical Datum data for the dropdown menu
	$option_block_vd = "<select name='VerticalDatum' id='VerticalDatum'>
	<option value='$VerticalDatumSQL'>$VerticalDatumSQL</option>
	<option value='-1'>$SelectEllipsis</option>";



	$sql_vdcv ="SELECT * FROM verticaldatumcv";

	$result_vdcv = @mysql_query($sql_vdcv,$connection)or die(mysql_error());

	while ($row = mysql_fetch_array ($result_vdcv)){

		$Term = $row['Term'];
       	//$Definition = $row['Definition'];

		$option_block_vd .= "<option value='$Term'>$Term</option>";
	};
	
	$option_block_vd .= "</select>";
//End of VD dropdown

$option_block_esite .= "<td>$option_block_vd*</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>$SpatialReferenceColon</strong></td>";

//Pull the Spatial Reference data for current Site
	$sql_lld ="SELECT * FROM spatialreferences WHERE SpatialReferenceID='$LatLongDatumID'";

	$result_lld = @mysql_query($sql_lld,$connection)or die(mysql_error());

	while ($row = mysql_fetch_array ($result_lld)){

		$SpatialReferenceID_orginal = $row['SpatialReferenceID'];
       	$SRSName_orginal = $row['SRSName'];
	};

//Now pull ALL the Spatial Reference data for the dropdown menu
	$option_block_sr = "<select name='LatLongDatumID' id='LatLongDatumID'>
	<option value='$SpatialReferenceID_orginal'>$SRSName_orginal</option>
	<option value='-1'>$SelectEllipsis</option>";
	

	$sql_sr ="SELECT * FROM spatialreferences";

	$result_sr = @mysql_query($sql_sr,$connection)or die(mysql_error());

	while ($row = mysql_fetch_array ($result_sr)){

		$SpatialReferenceID = $row['SpatialReferenceID'];
       	$SRSName = $row['SRSName'];

		$option_block_sr .= "<option value='$SpatialReferenceID'>$SRSName</option>";
	};
	
	$option_block_sr .= "</select>";
//End of SR dropdown

$option_block_esite .= "<td>$option_block_sr*</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <!--<td><strong>Comments:</strong></td>-->
    <td><strong>$Comments</strong></td>
    <td><input type='text' id='com' name='value' size='50' maxlength='500' value='$CommentsSQL'/>
      <!--<span class='em'>&nbsp;(Optional)</span></td>-->
      <span class='em'>&nbsp;$Optional</span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <!--<td colspan='2'><input type='SUBMIT' name='submit' value='Save Edits' class='button' style='width: 85px' onClick='updateSite()'/>&nbsp;&nbsp;<input type='button' name='delete' value='Delete' class='button' style='width: 55px' onClick='confirmBox()'/>&nbsp;&nbsp;<input type='button' name='Reset' value='Cancel' class='button' style='width: 65px' onClick='clearEverything()'/><div id='response'></div></td>-->
	<td colspan='2'><input type='SUBMIT' name='submit' value='$SaveEdits' class='button' style='width: auto' onClick='updateSite()'/>&nbsp;&nbsp;<input type='button' name='delete' value='$Delete' class='button' style='width: auto' onClick='confirmBox()'/>&nbsp;&nbsp;<input type='button' name='Reset' value='$Cancel' class='button' style='width: auto' onClick='clearEverything()'/><div id='response'></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
      </table>
</FORM>";

echo ($option_block_esite);

?>