<?php
$attributes = array('class' => 'form-horizontal', 'name' => 'editmethod', 'id' => 'editmethod');
echo form_open('methods/change', $attributes);
genInput('MethodId','MethodID2','MethodID2',false,'value="'.$Method['MethodID'].'" disabled');
echo '<span class="help-block"><br/>'.getTxt('MayNotEdit').'</span>';
genInput('MethodDescription2','MethodDescription2','MethodDescription2',true,'value="'.$Method['MethodDescription'].'" disabled');
echo '<span class="help-block"><br/>'.getTxt('ExMethodName').'</span>';
genInput('MethodLinkColon','MethodLink2','MethodLink2',false,'value="'.$Method['MethodLink'].'" disabled');
echo '<span class="help-block"><br/>'.getTxt('ExMethodLink').'</span>';
?>
<input type='button' name='submit' value='<?php echo getTxt('SaveEdits')?>' class='button' style='width: auto' onClick='updateMethod()'/>&nbsp;&nbsp;
<input type='button' name='delete' value='<?php echo getTxt('Delete')?>' class='button' style='width: auto' onClick='confirmBox()'/>&nbsp;&nbsp;
<input type='button' name='Reset' value='<?php echo getTxt('Cancel')?>' class='button' style='width: auto' onClick='clearEverything()'/>
</FORM>