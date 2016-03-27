//
// Helper functions required in sites/details
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

