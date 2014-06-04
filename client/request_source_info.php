<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
require_once 'authorization_check.php';

$SID = $_GET['SourceID'];

//connect to server and select database
require_once 'database_connection.php';

$option_block_es = "<FORM METHOD='POST' ACTION='' name='editsource' id='editsource'><table width='600' border='0' cellspacing='0' cellpadding='0'>";

//Delete the MethodID # provided
$sql_e ="SELECT * FROM sources WHERE SourceID='$SID'";

$result_e = @mysql_query($sql_e,$connection)or die(mysql_error());

	while ($single_array = mysql_fetch_array($result_e)){
			
   		$SourceID2 = $single_array["SourceID"];
       	$Organization2 = $single_array["Organization"];
		$SourceDescription2 = $single_array["SourceDescription"];
		$SourceLink2 = $single_array["SourceLink"];
		$ContactName2 = $single_array["ContactName"];
		$Phone2 = $single_array["Phone"];
		$Email2 = $single_array["Email"];
		$Address2 = $single_array["Address"];
		$City2 = $single_array["City"];
		$State2 = $single_array["State"];
		$ZipCode2 = $single_array["ZipCode"];
		$Citation2 = $single_array["Citation"];
		$MetadataID2 = $single_array["MetadataID"];
	}

	//Pull the Metadata ID for the dropdown menu	
	$sql_isomd ="SELECT * FROM isometadata WHERE MetadataID='$MetadataID2'";

	$result_isomd = @mysql_query($sql_isomd,$connection)or die(mysql_error());

	while ($row = mysql_fetch_array ($result_isomd)){

       	$MDID = $row["MetadataID"];
		$TopicCategory2 = $row["TopicCategory"];
		$Title2 = $row["Title"];
		$Abstract2 = $row["Abstract"];
		$ProfileVersion2 = $row["ProfileVersion"];
		$MetadataLink2 = $row["MetadataLink"];
	}


		$option_block_es .= "<tr>
          <!--<td width='130' valign='top'><strong>Source ID #:</strong></td>-->
		  <td width='130' valign='top'><strong>$SrcID</strong></td>
           <!--<td valign='top' width='470'><input type='text' name='SourceID2' id='SourceID2' size='5' value='$SourceID2' disabled>&nbsp;<span class='em'>(This may not be edited!)</span></td>-->
		  <td valign='top' width='470'><input type='text' name='SourceID2' id='SourceID2' size='5' value='$SourceID2' disabled>&nbsp;<span class='em'>$MayNotEdit</span></td>
        </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
        </tr>
        <tr>
           <!--<td valign='top'><strong>Organization:</strong></td>-->
		  <td valign='top'><strong>$Organization</strong></td>
           <!--<td valign='top'><input type='text' id='Organization2' name='Organization2' value='$Organization2' size='35' maxlength='100'/>*&nbsp;<span class='em'>(Ex: McCall Outdoor Science School)</span></td>-->
          <td valign='top'><input type='text' id='Organization2' name='Organization2' value='$Organization2' size='35' maxlength='100'/>*&nbsp;<span class='em'>$ExTitle</span></td>
		</tr>
        <tr>
          <td width='108' valign='top'>&nbsp;</td>
          <td width='22' valign='top'>&nbsp;</td>
          </tr>
        <tr>
           <!--<td valign='top'><strong>Description:</strong></td>-->
		  <td valign='top'><strong>$Description</strong></td>
           <!--<td valign='top'><input type='text' id='SourceDescription2' name='SourceDescription2' value='$SourceDescription2' size='35' maxlength='200'/>*&nbsp;<span class='em'>(Ex: The mission of the MOSS is....)</span></td>-->
		  <td valign='top'><input type='text' id='SourceDescription2' name='SourceDescription2' value='$SourceDescription2' size='35' maxlength='200'/>*&nbsp;<span class='em'>$ExDescipt</span></td>
          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td width='22' valign='top'>&nbsp;</td>
          </tr>
        <tr>
           <!--<td valign='top'><strong>Link to Org:</strong></td>-->
		  <td valign='top'><strong>$Link</strong></td>
          <td valign='top'><input type='text' id='SourceLink2' name='SourceLink2' value='$SourceLink2' size='35' maxlength='200'/>
           <!--&nbsp;<span class='em'>(Optional, Ex: http://www.mossidaho.org)</span></td>-->
		  &nbsp;<span class='em'>$ExMetaLink</span></td>
          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
          </tr>
        <tr>
          <td valign='top'><strong>$ContactName</strong></td>
		   <!--<td valign='top'><strong>Contact Name:</strong></td>-->
           <!--<td valign='top'><input type='text' id='ContactName2' name='ContactName2' value='$ContactName2' size='25' maxlength='200'/>*&nbsp;<span class='em'>(Full Name)</span></td>-->
		  <td valign='top'><input type='text' id='ContactName2' name='ContactName2' value='$ContactName2' size='25' maxlength='200'/>*&nbsp;<span class='em'>$ExName</span></td>
          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
          </tr>
        <tr>
           <!--<td valign='top'><strong>Phone:</strong></td>-->
		  <td valign='top'><strong>$Phone</strong></td>
           <!--<td valign='top'><input type='text' id='Phone2' name='Phone2' value='$Phone2' size='12' maxlength='15'/>*&nbsp;<span class='em'>(Ex: XXX-XXX-XXXX)</span></td>-->
		  <td valign='top'><input type='text' id='Phone2' name='Phone2' value='$Phone2' size='12' maxlength='15'/>*&nbsp;<span class='em'>$ExPhone</span></td>
          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
          </tr>
        <tr>
           <!--<td valign='top'><strong>Email:</strong></td>-->
		  <td valign='top'><strong>$Email</strong></td>
           <!--<td valign='top'><input type='text' id='Email2' name='Email2' value='$Email2' size='12' maxlength='50'/>*&nbsp;<span class='em'>(Ex: info@moss.org)</span></td>-->
		  <td valign='top'><input type='text' id='Email2' name='Email2' value='$Email2' size='12' maxlength='50'/>*&nbsp;<span class='em'>$ExEmail</span></td>

          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
          </tr>
        <tr>
           <!--<td valign='top'><strong>Address:</strong></td>-->
		  <td valign='top'><strong>$Address</strong></td>
          <td valign='top'><input type='text' id='Address2' name='Address2' value='$Address2' size='35' maxlength='100'/>*</td>
          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
          </tr>
        <tr>
           <!--<td valign='top'><strong>City:</strong></td>-->
		  <td valign='top'><strong>$City</strong></td>
          <td valign='top'><input type='text' id='City2' name='City2' value='$City2' size='25' maxlength='100'/>*</td>
          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
          </tr>
        <tr>
           <!--<td valign='top'><strong>State:</strong></td>-->
		  <td valign='top'><strong>$State</strong></td>
          <td valign='top'><select name='State2' id='State2'>
            <option value='$State2'>$State2</option>
            <!--<option value='-1'>Select....</option>-->
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
          </select>*</td>
          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
          </tr>
        <tr>
           <!--<td valign='top'><strong>Zip Code:</strong></td>-->
          <td valign='top'><strong>$Zip</strong></td>  
          <td valign='top'><input type='text' id='ZipCode2' name='ZipCode2' value='$ZipCode2' size='5' maxlength='8'/>*</td>
          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
          </tr>
        <tr>
           <!--<td valign='top'><strong>Citation:</strong></td>-->
		  <td valign='top'><strong>$Citation</strong></td>
          <td valign='top'><input type='text' id='Citation2' name='Citation2' value='$Citation2' size='35' maxlength='100'/>
            <!--&nbsp;<span class='em'>(Optional, Ex: Data collected by MOSS scientists and citizen scie...)</span></td>-->
			&nbsp;<span class='em'>$ExCitation</span></td>
          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
        </tr>";

//Start of MetadataID section
	$option_block_es .= "<tr>
           <!--<td valign='top'><strong>Metadata ID #:</strong></td>-->
		  <td valign='top'><strong>$MetadataId</strong></td>
           <!--<td valign='top'><input type='text' name='MetadataID2' id='MetadataID2' size='5' value='$MDID' disabled>&nbsp;<span class='em'>(This may not be edited!)</span></td>-->
		  <td valign='top'><input type='text' name='MetadataID2' id='MetadataID2' size='5' value='$MDID' disabled>&nbsp;<span class='em'>$MayNotEdit</span></td>
        </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
        </tr>		
        <tr>
          <td valign='top'><strong>$TopicCategory</strong></td>";
		  
		// Pull the Topic Category data for the dropdown menu
		$option_block_tc = "<select name='TopicCategory2' id='TopicCategory2'>
			<option value='$TopicCategory2'>$TopicCategory2</option>
			<option value='-1'>$SelectEllipsis</option>";

			$sql_tc ="SELECT * FROM topiccategorycv";

			$result_tc = @mysql_query($sql_tc,$connection)or die(mysql_error());

			while ($row = mysql_fetch_array ($result_tc)){

				$Term = $row["Term"];
		       	$Definition = $row["Definition"];

				$option_block_tc .= "<option value=$Term>$Term</option>";
			}
	
		$option_block_tc .= "</select>*";
		// End of Topic Category dropdown menu


$option_block_es .= "<td valign='top'>$option_block_tc &nbsp;<span class='em'>$SelectUnknown</span></td>
 <!--<td valign='top'>$option_block_tc &nbsp;<span class='em'>(You may select Unknown)</span></td>-->
        </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
          </tr>
        <tr>
          <!--<td valign='top'><strong>Title:</strong></td>-->
	  <td valign='top'><strong>$Title</strong></td>
          <!--<td valign='top'><input type='text' id='Title2' name='Title2' value='$Title2' size='25' maxlength='200'/>*&nbsp;<span class='em'>(Ex: McCall Outdoor Science School)</span></td>-->
	  <td valign='top'><input type='text' id='Title2' name='Title2' value='$Title2' size='25' maxlength='200'/>*&nbsp;<span class='em'>$ExTitle</span></td>
          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
          </tr>
        <tr>
           <!--<td valign='top'><strong>Abstract:</strong></td>-->
		  <td valign='top'><strong>$Abstract</strong></td>
           <!--<td valign='top'><textarea name='Abstract2' cols='50' rows='4' id='Abstract2'>$Abstract2</textarea>*<br><span class='em'>(Ex: MOSS collects water data from Ponderosa State Park...; Or you may enter Unknown)</span></td>-->
		  <td valign='top'><textarea name='Abstract2' cols='50' rows='4' id='Abstract2'>$Abstract2</textarea>*<br><span class='em'>$ExAbstract</span></td>
          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
          </tr>
        <tr>
           <!--<td valign='top'><strong>Profile Version:</strong></td>-->
		  <td valign='top'><strong>$MetaDataProfileVersion</strong></td>
           <!--<td valign='top'><input type='text' id='ProfileVersion2' name='ProfileVersion2' value='$ProfileVersion2' size='12' maxlength='200'/>*&nbsp;<a href='#' onClick='show_answerProf()' border='0'><img src='images/questionmark.png' border='0'></a>&nbsp;<span class='em'>(Ex: ISO8601; Or you may enter Unknown)</span></td>-->
		  <td valign='top'><input type='text' id='ProfileVersion2' name='ProfileVersion2' value='$ProfileVersion2' size='12' maxlength='200'/>*&nbsp;<a href='#' onClick='show_answerProf()' border='0'><img src='images/questionmark.png' border='0'></a>&nbsp;<span class='em'>$ExProfileVersion</span></td>
          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
          </tr>
        <tr>
           <!--<td valign='top'><strong>Metadata Link:</strong></td>-->
		  <td valign='top'><strong>$MetaLink</strong></td>
           <!--<td valign='top'><input type='text' id='MetadataLink2' name='MetadataLink2' value='$MetadataLink2' size='35' maxlength='250'/>&nbsp;<span class='em'>(Optional, Ex: http://www.mossidaho.org)</span></td>-->
		  <td valign='top'><input type='text' id='MetadataLink2' name='MetadataLink2' value='$MetadataLink2' size='35' maxlength='250'/>&nbsp;<span class='em'>$ExMetaLink</span></td>
          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
          </tr>";

//End of MetadataID section

$option_block_es .= "<tr>
           <!--<td colspan='2' valign='top'><input type='submit' name='submit' value='Save Edits' class='button' style='width: 85px' onClick='updateSource()'/>&nbsp;&nbsp;<input type='button' name='delete' value='Delete' class='button' style='width: 55px' onClick='confirmBox()'/>&nbsp;&nbsp;<input type='button' name='Reset' value='Cancel' class='button' style='width: 65px' onClick='clearEverything()'/></td>-->
		  <td colspan='2' valign='top'><input type='submit' name='submit' value='$SaveEdits' class='button' style='width: auto' onClick='updateSource()'/>&nbsp;&nbsp;<input type='button' name='delete' value='$Delete' class='button' style='width: auto' onClick='confirmBox()'/>&nbsp;&nbsp;<input type='button' name='Reset' value='$Cancel' class='button' style='width: auto' onClick='clearEverything()'/></td>
          </tr>
      </table>
	  </FORM>";
	
echo ($option_block_es);

?>