<?php
header('Content-Type: text/html; charset=utf-8');
HTML_Render_Head($js_vars, getTxt('SearchData'));

function beautify($html, $indentlevel = 4)
{
	$indentation = str_repeat('  ', $indentlevel);

	$html = str_replace('<script', "\n$indentation<script", $html);
	$html = str_replace('<link', "\n$indentation<link", $html);

	return $html;
}

echo beautify($JS_JQuery);
echo beautify($JS_JQueryUI);
echo beautify($JS_JQX);
echo beautify($JS_GetTheme);
echo beautify($JS_Globalization); // this is the only page that calls this. This is also the only refernce to MooTools
echo beautify($JS_Maps);
echo beautify($CSS_JQX);
echo beautify($CSS_Main);
echo beautify($CSS_JQuery_UI);
echo beautify($CSS_JQStyles);
?>


<!-- Load helper functions -->
<?php $source = base_url() . 'assets/js/details_helpers.js'; ?>
<script type="text/javascript" src="<?php echo $source;?>"></script>

<!-- Load configurations of UI Widgets -->
<?php $source = base_url() . 'assets/js/details_configs.js'; ?>
<script type="text/javascript" src="<?php echo $source;?>"></script>

<!-- Main Script -->
<script type="text/javascript">

//
// Define Global Variables
//

var globals = {
	siteID: <?php echo $SiteID;?>, // currently selected SiteID
	variableID: -1, // currently selected VariableID
	variableAndType: "", // currently selected "<VariableName> (<DataType>)"
	methodID: -1, // currently selected MethodID

	dateFrom: "",
	dateTo: "",

	date_from_sql: "",
	date_to_sql: "",

	chart: undefined,

	updateRequired: false
};

//
// Define all (translated) message texts beforehand
//

var DATA = {
	text:{
<?php
	$names = array(
		'InvalidTimeFive',
		'InvalidTimeHoursTwo',
		'InvalidTimeHoursZeros',
		'InvalidTimeHoursTwentyThree',
		'InvalidTimeMinutesTwo',
		'InvalidTimeMinutesZeros',
		'InvalidTimeMinutesFiftyNine',
		'InvalidCharacterNumbers',
		'EnterNumberValue',
		'EnterValidNumberValue',
		'DatesAvailable',
		'Date',
		'From',
		'To',
		'SelectStart',
		'SelectEnd',
		'Dataof',
		'ClickDrag',
		'TimeMsg',
		'OneD',
		'ThreeD',
		'OneW',
		'OneM',
		'SixM',
		'OneY',
		'All', 
		'DatabaseConfigurationError'
	);
	foreach ($names as $name) {
		echo "\t\t$name: \"" . getTxt($name) . "\",\n";
	}
?>
		SiteName: "<?php echo $site['SiteName'];?>",
		ValueID: "<?php echo str_replace(':', ' ID', getTxt('Value'));?>",
		Value: "<?php echo str_replace(':', '', getTxt('Value'));?>"
	}
};

//
// Functions returning objects for the initialisation of user interface elements
//

function getRangeSelectorButtonConfig(texts)
{
	return [
		{ type: 'day',   count: 1, text: texts.OneD },
		{ type: 'day',   count: 3, text: texts.ThreeD },
		{ type: 'week',  count: 1, text: texts.OneW },
		{ type: 'month', count: 1, text: texts.OneM },
		{ type: 'month', count: 6, text: texts.SixM },
		{ type: 'year',  count: 1, text: texts.OneY },
		{ type: 'all',             text: texts.All }
	];
}

function getGridConfigUpdate(unit)
{
	// Define source and settings for jqx.dataAdapter

	var source = {
		datatype: 'json',
		url: getDataURL(true),
		id: 'ValueID',
		datafields: [
			{ name: 'ValueID', type: 'int'},
			{ name: 'DataValue', type: 'float'},
			{ name: 'LocalDateTime', type: 'date'}
		]
	};

	var settings = {
	};

	// Define columns for jqxGrid

	var columns = [
		{
			datafield: 'ValueID',
			text: DATA.text.ValueID
			, width: 'auto' //180
		},
		{
			datafield: 'LocalDateTime',
			text: DATA.text.Date,
			cellsformat: 'ddd, yyyy-MM-dd HH:mm',
			cellsalign: 'right'
			, width: 'auto' //180
		},
		{
			datafield: 'DataValue',
			text: DATA.text.Value + ' (' + unit + ')',
			cellsalign: 'right'
			, width: 'auto' //180
		}
	];

	if (<?php echo (isLoggedIn() ? 'true' : 'false'); ?>) {
		columns = jQuery.merge(
			columns,
			[ jQuery.extend(editColumnConfig, {buttonclick: editClickHandler}) ]
		);
	}

	return {
		source: new $.jqx.dataAdapter(source, settings),
		columns: columns
	};
} // end of getGridConfigUpdate()

function getWindowConfig(offset, dx, dy)
{
	// Set defaults
	dx = dx || 220;
	dy = dy ||  60;

	return {
		position: {
			x: parseInt(offset.left, 10) + dx,
			y: parseInt(offset.top,  10) + dy
		}
	};
}

//
// Functions to validate time and value strings
//

function validateValueAndTime(textinput, timepicker)
{
	return (validatenum(textinput) && validatetime(timepicker));
}

function validatetime(timepicker)
{
	//Removing all space
	var timestring = trimAllSpace(timepicker.val());

	timepicker.val(timestring);

	// checkTimeFormat from assets/js/details_helpers.js
	if (! checkTimeFormat(timestring, DATA.text)) {
		return false;
	}

	timepicker.val(IsNumeric(timestring));

	return true;
}

function validatenum(textinput)
{
	// isValidNumber from assets/js/details_helpers.js
	return isValidNumber(textinput.val(), DATA.text);
}

//
// Helper functions
//

function toInfoString(x)
{
	var text = '';

	if (typeof x === 'undefined') {
		text = '<undefined>';
	}
	else if (Array.isArray(x)) {
		text = "<Array with " + x.length + " elements>";
	}
	else {
		text = x.toString();
	}

	return text;
}

function setGlobal(name, value)
{
	var message = "Setting global '" + name + 
		"' to '" + toInfoString(value) + "' ";

	// Does the value of the global variable change?
	changed = (globals[name] !== value);

	if (changed) {

		message += "(old value was: '" + toInfoString(globals[name]) + "')";

		// Set the global variable to the new value
		globals[name] = value;
	}
	else {
		message += "(nothing changed)";
	}

	console.log(message);

	// Return true if the value of the global variable changed, otherwise false
	return changed;
}

//
// Event Handlers
//

// Open the popup window when the user clicks a button.
function editClickHandler(row)
{
	editrow = row;

	var $grid = $('#jqxgrid');

	// get the clicked row's data and initialize the input fields.
	var dataRecord = $grid.jqxGrid('getrowdata', editrow);

	var valueID = dataRecord.ValueID;
	var dataValue = dataRecord.DataValue;
	var localDateTime = dataRecord.LocalDateTime;

	//var datepart = localDateTime.split(' ');
	var localISO = localDateTime.toISOString();
	var localDate = toDate(localISO.substring(0, 10));
	var localTime = toHourAndMinute(localISO.substring(11, 19));
	
	// Create a Date time Input
	$('#date').
		jqxDateTimeInput(getDateInputConfig(2)).
		jqxDateTimeInput('setDate', localDateTime);

	//$('#timepicker').val(localTime);

	$('#value').val(dataValue);

	vid = valueID;

	// Open the popup window
	$('#popupWindow').
		jqxWindow(getWindowConfig($grid.offset())).
		jqxWindow('show');
}

function variableSelectHandler(event)
{
	var item = $('#dropdownlist').
		jqxDropDownList('getItem', event.args.index);

	//Check if a valid value is selected and process futher to display dates
	if (item !== null) {

		//Clear the date range
		$('#daterange').html("");

		setGlobal('variableID', item.value);
		setGlobal('variableAndType', item.label);

		// Update the source property of the Methods drop down list. As the 
		// 'bindingComplete' event is bound to the list, the first entry will be 
		// selected automatically after the new list elements are loaded.

		var url = toURL('methods/getSiteVarJSON', {
			siteid: globals.siteID,
			varid: globals.variableID
		});

		$('#methodlist').jqxDropDownList({ 
			source: toJsonAdapter(url, ['MethodID', 'MethodDescription'])
		});
	}
}

function methodSelectHandler(event)
{
	var item = $('#methodlist').
		jqxDropDownList('getItem', event.args.index);

	//Check if a valid value is selected and process futher to display dates
	if (item !== null) {

		changed = setGlobal('methodID', item.value);

		if (changed) {
			setGlobal('updateRequired', true);
		}

		// Load the date range of available data and call a function that handles
		// the new dates after loading.
		get_dates(
			globals.siteID,
			globals.variableID,
			globals.methodID, function(result) {
				setDateTimeRange(result.BeginDateTime, result.EndDateTime);
			}
		);
	}
}

function setMinOrMaxDate(isFromDate, date)
{
	if (isFromDate) {
		//Setting the Second calendar's min date to be the date of the first calendar
		//$("#jqxDateTimeInputto").jqxDateTimeInput('setMinDate', date);
	}

	setGlobal((isFromDate ? 'dateFrom' : 'dateTo'), date);

	// If the month is 0 or 13 it causes issues. We need to keep it between 1 and 12.
	// var month = (isFromDate ? toMonthBegin(date), toMonthEnd(date));
	var month = date.getMonth() + 1;

	// Convert the date to text so that it can be used within SQL
	var changed = setGlobal(
		(isFromDate ? 'date_from_sql' : 'date_to_sql'),
		formatDateSQL(date, undefined, ' ' + date.toTimeString().substring(0, 8))
	);

	// If the SQL-formatted version of the date changed update the plot
	if (changed || globals.updateRequired) {

		// Get the unit and call the given function if the unit is available
		getUnit(globals.variableID, function(unit) {

			updateGridAndPlot(unit);
			setGlobal('updateRequired', false);
		});
	}
}

function setDateTimeRange(date_from, date_to)
{
	// Display the time range of available data
	updateDateRangeInfo(date_from, date_to);

	// Convert to Date object without using the time information
	var startDate = new Date(date_from.substring(0, 10) + 'T00:00:00');
	var endDate   = new Date(date_to.substring(0, 10) + 'T00:00:00');

	// Set endDate to the start of the next day unless it refers to 00:00:00
	if (date_to.substring(11) != '00:00:00') {
		endDate.setDate(endDate.getDate() + 1);
	}

	setGlobal('dateFrom', startDate);
	setGlobal('dateTo', endDate);

	// Setting min and max dates?
	//setMinMaxDates();

	// Set the dates in the input boxes. All dependent actions will be performed
	// in the callback function that is registered for the change event.
	$('#jqxDateTimeInput').jqxDateTimeInput('setDate', globals.dateFrom);
	$('#jqxDateTimeInputto').jqxDateTimeInput('setDate', globals.dateTo);
}

function updateDateRangeInfo(date_from, date_to)
{
	var html =
		'<p>' +
			'<strong>' + DATA.text.DatesAvailable + '</strong> ' + date_from +
			' <strong>' + DATA.text.To + ' </strong> ' + date_to +
		'</p>';

	$('#daterange').html("").prepend(html);
}

function setMinMaxDates()
{
	//$("#jqxDateTimeInput").jqxDateTimeInput('setMinDate', ???);
	//$("#jqxDateTimeInput").jqxDateTimeInput('setMaxDate', ???);
	//$("#jqxDateTimeInputto").jqxDateTimeInput('setMinDate', ???);
	//$("#jqxDateTimeInputto").jqxDateTimeInput('setMaxDate', ???);
}

function toMonthBegin(date)
{
	var monthBegin = date.getMonth();

	return (monthBegin === 0) ? 1 : monthBegin;
}

function toMonthEnd(date)
{
	var monthEnd = date.getMonth() + 2;

	return (monthEnd > 12) ? 12 : monthEnd;
}

function addValueClickHandler()
{
	$("#popupWindow_new").
		jqxWindow(getWindowConfig($("#jqxgrid").offset())).
		jqxWindow('show');
}

function delValClickHandler()
{
	//Send out a delete request
	$.ajax({
		dataType: "json",
		url: toURL("datapoint/delete/" + vid, {})
	}).
	done(function(result) {
		if(result.status == 'success') {
			//Remove that row from the table
			$('#jqxgrid').jqxGrid('deleterow', editrow); //This one might be having issues.
			$("#popupWindow").jqxWindow('hide');
		}
	});
} // end of delValClickHandler()

function saveClickHandler()
{
	// Update the edited row when the user clicks the 'Save' button.
	if (editrow >= 0) {
		return handleSaveClick(true);
	}

	return true;
}

function saveNewClickHandler()
{
	return handleSaveClick(false);
}

function handleSaveClick(edit)
{
	postfix = (edit ? '' : '_new');

	// Store references to jQuery objects
	var $date       = $('#date' + postfix);
	//var $timepicker = $('#timepicker' + postfix);
	var $value      = $('#value' + postfix);

	// Return if value or time are invalid
	//if (! validateValueAndTime($value, $timepicker)) {
	if (! validatenum($value)) {
		return false;
	}

	// Store currently selected date
	var seldate = $date.jqxDateTimeInput('getDate');

	// Provide the endpoint to be used for the Ajax request
	var endpoint = (edit ? "datapoint/edit/" + vid : "datapoint/add");

	var dateISOString = seldate.toISOString();

	// Provide the parameters to be used for the Ajax request
	var parameters = {
		//dt: formatDateSQL(seldate, undefined, ''),
		// time: $timepicker.val(),
		dt: dateISOString.substring(0, 10),
		time: dateISOString.substring(11, 19),
		val: $value.val()
	};

	// To add a new DataValue we need to add SiteID, VariableID and MethodID
	// to the parameter list
	if (! edit) {
		parameters = jQuery.extend(parameters, {
			sid: globals.siteID,
			varid: globals.variableID,
			mid: globals.methodID
		});
	}

	// Provide handler function for Ajax done event
	var doneHandler_edit = function(msg) {

		if (dataAddOrEditHandler(msg, true)) {

			newrow = {
				//date: formatDateSQL(seldate, undefined, ' ' + $timepicker.val() + ':00'),
				date: seldate,
				Value: $value.val(),
				vid: vid
			}

			// update the row in the grid
			$('#jqxgrid').jqxGrid('updaterow', editrow, newrow);
		}
	};

	var doneHandler_add = function(msg) {
		return dataAddOrEditHandler(msg, false);
	};

	// Create an ajax request to update or add a DataValue
	$ajax = $.ajax({dataType: "json", url: toURL(endpoint, parameters)});

	// Set the done-handler for the ajax request
	$ajax.done((edit ? doneHandler_edit : doneHandler_add));

	return true;
}

function dataAddOrEditHandler(msg, edit)
{
	var success = (msg.status === 'success');

	if (success) {

		// Hide the popup window
		$(edit ? '#popupWindow' : '#popupWindow_new').jqxWindow('hide');

		//plot_chart();
	}
	else {
		alert(edit ? msg : DATA.text.DatabaseConfigurationError);
	}

	return success;
}

function compareClickHandler()
{
	$("html, body").animate({scrollTop: 0}, "slow");

	$('#window').jqxWindow('show');

	$('#windowContent').load(toURL('datapoint/compare/1', {}), function() {});

} // end of compareClickHandler()

function exportClickHandler()
{
	var url = toURL('datapoint/export', {
		siteid: globals.siteID,
		varid: globals.variableID,
		meth: globals.methodID,
		startdate: globals.date_from_sql,
		enddate: globals.date_to_sql
	});

	window.open(url, '_blank');
}

//
// Initialise the User Interface
//

$(document).ready(function() {

	//Create Tabs for Table Chart Switching

	$tabs = $('#jqxtabs');

	$tabs.jqxTabs(tabsConfig);

	$tabs.jqxTabs('disable');
	$tabs.jqxTabs('enableAt', 0);
	
	$tabs.on('selected', function (event) {
			if (event.args.item == 1) {
				$(window).resize();
			}
	});

	// Create the Variables Drop Down list with data received in JSON format
	var dataAdapter = toJsonAdapter(
		toURL('variable/getSiteJSON', {
			siteid: globals.siteID,
			withtype: 1
		}),
		['VariableID', 'VarNameMod']
	);

	var config = jQuery.extend(dropDownConfig, {
		source: dataAdapter,
		displayMember: 'VarNameMod',
		valueMember: 'VariableID'
	});

	$("#dropdownlist").jqxDropDownList(config).
		on('select', variableSelectHandler);

	// Create the Methods Drop Down list. The source property will only be set in 
	// variableSelectHandler() that is called when a variable was selected.
	config = jQuery.extend(dropDownConfig, {
		displayMember: 'MethodDescription',
		valueMember: 'MethodID'
	});

	$('#methodlist').
		jqxDropDownList(dropDownConfig).
		on('select', methodSelectHandler).
		on('bindingComplete', function (event) {
			$('#methodlist').jqxDropDownList('selectIndex', 0);
		});

	// Create date selectors and bind the 'valuechanged' event
	var dateInputConfig = getDateInputConfig(1);

	$("#jqxDateTimeInput").
		jqxDateTimeInput(dateInputConfig).
		on("valuechanged", function(event) {
			setMinOrMaxDate(true, event.args.date);
		});

	$("#jqxDateTimeInputto").
		jqxDateTimeInput(dateInputConfig).
		on("valuechanged", function(event) {
			setMinOrMaxDate(false, event.args.date);
		});

	// Create the data table (grid) but without binding a data source
	// and without configuring the columns
	$("#jqxgrid").jqxGrid(getGridConfig(true));

	// Initialise the chart but without any data
	initChart();

	// Initialise the buttons foradding/downloading data
	initButtons();

	// Initialise the popup windows
	initPopups();
});

//End of Document Ready Function

//Function to get dates and plot a default plot
function get_dates(siteID, variableID, methodID, callback)
{
	$.ajax({
		type: "GET",
		url: toURL("series/getDateJSON", {
			siteid: siteID,
			varid: variableID,
			methodid: methodID
		}),
		dataType: "json",
		success: callback
	});
}

// Adding a Unit Fetcher! Author : Rohit Khattar ChangeDate : 11/4/2013
// Send out an ajax request to get a unit for a given VariableID and call
// the given callback function with the returned unit when the request ist done
function getUnit(variableID, callback)
{
	$.ajax({
		type: "GET",
		dataType: "json",
		url: toURL("variable/getUnit", {
			varid: variableID
		})
	}).
	done(function(units) {
		// Call the callback function with the unit that was returned
		callback(units[0].unitA);
	});
}

function getDataURL(json)
{
	var endpoint = (json ? 'datapoint/getDataJSON' : 'datapoint/getData');

	var parameters = {
		siteid: globals.siteID,
		varid: globals.variableID,
		meth: globals.methodID,
		startdate: globals.date_from_sql,
		enddate: globals.date_to_sql
	};

	return toURL(endpoint, parameters, true);
}

/*
function getDataAsScript(callback)
{
	$.ajax({
		url: getDataURL(false),
		type: "GET",
		dataType: "script"
	}).
	done(function() {
		callback();
	});
}
*/

function gridDataToSeriesData(data)
{
	var seriesData = [];
	var time;
	var dataValue;

	for (var i = 0; i < data.length; i++) {
		localDateTime = data[i].LocalDateTime;

		// Number of milliseconds since 1970-01-01, according to universal time
		time_ms = localDateTime.getTime();
		
		dataValue = data[i].DataValue;

		seriesData[i] = [time_ms, dataValue];
	}

	return seriesData;
}

function sortByFirstColumn(data)
{
	return data.sort(function (a, b) {
		if (a[0] === b[0]) {
			return 0;
		}
		else {
			return (a[0] < b[0]) ? -1 : 1;
		}
	});
}

function updateGridAndPlot(unit)
{
	var $grid = $("#jqxgrid");
	var $tabs = $('#jqxtabs');
	var eventname = 'bindingcomplete';
	var config;

	// Update data source and column configuration of the grid
	config = getGridConfigUpdate(unit);
	$grid.jqxGrid(config);

	// Rebind the bindingComplete event
	$grid.unbind(eventname);
	$grid.bind(eventname, function(event) {

		// Get the data from the grid
		var data = $grid.jqxGrid('getrows');

		// Recreate the chart object. Destroy the current chart if there is any.
		if (typeof globals.chart !== 'undefined') {

			globals.chart.destroy();
		}

		// Create a new chart object
		setGlobal('chart', newChart(data, unit));

		// Enable the grid tab and the plot tab
		$tabs.jqxTabs('enableAt', 2);
		$tabs.jqxTabs('enableAt', 1);
	});
}

function newChart(griddata, unit)
{
	var config = getStockChartConfig('de');
	var configUpdate;
	var labels = undefined;
	var dataseries = undefined;

	if (typeof griddata !== 'undefined') {

		labels = {
			title: getTitle(DATA.text, globals.dateFrom, globals.dateTo),
			yAxis: unit
		};

		dataseries = {
			data: sortByFirstColumn(gridDataToSeriesData(griddata)),
			name: globals.variableAndType
		};
	}

	// Create the variable parts of the configuration
	configUpdate = getChartConfigUpdate(DATA.text, labels, dataseries);

	// Use deep extension (recursive copy)
	config = jQuery.extend(true, config, configUpdate);

	return new Highcharts.StockChart(config);
}

function getTitle(texts, dateFrom, dateTo)
{
	var titleParts = [
		texts.Dataof, texts.SiteName,
		texts.From, toLocaleDateString_HH_MM(dateFrom),
		texts.To, toLocaleDateString_HH_MM(dateTo)
	];

	return titleParts.join(' ');
}

function getChartConfigUpdate(texts, labels, dataseries)
{
	// Define defaults
	var defaults = {
		labels: {
			title: '<title>',
			subtitle: texts.ClickDrag,
			xAxis: texts.TimeMsg,
			yAxis: '<y-axis>'
		},
		dataseries: {
			data: [0, 100, 0],
			name: "dummy series"
		}
	}

	// Use the defaults labels where no labels are given
	labels = (typeof labels === 'undefined') ?
		defaults.labels :
		jQuery.extend(defaults.labels, labels);

	// Use the default series if not dataseries is given
	if (typeof dataseries === 'undefined') {
		dataseries = defaults.dataseries;
	}

	return {
		chart: {
			renderTo: 'container'
		},
		title: {
			text: labels.title,
			style: { fontSize: '12px' }
		},
		subtitle: {
			text: labels.subtitle
		},
		rangeSelector: {
			buttons: getRangeSelectorButtonConfig(texts),
			selected: 6
		},
		xAxis: {
			title: {
				text: labels.xAxis
				//, margin: 30
			}
		},
		yAxis: {
			title: {
				text: labels.yAxis
				//, margin: 40
			}
		},
		series: [
			dataseries
		],
	};
}

function initChart()
{
	Highcharts.setOptions({
		global: {
			useUTC: false
		}
	});

	// Create a new chart object and save the reference in a global variable
	setGlobal('chart', newChart());
}

function initPopups()
{
	// Initialize the popup windows and the buttons within the windows

	// Popup window 1: Edit/Save/Delete existing values
	$("#popupWindow").jqxWindow(jQuery.extend(
		popupWindowConfig, {cancelButton: $("#Cancel")}
	));

	// Time picker
	//$("#timepicker").
	//	timepicker(timePickerConfig);

	// Button "Delete"
	$("#delval").
		jqxButton(buttonConfigBase).
		unbind("click"). //Multiple events are getting binded for some reason. This makes sure that doesn't happen. 
		click(delValClickHandler);

	// Button "Cancel"
	$("#Cancel").
		jqxButton(buttonConfigBase);

	// Button "Save"
	$("#Save").
		jqxButton(buttonConfigBase).
		unbind("click").
		bind('click', saveClickHandler);

	// Popup window 2: Add new values
	var config = jQuery.extend(
		popupWindowConfig, 
		{ cancelButton: $("#Cancel_new") }
	);

	$("#popupWindow_new").
		jqxWindow(config).
		jqxWindow('hide');

	// Button "Cancel"
	$("#Cancel_new")
		.jqxButton(buttonConfigBase);

	// Button "Save"
	$("#Save_new").
		jqxButton(buttonConfigBase).
		unbind("click").
		bind('click', saveNewClickHandler);

	$("#date_new").
		jqxDateTimeInput(getDateInputConfig(2));

	//$("#timepicker_new" ).
	//	timepicker(timePickerConfig);

}

function initButtons()
{
	// Add new values

<?php 
	if (isLoggedIn()) {
		echo "$(\"#addnew\").jqxButton(buttonConfig)." . 
			"bind('click', addValueClickHandler)\n";
	}
?>

	//Export Button
	$("#export").jqxButton(buttonConfig);
	$("#export").bind('click', exportClickHandler);
}

function initComparison()
{
	$('#window').jqxWindow('destroy');
	$('#mapOuter').empty();

	// Create and hide windows
	$('#window').jqxWindow(windowConfig).jqxWindow('hide');
	$('#window2').jqxWindow(windowConfig2).jqxWindow('hide');
	$('#window3').jqxWindow(windowConfig2).jqxWindow('hide');
	$('#window4').jqxWindow(windowConfig2).jqxWindow('hide');
	$('#window5').jqxWindow(windowConfig5).jqxWindow('hide');

	// Define the button for comparison
	$("#compare").
		jqxButton(buttonConfig).
		click(compareClickHandler);

	// Now Map Loaded. Another Function to open up a new window that will give
	// them options to select the data to be plotted against the existing data
}

</script>

<!-- 
#
# End of JavaScript 
#
-->

<STYLE type="text/css">
	.button a:link    { color: #FFF; text-decoration: none}
	.button a:visited { color: #FFF; text-decoration: none}
	.button a:hover   { color: #FFF; text-decoration: none}
	.button a:active  { color: #FFF; text-decoration: none}
</STYLE>

<?php 

//
// Helper functions to generate parts of the HTML output
//

function html_daterange_row()
{
	$html = html_div_beg('row');
	$html .=   html_div_beg('col-md-6');
	$html .=     html_div_beg('', 'jqxDateTimeInput') . html_div_end();
	$html .=   html_div_end();
	$html .=   html_div_beg('col-md-6');
	$html .=     html_div_beg('', 'jqxDateTimeInputto') . html_div_end();
	$html .=   html_div_end();
	$html .= html_div_end();
	$html .= br();

	return $html;
}

function html_ul_for_tabs()
{
	$html = "<ul style='margin-left: 20px;'>";
	$html .= "<li>" . getTxt('SiteInfo') . "</li>";
	$html .= "<li>" . getTxt('DataPlot') . "</li>";
	$html .= "<li>" . getTxt('DataTable') . "</li>";
	$html .= "</ul>";

	return $html;
}

function html_picture_area($picture, $siteID)
{
	if ($picture == null) {

		$html = br(2) . getTxt('NoImages');

		if (isLoggedIn()) {
			$html .= "<a href='" . site_url('sites/edit/' . $siteID) . "'> " .
				getTxt('ClickHere') . " </a>";
		}
	}
	else {
		$html = br(2) . "<img src='" . getDetailsImg('' . $picture) .
			"' width='368' height='250' />";
	}

	return $html;
}

function nonEmptyElements($elements)
{
	return array_filter($elements, function($element) {
		return ($element != "");
	});
}

function rows_for_values_table($data)
{
	//$id_timepicker = $data['id_timepicker'];
	$id_value = $data['id_value'];

	//$onChange = "onChange=\"validatetime('$(#$id_timepicker)')\"";

	$onBlur = "onBlur=\"validatenum($('#$id_value'))\"";

	$style = 'style="margin-right: 5px;"';

	return array(

		// row 1
		'<td colspan="2">' . getTxt($data['caption']) . '</td>',

		// row 2
		html_td_right(getTxt('Date')) .
		html_td_left('<div id="' . $data['id_date']. '">' . html_div_end()),

		// row 3
//		html_td_right(getTxt('Time')) .
//		html_td_left(
//			html_input(
//				$data['id_timepicker'],
//				'type="text" name="' . $data['id_timepicker'] . '" ' . 
//					$onChange . ' size="10"'
//			)
//		),

		// row 4
		html_td_right(getTxt('Value')) .
		html_td_left(html_input($data['id_value'], $onBlur)),

		// row 5
		html_td_right() .
		html_td_right(
			html_input_button($data['id_save'], getTxt('Save'), $style) .
			$data['button_delete'] .
			html_input_button($data['id_cancel'], getTxt('Cancel')),
			'style="padding-top: 10px;"'
		)
	);
}

function html_enter_values_div($rows)
{
	$empty_row = html_tr("<td>&nbsp;</td><td>&nbsp;</td>");

	$html  = "<div style=\"overflow: hidden;\">\n";
	$html .= "  <table>\n";
	$html .= implode($empty_row, array_map("html_tr", $rows));
	$html .= "  </table>\n";
	$html .= html_div_end();

	return $html;
}

function div_window($number = '')
{
	$html  = "<div id=\"window" . $number . "\">\n";
	$html .= "  <div id=\"window" . $number . "Header\">\n";
	$html .= "    " . html_span('', getTxt('CompareTwo')) . "\n";
	$html .= html_div_end(1);
	$html .= "  <div style=\"overflow: hidden;\" id=\"window" . 
		$number . "Content\">" . html_div_end();
	$html .= html_div_end() . "\n";

	return $html;
}

HTML_Render_Body_Start();

echo html_div_beg("col-md-9");

//possibly a future improvement. The sites could be accessed here in 
//addition to navigating back to the map 

echo html_div_beg('row');
echo html_formGroup_begin('Site');
echo $site['SiteName'] . "\n";
echo html_formGroup_end();
echo html_div_end(); // end of row

echo html_div_beg('row');
genDropLists('Variable', 'dropdownlist', 'dropdownlist', false) . br();
echo html_div_end();

//The type is already selected when the Variable is selected!
//echo html_div_beg('row');
//genDropLists('Type','typelist', 'typelist', false) . br();
//echo html_div_end();

echo html_div_beg('row');
genDropLists('Method', 'methodlist', 'methodlist', false) . br();
echo html_div_end();

echo html_div_beg('', 'daterange') . html_div_end();

echo html_daterange_row();

echo html_div_beg('', 'jqxtabs');

echo html_ul_for_tabs();

echo html_div_beg();

echo html_b(getTxt('Site')) . $site['SiteName'] . br();

echo html_picture_area($site['picname'], $SiteID);

echo(
	br(2) . 
	html_b(getTxt('Type'        )) .translateTerm($site['SiteType']) . br(2) .
	html_b(getTxt('Latitude'    )) . $site['Latitude' ] . br(2) .
	html_b(getTxt('Longitude'   )) . $site['Longitude'] . br(3) .
	html_b(getTxt('Measurements'))
);

echo implode("; ", nonEmptyElements(array_column($Variables, 'VariableName')));

echo br(2);

echo getTxt('WrongSite');
echo '<a href="' . site_url('sites/map') .'" style="color:#00F"> ' .
	getTxt('Here') . '</a> ';
echo getTxt('GoBack');
echo html_div_end();

echo encloseInBeginEndComments(
	html_div_beg() .
	"  " . html_div_beg("chart-wrapper") .
	"    " . html_div_beg("chart-inner") .
	"      <div id=\"container\" style=\"width:100%; height: 470px;\">" . html_div_end(3) .
	"      <!-- Button to compare data values-->\n" .
	"      " . html_input_button('compare', getTxt('Compare'), 'style=" float:right"') .
	"    " . html_div_end() .
	"  " . html_div_end() .
	html_div_end(),
	"of Chart DIV"
);

echo html_div_beg();

echo html_div_beg('', 'jqxgrid') . html_div_end();

echo encloseInBeginEndComments(
	html_div_beg('', 'popupWindow') .
		'<div>' . getTxt('Edit') . html_div_end() .
		html_enter_values_div(rows_for_values_table(array(
			'caption' => 'ChangeValues',
			'id_date' => 'date',
//			'id_timepicker' => 'timepicker',
			'id_value' => 'value',
			'id_save' => 'Save',
			'id_cancel' => 'Cancel',
			'button_delete' => html_input_button("delval", getTxt('Delete'))
		))) .
	html_div_end(),
	"of DIV #popupWindow (change values)"
);

echo '<div style="alignment-adjust: middle; float:right;">';

if (isLoggedIn()) {
	echo html_input_button('addnew', getTxt('AddRow')) . br(2);
}

echo html_input_button('export', getTxt('DownloadData'));

echo html_div_end();

echo html_div_end();

echo "<!-- End Of Grid Div.  -->\n";

echo html_div_end();

echo "<!-- Jqx Tabs end -->\n";

echo html_div_end();

echo encloseInBeginEndComments(
	html_div_beg('', 'popupWindow_new') .
		'<div>' . getTxt('Add') . html_div_end() .
		html_enter_values_div(rows_for_values_table(array(
			'caption' => 'EnterValues',
			'id_date' => 'date_new',
//			'id_timepicker' => 'timepicker_new',
			'id_value' => 'value_new',
			'id_save' => 'Save_new',
			'id_cancel' => 'Cancel_new',
			'button_delete' => ''
		))) .
	html_div_end(),
	"of DIV #popupWindow_new (enter new values)"
);

echo br();

echo html_div_end();

echo html_div_end();

echo html_div_end();

echo html_div_end();

echo div_window();
echo div_window('2');
echo div_window('3');
echo div_window('4');
echo div_window('5');

HTML_Render_Body_End();

?>
