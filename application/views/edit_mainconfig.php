<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
HTML_Render_Head($js_vars);
echo $JS_JQuery;
echo $CSS_JQX;
echo $JS_JQX;
echo $JS_Forms;
echo $CSS_Main;
HTML_Render_Body_Start(); 
genHeading('MainConfigTitle',true);
$attributes = array('class' => 'form-horizontal', 'name' => 'form1');
echo form_open('form1', $attributes);
?>
<div class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"></button>
            <div class="col-sm-12><p class="h2"><strong><?php echo getTxt('AdminWelcome');?></strong></p></div>
            <div class="col-sm-12><span class="help-block"><?php echo getTxt('MainConfigDirections');?></span></div>
			<?php 	
			genInput('CurrentUsername','username', 'username', false, " readonly value='his_admin'"); 
			genInputT('NewPassword','password','password',false,$extra='','EnterNow');
			?>
            <div class="col-sm-12><p class="h12><strong><?php echo getTxt('EnterDefaultSettings');?></strong></p></div></br>
          			<div class="col-sm-12><p class="h6"><strong>SETUP TYPE</strong>&nbsp;
                    <input type="radio" name= "setuptype" value="Basic" checked="checked" >Basic</input> &nbsp;
                    <input type="radio" name= "setuptype" value="Advanced" >Advanced</input><br /><br />
                    </p>
     				</div>
            <div class="col-sm-12><span class="h4"><strong><?php echo getTxt('MySQLConfiguration');?></strong></span></div>
			<?php
			genInputH('DatabaseHost','databasehost','databasehost',getTxt('DatabaseHostInfo'),false);
			genInput('DatabaseUsername','databaseusername','databaseusername');
			genInput('DatabasePassword','databasepassword','databasepassword');
			genInputH('DatabaseName','databasename','databasename',getTxt('DatabaseNameInfo'),false);
			genInput('LanguageCode','LangCode','lang');
			?>
            <div class="col-sm-12><p class="h4"><strong><?php echo getTxt('ConfigurationSettingsLook');?></strong></p></div>
            <?php
			genInputT('OrganizationName','orgname','orgname',false,$extra='','OrganizationNameEx');
			genInputT('ParentWebsiteName','parentname','parentname',false,$extra='','ParentWebsiteNameEx');
			genInputT('ParentWebsite','parentweb','parentweb',false,$extra='','WebsiteDomainEx');
			genInputT('SoftwareVersion','sversion','sversion',false,$extra='','VersionNumber');									
			?>            
			<div class="col-sm-12><span class="h4"><strong><?php echo getTxt('ConfigurationSettingsSecurity');?></strong></span></div>
			<?php
			genInputT('WebsiteDomain','domain','domain',false,$extra='','WebsiteDomainEx');	
			?>
            <div class="col-sm-12><p class="h4"><strong><?php echo getTxt('ConfigurationSettingsSource');?></strong></p></div>
            <?php
			genInputH('MetaDataProfileVersion','Profile Version','profilev',getTxt('ProfileVersionInfo'));
			?>
            <div class="col-sm-12><p class="h4"><strong><?php echo getTxt('ConfigurationSettingsSites');?></strong></p></div>
            <?php
			genInputT('Source','source','source',false,$extra='','OrganizationNameEx');
			genInputH('LocalX','localx','localx',getTxt('LocalXInfo'));	
			genInputH('LocalY','localy','localy',getTxt('LocalYInfo'));	
			genInputH('LocalProjectionID','localpid','localpid',getTxt('LocalProjectionIDInfo'));	
			genInputH('PosAccuracy','posaccuracy','posaccuracy',getTxt('PositionalAccuracyInfo'));	
			genInputH('VerticalDatum','Vertical datum','vdatum',getTxt('VerticalDatumInfo'));	
			genInputH('SpatialReference','Spatial Reference','spatialref',getTxt('SpatialReferenceInfo'));	
			?>
            <div class="col-sm-12><p class="h4"><strong><?php echo getTxt('ConfigurationSettingsVariables');?></strong></p></div>
            <?php
			genInputH('VariableCode','Variable Code','varcode',getTxt('VariableCodeInfo'));	
			genInputH('TimeSupport','Time Support','timesupport',getTxt('LocalXInfo'));	
			?>
            <div class="col-sm-12><p class="h4"><strong><?php echo getTxt('ConfigurationSettingsDataValues');?></strong></p></div>
            <?php
			genInputH('UTCOffset','UTC Offset','utcoffset1',getTxt('UTCOffsetInfo'));
			genInputH('CensorCode','localpid','localpid',getTxt('CensorCodeInfo'));
			genInputH('QualityControlLevel','localpid','localpid',getTxt('QualityControlLevelInfo'));
			genInputH('ValueAccuracy','localpid','localpid',getTxt('ValueAccuracyInfo'));
			genInputH('OffsetTypeID','localpid','localpid',getTxt('OffsetIntergerInfo'));
			genInputH('QualifierID','localpid','localpid',getTxt('QualifierIDInfo'));
			genInputH('SampleID','localpid','localpid',getTxt('SampleIDInfo'));
			genInputH('DerivedFromID','localpid','localpid',getTxt('DerivedFromIDInfo'));
			?> 
            <div class="col-md-5 col-md-offset-5">
       <input type="SUBMIT" name="submit" value="<?php echo getTxt('SaveSettings');?>" class="button"/>
       <input type="reset" name="Reset" value="<?php echo getTxt('Cancel'); ?>" class="button" style="width: auto" />
</div>
</div>
            
           
</div>          
</div>
</div>
</div>
</div>
<!--genInput('NewPassword','password1','password1',true);
//genInput('NewPassword1','password','password',true);
//genSubmit('ChangePassword');-->
<?php HTML_Render_Body_End();?>




<script type= "text/javascript">
</script>