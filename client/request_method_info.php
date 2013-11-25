<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
//require_once 'authorization_check.php';

$MID = $_GET['MethodID'];

//connect to server and select database
require_once 'database_connection.php';

$option_block_e = "<FORM METHOD='POST' ACTION='' name='editmethod' id='editmethod'><table width='632' border='0'>";

//Delete the MethodID # provided

$sql_e ="SELECT * FROM methods WHERE MethodID='$MID'";

$result_e = @mysql_query($sql_e,$connection)or die(mysql_error());

	while ($single_array = mysql_fetch_array($result_e)){
			
   		$MethodID = $single_array["MethodID"];
       	$MethodDesc = $single_array["MethodDescription"];
		$MethodLink = $single_array["MethodLink"];

		$option_block_e .= "<tr>
			<td width='97'>&nbsp;</td>
			<td width='292'>&nbsp;</td>
			<td width='229'>&nbsp;</td>
		</tr>
		<tr>
			<!--<td width='97'><strong>Method ID #:</strong></td>-->
			<td width='97'><strong>$MethodId</strong></td>
			<!--<td colspan='2'><input type='text' name='MethodID2' id='MethodID2' size='5' value='$MethodID' disabled>&nbsp;<span class='em'>(This may not be edited!)</span></td>-->
			<td colspan='2'><input type='text' name='MethodID2' id='MethodID2' size='5' value='$MethodID' disabled>&nbsp;<span class='em'>$MayNotEdit</span></td>
		</tr>
		<tr>
			<td width='97'>&nbsp;</td>
			<td width='292'>&nbsp;</td>
			<td width='229'>&nbsp;</td>
		</tr>
		<tr>
			<!--<td><strong>Method Name:</strong></td>
			<td colspan='2'><label for='MethodDescription2'></label>
      <input type='text' name='MethodDescription2' id='MethodDescription2' value='$MethodDesc'>*&nbsp;<span class='em'>(Ex: YSI DO 200 Meter)</span></td>-->
	  		<td><strong>$MethodName</strong></td>
			<td colspan='2'><label for='MethodDescription2'></label>
      <input type='text' name='MethodDescription2' id='MethodDescription2' value='$MethodDesc'>*&nbsp;<span class='em'>$ExMethodName</span></td>

		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<!--<td><strong>Method Link:</strong></td>
			<td colspan='2'><input type='text' name='MethodLink2' id='MethodLink2' value='$MethodLink'>&nbsp;<span class='em'>(Optional; Ex: http://www.ysi.com/productsdetail.php?DO200-35)</span></td>-->
			<td><strong>$MethodLinkColon</strong></strong></td>
			<td colspan='2'><input type='text' name='MethodLink2' id='MethodLink2' value='$MethodLink'>&nbsp;<span class='em'>$ExMethodLink</span></td>
		</tr>";
	}

$option_block_e .= "<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <!--<td><input type='button' name='submit' value='Save Edits' class='button' style='width: 85px' onClick='updateMethod()'/>&nbsp;&nbsp;<input type='button' name='delete' value='Delete' class='button' style='width: 55px' onClick='confirmBox()'/>&nbsp;&nbsp;<input type='button' name='Reset' value='Cancel' class='button' style='width: 65px' onClick='clearEverything()'/></td>-->
	<td><input type='button' name='submit' value='$SaveEdits' class='button' style='width: auto' onClick='updateMethod()'/>&nbsp;&nbsp;<input type='button' name='delete' value='$Delete' class='button' style='width: auto' onClick='confirmBox()'/>&nbsp;&nbsp;<input type='button' name='Reset' value='$Cancel' class='button' style='width: auto' onClick='clearEverything()'/></td>
    <td>&nbsp;</td>
  </tr>
</table></FORM>";

echo ($option_block_e);

?>