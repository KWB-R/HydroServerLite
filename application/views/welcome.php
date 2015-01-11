<?php
HTML_Render_Head($js_vars);
echo $CSS_Main;
echo $JS_JQuery;
echo $JS_Forms;
HTML_Render_Body_Start();
?>
<br />
<div class="col-md-6">
	<?php showMsgs();?>
	<h1>Welcome</h1>
    <p><?php echo getTxt('Paragraph1'); ?></p>
    <p><?php echo getTxt('Paragraph2'); ?></p>
    <p><?php echo getTxt('Paragraph3'); ?></p>
    <?php if($multi){?>
    <p><?php echo getTxt('congratsMultiple')."<a href='".base_url('index.php/default/home/installation')."'>Click Here</a>."; ?></p>
    <?php }?>
</div>
<div class="col-md-3"><img src="<?php echo getImg('homepage_shot.jpg');?>" class="img-responsive" alt="site picture"/></div>
<?php HTML_Render_Body_End(); ?>
<script type="text/javascript">

//Validate username and password
$("form").submit(function(){
	if(($("#username").val())==""){
	alert("Please enter a username!");
	return false;
	}

	if(($("#password").val())==""){
	alert("Please enter a password!");
	return false;
	}

//Now that all validation checks are completed, allow the data to query database

	return true;
});
</script>
