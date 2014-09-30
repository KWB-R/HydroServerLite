<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
//require_once 'authorization_check.php';

$MID = $_GET['MethodID'];

//All queries go through a translator. 
require_once 'DBTranslator.php';

$option_block_e = "<FORM METHOD='POST' class=\"form-horizontal\" ACTION='' name='editmethod' id='editmethod'>";

//Delete the MethodID # provided

$sql_e ="SELECT * FROM methods WHERE MethodID='$MID'";

$result_e = transQuery($sql_e,0,1);

		foreach ($result_e as $single_array) {	
   		$MethodID = $single_array["MethodID"];
       	$MethodDesc = $single_array["MethodDescription"];
		$MethodLink = $single_array["MethodLink"];

		$option_block_e .= '
		
		 <div class="form-group">
        <label class="col-sm-3 control-label">'.$MethodId.'</label>
        <div class="col-sm-9">
		  <input type="text" class="form-control" name="MethodID2" id="MethodID2" size="5" value="'.$MethodID.'" disabled>
           <span class="help-block"><br/>'.$MayNotEdit.'</span>
		</div>             
      </div>
	  
	   <div class="form-group">
        <label class="col-sm-3 control-label">'.$MethodDescription2.'</label>
        <div class="col-sm-9">
		<input type="text" class="form-control" name="MethodDescription2" id="MethodDescription2" value="'.$MethodDesc.'">
		<span class="required">*</span>
           <span class="help-block"><br/>'.$ExMethodName.'</span>
		</div>             
      </div>	   
	  
	  <div class="form-group">
        <label class="col-sm-3 control-label">'.$MethodLinkColon.'</label>
        <div class="col-sm-9">
		<input type="text" class="form-control" name="MethodLink2" id="MethodLink2" value="'.$MethodLink.'">
           <span class="help-block"><br/>'.$ExMethodLink.'</span>
		</div>             
      </div>';
	}

$option_block_e .= "
<input type='button' name='submit' value='$SaveEdits' class='button' style='width: auto' onClick='updateMethod()'/>&nbsp;&nbsp;<input type='button' name='delete' value='$Delete' class='button' style='width: auto' onClick='confirmBox()'/>&nbsp;&nbsp;<input type='button' name='Reset' value='$Cancel' class='button' style='width: auto' onClick='clearEverything()'/>
 </FORM>";

echo ($option_block_e);

?>