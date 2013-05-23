<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Hydrodata Web Tester</title>
	<link rel="shortcut icon" href="<?=base_url()?>assets/images/favicon.ico" type="image/x-icon" />
	<link rel="bookmark" href="<?=base_url()?>assets/images/favicon.ico" />

	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 50px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}

	label {
	    width:150px;
	    height:30px;
	    text-align:left;
	    float:left;
	}
	.param_container{
	    width:auto;
	}
	.content{
	    height:30px;
	}
	.content-info{
	    height:60px;
	}
	.remove {
		cursor: pointer;
	}

	.numeric {
		 text-align: right;
	}

	select {
		text-align: center;
	}
	select .opt {
		text-align:left;
	}
	</style>

	<link href="<?=base_url()?>assets/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>

	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery-ui-1.10.3.custom.min.js"></script>

</head>
<body>
<script type="text/javascript">
$(document).ready(function() {
	$(".datepicker").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#method").change(function() {
		$("#generated_url").html("");
		if ($(this).val() != "") {
			$("#generator_param").show();
		} else {
			$("#generator_param").hide();
		}

		$.ajax({
			url:"<?=site_url()?>services/method_get_params/" + $(this).val(),
			success: function(response){
				$("#method_param").html(response);
				/*$(document).tooltip({
					position: {
						my: "center bottom-20",
						at: "center top"
					}
				});*/
				$(document).tooltip();
				$(".datepicker").datepicker("destroy");
			    $("#endDate").datepicker({ dateFormat: 'yy-mm-dd' });
			    $("#startDate").datepicker({
			    	dateFormat: 'yy-mm-dd',
			    	onSelect: function(dateStr) {
			    		$("#endDate").datepicker("option", "setDate", $(this).datepicker('getDate'));
			    		$("#endDate").datepicker("option", "minDate", $(this).datepicker('getDate'));
			    	}
				});
	  		},
	  		dataType:"html"
	  	});
	});

	$("#btnGenerator").click(function(e) {
	    $.ajax({
           type: "POST",
           url: "<?=base_url()?>index.php/services/generate_url/",
           data: $("#form_url_generator").serialize(), // serializes the form's elements.
           success: function(data)
           {
               $("#generated_url").html(data); // show response from the php script.
           }
        });
	    e.preventDefault(); // avoid to execute the actual submit of the form.
	});
});

function remove(obj) {
	$(obj).parent().parent().remove();
}
</script>
<div id="container">
	<h1>Hydrodata Web Tester</h1>

	<div id="body">
		<form id="form_url_generator">
		<div id="base_param">
			<div class="param_container">
			    <label>Service Method</label>
				<div class="content">
					<select id="method" name="method" title="Select service method here.">
						<option value="">.: Select Method :.</option>
						<option class="opt" value="GetSiteInfo">GetSiteInfo</option>
						<option class="opt" value="GetSiteInfoMultpleObject">GetSiteInfoMultpleObject</option>
						<option class="opt" value="GetSiteInfoObject">GetSiteInfoObject</option>
						<option class="opt" value="GetSites">GetSites</option>
						<option class="opt" value="GetSitesByBoxObject">GetSitesByBoxObject</option>
						<option class="opt" value="GetSitesObject">GetSitesObject</option>
						<option class="opt" value="GetValues">GetValues</option>
						<option class="opt" value="GetValuesForASiteObject">GetValuesForASiteObject</option>
						<option class="opt" value="GetValuesObject">GetValuesObject</option>
						<option class="opt" value="GetVariableInfo">GetVariableInfo</option>
						<option class="opt" value="GetVariableInfoObject">GetVariableInfoObject</option>
						<option class="opt" value="GetVariables">GetVariables</option>
						<option class="opt" value="GetVariablesObject">GetVariablesObject</option>
					</select>
				</div> 
			</div>
		</div>
		<div id="method_param"></div>
		<div id="generator_param" style="display:none;">
			<div class="param_container">
			    <label>&nbsp;</label>
				<div class="content">
					<input type="submit" id="btnGenerator" value="Generate URL" />
				</div> 
			</div>
		</div>
		</form>
		<br />
		<div id="generated_url"></div>

	</div>

	<p class="footer"><font color=#000000 face=Arial, Helvetica, sans-serif size=2><i>Copyright &copy; 2012. <a href='http://hydroserverlite.codeplex.com/' target='_blank' class='reversed'>Hydroserver Lite</a>. All Rights Reserved. <a href='http://hydroserverlite.codeplex.com/team/view' target='_blank' class='reversed'>Meet the Developers</a></i></font></p>
</div>

</body>
</html>