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

var varid;           // currently selected VariableID
var variableAndType; // currently selected "<VariableName> (<DataType>)"
var methodid;        // currently selected MethodID

var glob_df;
var glob_dt;

var date_from;
var date_to;

var date_from_sql;
var date_to_sql;

var sitename;
var flag = 0;
var chart = "";

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
		echo "\t\t$name: \"" . getTxt($name) . "\",\n";
	}
?>
		SiteName: "<?php echo $site['SiteName'];?>",
		ValueID: "<?php echo str_replace(':', ' ID', getTxt('Value'));?>",
		Value: "<?php echo str_replace(':', '', getTxt('Value'));?>"
	}
};

function getStockChartConfig(
	date_chart_from,
	date_chart_to,
	unit_yaxis,
	data_test,
	variableAndType
)
{
	var configExtension = {
		title: {
			text: DATA.text.Dataof + " " + DATA.text.SiteName + " " +
						DATA.text.From   + " " + date_chart_from + " " +
						DATA.text.To     + " " + date_chart_to,
			style: {
				fontSize: '12px'
			}
		},
		subtitle: {
			text: DATA.text.ClickDrag
		},
		xAxis: {
			type: 'datetime',
			dateTimeLabelFormats: {
				month: '%e.%b / %Y',
				year: '%b.%Y'
			},
			title: {
				text: DATA.text.TimeMsg,
				margin: 30
			}
		},
		yAxis: {
			title: {
				text: unit_yaxis,
				margin: 40
			}
		},
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
		series: [ {data: data_test, name: variableAndType} ]
	}

	return jQuery.extend(chartConfig, configExtension);

} // end of getStockChartConfig()

function getGridConfig(dataAdapter, columnsConfig)
{
	return {
		source: dataAdapter,
		width: '100%',
		columnsresize: true,
		columns: columnsConfig
	};

} // end of getGridConfig()

function getColumnsConfig(unitGrid, editable)
{
	var columns = [
		{text: DATA.text.ValueID, datafield: 'ValueID'},
		{text: DATA.text.Date, datafield: 'LocalDateTime'},
		{text: DATA.text.Value + ' (' + unitGrid + ')', datafield: 'DataValue'}
	];

	if (editable === true) {

		var editColumn = {
			text: 'Edit',
			datafield: 'Edit',
			columntype: 'button',
			cellsrenderer: function () {
				return 'Edit';
			},
			buttonclick: editClickHandler
		};

		columns = jQuery.merge(columns, [ editColumn ]);
	}

	return columns;
} // end of getColumnsConfig()

function editClickHandler(row)
{
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

	$('#date').
		jqxDateTimeInput(dateInputConfig2).
		jqxDateTimeInput('setDate', toDate(datepart[0]));

	$('#timepicker').val(toHourAndMinute(datepart[1]));

	$('#value').val(dataRecord.DataValue);

	vid = dataRecord.ValueID;
}

//Time Validation Script General
function validatetime(idString)
{
	//Removing all space
	var timestring = trimAllSpace($(idString).val());

	$(idString).val(timestring);

	// checkTimeFormat from assets/js/details_helpers.js
	if (! checkTimeFormat(timestring, DATA.text)) {
		return false;
	}

	$(idString).val(IsNumeric(timestring));

	return true;
}

function validatenum(idSelector)
{
	// isValidNumber from assets/js/details_helpers.js
	return isValidNumber($(idSelector).val(), DATA.text);
}

// Helper function to generate a URL with parameters
function toURL(endpoint, parameters)
{
	var relative = endpoint + '?' + jQuery.param(parameters);

	//alert("relative URL:\n" + relative);

	return base_url + relative;
}

function toDatafields(fieldnames)
{
	var datafields = [];

	for (var i = 0; i < fieldnames.length; i++) {
		datafields.push({ name: fieldnames[i] });
	}

	return datafields;
}

function toJsonAdapter(url, fieldnames)
{
	return new $.jqx.dataAdapter({
		datatype: "json",
		datafields: toDatafields(fieldnames),
		url: url
	});
}

function variableSelectHandler(event)
{
	var item = $('#dropdownlist').jqxDropDownList('getItem', event.args.index);

	//Check if a valid value is selected and process futher to display dates
	if (item !== null) {
		//Clear the Box
		$('#daterange').html("");

		varid = item.value;

		variableAndType = item.label;

		//Going to the next function that will generate a list of data types available for that variable
		//var t = setTimeout("create_var_list()", 300);
		get_methods(varid);
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
	//alert(event.data.origin + ' changed to:' + event.args.date);

	var newDate = new Date(event.args.date);

	switch (event.data.origin) {

		case 'from':

			glob_df = newDate;

			/*//Setting the Second calendar's min date to be the date of the first calendar
			//$("#jqxDateTimeInputto").jqxDateTimeInput('setMinDate', event.args.date);
			//	$("#fromdatedrop").jqxDropDownButton('setContent', formatDate(glob_df));

			//Converting to SQL Format for Searching
			var date_sql = formatDateSQL(glob_df);

			if (date_from_sql != date_sql) {
				date_from_sql = date_sql;
				plot_chart();
			}*/

			break;

		case 'to':

			glob_dt = newDate;

			//	$("#todatedrop").jqxDropDownButton('setContent', formatDate(glob_dt));

			date_to_sql = formatDateSQL(glob_dt);

			break;

		default:

			alert(
				"Unexpected origin calling dateChangedHanlder: " + event.data.origin
			);

			break;
	}

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
			'<strong> ' + DATA.text.To + ' </strong> ' + date_to +
			'</p>');

	//$("#jqxDateTimeInput").jqxDateTimeInput(dateInputConfig);
		//.off().unbind('valueChanged'); // reset the bind functions

	//$("#jqxDateTimeInputto").jqxDateTimeInput(dateInputConfig);
		//.off().unbind('valueChanged'); // reset the bind functions

	//Restricting the Calendar to those available dates

	// Convert to Date object without using the time information
	glob_df = timeconvert(date_from, false);
	glob_dt = timeconvert(date_to,   false);

//	$("#fromdatedrop").jqxDropDownButton(dateDropConfig);
//	$("#todatedrop"  ).jqxDropDownButton(dateDropConfig);

	//Use Show And Hide Method instead of repeating formation - optimization number 2

	$('#jqxDateTimeInput').jqxDateTimeInput('setDate', glob_df);
	//$("#jqxDateTimeInput").jqxDateTimeInput('setMinDate', new Date(year, month - 1, day));
	//$("#jqxDateTimeInput").jqxDateTimeInput('setMaxDate', new Date(year_to, month_to - 1, day_to)); 

	$('#jqxDateTimeInputto').jqxDateTimeInput('setDate', glob_dt);
	//$("#jqxDateTimeInputto").jqxDateTimeInput('setMaxDate', new Date(year_to, month_to - 1, day_to)); 

	//Plot the Chart with default limits

	//If the month is 0 or 13 it causes issues. We need to keep it between 1 and 12. 
	date_from_sql = formatDateSQL(glob_df, toMonthBegin(glob_df));
	date_to_sql   = formatDateSql(glob_dt, toMonthEnd(glob_dt));

//	$("#fromdatedrop").jqxDropDownButton('setContent', DATA.text.SelectStart);
//	$("#todatedrop"  ).jqxDropDownButton('setContent', DATA.text.SelectEnd);

	plot_chart();

	//Binding An Event to the first calender
//	$('#jqxDateTimeInput').on('change', dateChangedHandler);

	//Binding An Event To the Second Calendar
//	$('#jqxDateTimeInputto').on('change', dateToChangedHandler);
}
// end of ajaxSuccessHandler()

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
	$("#popupWindow_new").jqxWindow('show');

	var offset = $("#jqxgrid").offset();

	$("#popupWindow_new").jqxWindow({
		position: {
			x: parseInt(offset.left, 10) + 220,
			y: parseInt(offset.top,  10) +  60
		}
	});

	$("#date_new").jqxDateTimeInput(dateInputConfig2);

	$("#timepicker_new" ).timepicker({
		showOn: "focus",
		showPeriodLabels: false
	});
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
			url: toURL("datapoint/edit/" + vid, {
				val: vt,
				dt: formatDateSQL(seldate, undefined, ''),
				time: $("#timepicker").val()
			})
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
		url: toURL("datapoint/add", {
			varid: varid,
			val: vt,
			dt: formatDateSQL(seldate, undefined, ''),
			time: $("#timepicker_new").val(),
			sid: DATA.siteid,
			mid: methodid
		})
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

	$('#windowContent').load(toURL('datapoint/compare/1', {}), function() {});

} // end of compareClickHandler()

function exportClickHandler()
{
	var url = toURL('datapoint/export', {
		siteid: DATA.siteid,
		varid: varid,
		meth: methodid,
		startdate: date_from_sql,
		enddate: date_to_sql
	});

	window.open(url, '_blank');
}

//Populate the Drop Down list with values from the JSON output of the php page

$(document).ready(function() {

	// There is no such element with id "loadingtext"
	//$("#loadingtext").hide();

	//Create date selectors and hide them

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

	//Defining the Data adapter for the variable list
	var dataAdapter = toJsonAdapter(
		toURL('variable/getSiteJSON', { siteid: DATA.siteid, withtype: 1 }),
		['VariableID', 'VarNameMod']
	);

	//Creating the Variables Drop Down list
	$("#dropdownlist").
		jqxDropDownList(
			jQuery.extend(dropDownConfig, {
				source: dataAdapter,
				displayMember: 'VarNameMod',
				valueMember: 'VariableID'
			})
		).
		bind('select', variableSelectHandler);

	$("#jqxDateTimeInput").
		jqxDateTimeInput(dateInputConfig).
		on("valuechanged", {origin: "from"}, dateChangedHandler);

	$("#jqxDateTimeInputto").
		jqxDateTimeInput(dateInputConfig).
		on("valuechanged", {origin: "to"}, dateChangedHandler);
});

//End of Document Ready Function

//Function to get dates and plot a default plot

function get_methods(variableID)
{
	var dataAdapter = toJsonAdapter(
		toURL('methods/getSiteVarJSON', {siteid: DATA.siteid, varid: variableID}),
		[ 'MethodID', 'MethodDescription' ]
	);

	$('#methodlist').
		//off().
		//unbind('valuechanged').
		//Creating the Drop Down list
		jqxDropDownList(
			jQuery.extend(dropDownConfig, {
				source: dataAdapter,
				displayMember: 'MethodDescription',
				valueMember: 'MethodID'
			})
		).
		//Binding an Event in case of Selection of Drop Down List to update the varid according to the selection
		bind('select', methodSelectHandler).
		jqxDropDownList('selectIndex', 0);
}

function get_dates()
{
	$.ajax({
		type: "GET",
		url: toURL("series/getDateJSON", {
			siteid: DATA.siteid,
			varid: varid,
			methodid: methodid
		}),
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
			url: toURL("variable/getUnit", {
				varid: varid
			})
		}).
		done(function(msg) {
			unit_yaxis = msg[0].unitA;
		});
	}

	//Chaning Complete Data loading technique..need to create a php page that will output javascript...
	$.ajax({
		url: toURL('datapoint/getData', {
			siteid: DATA.siteid,
			varid: varid,
			meth: methodid,
			startdate: date_from_sql,
			enddate: date_to_sql
		}),
		type: "GET",
		dataType: "script"
	}).
	done(function(datatest) {
		var date_chart_from = formatDateSQL(glob_df, undefined, '');
		var date_chart_to   = formatDateSQL(glob_dt, undefined, '');

		// var data_test=datatest;
		chart = new Highcharts.StockChart(getStockChartConfig(
			date_chart_from, date_chart_to, unit_yaxis, data_test, variableAndType
		));

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

	var dataAdapter = toJsonAdapter(
		toURL('datapoint/getDataJSON', {
			siteid: DATA.siteid,
			varid: varid,
			meth: methodid,
			startdate: date_from_sql,
			enddate: date_to_sql
		}),
		[ 'ValueID', 'DataValue', 'LocalDateTime' ]
	);

	//Adding a Unit Fetcher! Author : Rohit Khattar ChangeDate : 11/4/2013
	var unitGrid = "Unit: None";

	$.ajax({
		dataType: "json",
		url: toURL("variable/getUnit", { varid: varid })
	}).
	done(function(msg) {

		var columnsConfig = getColumnsConfig(
			msg[0].unitA, // unit
			<?php echo (isLoggedIn() ? 'true' : 'false'); ?> // logged in?
		);

		var gridConfig = getGridConfig(dataAdapter, columnsConfig);

		if (flag !== 1) {
			gridConfig = jQuery.extend(gridConfig, gridConfigExtended);
			flag = 1;
		}

		$("#jqxgrid").jqxGrid(gridConfig);
	});

	//Editing functionality

	// initialize the popup window and buttons.

	$("#popupWindow").jqxWindow(jQuery.extend(
		popupWindowConfig, {cancelButton: $("#Cancel")}
	));

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
	$("#popupWindow_new").jqxWindow(jQuery.extend(
		popupWindowConfig, {cancelButton: $("#Cancel_new")}
	));

	$("#Cancel_new").jqxButton(buttonConfigBase);
	$("#Save_new"  ).jqxButton(buttonConfigBase);

<?php 

if (isLoggedIn()) {
	echo "$(\"#addnew\").jqxButton(buttonConfig)." . 
		"bind('click', addValueClickHandler)\n";
}

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
	$id_timepicker = $data['id_timepicker'];
	$id_value = $data['id_value'];

	$onChange = "onChange=\"validatetime('#$id_timepicker')\"";

	$onBlur = "onBlur=\"validatenum('#$id_value')\"";

	$style = 'style="margin-right: 5px;"';

	return array(

		// row 1
		'<td colspan="2">' . getTxt($data['caption']) . '</td>',

		// row 2
		html_td_right(getTxt('Date')) .
		html_td_left('<div id="' . $data['id_date']. '">' . html_div_end()),

		// row 3
		html_td_right(getTxt('Time')) .
		html_td_left(
			html_input(
				$data['id_timepicker'],
				'type="text" name="' . $data['id_timepicker'] . '" ' . 
					$onChange . ' size="10"'
			)
		),

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
			'id_timepicker' => 'timepicker',
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
			'id_timepicker' => 'timepicker_new',
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
