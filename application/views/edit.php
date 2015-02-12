<?php
HTML_Render_Head($js_vars,getTxt('Welcome'));
echo $CSS_Main;
echo $JS_JQuery;
echo $JS_Forms;

HTML_Render_Body_Start(); 
genHeading('Edit',true);
$attributes = array('class' => 'form-horizontal', 'name' => 'welcome', 'id' => 'welcome');
echo form_open('home/edit', $attributes);
genInput('Title','title','title',true);
genInput('Name','groupname','groupname',true);
genInput('Description','description','description',true);
genInput('Citation','citation','citation',true);
?>
<div class="col-md-5 col-md-offset-5">
       <input type="SUBMIT" name="submit" value="<?php echo getTxt('Submit');?>" class="button"/>
       <input type="reset" name="Reset" value="<?php echo getTxt('Cancel'); ?>" class="button" style="width: auto" />
</div>
</FORM>
</div>
</div>
<?php HTML_Render_Body_End(); ?>
<script>
$(document).ready(function(){

($("#title").val('<?php echo addslashes($welcome[0]);?>'));
($("#groupname").val('<?php echo addslashes($welcome[1]);?>'));
($("#description").val('<?php echo addslashes($welcome[2]);?>'));
($("#citation").val('<?php echo addslashes($welcome[3]);?>'));

$("#welcome").submit(function(e){ //NEED TO FIX TO ACCESS ONLY THE MAIN FORM.
if(($("#title").val())==""){
		alert("Please enter a Title");
		return false;
	}
if(($("#groupname").val())==""){
		alert("Please enter your Group Name");
		return false;
	}
if(($("#description").val())==""){
		alert("Please enter a Description");
		return false;
	}

if(($("#citation").val())==""){
		alert("Please enter a Citation");
		return false;
	} 
	
});
return true;
});	
</script>