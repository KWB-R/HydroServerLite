//
// Define the configurations of the controls in sites/details
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

var dateInputConfig = {
	width: '100%', 
	height: '25px', 
	theme: 'darkblue', 
	formatString: 'dd.MM.yyyy hh:mm'
};

var dateDropConfig  = {width: '100%', height: 25, theme: 'darkblue'};

