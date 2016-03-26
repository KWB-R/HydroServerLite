<?php
header('Content-Type: text/html; charset=utf-8');
HTML_Render_Head($js_vars, getTxt('SearchData'));
echo $JS_JQuery;
echo $JS_JQueryUI;
echo $JS_JQX;
echo $JS_GetTheme;
echo $JS_Globalization; // this is the only page that calls this. This is also the only refernce to MooTools
echo $JS_Maps;
echo $CSS_JQX;
echo $CSS_Main;
echo $CSS_JQuery_UI;
echo $CSS_JQStyles;
?>

<!--Main Script to display the data-->
<script type="text/javascript">

//
// Define Global Variables
//

var glob_df;
var glob_dt;
var date_to;
var date_from;
var date_select_to;
var date_select_from;
var varid;
var date_from_sql;
var date_to_sql;
var varname;
var datatype;
var displayType;
var sitename;
var flag = 0;
var methodid;
var chart = "";
var displayVar;

//
// Helper functions (should be moved to some external script in my opinion)
//

function formatDateSQL(date, month, minutes)
{
	// Set default values if month or minutes are undefined
	month = month || (date.getMonth()+1);
	minutes = minutes || ' 00:00:00';

	return date.getFullYear() + '-' + 
		add_zero(month) + '-' + 
		add_zero(date.getDate()) + minutes;
}

function formatDate(date)
{
	return add_zero((date.getMonth() + 1)) + '/' + 
		add_zero( date.getDate()) + '/' + 
		date.getFullYear();
}

function add_zero(value)
{
	if (value < 10) {
		value = '0' + value;
	}

	return value;
}

function timeconvert(timestamp, useTime)
{
	// set default of useTime to true
	useTime = useTime || true;

	var year   = parseInt(timestamp.slice( 0,  4), 10);
	var month  = parseInt(timestamp.slice( 5,  7), 10);
	var day    = parseInt(timestamp.slice( 8, 10), 10);
	var hour   = (useTime ? parseInt(timestamp.slice(11, 13), 10) : 0);
	var minute = (useTime ? parseInt(timestamp.slice(14, 16), 10) : 0);
	var sec    = (useTime ? parseInt(timestamp.slice(17, 19), 10) : 0);

	return new Date(year, month - 1, day, hour, minute, sec);
}

function toDate(datestring)
{
	var parts = datestring.split('-');

	return new Date(parts[0], parts[1] - 1, parts[2]);
}

function toHourAndMinute(timestring)
{
	var parts = timestring.split(':');

	return parts[0] + ':' + parts[1];
}

function splitTimeString(timestring)
{
	var parts = timestring.split(":");

	return {
		hour: parts[0],
		minute: parts[1]
	};
}

//The trimAllSpace() function will remove any extra spaces
function trimAllSpace(str) 
{
	return str.replace(/ /g, '');
}

function IsNumeric(str)
{
	// test if str consists of only numbers or the colon
	var pattern = /^[0-9:]+$/;

	if (! pattern.test(str)) {
		alert (DATA.text.InvalidCharacterNumbers);
	}

	// remove non-numeric or colon characters
	return str.replace(/[^0-9:]/g, '');
}

//
// Define all (translated) message texts beforehand
//

var DATA = {
	siteid:<?php echo $SiteID;?>,
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
		echo "$name: \"" . getTxt($name) . "\",\n";
	}
?>
		SiteName: "<?php echo $site['SiteName'];?>",
		ValueID: "<?php echo str_replace(':', ' ID', getTxt('Value'));?>",
		Value: "<?php echo str_replace(':', '', getTxt('Value'));?>"
	}
};

//
// Define the configurations of the controls beforehand
//

var windowConfig = {
	maxHeight: 800,
	maxWidth: 800,
	minHeight: 200,
	minWidth: 200,
	height: 520,
	width: 720,
	theme: 'darkblue'
};

var windowConfig2 = {
	maxHeight: 100,
	maxWidth: 350,
	minHeight: 100,
	minWidth: 350,
	height: 100,
	width: 350,
	theme: 'darkblue'
};

var windowConfig5 = {
	maxHeight: 300,
	maxWidth: 650,
	minHeight: 300,
	minWidth: 650,
	height: 300,
	width: 650,
	theme: 'darkblue'
};

var popupWindowConfigBase = {
	width: 300,
	height: 350,
	resizable: false,
	theme: 'darkblue',
	isModal: true,
	autoOpen: false,
	modalOpacity: 0.01
};

var popupWindowConfig = jQuery.extend(
	popupWindowConfigBase, {cancelButton: $("#Cancel")}
);

var popupWindowNewConfig = jQuery.extend(
	popupWindowConfig, {cancelButton: $("#Cancel_new")}
);

var buttonConfigBase = {theme: 'darkblue'};
var buttonConfig     = {theme: 'darkblue', width: '250', height: '25'};

var dateInputConfig = {width: '100%', height: '25px', theme: 'darkblue', formatString: 'd'};
var dateDropConfig  = {width: '100%', height: 25, theme: 'darkblue'};

function getStockChartConfig(
	date_chart_from,
	date_chart_to,
	unit_yaxis,
	data_test,
	displayVar,
	displayType) 
{
	return {
		chart: {renderTo: 'container', zoomType: 'x'},
		legend: {
			verticalAlign: 'top', enabled: true, shadow: true, y: 40,
			margin: 50
		},
		title: {
			text: DATA.text.Dataof + " " + DATA.text.SiteName + " " +
						DATA.text.From   + " " + date_chart_from + " " +
						DATA.text.To     + " " + date_chart_to,
			style: {
				fontSize: '12px'
			}
		},
		credits: {enabled: false},
		subtitle: {text: DATA.text.ClickDrag},
		xAxis: {
			type: 'datetime',
			dateTimeLabelFormats: { // don't display the dummy year
				month: '%e.%b / %Y',
				year: '%b.%Y'
			},
			title: {text: DATA.text.TimeMsg, margin: 30}
		},
		yAxis: {
			title: {text: unit_yaxis, margin: 40}
		},
		exporting: {enabled: true, width: 5000},
		rangeSelector: {
			buttons: [
				{type: 'day',   count: 1, text: DATA.text.OneD},
				{type: 'day',   count: 3, text: DATA.text.ThreeD},
				{type: 'week',  count: 1, text: DATA.text.OneW},
				{type: 'month', count: 1, text: DATA.text.OneM},
				{type: 'month', count: 6, text: DATA.text.SixM},
				{type: 'year',  count: 1, text: DATA.text.OneY},
				{type: 'all', text: DATA.text.All}
			],
			selected: 6
		},
		series: [
			{data: data_test, name: displayVar +'(' + displayType + ')'}
		]
	};
} // end of getStockChartConfig()

function getGridConfig(dataAdapter, unitGrid)
{
	return {
		source: dataAdapter,
		width: '100%',
		columnsresize: true,
		columns: [
			{text: DATA.text.ValueID, datafield: 'ValueID'},
			{text: DATA.text.Date, datafield: 'LocalDateTime'},
			{text: DATA.text.Value + ' (' + unitGrid + ')' , datafield: 'DataValue'}
	<?php
	if (isLoggedIn()) {
		echo(",
			{
				text: 'Edit',
				datafield: 'Edit',
				columntype: 'button',
				cellsrenderer: function () {
					return 'Edit';
				},
				buttonclick: function (row) {

					// open the popup window when the user clicks a button.
					editrow = row;

					var offset = $('#jqxgrid').offset();

					$('#popupWindow').jqxWindow({
						position: {
							x: parseInt(offset.left, 10) + 220,
							y: parseInt(offset.top,  10) +  60
						}
					});

					// get the clicked row's data and initialize the input fields.
					var dataRecord = $('#jqxgrid').jqxGrid('getrowdata', editrow);

					//Create a Date time Input
					var datepart = dataRecord.LocalDateTime.split(' ');

					$('#popupWindow').jqxWindow('show');
					// $('#date').val(datepart[0]);

					$('#date').jqxDateTimeInput({
							width: '125px',
							height: '25px',
							theme: 'darkblue',
							formatString: 'MM/dd/yyyy',
							textAlign: 'center'
						}).jqxDateTimeInput('setDate', toDate(datepart[0]));

					$('#timepicker').val(toHourAndMinute(datepart[1]));
					//$('#timepicker').timepicker('setTime', toHourAndMinute(datepart[1]))

					$('#value').val(dataRecord.DataValue);
					vid = dataRecord.ValueID;

					// show the popup window.
				} // end of buttonclick function
			}"
		); // end of echo
	} // end of isLoggedIn()
	?>
		] // end of columns array
	};
} // end of getGridConfig()

function getColumnsConfig(unitGrid)
{
	return [
		{
			text: DATA.text.ValueID,
			datafield: 'ValueID'
		},
		{
			text: DATA.text.Date,
			datafield: 'LocalDateTime'
		},
		{
			text: DATA.text.Value + ' (' + unitGrid + ')',
			datafield: 'DataValue'
		}
<?php
	if (isLoggedIn()) {
		echo(",
			{
				text: 'Edit',
				datafield: 'Edit',
				columntype: 'button',
				cellsrenderer: function () {
					return 'Edit';
				},
				buttonclick: function (row) {

					// open the popup window when the user clicks a button.
					editrow = row;

					var offset = $('#jqxgrid').offset();

					$('#popupWindow').jqxWindow({
						position: {
							x: parseInt(offset.left, 10) + 220, 
							y: parseInt(offset.top,  10) +  60
						}
					});

					// get the clicked row's data and initialize the input fields.
					var dataRecord = $('#jqxgrid').jqxGrid('getrowdata', editrow);

					//Create a Date time Input
					$('#popupWindow').jqxWindow('show');

					var datepart = dataRecord.LocalDateTime.split(' ');
					// $('#date').val(datepart[0]);

					$('#date').jqxDateTimeInput({
							width: '125px',
							height: '25px',
							theme: 'darkblue',
							formatString: 'MM/dd/yyyy',
							textAlign: 'center'
						}).jqxDateTimeInput('setDate', toDate(datepart[0]));

					$('#timepicker').val(toHourAndMinute(datepart[1]));
					//$('#timepicker').timepicker('setTime', toHourAndMinute(datepart[1]))

					$('#value').val(dataRecord.DataValue);

					vid = dataRecord.ValueID;

					// show the popup window.
				} // end of buttonclick function
			}"
		); // end of echo()
	} // end of if (IsLoggedIn())
?>
	];
} // end of getColumnsConfig()

function getGridConfig2(dataAdapter, columnsConfig)
{
	return {
		width: '100%',
		source: dataAdapter,
		theme: 'darkblue',
		columnsresize: true,
		sortable: true,
		pageable: true,
		autoheight: true,
		editable: false,
		selectionmode: 'singlecell',
		columns: columnsConfig
	};
} // end of getGridConfig2()

//Time Validation Script General
function validatetime(idString)
{
	//Removing all space
	var strval = trimAllSpace($(idString).val());

	//alert("validatetime(" + idString + "): >" + strval + "<")

	$(idString).val(strval);

	//Minimum and maximum length is 5, for example, 01:20
	if (strval.length != 5) {
		alert(DATA.text.InvalidTimeFive);
		return false;
	}

	//Split the string
	var timeparts = splitTimeString(strval);

	//Checking hours

	//minimum length for hours is two digits, for example, 12
	if (timeparts.hour.length != 2) {
		alert(DATA.text.InvalidTimeHoursTwo);
		return false;
	}

	if (timeparts.hour < 0 || timeparts.hour > 23) {
		alert(timeparts.hour < 0 ?
			DATA.text.InvalidTimeHoursZeros :
			DATA.text.InvalidTimeHoursTwentyThree);
		return false;
	}

	//Checking minutes

	//minimum length for minutes is 2, for example, 59
	if (timeparts.minute.length != 2) {
		alert(DATA.text.InvalidTimeMinutesTwo);
		return false;
	} 

	if (timeparts.minute < 0 || timeparts.minute > 59) {
		alert(timeparts.minute < 0 ?
			DATA.text.InvalidTimeMinutesZeros :
			DATA.text.InvalidTimeMinutesFiftyNine);
		return false;
	}

	$(idString).val(IsNumeric(strval));

	return true;
}

//Time Validation Script Ends

//Number validation script
function validatenum(idSelector)
{
	return isValidNumber($(idSelector).val());
}

function isValidNumber(val)
{
	if (val === null || val.length === 0) {
		alert(DATA.text.EnterNumberValue);
		return false;
	}

	var DecimalFound = false;

	for (var i = 0; i < val.length; i++) {

		var ch = val.charAt(i);

		if (i === 0 && ch === "-") {
			continue;
		}

		if (ch === "." && !DecimalFound) {
			DecimalFound = true;
			continue;
		}

		if (ch < "0" || ch > "9") {
			alert(DATA.text.EnterValidNumberValue);
			return false;
		}
	}

	return true;
}

//Number Validation script ends

//Defining the Data adapter for the variable list
var variablesAdapter = new $.jqx.dataAdapter({
	datatype: "json",
	datafields: [
		{ name: 'VariableID' },
		{ name: 'VariableName' }
	],
	url: base_url + 'variable/getSiteJSON?siteid=' + DATA.siteid
});

//Defining the Data adapter for the data types
function getTypesAdapter(varname)
{
	return new $.jqx.dataAdapter({
		datatype: "json",
		datafields: [
			{name: 'DataType'},
			{name: 'display'}
		],
		url: base_url + 'variable/getTypes?siteid=' + DATA.siteid +
			'&varname=' + varname
	});
}

//Defining the Data adapter for the methods
function getMethodsAdapter(varid)
{
	return new $.jqx.dataAdapter({
		datatype: "json",
		datafields: [
			{ name: 'MethodID' },
			{ name: 'MethodDescription' }
		],
		url: base_url + 'methods/getSiteVarJSON?siteid=' + DATA.siteid +
			'&varid=' + varid
	});
}

function variableSelectHandler(event)
{
	var item = $('#dropdownlist').jqxDropDownList('getItem', event.args.index);

	//Check if a valid value is selected and process futher to display dates
	if (item !== null) {
		//Clear the Box
		$('#daterange').html("");

		varname = item.value;
		displayVar = item.label;

		//Going to the next function that will generate a list of data types available for that variable
		var t = setTimeout("create_var_list()", 300);
	}
}

function typeSelectHandler(event)
{
	var item = $('#typelist').jqxDropDownList('getItem', event.args.index);

	//Check if a valid value is selected and process futher to display dates
	if (item !== null) {
		datatype = item.value;
		displayType = item.label;
		update_var_id();
	}
}

function methodSelectHandler(event)
{
	var item = $('#methodlist').jqxDropDownList('getItem', event.args.index);

	//Check if a valid value is selected and process futher to display dates
	if (item !== null) {
		methodid = item.value;
		get_dates();
		//Now call to check dates
	}
}

function dateChangedHandler(event)
{
	glob_df = new Date(event.args.date);

	//Setting the Second calendar's min date to be the date of the first calendar
	//$("#jqxDateTimeInputto").jqxDateTimeInput('setMinDate', event.args.date);

	$("#fromdatedrop").jqxDropDownButton('setContent', formatDate(glob_df));

	//Converting to SQL Format for Searching
	var date_sql = formatDateSQL(glob_df);

	if (date_from_sql != date_sql) {
		date_from_sql = date_sql;
		plot_chart();
	}
}

function dateToChangedHandler(event)
{
	glob_dt = new Date(event.args.date);

	$("#todatedrop").jqxDropDownButton('setContent', formatDate(glob_dt));

	date_to_sql = formatDateSQL(glob_dt);

	plot_chart();
}

function ajaxSuccessHandler(result)
{
	//Displaying the Available Dates
	date_from = String(result.BeginDateTime);
	date_to   = String(result.EndDateTime);

	//Call the next function to display the data
	$('#daterange').html("").prepend(
			'<p>' + 
			'<strong>' + DATA.text.DatesAvailable + '</strong> ' + date_from + 
			'<strong>' + DATA.text.To + '</strong> ' + date_to +
			'</p>');

	$("#jqxDateTimeInput").
		jqxDateTimeInput(dateInputConfig).
		off(). // Reset the bind functions
		unbind('valuechanged');

	$("#jqxDateTimeInputto").
		jqxDateTimeInput(dateInputConfig).
		off(). // reset the bind functions
		unbind('valuechanged');

	//Restricting the Calendar to those available dates

	// Convert to Date object without using the time information
	glob_df = timeconvert(date_from, false);

	$("#fromdatedrop").jqxDropDownButton(dateDropConfig);
	$("#todatedrop"  ).jqxDropDownButton(dateDropConfig);

	//Use Show And Hide Method instead of repeating formation - optimization number 2

	//$('#jqxDateTimeInput').jqxDateTimeInput('setDate', glob_df);
	//$("#jqxDateTimeInput").jqxDateTimeInput('setMinDate', new Date(year, month - 1, day));

	// Convert to Date object without using the time information
	glob_dt = timeconvert(date_to, false);

	//$('#jqxDateTimeInputto').jqxDateTimeInput('setDate', glob_dt);
	//$("#jqxDateTimeInput").jqxDateTimeInput('setMaxDate', new Date(year_to, month_to - 1, day_to)); 
	//$("#jqxDateTimeInputto").jqxDateTimeInput('setMaxDate', new Date(year_to, month_to - 1, day_to)); 

	//Plot the Chart with default limits

	//If the month is 0 or 13 it causes issues. We need to keep it between 1 and 12. 

	var monthBegin = glob_df.getMonth();

	if (monthBegin === 0) {
		monthBegin = 1;
	}

	var monthEnd = glob_dt.getMonth() + 2;

	if (monthEnd > 12) {
		monthEnd = 12;
	}

	date_from_sql = formatDateSQL(glob_df, monthBegin);
	date_to_sql   = formatDateSql(glob_dt, monthEnd);

	$("#fromdatedrop").jqxDropDownButton('setContent', DATA.text.SelectStart);
	$("#todatedrop"  ).jqxDropDownButton('setContent', DATA.text.SelectEnd);

	plot_chart();

	//Binding An Event to the first calender
	$('#jqxDateTimeInput').bind('valuechanged', dateChangedHandler);

	//Binding An Event To the Second Calendar
	$('#jqxDateTimeInputto').bind('valuechanged', dateToChangedHandler);
}
// end of ajaxSuccessHandler()

function delValClickHandler()
{
	//Send out a delete request
	$.ajax({
		dataType: "json",
		url: base_url+"datapoint/delete/" + vid
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
	if (editrow >= 0) {

		var seldate= $('#date').jqxDateTimeInput('getDate');

		var row = {
			date: formatDateSQL(seldate, undefined, ' ' + $("#timepicker").val() + ':00'),
			Value: $("#value").val(),
			vid: vid
		};

		// Validate value and time
		if (
			validatenum("#value") === false || 
			validatetime("#timepicker") === false) {
			return false;
		}

		var vt = $("#value").val();

		//Send out an ajax request to update that data field
		$.ajax({
			dataType: "json",
			url: base_url + "datapoint/edit/" + vid +
				"?val=" + vt +
				"&dt=" + formatDateSQL(seldate, undefined, '') +
				"&time=" + $("#timepicker").val()
		}).
		done(function(msg) {
			if (msg.status == 'success') {
				$('#jqxgrid').jqxGrid('updaterow', editrow, row);
				$("#popupWindow").jqxWindow('hide');
				plot_chart();
				return true;
			}
			else {
				alert(msg);
				return false;
			}
		});
	} // end of if (editrow >= 0)

	return true;
} // end of saveClickHandler()

function saveNewClickHandler()
{
	// Validate value and time
	if (
		validatenum("#value_new") === false ||
		validatetime("#timepicker_new") === false) {
		return false;
	}

	var vt = $("#value_new").val();

	var seldate= $('#date_new').jqxDateTimeInput('getDate');

	//Send out ajax request to add new value

	$.ajax({
		dataType: "json",
		url: base_url + "datapoint/add?varid=" + varid + 
			"&val=" + vt + 
			"&dt=" + formatDateSQL(seldate, undefined, '') +
			"&time=" + $("#timepicker_new").val()+
			"&sid=" + DATA.siteid +
			"&mid=" + methodid
	}).
	done(function(msg) {
		if (msg.status == 'success') {
			$("#popupWindow_new").jqxWindow('hide');
			plot_chart();
			return true;
		}
		else {
			alert(DATA.text.DatabaseConfigurationError);
			return false;
		}
	});

	return true;
} // end of saveNewClickHandler()

function compareClickHandler()
{
	$("html, body").animate({scrollTop: 0}, "slow");
	$('#window').jqxWindow('show');
	$('#windowContent').load(base_url + 'datapoint/compare/1', function() {});
} // end of compareClickHandler()

function exportClickHandler()
{
	var url = base_url + 'datapoint/export?siteid=' + DATA.siteid +
		'&varid=' + varid +
		'&meth=' + methodid +
		'&startdate=' + date_from_sql +
		'&enddate=' + date_to_sql;

	window.open(url, '_blank');
}

//Populate the Drop Down list with values from the JSON output of the php page

$(document).ready(function() {

	alert("document ready");

	// There is no such element with id "loadingtext"
	//$("#loadingtext").hide();

	//Create date selectors and hide them

	//Create Tabs for Table Chart Switching

	$tabs = $('#jqxtabs');

	$tabs.jqxTabs({
		width:'100%',
		height: 550,
		theme: 'darkblue',
		collapsible: true
	});

	$tabs.jqxTabs('disable');

	$tabs.jqxTabs('enableAt', $tabs.jqxTabs('selectedItem'));

//		.jqxTabs.enableAt($tabs.jqxTabs('selectedItem'))

	$tabs.on('selected', function (event) {
			if (event.args.item == 1) {
				$(window).resize();
			}
	});

	//Creating the Variables Drop Down list
	$("#dropdownlist").
		jqxDropDownList({
			source: variablesAdapter,
			theme: 'darkblue',
			height: 25,
			width: "100%",
			selectedIndex: 0,
			displayMember: 'VariableName',
			valueMember: 'VariableID'
		}).
		bind('select', variableSelectHandler);
});

//End of Document Ready Function

function create_var_list()
{
	//Generate data types available for that varname
	//Creating the Drop Down list
	$("#typelist").
		jqxDropDownList({
			source: getTypesAdapter(varname),
			theme: 'darkblue',
			height: 25,
			width: "100%",
			selectedIndex: 0,
			displayMember: 'display',
			valueMember: 'DataType'
		}).
		//Binding an Event in case of Selection of Drop Down List to update the varid according to the selection
		bind('select', typeSelectHandler).
		jqxDropDownList('selectIndex', 0);
}
// End of create_var_list()

function update_var_id()
{
	$.ajax({
		type: "GET",
		url: base_url + "variable/updateVarID?siteid=" + DATA.siteid +
			"&varname=" + varname + "&type=" + datatype,
		//Processing The Dates
		success: function(data) {
			varid = data;
			//Now We have the VariableID, We call the dates function
			//Filter by methods available for that specific selection of variable and site
			get_methods();
		}
	});
}

//Function to get dates and plot a default plot

function get_methods()
{
	$('#methodlist').
		off().
		unbind('valuechanged').
		//Creating the Drop Down list
		jqxDropDownList({
			source: getMethodsAdapter(varid),
			theme: 'darkblue',
			height: 25,
			width: "100%",
			selectedIndex: 0,
			displayMember: 'MethodDescription',
			valueMember: 'MethodID'
		}).
		jqxDropDownList('selectIndex', 0).
		//Binding an Event in case of Selection of Drop Down List to update the varid according to the selection
		bind('select', methodSelectHandler);
}

function get_dates()
{
	var url = base_url + "series/getDateJSON?siteid=" + DATA.siteid +
		"&varid=" + varid+"&methodid=" + methodid;

	$.ajax({
		type: "GET",
		url: url,
		dataType: "json",
		success: ajaxSuccessHandler
	});

} //End of get_dates()

function plot_chart()
{
	var unit_yaxis = "unit";

	//Adding a Unit Fetcher! Author : Rohit Khattar ChangeDate : 4/11/2013
	if (varid != -1) {
		$.ajax({
			type: "GET",
			dataType: "json",
			url: base_url+"variable/getUnit?varid=" + varid
		}).
		done(function(msg) {
			unit_yaxis = msg[0].unitA;
		});
	}

	//Chaning Complete Data loading technique..need to create a php page that will output javascript...
	var url_test = base_url + 'datapoint/getData?siteid=' + DATA.siteid + 
		'&varid=' + varid + '&meth=' + methodid + 
		'&startdate=' + date_from_sql + '&enddate=' + date_to_sql;

	$.ajax({
		url: url_test,
		type: "GET",
		dataType: "script"
	}).
	done(function(datatest) {
		var date_chart_from = formatDateSQL(glob_df, undefined, '');
		var date_chart_to   = formatDateSQL(glob_dt, undefined, '');

		// var data_test=datatest;
		chart = new Highcharts.StockChart(getStockChartConfig(
			date_chart_from, date_chart_to, unit_yaxis, data_test, displayVar,
			displayType));

		// end of new Highcharts.StockChart()

		// There is no such element with id "loadingtext"
		//$("#loadingtext").hide();

		make_grid();

		$('#jqxtabs').jqxTabs('enable');
	});
}

function make_grid()
{
	var editrow = -1;
	var vid = 0;
	var url = base_url + 'datapoint/getDataJSON?siteid=' + DATA.siteid +
		'&varid=' + varid +
		'&meth=' + methodid +
		'&startdate=' + date_from_sql +
		'&enddate=' + date_to_sql;

	var dataAdapter = new $.jqx.dataAdapter({
		datatype: "json",
		datafields: [
			{name: 'ValueID'},
			{name: 'DataValue'},
			{name: 'LocalDateTime'}
		],
		url: url
	});

	//Adding a Unit Fetcher! Author : Rohit Khattar ChangeDate : 11/4/2013
	var unitGrid = "Unit: None";

	$.ajax({
		dataType: "json",
		url: base_url+"variable/getUnit?varid=" + varid
	}).
	done(function(msg) {
		unitGrid = msg[0].unitA;

		if (flag == 1) {
			$("#jqxgrid").jqxGrid(getGridConfig(dataAdapter, unitGrid));
		}

		if (flag !=1 ) {
			$("#jqxgrid").jqxGrid(getGridConfig2(dataAdapter, columnsConfig));
			flag = 1;
		}
	});

	//Editing functionality

	// initialize the popup window and buttons.

	$("#popupWindow").jqxWindow(popupWindowConfig);

	$("#timepicker").timepicker({showOn: "focus", showPeriodLabels: false});

	$("#delval").jqxButton(buttonConfigBase);
	$("#Cancel").jqxButton(buttonConfigBase);
	$("#Save"  ).jqxButton(buttonConfigBase);

	//Delete Value
	$("#delval").unbind("click"); //Multiple events are getting binded for some reason. This makes sure that doesn't happen. 
	$("#delval").click(delValClickHandler);

	// update the edited row when the user clicks the 'Save' button.
	$("#Save").unbind("click");
	$("#Save").click(saveClickHandler);

	//End of Editing 

	//Add A new Value to the table
	$("#popupWindow_new").jqxWindow(popupWindowNewConfig);
	$("#Cancel_new").jqxButton(buttonConfigBase);
	$("#Save_new"  ).jqxButton(buttonConfigBase);

<?php
if (isLoggedIn()) {
echo(
'	$("#addnew").jqxButton({
		width: \'250\',
		height: \'25\',
		theme: \'darkblue\'
	}).
	bind(\'click\', function () {

		$("#popupWindow_new").jqxWindow(\'show\');

		var offset = $("#jqxgrid").offset();

		$("#popupWindow_new").jqxWindow({
			position: {
				x: parseInt(offset.left, 10) + 220,
				y: parseInt(offset.top,  10) +  60
			}
		});

		$("#date_new").jqxDateTimeInput({
			width: \'125px\',
			height: \'25px\',
			theme: \'darkblue\',
			formatString: "MM/dd/yyyy",
			textAlign: "center"
		});

		$("#timepicker_new" ).timepicker({
			showOn: "focus",
			showPeriodLabels: false
		});
	});' // end of click handler
); // end of echo
} // end of if (isLoggedIn())
?>

	$("#Save_new").unbind("click");
	$("#Save_new").bind('click', saveNewClickHandler);

	//End of adding a new value

	//Export Button

	$("#export").jqxButton(buttonConfig);
	$("#export").bind('click', exportClickHandler);

	//End of Exporting

	//Comparing

	//Define the button for comaprision

	$("#compare").jqxButton(buttonConfig);
	$('#window').jqxWindow('destroy');
	$('#mapOuter').empty();

	$('#window' ).jqxWindow(windowConfig);
	$('#window2').jqxWindow(windowConfig2);
	$('#window3').jqxWindow(windowConfig2);
	$('#window4').jqxWindow(windowConfig2);
	$('#window5').jqxWindow(windowConfig5);

	$('#window' ).jqxWindow('hide');
	$('#window2').jqxWindow('hide');
	$('#window3').jqxWindow('hide');
	$('#window4').jqxWindow('hide');
	$('#window5').jqxWindow('hide');

	$("#compare").click(compareClickHandler);

	//Now Map Loaded. Another Function to open up a new window that will Give them options to select the data to be plotted against the esiting data

	//End of Comparing

} // end of make_grid()

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

HTML_Render_Body_Start();

echo html_div_beg("col-md-9");

//possibly a future improvement. The sites could be accessed here in 
//addition to navigating back to the map 

echo html_div_beg('row');
genDropLists('Site', '', '', false);
echo html_div_beg('site_title') . $site['SiteName'] . '</div>' . html_br();
echo '</div>';

echo html_div_beg('row');
genDropLists('Variable','dropdownlist', 'dropdownlist', false) . html_br();
echo '</div>';

//The type is already selected when the Variable is selected!
//echo html_div_beg('row');
//genDropLists('Type','typelist', 'typelist', false) . html_br();
//echo '</div>';

echo html_div_beg('row');
genDropLists('Method','methodlist', 'methodlist', false) . html_br();
echo '</div>';

?>

<div id='daterange'></div>

<div class="row">

	<div class="col-md-6">
		<div id='fromdatedrop'>
			<div id='jqxDateTimeInput'></div>
		</div>
	</div>

	<div class="col-md-6">
		<div id='todatedrop'>
			<div id='jqxDateTimeInputto'></div>
		</div>
	</div>

</div> <!-- end of row -->

<br />

<div id='jqxtabs'>

	<ul style='margin-left: 20px;'>
		<li><?php echo getTxt('SiteInfo'); ?></li>
		<li><?php echo getTxt('DataPlot'); ?></li>
		<li><?php echo getTxt('DataTable'); ?></li>
	</ul>

	<div>

<?php
echo(html_b(getTxt('Site')).$site['SiteName'].html_br());

if ($site['picname'] == null) {
	if(isLoggedIn()) {
		echo(
			html_br(2) . getTxt('NoImages').
			"<a href='" . site_url('sites/edit/' . $SiteID) . "'> " . getTxt('ClickHere') . " </a>"
		);
	}
	else {	
		echo(html_br(2) . getTxt('NoImages'));
	}
} 
else {
	echo(
		html_br(2) . "<img src='" . getDetailsImg('' . $site['picname']) .
		"' width='368' height='250' />"
	);
}

echo(
	html_br(2) . 
	html_b(getTxt('Type'        )) .translateTerm($site['SiteType']) . html_br(2) .
	html_b(getTxt('Latitude'    )) . $site['Latitude' ] . html_br(2) .
	html_b(getTxt('Longitude'   )) . $site['Longitude'] . html_br(3) .
	html_b(getTxt('Measurements'))
);

$num_rows = count($Variables);
$count = 1;

foreach ($Variables as $var) {
	if ($var['VariableName'] != "") {
		echo($var['VariableName']);
		if ($count != $num_rows) {
			echo "; ";
		}
	}
	$count = $count + 1;
}

echo html_br(2);
?>

<?php echo getTxt('WrongSite'); ?>
<a href="<?php echo site_url('sites/map'); ?>" style="color:#00F">
<?php echo ' '.getTxt('Here'); ?></a>
<?php echo getTxt('GoBack'); ?> 
</div>

<div>
<div class="chart-wrapper">
<div class="chart-inner">
<div id="container" style="width:100%; height: 470px;"></div>
<!-- Button to compare data values-->
<input type="button" style=" float:right" value="<?php echo getTxt('Compare');?>" id='compare' />
</div>
</div>
</div>
<!-- End of Chart DIV -->
<div>
<div id="jqxgrid"></div>
<div id="popupWindow">
<div><?php echo getTxt('Edit'); ?></div>
<div style="overflow: hidden;">

	<table>

		<tr>
		<td colspan="2"><?php echo getTxt('ChangeValues'); ?></td>
		</tr>

		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>

		<tr>
		<td align="right"><?php echo getTxt('Date'); ?></td>
		<td align="left"><div id="date"></div></td>
		</tr>

		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>

		<tr>
		<td align="right"><?php echo getTxt('Time');?></td>
		<td align="left"> <input type="text" id="timepicker" name="timepicker" onChange="validatetime('#timepicker')" size="10" /></td>
		</tr>

		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>

		<tr>
		<td align="right"><?php echo getTxt('Value'); ?> </td>
		<td align="left"><input id="value" onBlur="validatenum('#value')" /></td>
		</tr>

		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>

		<tr>
		<td align="right"></td>
		<td style="padding-top: 10px;" align="right"><input style="margin-right: 5px;" type="button" id="Save" value="<?php echo getTxt('Save');?>" /><input id="delval" type="button" value="<?php echo getTxt('Delete');?>" />&nbsp;<input id="Cancel" type="button" value="<?php echo getTxt('Cancel'); ?>" /></td>
		</tr>

	</table>

</div>

</div>

<div style="alignment-adjust: middle; float:right;">
<?php
if (isLoggedIn()) {
	echo("<input type='button' value='".getTxt('AddRow')."' id='addnew' /> <br/>  <br/>");
}
?>
<input type="button" value="<?php echo getTxt('DownloadData');?>" id='export' />
</div>
</div>
<!-- End Of Grid Div.  -->
</div>
<!-- Jqx Tabs end -->
</div> 
<div id="popupWindow_new">
<div><?php echo getTxt('Add'); ?></div>
<div style="overflow: hidden;">
			<table>

				<tr>
					<td colspan="2"><?php echo getTxt('EnterValues'); ?></td>
				</tr>

				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>

				<tr>
					<td align="right"><?php echo getTxt('Date'); ?></td>
					<td align="left"><div id="date_new"></div></td>
				</tr>

				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>

				<tr>
					<td align="right"><?php echo getTxt('Time'); ?></td>
					<td align="left"> <input type="text" id="timepicker_new" name="timepicker_new" onChange="validatetime('#timepicker_new')" size="10" /></td>
				</tr>

				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>

				<tr>
					<td align="right"><?php echo getTxt('Value'); ?></td>
					<td align="left"><input id="value_new" onBlur="validatenum('#value_new')"/></td>
				</tr>

				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>

				<tr>
					<td align="right"></td>
					<td style="padding-top: 10px;" align="right">
						<input style="margin-right: 5px;" type="button" id="Save_new" value="<?php echo getTxt('Save'); ?>" />
						<input id="Cancel_new" type="button" value="<?php echo getTxt('Cancel'); ?>" />
					</td>
				</tr>

			</table>

		</div>
		</div>
		<br/>
		</div>
		</div>
	</div>
</div>

<div id="window">

	<div id="windowHeader">
		<span><?php echo getTxt('CompareTwo'); ?></span>
	</div>

	<div style="overflow: hidden;" id="windowContent"></div>

</div>

<div id="window2">

	<div id="window2Header">
		<span><?php echo getTxt('CompareTwo'); ?></span>
	</div>

	<div style="overflow: hidden;" id="window2Content"></div>

</div>

<div id="window3">

	<div id="window3Header">
		<span><?php echo getTxt('CompareTwo'); ?></span>
	</div>

	<div style="overflow: hidden;" id="window3Content"></div>

</div>

<div id="window4">

	<div id="window4Header">
		<span><?php echo getTxt('CompareTwo'); ?></span>
	</div>

	<div style="overflow: hidden;" id="window4Content"></div>

</div>

<div id="window5">

	<div id="window5Header">
		<span><?php echo getTxt('CompareTwo'); ?></span>
	</div>

	<div style="overflow: hidden;" id="window5Content"></div>

</div>

<?php HTML_Render_Body_End(); ?>
