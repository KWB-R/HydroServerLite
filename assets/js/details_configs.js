//
// Define the configurations of the controls in sites/details
//

var dropDownConfig = {
	theme: 'darkblue',
	height: 25,
	width: "100%",
	selectedIndex: 0
};

var chartConfig = {
	chart: {
		renderTo: 'container',
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

// Placeholders in formatString (see http://www.jqwidgets.com/jquery-widgets-
// documentation/documentation/jqxdatetimeinput/jquery-datetimeinput-api.htm):
//
// 'hh'-the hour, using a 12-hour clock from 01 to 12
// 'HH'-the hour, using a 24-hour clock from 00 to 23

var dateInputConfig = {
	width: '100%',
	height: '25px',
	theme: 'darkblue',
	formatString: 'dd.MM.yyyy HH:mm'
};

var dateInputConfig2 = {
	width: '125px',
	height: '25px',
	theme: 'darkblue',
	formatString: 'dd.MM.yyyy hh:mm', //"MM/dd/yyyy",
	textAlign: 'center'
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

var gridConfigGeneral = {
	width: '100%',
	columnsresize: true
};

var gridConfigExtended = {
	theme: 'darkblue',
	sortable: true,
	pageable: true,
	autoheight: true,
	editable: false,
	selectionmode: 'singlecell'
};

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

