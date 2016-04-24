<?php $base_url = "../assets/js"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title id='Description'>Overview on Sources, Sites, Variabls, Methods objects</title>
	<link rel="stylesheet" href="<?php echo $base_url; ?>/jqwidgets/styles/jqx.base.css" type="text/css" />
	<script type="text/javascript" src="<?php echo $base_url; ?>/jquery.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>/jqwidgets/jqxcore.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>/jqwidgets/jqxdata.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>/jqwidgets/jqxbuttons.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>/jqwidgets/jqxtabs.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>/jqwidgets/jqxscrollbar.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>/jqwidgets/jqxmenu.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>/jqwidgets/jqxgrid.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>/jqwidgets/jqxgrid.selection.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>/jqwidgets/jqxgrid.columnsresize.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>/jqwidgets/jqxgrid.export.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>/jqwidgets/jqxdata.export.js"></script>

<script type="text/javascript">

	var server = 'http://localhost/hydroserverlite_cc/index.php/default/';

	function getColumnConfig(object)
	{
		switch (object) {
			case 'sources':
				return [
					{ text: 'SourceID', datafield: 'SourceID'},
					{ text: 'Organization', datafield: 'Organization'},
					{ text: 'SourceDescription', datafield: 'SourceDescription'},
					//{ text: 'SourceLink', datafield: 'SourceLink'},
					{ text: 'ContactName', datafield: ''},
					{ text: 'Phone', datafield: 'ContactName'},
					{ text: 'Email', datafield: 'Email'},
					//{ text: 'Address', datafield: 'Address'},
					//{ text: 'City', datafield: 'City'},
					//{ text: 'State', datafield: 'State'},
					//{ text: 'ZipCode', datafield: 'ZipCode'},
					//{ text: 'Citation', datafield: 'Citation'},
					//{ text: 'MetadataID', datafield: 'MetadataID'},
					{ text: 'TopicCategory', datafield: 'TopicCategory'}
					//{ text: 'Title', datafield: 'Title'},
					//{ text: 'Abstract', datafield: 'Abstract'},
					//{ text: 'ProfileVersion', datafield: 'ProfileVersion'},
					//{ text: 'MetadataLink', datafield: 'MetadataLink'}
				];
			case 'sites':
				return [
					{ text: 'SiteID', datafield: 'SiteID' },
					{ text: 'SiteCode', datafield: 'SiteCode' },
					{ text: 'SiteName', datafield: 'SiteName' },
					{ text: 'Latitude', datafield: 'Latitude' },
					{ text: 'Longitude', datafield: 'Longitude' },
					{ text: 'SiteType', datafield: 'SiteType' }
			/*	"SiteID": "1",
        "SiteCode": "KWB_KBW",
        "SiteName": "Berlin, Spandau, kleine Badewiese",
        "Latitude": "52.49429",
        "Longitude": "13.18583",
        "LatLongDatumID": "3",
        "SiteType": "Stream",
        "Elevation_m": "30",
        "VerticalDatum": "MSL",
        "LocalX": null,
        "LocalY": null,
        "LocalProjectionID": null,
        "PosAccuracy_m": null,
        "State": "NULL",
        "County": "",
        "Comments": "",
        "siteid": "1",
        "picname": "siteimg1440730659.JP*/
				];
			case 'variables':
				return [
					{ text: 'VariableID', datafield: 'VariableID' },
					{ text: 'VariableCode', datafield: 'VariableCode' },
					{ text: 'VariableName', datafield: 'VariableName' },
					{ text: 'DataType', datafield: 'DataType' },
					{ text: 'UnitsID', datafield: 'unitsID' },
					{ text: 'UnitsType', datafield: 'unitsType' },
					{ text: 'UnitsName', datafield: 'unitsName' },
					{ text: 'UnitsAbbreviation', datafield: 'unitsAbbreviation' }
//					{ text: '', datafield: '' },
/*       "VariableID": "1",
        "VariableCode": "ZPK",
        "VariableName": "Zooplankton",
        "Speciation": "",
        "VariableunitsID": "342",
        "SampleMedium": "Surface Water",
        "ValueType": "Field Observation",
        "IsRegular": "0",
        "TimeSupport": "0",
        "TimeunitsID": "104",
        "DataType": "Average",
        "GeneralCategory": "Biota",
        "NoDataValue": "-9999",
        "unitsID": "342",
        "unitsName": "number of organisms per 100 milliliter",
        "unitsType": "Organism Concentration",
        "unitsAbbreviation": "#\/100 mL"*/
				];
			case 'methods':
				return [
					{ text: 'MethodID', datafield: 'MethodID' },
					{ text: 'MethodDescription', datafield: 'MethodDescription' },
					{ text: 'MethodLink', datafield: 'MethodLink' }
/*				"MethodID": "1",
        "MethodDescription": "No method specified",
        "MethodLink": null*/
				];
			default: return [];
		}
	}

	function getDataAdapter(source)
	{
		return new $.jqx.dataAdapter(source, {
			loadComplete: function (data) { },
			loadError: function (xhr, status, error) {
				alert('error');
			}
		});
	}

	function getSource(object)
	{
		var endpoints = {
			sources: 'source/get/-1',
			sites: 'sites/getSiteJSON?siteid=-1',
			variables: 'variable/getWithUnit?varid=-1',
			methods: 'methods/getJSON'
		};

		return {
			url: server + endpoints[object],
			datatype: 'json'
		};
	}

	function getGridConfig(object)
	{
		var gridConfig = {
			width: 800,
			height: 300,
			columnsresize: true
		};

		return jQuery.extend(gridConfig, {
			source: getDataAdapter(getSource(object)),
			columns: getColumnConfig(object)
		});
	}

	function getButtonConfig($grid, filename)
	{
		return {
			width: 160
		};
	}

	function getExportHandler($grid, filename)
	{
		return (function(event) {
			$grid.jqxGrid('exportdata', 'xls', filename);
		});
	}

	$(document).ready(function () {

		$('#tabs').jqxTabs();

		var objects = ['sources', 'sites', 'variables', 'methods'];

		objects.forEach(function(object) {

			var $grid = $("#grid_" + object);
			var $button = $("#export_" + object);

			$grid.jqxGrid(getGridConfig(object));

			$button.jqxButton(getButtonConfig())
				.on('click', getExportHandler($grid, object));
		});

	});

	</script>
</head>
<body class='default'>

	<div id="tabs">

		<ul id="tabs">
			<li>Sources</li>
			<li>Sites</li>
			<li>Variables</li>
			<li>Methods</li>
		</ul>

		<div>
			<br />
			<div id="grid_sources"></div>
			<br />
			<input id="export_sources" type="button" value="Export Sources" />
		</div>

		<div>
			<br />
			<div id="grid_sites"></div>
			<br />
			<input id="export_sites" type="button" value="Export Sites" />
		</div>

		<div>
			<br />
			<div id="grid_variables"></div>
			<br />
			<input id="export_variables" type="button" value="Export Variables" />
		</div>

		<div>
			<br />
			<div id="grid_methods"></div>
			<br />
			<input id="export_methods" type="button" value="Export Methods" />
		</div>

	</div>

</body>
</html>
