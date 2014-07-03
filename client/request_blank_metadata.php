<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
require_once 'authorization_check.php';

//All queries go through a translator. 
require_once 'DBTranslator.php';

//get list of TopicCategories to choose from
$sql2 ="Select Term FROM topiccategorycv";

$result2 = transQuery($sql2,0,1);
	if (count($result2) < 1) {

    $msg_tc = "<P><em2>$NoData</em></p>";

	} else {

	foreach ($result2 as $row2){

		$metaTerm = $row2["Term"];
		
		$option_block_tc .= "<option value=$metaTerm>$metaTerm</option>";

		}
	}

//start of the main option block for the table
$option_block_blankmdata = "<table width='600' border='0' cellspacing='0' cellpadding='0'>
		<tr>
         <!--<td valign='top' width='130'><strong>Topic Category:</strong></td>-->
		  <td valign='top' width='130'><strong>$TopicCategory</strong></td>

          <td valign='top' width='470'><select name='TopicCategory' id='TopicCategory'>
            <!--<option value='-1'>Select....</option>-->
			<option value='-1'>$SelectEllipsis</option>
            <?php echo '$option_block_tc'; ?>
          </select>*&nbsp;<?php echo '$msg_tc'; ?></td>
          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
        </tr>
        <tr>
          <!--<td valign='top'><strong>Title:</strong></td>
          <td valign='top'><input type='text' id='Title' name='Title' size='35' maxlength='100'/>*&nbsp;<span class='em'>(Ex: Twin Falls High School)</span></td>-->
		  <td valign='top'><strong>$Title</strong></td>
          <td valign='top'><input type='text' id='Title' name='Title' size='35' maxlength='100'/>*&nbsp;<span class='em'>$ExTitle2</span></td>

          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
        </tr>
        <tr>
          <!--<td valign='top'><strong>Abstract:</strong></td>
          <td valign='top'><input type='text' id='Abstract' name='Abstract' size='35' maxlength='250'/>
          &nbsp;<span class='em'>(Optional, Ex: High school students/citizen scientists collecting...)</span></td>-->
          <td valign='top'><strong>$Abstract</strong></td>
          <td valign='top'><input type='text' id='Abstract' name='Abstract' size='35' maxlength='250'/>
          &nbsp;<span class='em'>$ExAbstract1</span></td>
          </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
        </tr>
        <tr>
          <!--<td valign='top'><strong>Metadata Link:</strong></td>
          <td valign='top'><input type='text' id='MetadataLink' name='MetadataLink' size='35' maxlength='250'/>&nbsp;<span class='em'>(Optional)</span></td>-->
          <td valign='top'><strong>$MetaLink</strong></td>
          <td valign='top'><input type='text' id='MetadataLink' name='MetadataLink' size='35' maxlength='250'/>&nbsp;<span class='em'>$Optional</span></td>
		  </tr>
        <tr>
          <td valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
        </tr>
		</table>";
		  
echo ($option_block_blankmdata);

?>