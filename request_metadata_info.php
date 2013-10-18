<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
//require_once 'authorization_check.php';

$MetaID = $_GET['MetadataID'];

//connect to server and select database
require_once 'database_connection.php';

//start of the main option block for the table
$option_block_emdata = "<FORM METHOD='POST' ACTION='' name='edit_md' id='edit_md'><table width='600' border='0' cellspacing='0' cellpadding='0'>";

//Pull the data from the appropriate table using the MetadataID # provided
$sql_emd ="SELECT * FROM isometadata WHERE MetadataID='$MetaID'";

$result_emd = @mysql_query($sql_emd,$connection)or die(mysql_error());

	while ($single_array = mysql_fetch_array($result_emd)){
			
   		$MetadataID2 = $single_array["MetadataID"];
       	$TopicCategory2 = $single_array["TopicCategory"];
		$Title2 = $single_array["Title"];
		$Abstract2 = $single_array["Abstract"];
		$ProfileVersion2 = $single_array["ProfileVersion"];
		$MetadataLink2 = $single_array["MetadataLink"];
	}

$option_block_emdata .= "<tr>
          <!--<td width='108' valign='top'><strong>Metadata ID #:</strong></td>-->
          <td width='108' valign='top'><strong>$MetadataId</strong></td>
          <!--<td valign='top' width='492'><input type='text' name='MetadataID2' id='MetadataID2' size='5' value='$MetadataID2' disabled>&nbsp;<span class='em'>(You may not edit this.)</span></td>-->
		  <td valign='top' width='492'><input type='text' name='MetadataID2' id='MetadataID2' size='5' value='$MetadataID2' disabled>&nbsp;<span class='em'>$NoEdit</span></td>
        </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
        </tr>		
        <tr>
          <td valign='top'><strong>$TopicCategory</strong></td>";
		 
		  
//Pull the Topic Category data for the dropdown menu
$option_block_tc = "<select name='TopicCategory2' id='TopicCategory2'>
	<option value='$TopicCategory2'>$TopicCategory2</option>
	<option value='-1'>$SelectEllipsis</option>";
	

$sql_tc ="SELECT * FROM topiccategorycv";

$result_tc = @mysql_query($sql_tc,$connection)or die(mysql_error());

	while ($row = mysql_fetch_array ($result_tc)){

		$Term = $row["Term"];
       	$Definition = $row["Definition"];

		$option_block_tc .= "<option value=$Term>$Term</option>";
	};
	
$option_block_tc .= "</select>*";

$option_block_emdata .= "<td valign='top'>$option_block_tc &nbsp;<span class='em'>$SelectUnknown</span></td>
		<!--<td valign='top'>$option_block_tc &nbsp;<span class='em'>(You may select Unknown)</span></td>-->
        </tr>
        <tr>
          <td width='108' valign='top'>&nbsp;</td>
          <td width='22' valign='top'>&nbsp;</td>
          </tr>
        <tr>
		  <!--<td valign='top'><strong>Title:</strong></td>-->
		  <td valign='top'><strong>$Title</strong></td>
          <!--<td valign='top'><input type='text' id='Title2' name='Title2' value='$Title2' size='25' maxlength='200'/>*&nbsp;<span class='em'>(Ex: McCall Outdoor Science School)</span></td>-->
		  <td valign='top'><input type='text' id='Title2' name='Title2' value='$Title2' size='25' maxlength='200'/>*&nbsp;<span class='em'>$ExTitle1</span></td>
          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td width='22' valign='top'>&nbsp;</td>
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
          </span></td>
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
          </tr>
        <tr>
          <!--<td colspan='2' valign='top'><input type='button' name='submit' value='Save Edits' class='button' style='width: 85px' onClick='updateMD()'/>&nbsp;&nbsp;<input type='button' name='delete' value='Delete' class='button' style='width: 55px' onClick='confirmBox()'/>&nbsp;&nbsp;<input type='button' name='Reset' value='Cancel' class='button' style='width: 65px' onClick='clearEverything()'/></td>-->
		  <td colspan='2' valign='top'><input type='button' name='submit' value='$SaveEdits' class='button' style='width: auto' onClick='updateMD()'/>&nbsp;&nbsp;<input type='button' name='delete' value='$Delete' class='button' style='width: auto' onClick='confirmBox()'/>&nbsp;&nbsp;<input type='button' name='Reset' value='$Cancel' class='button' style='width: auto' onClick='clearEverything()'/></td>
          </tr>
      </table></FORM>";

echo ($option_block_emdata);

?>