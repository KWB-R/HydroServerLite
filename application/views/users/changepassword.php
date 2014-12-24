<?php
HTML_Render_Head($js_vars);
echo $CSS_Main;
echo $JS_JQuery;
HTML_Render_Body_Start(); ?>
<div class='col-md-9'>
<?php showMsgs();?>
<p class="em" align="right"><?php echo getTxt('RequiredFieldsAsterisk');?></p>
      <h1><?php echo getTxt('ChangeUserPassword');?></h1>
      <p>&nbsp;</p>
<?php
$attributes = array('class' => 'form-horizontal');
echo form_open('user/changepass', $attributes);
?>
	  <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo getTxt('UserName'); ?></label>
        <div class="col-sm-9">
        <select name="username" class="form-control" id="username"><option value=""><?php echo getTxt('SelectUsernameEllipisis'); ?></option><?php echo "$option_block"; ?></select><span class="required">*</span>
		</div>             
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo getTxt('NewPassword'); ?></label>
        <div class="col-sm-9">
     	   <input type="text" class="form-control" name="password" size="25" maxlength="25"  /><span class="required">*</span>
		</div>             
      </div>
		 <div class="col-md-3 col-md-offset-9">
      <input type="SUBMIT" name="submit" value="<?php echo getTxt('ChangePassword');?>" class="button"/></div>
  </FORM> 
</div>
<?php HTML_Render_Body_End(); ?>