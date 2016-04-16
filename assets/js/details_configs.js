//
// Define the configurations of the controls in sites/details
//

var dropDownConfig = {
	theme: 'darkblue',
	height: 25,
	width: "100%",
	selectedIndex: 0
};

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

var popupWindowConfig = {
	width: 300,
	height: 350,
	resizable: false,
	theme: 'darkblue',
	isModal: true,
	autoOpen: false,
	modalOpacity: 0.01
};

var buttonConfigBase = {
	theme: 'darkblue'
};

var buttonConfig = {
	theme: 'darkblue',
	width: '250',
	height: '25'
};

var dateDropConfig  = {
	width: '100%',
	height: 25,
	theme: 'darkblue'
};

var timePickerConfig = {
	showOn: "focus",
	showPeriodLabels: false
};

function getGridConfig(extended)
{
	if (typeof extended === 'undefined') {
		extended = true;
	}

	var configGeneral = {
		width: '100%',
		columnsresize: true
	};

	var configExtended = {
		theme: 'darkblue',
		sortable: true,
		pageable: true,
		//autoheight: true,
		editable: false,
		selectionmode: 'singlecell'
	};

	return (
		extended ?
		jQuery.extend(configGeneral, configExtended) :
		configGeneral
	);
}

var editColumnConfig = {
	text: 'Edit',
	datafield: 'Edit',
	columntype: 'button',
	cellsrenderer: function () {
		return 'Edit';
	},
	width: 80,
	sortable: false
};

var tabsConfig = {
	width:'100%',
	height: 550,
	theme: 'darkblue',
	collapsible: true
};

// Sub configurations for Highcharts.StockChart
var dateTimeLabelFormats_en = {
	millisecond: "%A, %b %e, %H:%M:%S.%L",
	second:      "%A, %b %e, %H:%M:%S",
	minute:      "%A, %b %e, %H:%M",
	hour:        "%A, %b %e, %H:%M",
	day:         "%A, %b %e, %Y %H:%M",
	week:        "Week from %A, %b %e, %Y %H:%M",
	month:       "%B %Y %H:%M",
	year:        "%Y"
};

var dateTimeLabelFormats_de = {
	millisecond: "%a, %e. %b, %H:%M:%S.%L",
	second:      "%a, %e. %b, %H:%M:%S",
	minute:      "%a, %e. %b, %H:%M",
	hour:        "%a, %e. %b, %H:%M",
	day:         "%a, %e. %b, %Y %H:%M",
	week:        "Woche vom %a, %e. %b %Y %H:%M",
	month:       "%B %Y %H:%M",
	year:        "%Y"
};

var dateTimeLabelFormatsGroup_en = {
	millisecond: [
		dateTimeLabelFormats_en.millisecond,
		dateTimeLabelFormats_en.millisecond,
		'-%H:%M:%S.%L'
	],
	second: [
		dateTimeLabelFormats_en.second,
		dateTimeLabelFormats_en.second,
		'-%H:%M:%S'
	],
	minute: [
		dateTimeLabelFormats_en.minute,
		dateTimeLabelFormats_en.minute,
		'-%H:%M'
	],
	hour: [
		dateTimeLabelFormats_en.hour,
		dateTimeLabelFormats_en.hour,
		'-%H:%M'
	],
	day: ['%A, %b %e, %Y', '%A, %b %e', '-%A, %b %e, %Y'],
	week: ['Week from %A, %b %e, %Y', '%A, %b %e', '-%A, %b %e, %Y'],
	month: ['%B %Y', '%B', '-%B %Y'],
	year: ['%Y', '%Y', '-%Y']
};

var dateTimeLabelFormatsGroup_de = {
	millisecond: [
		dateTimeLabelFormats_de.millisecond,
		dateTimeLabelFormats_de.millisecond,
		'-%H:%M:%S.%L'
	],
	second: [
		dateTimeLabelFormats_de.second,
		dateTimeLabelFormats_de.second,
		'-%H:%M:%S'
	],
	minute: [
		dateTimeLabelFormats_de.minute,
		dateTimeLabelFormats_de.minute,
		'-%H:%M'
	],
	hour: [
		dateTimeLabelFormats_de.hour,
		dateTimeLabelFormats_de.hour,
		'-%H:%M'
	],
	day: ['%a, %e. %b %Y', '%a, %e. %b', '-%a, %e. %b %Y'],
	week: ['Woche vom %a, %e. %b %Y', '%a, %e. %b', '-%a, %e. %b %Y'],
	month: ['%b %Y', '%b', '-%b %Y'],
	year: ['%Y', '%Y', '-%Y']
};

//
// Functions returning objects for the initialisation of user interface elements
//

function getDateInputConfig(version)
{
	// Placeholders in formatString (see http://www.jqwidgets.com/jquery-widgets-
	// documentation/documentation/jqxdatetimeinput/jquery-datetimeinput-api.htm):
	//
	// 'hh'-the hour, using a 12-hour clock from 01 to 12
	// 'HH'-the hour, using a 24-hour clock from 00 to 23
	return {
		height: '25px',
		theme: 'darkblue',
		formatString: 'dd.MM.yyyy HH:mm',
		width:     (version === 1 ? '100%' : '100%'), // '150px'
		textAlign: (version === 1 ? 'left' : 'left')  // 'center'
	};
}

function getStockChartConfig(country)
{
	country = country || 'en'

	var inputDateFormat = (
		country === 'de' ?
		'%d.%m.%Y %H:%M' :
		'%m/%d/%Y %H:%M'
	);

	var dateTimeLabelFormatsGroup = (
		country === 'de' ?
		dateTimeLabelFormatsGroup_de :
		dateTimeLabelFormatsGroup_en
	);

	var dateTimeLabelFormats = (
		country === 'de' ?
		dateTimeLabelFormats_de :
		dateTimeLabelFormats_en
	);

	return {
		chart: {
			zoomType: 'x'
		},
		legend: {
			verticalAlign: 'top',
			enabled: true,
			shadow: true,
			y: 40,
			margin: 50
		},
		credits: {
			enabled: false
		},
		exporting: {
			enabled: true,
			width: 5000
		},
		plotOptions: {
			line: {
				marker: {
					enabled: true
				}
			},
			series: { 
				dataGrouping: {
					dateTimeLabelFormats: dateTimeLabelFormatsGroup
				}
			}
		},
		xAxis: {
			title: {
				text: 'X-AXIS'
			},
			type: 'datetime',
			ordinal: false,
			dateTimeLabelFormats: {
				month: '%e.%b / %Y',
				year: '%b.%Y'
			}
		},
		yAxis: {
			title: {
				text: 'Y-AXIS'
			}
		},
		rangeSelector: {
			inputDateFormat: inputDateFormat,
			inputEditDateFormat: inputDateFormat,
			enabled: true,
			inputEnabled: true,
			inputBoxHeight: 25,
			inputStyle: {
				//color: 'red',
				//fontWeight: 'bold'
				//fontSize: '10pt',
				width: '140px',
				height: '20px'
			}
		},
		tooltip: {
			enabled: true,
			dateTimeLabelFormats: dateTimeLabelFormats
		}
	}
}

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

