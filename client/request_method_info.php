<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
//require_once 'authorization_check.php';

$MID = $_GET['MethodID'];

//All queries go through a translator. 
require_once 'DBTranslator.php';

$option_block_e = "<FORM METHOD='POST' ACTION='' name='editmethod' id='editmethod'><table width='632' border='0'>";

//Delete the MethodID # provided

$sql_e ="SELECT * FROM methods WHERE MethodID='$MID'";

$result_e = transQuery($sql_e,0,1);

		foreach ($result_e as $single_array) {	
   		$MethodID = $single_array["MethodID"];
       	$MethodDesc = $single_array["MethodDescription"];
		$MethodLink = $single_array["MethodLink"];

		$option_block_e .= "<tr>
			<td width='97'>&nbsp;</td>
			<td width='292'>&nbsp;</td>
			<td width='229'>&nbsp;</td>
		</tr>
		<tr>
			<td width='97'><strong>$MethodId</strong></td>
			<td colspan='2'><input type='text' name='MethodID2' id='MethodID2' size='5' value='$MethodID' disabled>&nbsp;<span class='em'>$MayNotEdit</span></td>
		</tr>
		<tr>
			<td width='97'>&nbsp;</td>
			<td width='292'>&nbsp;</td>
			<td width='229'>&nbsp;</td>
		</tr>
		<tr>
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
	<td><input type='button' name='submit' value='$SaveEdits' class='button' style='width: auto' onClick='updateMethod()'/>&nbsp;&nbsp;<input type='button' name='delete' value='$Delete' class='button' style='width: auto' onClick='confirmBox()'/>&nbsp;&nbsp;<input type='button' name='Reset' value='$Cancel' class='button' style='width: auto' onClick='clearEverything()'/></td>
    <td>&nbsp;</td>
  </tr>
</table></FORM>";

echo ($option_block_e);

?>