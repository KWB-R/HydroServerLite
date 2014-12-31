<?php
$attributes = array('class' => 'form-horizontal', 'name' => 'editmethod', 'id' => 'editmethod');
echo form_open('methods/change', $attributes);
genInputT('MethodId','MethodID2','MethodID2',false,'value="'.$Method['MethodID'].'" readonly','MayNotEdit');
//echo '<span class="help-block"><br/>'.getTxt('MayNotEdit').'</span>';
genInputT('MethodName','MethodDescription2','MethodDescription2',true,'value="'.$Method['MethodDescription'].'"','ExMethodName');
//echo '<span class="help-block"><br/>'.getTxt('ExMethodName').'</span>';
genInputT('MethodLinkColon','MethodLink2','MethodLink2',false,'value="'.$Method['MethodLink'].'"','ExMethodLink');
//echo '<span class="help-block"><br/>'.getTxt('ExMethodLink').'</span>';
?>
<div class="col-md-5 col-md-offset-5">
<input type='SUBMIT' name='submit' value='<?php echo getTxt('SaveEdits')?>' class='button' style='width: auto'/>&nbsp;&nbsp;
<input type='button' name='delete' value='<?php echo getTxt('Delete')?>' class='button' style='width: auto' onClick='confirmBox()'/>&nbsp;&nbsp;
<input type='button' name='Reset' value='<?php echo getTxt('Cancel')?>' class='button' style='width: auto' onClick='clearEverything()'/>
</FORM>
</div>