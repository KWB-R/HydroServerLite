<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>Hydroserver JSON API;?></title>
	<link rel="shortcut icon" href="<?=base_url()?>assets/services/images/favicon.ico" type="image/x-icon" />
	<link rel="bookmark" href="<?=base_url()?>assets/services/images/favicon.ico" />

	<link href="<?=base_url()?>assets/services/css/style.css" rel="stylesheet"/>

	<link href="<?=base_url()?>assets/services/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>

	<script type="text/javascript" src="<?=base_url()?>assets/services/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/services/js/jquery-ui-1.10.3.custom.min.js"></script>

</head>
<body>
</script>
<div id="container">
	<h1><?php echo getTxt('Welcome').' '.getTxt('To').' Hydroserver Lite JSON API';?></h1>

	<div id="body">
		
		<div id="base_info">
		
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo site_url('services/api/sources');?>" class="info_link">sources</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;
                         Add new sources to HydroServer. See example code in 
						 <a class="info_link" href="<?=base_url()?>assets/api/add_source.py">Python</a>,&nbsp;
						 <a class="info_link" href="<?=base_url()?>assets/api/AddSource.cs">c#</a>,&nbsp;
						 <a class="info_link" href="<?=base_url()?>assets/api/add_source.R">R</a> 
                    
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label">
				<a href="<?php echo site_url('services/api/sites');?>" class="info_link">sites</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;Add new sites to HydroServer. See example code in 
						<a class="info_link" href="<?=base_url()?>assets/api/add_site.py">Python</a>,&nbsp;
						<a class="info_link" href="<?=base_url()?>assets/api/AddSite.cs">c#</a>,&nbsp;
						<a class="info_link" href="<?=base_url()?>assets/api/add_site.R">R</a> 
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo site_url('services/api/variables');?>" class="info_link">variables</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;Add new variables to HydroServer. See example code in 
						<a class="info_link" href="<?=base_url()?>assets/api/add_variable.py">Python</a>,&nbsp;
						<a class="info_link" href="<?=base_url()?>assets/api/AddVariable.cs">c#</a>,&nbsp;
						<a class="info_link" href="<?=base_url()?>assets/api/add_variable.R">R</a> 
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo site_url('services/api/methods');?>" class="info_link">methods</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;Add new methods to HydroServer. See example code in 
						<a class="info_link" href="<?=base_url()?>assets/api/add_method.py">Python</a>,&nbsp;
						<a class="info_link" href="<?=base_url()?>assets/api/AddMethod.cs">c#</a>,&nbsp;
						<a class="info_link" href="<?=base_url()?>assets/api/add_method.R">R</a> 
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo site_url('services/api/values') ;?>" class="info_link">values</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;Add new data values to HydroServer. See example code in 
						<a class="info_link" href="<?=base_url()?>assets/api/add_values.py">Python</a>,&nbsp;
						<a class="info_link" href="<?=base_url()?>assets/api/AddValues.cs">c#</a>,&nbsp;
						<a class="info_link" href="<?=base_url()?>assets/api/add_values.R">R</a> 
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo site_url('services/api/GetSitesJSON') ;?>" class="info_link">GetSitesJSON</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;Get sites from HydroServer in JSON format  
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo site_url('services/api/GetSourcesJSON') ;?>" class="info_link">GetSourcesJSON</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;Get sources from HydroServer in JSON format  
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo site_url('services/api/GetVariablesJSON') ;?>" class="info_link">GetVariablesJSON</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;Get variables from HydroServer in JSON format  
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo site_url('services/api/GetMethodsJSON') ;?>" class="info_link">GetMethodsJSON</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;Get methods from HydroServer in JSON format  
					</div>
				</div> 
			</div>
		</div>
	
<?php $this->load->view('services/footer'); ?>