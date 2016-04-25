<?php
HTML_Render_Head($js_vars, "This is the download section");
echo $JS_JQuery;
echo $CSS_JQX;
echo $JS_JQX;
echo $JS_JQX_GridExtra;
echo $JS_GetTheme;
echo $CSS_Main;
?>

<script type="text/javascript">

function initGrid()
{
	var source = jQuery.extend(getSourceConfig(), {
		url: base_url + "series/getJSON"
	});

	var $grid = $('#grid_series');

	// Get grid column (width of ID columns = 25px)
	var config = jQuery.extend(getGridConfig2('35px', '60px', '170px'), {
		source: getDataAdapter(source)
	});

	$grid.jqxGrid(config);
}

function getDataAdapter(source)
{
	return new $.jqx.dataAdapter(source, {
		loadComplete: function (data) { 
			mylog('loadComplete');
		},
		loadError: function (xhr, status, error) {
			alert('Error loading ' + source.url + ": " + error);
			console.log(error);
		},
		downloadComplete: function (data, status, xhr) {
			mylog('downloadComplete');
		},
	});
}

function initButtons()
{
	$('#export_series').jqxButton(getButtonConfig())
		.on('click', getExportHandler($('#grid_series'), 'seriesCatalog'));

	$('#downloadButtonXls').jqxButton(getButtonConfig())
	.on('click', function(event) {
		handleExportClick('xls');
	});

	$('#downloadButtonCsv').jqxButton(getButtonConfig())
	.on('click', function(event) {
		handleExportClick('csv');
	});

	var $checkbox = $('#showIdColumns');
	$checkbox.on('change', function(event) {
		var checked = $checkbox.prop('checked');
		showOrHideIdColumns(checked);
	});
}

function handleExportClick(format)
{
	var seriesIDs = getSelectedIDs();

	if (seriesIDs.length === 0) {
		alert("Please select at least one series!");
		return;
	}

	var parameterString = jQuery.param({seriesid: seriesIDs, format: format});
	var url = base_url + 'datapoint/getSeries?' + parameterString;

	//alert("downloading series via url: " + url);

	window.open(url, '_blank');
}

function showOrHideIdColumns(show)
{
	var method = (show ? 'showcolumn' : 'hidecolumn');
	var $grid = $('#grid_series');
	var objects = ['Source', 'Site', 'Variable', 'Method']; //, 'QualityControlLevel'];

	objects.forEach(function(columnName) {
		$grid.jqxGrid(method, columnName + 'ID');
	});

	objects = ['Site', 'Variable'];

	objects.forEach(function(columnName) {
		$grid.jqxGrid(method, columnName + 'Code');
	});
}

function getSelectedIDs()
{
	var $grid = $('#grid_series');
	var rowIndices = $grid.jqxGrid('getselectedrowindexes');
	var seriesIDs = [];
	var rowData;

	rowIndices.forEach(function(rowIndex) {
		if (rowIndex != undefined) {
			rowData = $grid.jqxGrid('getrowdata', rowIndex);
			seriesIDs.push(rowData.SeriesID);
		}
	});

	return seriesIDs;
}

function getSourceConfig()
{
	// possible values for type: 'int', 'float', 'bool'
	return {
		datatype: "json",
		datafields: [
			{ name: 'SeriesID', type: 'int'},
			{ name: 'ValueCount', type: 'int'},
			{ name: 'BeginDateTime', type: 'date'},
			{ name: 'EndDateTime', type: 'date'},
			{ name: 'SourceID', type: 'int'},
			{ name: 'Organization'},
			{ name: 'SiteID', type: 'int'},
			{ name: 'SiteCode'},
			{ name: 'SiteName'},
			{ name: 'VariableID', type: 'int'},
			{ name: 'VariableCode'},
			{ name: 'VariableName'},
			{ name: 'MethodID', type: 'int'},
			{ name: 'MethodDescription'}
			//{ name: 'QualityControlLevelID', type: 'int'},
			//{ name: 'QualityControlLevelCode'}
			// "SiteType":"Stream",
			// "Speciation":"",
			// "VariableunitsID":"342",
			// "VariableunitsName":"number of organisms per 100 milliliter",
			// "SampleMedium":"Surface Water",
			// "ValueType":"Field Observation",
			// "TimeSupport":"0",
			// "TimeunitsID":"104",
			// "TimeunitsName":"day",
			// "DataType":"Average",
			// "GeneralCategory":"Biota",
			// "SourceDescription":"Kompetenzzentrum Wasser Berlin",
			// "Citation":"",
			// "BeginDateTime":"2000-01-01 12:00:00",
			// "EndDateTime":"2016-04-18 21:45:00",
			// "BeginDateTimeUTC":"1969-12-31 23:00:00",
			// "EndDateTimeUTC":"2016-04-18 20:45:00",
		],
		id: 'SeriesID'
	};
}

function getGridConfigBase()
{
	return {
		width: 800,
		height: 300,
		columnsresize: true,
		pageable: true,
		pagesizeoptions: ['10', '20', '50'],
		//autoheight: true,
		sortable: true,
		filterable: true,
		filtermode: [
			'default', // 0
			'excel'    // 1
		][1],
		groupable: true
		//showgroupsheader: false,
	}
}

function getGridConfig(object)
{
	var gridConfig = jQuery.extend(getGridConfigBase(), {
	});

	return jQuery.extend(gridConfig, {
		source: getDataAdapter(getSource(object)),
		columns: getColumnConfig(object)
	});
}

function getGridConfig2(idWidth, codeWidth, textWidth)
{
	var extraConfig = {
		//groups: [ "VariableCode" ], //"Organization", "SiteName" ],
		altrows: true,
		enabletooltips: true,
		editable: true,
		ready: function (){
			// Start with hidden ID columns once the grid is loaded
			showOrHideIdColumns(false);
		},
		selectionmode: [
			'none',                  // 0
			'singlerow',             // 1
			'multiplerows',          // 2
			'multiplerowsextended',  // 3
			'multiplerowsadvanced',  // 4
			'singlecell',            // 5
			'multilpecells',         // 6
			'multiplecellsextended', // 7
			'multiplecellsadvanced', // 8
			'checkbox'               // 9
		][9],
		columns: [
			// Possible attributes:
			// text: 'Product Name', 
			// columngroup: 'ProductDetails', 
			// columntype: 'number', 'checkbox', 'numberinput', 'dropdownlist',
    	// 	'combobox', 'datetimeinput', 'textbox', 'template', 'custom'
			// datafield: 'ProductName', 
			// width: 250 , 
			// cellsalign: 'right', 
			// align: 'right', 
			// cellsformat: 'c2',	'ddd, yyyy-MM-dd HH:mm',
			// cellsrenderer: cellsrenderer,
	//			{ text: 'Selected', columntype: 'checkbox', datafield: 'Selected', width: 80 },
			{ columngroup: 'Series', text: 'ID', datafield: 'SeriesID', width: idWidth },
			{ columngroup: 'Series', text: 'Count', datafield: 'ValueCount', width: 50, cellsalign: 'right' },
			{ columngroup: 'Series', text: 'First', datafield: 'BeginDateTime', width: 85, cellsformat: 'yyyy-MM-dd' },
			{ columngroup: 'Series', text: 'Last', datafield: 'EndDateTime', width: 85, cellsformat: 'yyyy-MM-dd' },
			{ columngroup: 'Source', text: 'ID', datafield: 'SourceID', width: idWidth },
			{ columngroup: 'Source', text: 'Organization', datafield: 'Organization', width: codeWidth },
			{ columngroup: 'Site', text: 'ID', datafield: 'SiteID', width: idWidth },
			{ columngroup: 'Site', text: 'Code', datafield: 'SiteCode', width: codeWidth },
			{ columngroup: 'Site', text: 'Site', datafield: 'SiteName', width: textWidth },
			{ columngroup: 'Variable', text: 'ID', datafield: 'VariableID', width: idWidth },
			{ columngroup: 'Variable', text: 'Code', datafield: 'VariableCode', width: codeWidth },
			{ columngroup: 'Variable', text: 'Variable', datafield: 'VariableName', width: textWidth },
			{ columngroup: 'Method', text: 'ID', datafield: 'MethodID', width: idWidth } ,
			{ columngroup: 'Method', text: 'Method',  datafield: 'MethodDescription', width: textWidth }
			//{ columngroup: 'QualityControlLevel', text: 'ID', datafield: 'QualityControlLevelID' },
			//{ columngroup: 'QualityControlLevel', text: 'Code', datafield: 'QualityControlLevelCode' }
		],
		columngroups: [
			{ text: 'Series', align: 'center', name: 'Series' },
			{ text: 'Source', align: 'center', name: 'Source' },
			{ text: 'Site', align: 'center', name: 'Site' },
			{ text: 'Variable', align: 'center', name: 'Variable' },
			{ text: 'Method', align: 'center', name: 'Method' }
			//{ text: 'QualityControlLevel', align: 'center', name: 'QualityControlLevel' }
		]
	};
	
	return jQuery.extend(getGridConfigBase(), extraConfig);
}

function mylog(message)
{
	console.log(message);
	// alert(message);
}

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

	function getSource(object)
	{
		var endpoints = {
			sources: 'source/get/-1',
			sites: 'sites/getSiteJSON?siteid=-1',
			variables: 'variable/getWithUnit?varid=-1',
			methods: 'methods/getJSON'
		};

		return {
			url: base_url + endpoints[object],
			datatype: 'json'
		};
	}

	function getButtonConfig($grid, filename)
	{
		return {
			width: 220
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

		// Initialise the content of the Series tab
		initGrid();

		initButtons();

	});

</script>

<?php HTML_Render_Body_Start(); ?>

	<h1>Download area</h1>

	<div id="tabs">

		<ul id="tabs">
			<li>Series</li>
			<li>Sources</li>
			<li>Sites</li>
			<li>Variables</li>
			<li>Methods</li>
		</ul>

<!-- Tab "Series" Begin -->
		<div>
			<!-- <div id="source"></div> -->
			<div>
				<input id="showIdColumns" type="checkbox" name="idcolumns" value="idcolumns" /> Show ID and Code columns<br />
			</div>
			
			<div id="grid_series"></div>

			<div>
				<input id="export_series" type="button" value="Download SeriesCatalog" />
				<input id='downloadButtonXls' type="button" value="Download selected series (xls)" />
				<input id='downloadButtonCsv' type="button" value="Download selected series (csv)" />
			</div>

		</div>
<!-- Tab "Series" End -->

		<div>
			<br />
			<div id="grid_sources"></div>
			<br />
			<input id="export_sources" type="button" value="Download Sources" />
		</div>

		<div>
			<br />
			<div id="grid_sites"></div>
			<br />
			<input id="export_sites" type="button" value="Download Sites" />
		</div>

		<div>
			<br />
			<div id="grid_variables"></div>
			<br />
			<input id="export_variables" type="button" value="Download Variables" />
		</div>

		<div>
			<br />
			<div id="grid_methods"></div>
			<br />
			<input id="export_methods" type="button" value="Download Methods" />
		</div>

	</div>

<?php HTML_Render_Body_End(); ?>

