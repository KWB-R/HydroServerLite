<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>Hydrodata</title>
	<link rel="shortcut icon" href="<?=base_url()?>assets/images/favicon.ico" type="image/x-icon" />
	<link rel="bookmark" href="<?=base_url()?>assets/images/favicon.ico" />

	<link href="<?=base_url()?>assets/css/style.css" rel="stylesheet"/>

	<link href="<?=base_url()?>assets/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>

	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery-ui-1.10.3.custom.min.js"></script>

</head>
<body>
</script>
<div id="container">
	<h1>Welcome to Hydrodata Server</h1>

	<div id="body">
		<p>
		The HydroServer Lite Interactive Web Client is an online software tool that helps store, organize, and publish data provided by citizen scientists.
		</p>
		<p>
What are citizen scientists? They can be anyone who collects and shares scientific data with professional scientists to achieve common goals.
		</p>
		<p>
Once you are a registered user, you will be able to login and upload your own data into our database to provide valuable input into the research being done in your area as well as around the world. 
		</p>
		<br />
		<div id="base_info">
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo base_url('test');?>" class="info_link">REST Service Test</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;You can perform tests on all of the methods in Hydrodata Server on this page. In this case the test for REST Service.
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo base_url('cuahsi_1_1.asmx');?>" class="info_link">SOAP Web Service</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;Hydrodata soap service page.
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo base_url('updatecv.php');?>" class="info_link">Update Controlled Vocabulary</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;Update Controlled Vocabulary from HIS Central.
					</div>
				</div> 
			</div>
		</div>
	</div>

	<p class="footer"><font color=#000000 face=Arial, Helvetica, sans-serif size=2><i>Copyright &copy; 2012. <a href='http://hydroserverlite.codeplex.com/' target='_blank' class='reversed'>Hydroserver Lite</a>. All Rights Reserved. <a href='http://hydroserverlite.codeplex.com/team/view' target='_blank' class='reversed'>Meet the Developers</a></i></font></p>
</div>

</body>
</html>