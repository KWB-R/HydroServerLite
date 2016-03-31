//
// Helper functions required in sites/details
//

function checkTimeFormat(timestring, messages)
{
	//Minimum and maximum length is 5, for example, 01:20
	if (timestring.length != 5) {
		alert(messages.InvalidTimeFive);
		return false;
	}

	//Split the string
	var timeparts = splitTimeString(timestring);

	//Checking hours

	//minimum length for hours is two digits, for example, 12
	if (timeparts.hour.length != 2) {
		alert(messages.InvalidTimeHoursTwo);
		return false;
	}

	if (timeparts.hour < 0 || timeparts.hour > 23) {
		alert(timeparts.hour < 0 ?
			messages.InvalidTimeHoursZeros :
			messages.InvalidTimeHoursTwentyThree);
		return false;
	}

	//Checking minutes

	//minimum length for minutes is 2, for example, 59
	if (timeparts.minute.length != 2) {
		alert(messages.InvalidTimeMinutesTwo);
		return false;
	} 

	if (timeparts.minute < 0 || timeparts.minute > 59) {
		alert(timeparts.minute < 0 ?
			messages.InvalidTimeMinutesZeros :
			messages.InvalidTimeMinutesFiftyNine);
		return false;
	}

	return true;
}

function isValidNumber(valuetext, messages)
{
	if (valuetext === null || valuetext.length === 0) {
		alert(messages.EnterNumberValue);
		return false;
	}

	var DecimalFound = false;

	for (var i = 0; i < valuetext.length; i++) {

		var ch = valuetext.charAt(i);

		if (i === 0 && ch === "-") {
			continue;
		}

		if (ch === "." && ! DecimalFound) {
			DecimalFound = true;
			continue;
		}

		if (ch < "0" || ch > "9") {
			alert(messages.EnterValidNumberValue);
			return false;
		}
	}

	return true;
}

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

