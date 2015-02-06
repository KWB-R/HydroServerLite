<?php
HTML_Render_Head($js_vars,'Setup');
echo $JS_JQuery;
echo $CSS_Main;
?>
<?php
HTML_Render_Body_StartInstall(); 
genHeading('InstallationComplete',true);
?>

<p><?php echo getTxt('CongratsInstall');?></p>
<p><?php echo getTxt('Login');?></p>
<p><a href='<?php echo base_url('index.php/'.$db.'/home')?>' class="button"><?php echo getTxt('GoToSite');?></a></p>
</div>
<?php HTML_Render_Body_End();?>