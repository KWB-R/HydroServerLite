<?php
	//check authority to be here
	require_once 'authorization_check2.php';
	
	//This is required to get the international text strings dictionary
	include('internationalize.php');
	//include("languages/es/details_text.php");
	//include("languages/es/_common_text.php");
?>
<html>
<head>
<!--<title>HydroServer Lite Web Client</title>-->
<title><?php echo $WebClient; ?></title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="favicon.ico" >

<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxbuttons.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxscrollbar.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxdata.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxlistbox.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxdropdownlist.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxdropdownbutton.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxpanel.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxdatetimeinput.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxcalendar.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxtooltip.js"></script>
<script type="text/javascript" src="js/gettheme.js"></script>
<script type="text/javascript" src="js/jqwidgets/globalization/jquery.global.js"></script>
<script src="js/highstock.js" type="text/javascript"></script>
<script src="js/modules/exporting.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jqwidgets/jqxtabs.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxcheckbox.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxmenu.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxgrid.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxgrid.selection.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxgrid.columnsresize.js"></script> 
<script type="text/javascript" src="js/jqwidgets/jqxgrid.pager.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxgrid.sort.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxgrid.edit.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxwindow.js"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC3d042tZnUAA8256hCC2Y6QeTSREaxrY0&sensor=true"></script> 



<link rel="stylesheet" href="js/jqwidgets/styles/jqx.base.css" type="text/css" />
<link rel="stylesheet" href="js/jqwidgets/styles/jqx.classic.css" type="text/css" />
<link rel="stylesheet" href="js/jqwidgets/styles/jqx.darkblue.css" type="text/css" />
<link href="styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" href="styles/jqstyles/jquery.ui.all.css">
<link rel="stylesheet" href="styles/jqstyles/jquery.ui.timepicker.css">
<script src="js/ui/jquery.ui.core.js"></script>
<script src="js/ui/jquery.ui.widget.js"></script>
<script src="js/ui/jquery.ui.datepicker.js"></script>
<script src="js/ui/jquery.ui.timepicker.js"></script>
<link rel="stylesheet" href="styles/jqstyles/demos.css">


<!--Main Script to display the data-->
<script type="text/javascript">
var siteid=<?php echo $_GET['siteid'];?>;
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
var sitename;
var flag=0;
var methodid;
var chart="";
//Time Validation Script
function  validatetime(){

var strval = $("#timepicker").val();

//Minimum and maximum length is 5, for example, 01:20
	if(strval.length < 5 || strval.length > 5){
		/*alert("Invalid time. Time format should be five characters long and formatted HH:MM");*/
		alert(<?php echo "'".$InvalidTimeFive."'";?>);
	return false;
	}

	//Removing all space
	strval = trimAllSpace(strval);
	$("#timepicker").val(strval)

	//Split the string
	var newval = strval.split(":");
	var horval = newval[0]
	var minval = newval[1];

	//Checking hours

	//minimum length for hours is two digits, for example, 12
	if(horval.length != 2){
		/*alert("Invalid time. Hours format should be two digits long.");*/
		alert(<?php echo "'".$InvalidTimeHoursTwo."'";?>);
		return false;
		}
	if(horval < 0){
		/*alert("Invalid time. Hours cannot be less than 00.");*/
		alert(<?php echo "'".$InvalidTimeHoursZeros."'";?>);		
		return false;
		}
	else if(horval > 23){
		/*alert("Invalid time. Hours cannot be greater than 23.");*/
		alert(<?php echo "'".$InvalidTimeHoursTwentyThree."'";?>);
		return false;
		}

	//Checking minutes

 	//minimum length for minutes is 2, for example, 59
	if(minval.length != 2){
		/*alert("Invalid time. Minutes format should be two digits long.");*/
		alert(<?php echo "'".$InvalidTimeMinutesTwo."'";?>);
	return false;
	} 
	if(minval < 0){
		/*alert("Invalid time. Minutes cannot be less than 00.");*/
		alert(<?php echo "'".$InvalidTimeMinutesZeros."'";?>);
		return false;
		}   
	else if(minval > 59){
		/*alert("Invalid time. Minutes cannot be greater than 59.");*/
		alert(<?php echo "'".$InvalidTimeMinutesFiftyNine."'";?>);
		return false;
		}
	strval = IsNumeric(strval);
	$("#timepicker").val(strval)

}

//The trimAllSpace() function will remove any extra spaces
function trimAllSpace(str) 
{ 
    var str1 = ''; 
    var i = 0; 
    while(i != str.length) 
    { 
        if(str.charAt(i) != ' ') 
            str1 = str1 + str.charAt(i); i ++; 
    } 
    return str1; 
}

//The trimString() function will remove 
function trimString(str) 
{ 
     var str1 = ''; 
     var i = 0; 
     while ( i != str.length) 
     { 
         if(str.charAt(i) != ' ') str1 = str1 + str.charAt(i); i++; 
     }
     var retval = IsNumeric(str1); 
     if(retval == false) 
         return -100; 
     else 
         return str1; 
}
function IsNumeric(strString){ 
    var strValidChars = "0123456789:"; 
    var blnResult = true; 

    //test strString consists of valid characters listed above
    for (i = 0; i < strString.length && blnResult == true; i++) 
    { 
        var strChar = strString.charAt(i); 
        if (strValidChars.indexOf(strChar) == -1) 
        {
			/*alert ("Invalid character. You may only use numbers.");*/
			alert (<?php echo "'".$InvalidCharacterNumbers."'"; ?>);
			strString = strString.replace(strString[i],"");
            blnResult = false;
        } 
     }
	return strString;
}

//Time Validation Script Ends

//Time Validation Script NEW
function  validatetime_new(){

var strval = $("#timepicker_new").val();

//Minimum and maximum length is 5, for example, 01:20
	if(strval.length < 5 || strval.length > 5){
		/*alert("Invalid time. Time format should be five characters long and formatted HH:MM");*/
		alert(<?php echo "'".$InvalidTimeFive."'";?>);
	return false;
	}

	//Removing all space
	strval = trimAllSpace(strval);
	$("#timepicker_new").val(strval)

	//Split the string
	var newval = strval.split(":");
	var horval = newval[0]
	var minval = newval[1];

	//Checking hours

	//minimum length for hours is two digits, for example, 12
	if(horval.length != 2){
		/*alert("Invalid time. Hours format should be two digits long.");*/
		alert(<?php echo "'".$InvalidTimeHoursTwo."'";?>);
		return false;
		}
	if(horval < 0){
		/*alert("Invalid time. Hours cannot be less than 00.");*/
		alert(<?php echo "'".$InvalidTimeHoursZeros."'";?>);		
		return false;
		}
	else if(horval > 23){
		/*alert("Invalid time. Hours cannot be greater than 23.");*/
		alert(<?php echo "'".$InvalidTimeHoursTwentyThree."'";?>);
		return false;
		}

	//Checking minutes

 	//minimum length for minutes is 2, for example, 59
	if(minval.length != 2){
		/*alert("Invalid time. Minutes format should be two digits long.");*/
		alert(<?php echo "'".$InvalidTimeMinutesTwo."'";?>);
	return false;
	} 
	if(minval < 0){
		/*alert("Invalid time. Minutes cannot be less than 00.");*/
		alert(<?php echo "'".$InvalidTimeMinutesZeros."'";?>);
		return false;
		}   
	else if(minval > 59){
		/*alert("Invalid time. Minutes cannot be greater than 59.");*/
		alert(<?php echo "'".$InvalidTimeMinutesFiftyNine."'";?>);
		return false;
		}
	strval = IsNumeric(strval);
	$("#timepicker_new").val(strval)

}

//Time Validation NEW Script Ends

//Number validatin script
function validatenum() {
var v = $("#value").val();
var Value = isValidNumber(v);
return Value;
}

function isValidNumber(val){
      if(val==null || val.length==0){
  		  /*alert("Please enter a number in the Value box");*/
   		  alert(<?php echo "'".$EnterNumberValue."'";?>);

		  return false;
		  }

      var DecimalFound = false
      for (var i = 0; i < val.length; i++) {
            var ch = val.charAt(i)
            if (i == 0 && ch == "-") {
                  continue
            }
            if (ch == "." && !DecimalFound) {
                  DecimalFound = true
                  continue
            }
            if (ch < "0" || ch > "9") {
       		    /*alert("Please enter a valid number in the Value box");*/
		    alert(<?php echo "'".$EnterValidNumberValue."'";?>);
			    return false;
            	}
      }
	  return true;
}
//Number Validation script ends


//Number validatin NEW script
function validatenum_new() {
var v = $("#value_new").val();
var Value = isValidNumber(v);
return Value;
}

function isValidNumber(val){
      if(val==null || val.length==0){
  		  /*alert("Please enter a number in the Value box");*/
   		  alert(<?php echo "'".$EnterNumberValue."'";?>);
		  return false;
		  }

      var DecimalFound = false
      for (var i = 0; i < val.length; i++) {
            var ch = val.charAt(i)
            if (i == 0 && ch == "-") {
                  continue
            }
            if (ch == "." && !DecimalFound) {
                  DecimalFound = true
                  continue
            }
            if (ch < "0" || ch > "9") {
       		    /*alert("Please enter a valid number in the Value box");*/
		    alert(<?php echo "'".$EnterValidNumberValue."'";?>);
			    return false;
            	}
      }
	  return true;
}
//Number Validation NEW  script ends

//Populate the Drop Down list with values from the JSON output of the php page

    $(document).ready(function () {
		 	$("#loadingtext").hide();
		

//Create date selectors and hide them

//Create Tabs for Table Chart Switching
$('#jqxtabs').jqxTabs({ width: 620, height: 550, theme: 'darkblue', collapsible: true });
$('#jqxtabs').jqxTabs('disable');
var selectedItem = $('#jqxtabs').jqxTabs('selectedItem');
$('#jqxtabs').jqxTabs('enableAt', selectedItem);
			

//Defining the Variable List
var source =
        {
            datatype: "json",
            datafields: [
                { name: 'variableid' },
                { name: 'variablename' },
            ],
            url: 'db_get_variablelist.php?siteid='+siteid
        };
//Defining the Data adapter
var dataAdapter = new $.jqx.dataAdapter(source);
//Creating the Drop Down list
        $("#dropdownlist").jqxDropDownList(
        {
            source: dataAdapter,
            theme: 'darkblue',
            width: 200,
            height: 25,
            selectedIndex: 1,
            displayMember: 'variablename',
            valueMember: 'variableid'
        });

$('#dropdownlist').bind('select', function (event) {
var args = event.args;
var item = $('#dropdownlist').jqxDropDownList('getItem', args.index);
//Check if a valid value is selected and process futher to display dates
if ((item != null)&&(item.label != "Please select a variable")) {		

//Clear the Box
//$('#daterange').empty();	
$('#daterange').html("");


varname=item.label;
//varid=item.value;

//Going to the next function that will generate a list of data types available for that variable
var t=setTimeout("create_var_list()",300)


}
});


});
//End of Document Ready Function

function create_var_list()
{

//Generate data types available for that varname

        var source =
        {
            datatype: "json",
            datafields: [
                { name: 'dataid' },
                { name: 'dataname' },
            ],
            url: 'datavalue.php?siteid='+siteid+'&varname='+varname
        };
//Defining the Data adapter
var dataAdapter = new $.jqx.dataAdapter(source);
//Creating the Drop Down list
        $("#typelist").jqxDropDownList(
        {
            source: dataAdapter,
            theme: 'darkblue',
            width: 200,
            height: 25,
            selectedIndex: 0,
            displayMember: 'dataname',
            valueMember: 'dataid'
        });

//Binding an Event in case of Selection of Drop Down List to update the varid according to the selection

$('#typelist').bind('select', function (event) {
	 
var args = event.args;
var item = $('#typelist').jqxDropDownList('getItem', args.index);
//Check if a valid value is selected and process futher to display dates
if (item != null) {		
datatype=item.label;
//Update Var ID

update_var_id();

//Call Function to set default dates and plot

}

});

}

//End of create_var_list function	

function update_var_id()
{	
$.ajax({
  type: "GET",
  url: "db_update_varid.php?siteid="+siteid+"&varname="+varname+"&type="+datatype,
//Processing The Dates
    success: function(data) {
varid=data;
//Now We have the VariableID, We call the dates function


//Filter by methods available for that specific selection of variable and site

get_methods();



//get_dates();
	}
});

}

//Function to get dates and plot a default plot

function get_methods()
{

$('#methodlist').off()
$('#methodlist').unbind('valuechanged');

   var source122 =
        {
            datatype: "json",
            datafields: [
                { name: 'methodid' },
                { name: 'methodname' },
            ],
            url: 'db_get_methods.php?siteid='+siteid+'&varid='+varid
        };

//Defining the Data adapter
var dataAdapter122 = new $.jqx.dataAdapter(source122);

//Creating the Drop Down list
        $("#methodlist").jqxDropDownList(
        {
            source: dataAdapter122,
            theme: 'darkblue',
            width: 200,
            height: 25,
            selectedIndex: 0,
            displayMember: 'methodname',
            valueMember: 'methodid'
        });

//Binding an Event in case of Selection of Drop Down List to update the varid according to the selection

$('#methodlist').bind('select', function (event) {
	 
var args = event.args;
var item = $('#methodlist').jqxDropDownList('getItem', args.index);
//Check if a valid value is selected and process futher to display dates
if (item != null) {		
methodid=item.value;

get_dates();
//Now call to check dates



}

});
	
	
	
}
function get_dates()
{


var url="get_date.php?siteid="+siteid+"&varid=" + varid+"&methodid=" + methodid;
$.ajax({
        type: "GET",
	url: url,
	dataType: "xml",
	success: function(xml) {

//Displaying the Available Dates	
$(xml).find("dates").each(function()
{



//Displaying the Available Dates
sitename=String($(this).attr("sitename"));	
date_from=String($(this).attr("date_from"));
date_to=String($(this).attr("date_to"));		
//Call the next function to display the data

$('#daterange').html("");
//$('#daterange').empty();
$('#daterange').prepend('<p><strong>'+<?php echo "'".$DatesAvailable."'";?>+'</strong> ' + date_from + ' <strong>'+<?php echo "'".$To."'";?>+'</strong> ' + date_to +'</p>');
//$('#daterange').prepend('<p><strong>Dates Available:</strong> ' + date_from + ' <strong>to</strong> ' + date_to +'</p>');

$("#jqxDateTimeInput").jqxDateTimeInput({ width: '250px', height: '25px', theme: 'darkblue'});
$("#jqxDateTimeInput").jqxDateTimeInput({ formatString: 'd' });
$("#jqxDateTimeInputto").jqxDateTimeInput({ width: '250px', height: '25px', theme: 'darkblue'});
$("#jqxDateTimeInputto").jqxDateTimeInput({ formatString: 'd' });

//Resetting the bind functions
$('#jqxDateTimeInput').off()
$('#jqxDateTimeInputto').off()
$('#jqxDateTimeInput').unbind('valuechanged');
//Binding An Event To the Second Calendar
$('#jqxDateTimeInputo').unbind('valuechanged');

//Restricting the Calendar to those available dates
var year = parseInt(date_from.slice(0,4));
var month = parseInt(date_from.slice(5,7),10);
var day = parseInt(date_from.slice(8,10),10);
month=month-1;
var date1 = new Date();
glob_df=date1;
date1.setFullYear(year, month, day);

$("#fromdatedrop").jqxDropDownButton({ width: 250, height: 25, theme: 'darkblue'});

$("#todatedrop").jqxDropDownButton({ width: 250, height: 25, theme: 'darkblue'});


//Use Show And Hide Method instead of repeating formation - optimization number 2

$('#jqxDateTimeInput').jqxDateTimeInput('setDate', date1);
$("#jqxDateTimeInput").jqxDateTimeInput('setMinDate', new Date(year, month, day));
var year_to = parseInt(date_to.slice(0,4));		
var month_to = parseInt(date_to.slice(5,7),10);
var day_to = parseInt(date_to.slice(8,10),10);	
//month_to=month_to-1;
var date2 = new Date();
date2.setFullYear(year_to, month_to-1, day_to);
glob_dt=date2;

$('#jqxDateTimeInputto').jqxDateTimeInput('setDate', date2);
$("#jqxDateTimeInput").jqxDateTimeInput('setMaxDate', new Date(year_to, month_to, day_to)); 
$("#jqxDateTimeInputto").jqxDateTimeInput('setMaxDate', new Date(year_to, month_to, day_to)); 
//Plot the Chart with default limits

date_from_sql=date1.getFullYear() + '-' + add_zero((date1.getMonth())) + '-' + add_zero(date1.getDate()) + ' 00:00:00';
date_to_sql=date2.getFullYear() + '-' + add_zero((date2.getMonth()+2)) + '-' + add_zero(date2.getDate()) + ' 00:00:00';
//$("#fromdatedrop").jqxDropDownButton('setContent', "Select start date");
$("#fromdatedrop").jqxDropDownButton('setContent', <?php echo "'".$SelectStart."'";?> );
//$("#todatedrop").jqxDropDownButton('setContent', "Select end date");
$("#todatedrop").jqxDropDownButton('setContent', <?php echo "'".$SelectEnd."'";?> );

plot_chart();	
//Binding An Event to the first calender

$('#jqxDateTimeInput').bind('valuechanged', function (event) 
{
	

var date = event.args.date;
date_select_from=new Date(date);
glob_df=date_select_from;
//Converting to SQL Format for Searching

var date_from_sql2=date_select_from.getFullYear() + '-' + add_zero((date_select_from.getMonth()+1)) + '-' + add_zero(date_select_from.getDate()) + ' 00:00:00';
//Setting the Second calendar's min date to be the date of the first calendar
$("#jqxDateTimeInputto").jqxDateTimeInput('setMinDate', date);
var tempdate2=add_zero((date_select_from.getMonth()+1))+'/'+add_zero(date_select_from.getDate())+'/'+date_select_from.getFullYear();

$("#fromdatedrop").jqxDropDownButton('setContent', tempdate2);

if(date_from_sql!=date_from_sql2)
{date_from_sql=date_from_sql2;
plot_chart();				
}
});
//Binding An Event To the Second Calendar
$('#jqxDateTimeInputto').bind('valuechanged', function (event) {
	
var date = event.args.date;
date_select_to=new Date(date);
glob_dt=date_select_to;
var tempdate=add_zero((date_select_to.getMonth()+1))+'/'+add_zero(date_select_to.getDate())+'/'+date_select_to.getFullYear();
$("#todatedrop").jqxDropDownButton('setContent', tempdate);
date_to_sql=date_select_to.getFullYear() + '-' + add_zero((date_select_to.getMonth()+1)) + '-' + add_zero(date_select_to.getDate()) + ' 00:00:00';
plot_chart();
});



});
	}
});


} //End of function get_dates
	
function plot_chart()
{

var unit_yaxis="unit";
//Adding a Unit Fetcher! Author : Rohit Khattar ChangeDate : 11/4/2013
if (varid != -1)
{
$.ajax({
  type: "GET",
  url: "getUnit2.php?varid="+varid
}).done(function( msg ) {
  unit_yaxis = msg;
});
}

//Chaning Complete Data loading technique..need to create a php page that will output javascript...

var url_test='db_get_data.php?siteid='+siteid+'&varid='+varid+'&meth='+methodid+'&startdate='+date_from_sql+'&enddate='+date_to_sql;


$.ajax({
  url: url_test,
  type: "GET",
  dataType: "script"
}).done(function( datatest ) {
   
var date_chart_from=glob_df.getFullYear() + '-' + add_zero((glob_df.getMonth()+1)) + '-' + add_zero(glob_df.getDate());
var date_chart_to=glob_dt.getFullYear() + '-' + add_zero((glob_dt.getMonth()+1)) + '-' + add_zero(glob_dt.getDate());
 
// var n=str.replace("Microsoft","W3Schools"); 
  
// var data_test=datatest;

 chart=new Highcharts.StockChart({
    chart: {
		width: 580,
        renderTo: 'container',
		 zoomType: 'x'
    },
	 legend: {
		           verticalAlign: 'top',
            enabled: true,
            shadow: true,
			y:40,
			margin:50
          
        },
    title: {
        //text: 'Data of '+sitename+' from '+ date_chart_from + ' to ' + date_chart_to,
	text: <?php echo "'".$Dataof."'"; ?>+" "+sitename+" "+ <?php echo "'".$From."'"; ?>+" "+ date_chart_from +" "+  <?php echo "'".$To."'"; ?>+" " + date_chart_to,
		style: {
                fontSize: '12px'
            }
    },
	
	
        credits: {
            enabled: false
        },
	
	 subtitle: {
        //text: 'Click and Drag to Zoom a certain Portion'
	text: <?php echo "'".$ClickDrag."'"; ?>
    },
	
   xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                 month: '%e.%b / %Y',
                year: '%b.%Y'
            },
			title: {
                //text: 'Time',
		text: <?php echo "'".$TimeMsg."'"; ?>,
				margin: 30
            }
			
        },
  yAxis: {
            title: {
                text: unit_yaxis,
				margin: 40
            }
			
        },
	
	 
	
	 exporting: {
            enabled: true,
			width: 5000
        },	
		

	rangeSelector: {
                buttons: [
				{
                    type: 'day',
                    count: 1,
                    //text: '1d'
		    text: <?php echo "'".$OneD."'"; ?>
                },
				{
                    type: 'day',
                    count: 3,
                    //text: '3d'
		    		text: <?php echo "'".$ThreeD."'"; ?>
                }, {
                    type: 'week',
                    count: 1,
                    //text: '1w'
		    		text: <?php echo "'".$OneW."'"; ?>
                }, {
                    type: 'month',
                    count: 1,
                    //text: '1m'
		   			text: <?php echo "'".$OneM."'"; ?>
                }, {
                    type: 'month',
                    count: 6,
                    //text: '6m'
		   			text: <?php echo "'".$SixM."'"; ?>
                }, {
                    type: 'year',
                    count: 1,
                    //text: '1y'
		    		text: <?php echo "'".$OneY."'"; ?>
                }, {
                    type: 'all',
                    //text: 'All'
		    		text: <?php echo "'".$All."'"; ?>
                }],
            selected: 6
            },
	
	
     series: [{
            data: data_test,
			name: varname +'('+datatype+')'     
        }]
    
});

	$("#loadingtext").hide();
make_grid();
	$('#jqxtabs').jqxTabs('enable');
 
 });

}

function add_zero(value)
{
var ret;
if (value<10)
{
ret='0'+value;
}
else
{ret=value;
}
return ret;
}
	
function timeconvert(timestamp) {
var year = parseInt(timestamp.slice(0,4));
var month = parseInt(timestamp.slice(5,7),10);
var day = parseInt(timestamp.slice(8,10),10);
month=month-1;
var hour = parseInt(timestamp.slice(11,13),10);
var minute = parseInt(timestamp.slice(14,16),10);
var sec = parseInt(timestamp.slice(17,19),10); 
return new Date(year,month,day,hour,minute,sec);
}

function make_grid()
{
               var editrow = -1;
			   var vid=0;
var url='db_get_data2.php?siteid='+siteid+'&varid='+varid+'&meth='+methodid+'&startdate='+date_from_sql+'&enddate='+date_to_sql;

var source12 =
            {
                datatype: "csv",
                datafields: [
                    { name: 'vid'},
                    { name: 'Value'},
                    { name: 'date'}
                ],
				
                url: url
            };
var dataAdapter12 = new $.jqx.dataAdapter(source12);   

//Adding a Unit Fetcher! Author : Rohit Khattar ChangeDate : 11/4/2013
var unitGrid = "Unit: None";


$.ajax({
  type: "GET",
  url: "getUnit2.php?varid="+varid
}).done(function( msg ) {
  unitGrid = msg;
  
  if (flag==1)    
{

 

 
   $("#jqxgrid").jqxGrid(
            {
             
                source: dataAdapter12,
               
                columns: [
				  { text: 'ValueID', datafield: 'vid', width: 90 },
                  { text: 'Date', datafield: 'date', width: 200 },
	             { text: 'Value (' + unitGrid +')' , datafield: 'Value', width: 200} <?php
      if(isset($_COOKIE['power']))
	  {
		echo(",
				  
				   { text: 'Edit', datafield: 'Edit', columntype: 'button', cellsrenderer: function () {
                     return 'Edit';
                 }, buttonclick: function (row) {
                     // open the popup window when the user clicks a button.
                     editrow = row;
                     var offset = $('#jqxgrid').offset();
                     $('#popupWindow').jqxWindow({ position: { x: parseInt(offset.left) + 220, y: parseInt(offset.top) + 60} });
                     // get the clicked row's data and initialize the input fields.
                     var dataRecord = $('#jqxgrid').jqxGrid('getrowdata', editrow);
					 //Create a Date time Input
					 
					 var datepart=dataRecord.date.split(' ');
					 
		
					  $('#popupWindow').jqxWindow('show');
                    // $('#date').val(datepart[0]);
					 
					 $('#date').jqxDateTimeInput({ width: '125px', height: '25px', theme: 'darkblue', formatString: 'MM/dd/yyyy', textAlign: 'center' });
                     
					 var dateparts=datepart[0].split('-');
					 $('#date').jqxDateTimeInput('setDate', new Date(dateparts[0], dateparts[1]-1, dateparts[2])); 
					var timepart=datepart[1].split(':')
					$('#timepicker').val(timepart[0]+':'+timepart[1]);
					// $('#timepicker').timepicker('setTime',timepart[0]+':'+timepart[1]);
					
					 
					 $('#value').val(dataRecord.Value);
					 vid=dataRecord.vid;
                     // show the popup window.
                    
                 }
                 }                 ");
	  }
      ?>
                ]
            });		

}
if(flag!=1)
{


            $("#jqxgrid").jqxGrid(
            {
                width: 610,
                source: dataAdapter12,
                theme: 'darkblue',   
                columnsresize: true,
				sortable: true,
                pageable: true,
                autoheight: true,
				 editable: false,
				   selectionmode: 'singlecell',
                columns: [
			  { text: 'ValueID', datafield: 'vid', width: 90 },
                  { text: 'Date', datafield: 'date', width: 200 },
	          { text: 'Value (' + unitGrid +')', datafield: 'Value', width: 200} <?php
      if(isset($_COOKIE['power']))
	  {
		echo(",
				  
				   { text: 'Edit', datafield: 'Edit', columntype: 'button', cellsrenderer: function () {
                     return 'Edit';
                 }, buttonclick: function (row) {
                     // open the popup window when the user clicks a button.
                     editrow = row;
                     var offset = $('#jqxgrid').offset();
                     $('#popupWindow').jqxWindow({ position: { x: parseInt(offset.left) + 220, y: parseInt(offset.top) + 60} });
                     // get the clicked row's data and initialize the input fields.
                     var dataRecord = $('#jqxgrid').jqxGrid('getrowdata', editrow);
					 //Create a Date time Input
					  $('#popupWindow').jqxWindow('show');
					 var datepart=dataRecord.date.split(' ');
					 
		
					 
                    // $('#date').val(datepart[0]);
					 
					 $('#date').jqxDateTimeInput({ width: '125px', height: '25px', theme: 'darkblue', formatString: 'MM/dd/yyyy', textAlign: 'center' });
                     
					 var dateparts=datepart[0].split('-');
					 $('#date').jqxDateTimeInput('setDate', new Date(dateparts[0], dateparts[1]-1, dateparts[2])); 
					var timepart=datepart[1].split(':')
					$('#timepicker').val(timepart[0]+':'+timepart[1]);
					// $('#timepicker').timepicker('setTime',timepart[0]+':'+timepart[1]);
					
					 
					 $('#value').val(dataRecord.Value);
					 vid=dataRecord.vid;
                     // show the popup window.
                    
                 }
                 }                 ");
	  }
      ?>
                ]
            });		
		flag=1;		
			
	}
	
});
//Editing functionality

  // initialize the popup window and buttons.

$("#popupWindow").jqxWindow({ width: 250, resizable: false, theme: 'darkblue', isModal: true, autoOpen: false, cancelButton: $("#Cancel"), modalOpacity: 0.01 });
$( "#timepicker" ).timepicker({ showOn: "focus", showPeriodLabels: false });
$("#delval").jqxButton({ theme: 'darkblue' });
$("#Cancel").jqxButton({ theme: 'darkblue' });
$("#Save").jqxButton({ theme: 'darkblue'});
//Delete Value
$("#delval").click(function () {

//Send out a delete request
		
	 $.ajax({
  type: "POST",
  url: "do_edit_value.php?del=1&vid="+vid
}).done(function( msg ) {
  if(msg==1)
  {

//Remove that row from the table
$('#jqxgrid').jqxGrid('deleterow', editrow);        
$("#popupWindow").jqxWindow('hide');

 
  }
});
		   
		   
		   
		});
			// update the edited row when the user clicks the 'Save' button.
$("#Save").click(function () {
    if (editrow >= 0) {
		
var seldate= $('#date').jqxDateTimeInput('getDate'); 
var row = {date: seldate.getFullYear() + '-' + add_zero((seldate.getMonth()+1)) + '-' + add_zero(seldate.getDate())+' '+$("#timepicker").val()+':00', Value: $("#value").val(), vid: vid};
        
		//Validate
if(validatenum()==false){
		return false;
	}
	
var vt = $("#value").val();

switch(varid)
{
case "19":
if((vt<0)||(vt>100))
{
//alert("Value has to be between 0 and 100.");
alert(<?php echo "'".$ValueBetweenZeroAndHundred."'";?>);
		return false;
}
break;
case "13":
case "22":
if((vt<0)||(vt>14))
{
//alert("Value has to be between 0 and 14.");
alert(<?php echo "'".$ValueBetweenZeroAndFourteen."'";?>);
		return false;
}
break;
case "7":
case "24":break;
default:
if(vt<0)
{
//alert("Value can't be less than 0.");
alert(<?php echo "'".$ValueLessThanZero."'";?>);
		return false;
}
  break;
}
	
		
	//Time checking
result=validatetime();
	if(result==false){
		return false;
	}		

//Send out an ajax request to update that data field
	 $.ajax({
  type: "POST",
  url: "do_edit_value.php?vid="+vid+"&val="+vt+"&dt="+seldate.getFullYear() + '-' + add_zero((seldate.getMonth()+1)) + '-' + add_zero(seldate.getDate())+"&time="+$("#timepicker").val()
}).done(function( msg )
 {
  if(msg==1)
  {  
$('#jqxgrid').jqxGrid('updaterow', editrow, row);        
$("#popupWindow").jqxWindow('hide');
	 	plot_chart(); 
  }
  else
  {
	alert(msg);
	return false;  
  }
});




              
				}
            }); 


//End of Editing 

//Add A new Value to the table
$("#popupWindow_new").jqxWindow({ width: 250, resizable: false, theme: 'darkblue', isModal: true, autoOpen: false, cancelButton: $("#Cancel_new"), modalOpacity: 0.01 });
$("#Cancel_new").jqxButton({ theme: 'darkblue' });
$("#Save_new").jqxButton({ theme: 'darkblue'});
$("#addnew").jqxButton({ width: '250', height: '25', theme: 'darkblue'});

$("#addnew").bind('click', function () {
$("#popupWindow_new").jqxWindow('show');
var offset = $("#jqxgrid").offset();
$("#popupWindow_new").jqxWindow({ position: { x: parseInt(offset.left) + 220, y: parseInt(offset.top) + 60} });
$("#date_new").jqxDateTimeInput({ width: '125px', height: '25px', theme: 'darkblue', formatString: "MM/dd/yyyy", textAlign: "center" });
$( "#timepicker_new" ).timepicker({ showOn: "focus", showPeriodLabels: false });

 });

$("#Save_new").bind('click', function () {

		//Validate
if(validatenum_new()==false){
		return false;
	}

	
var vt = $("#value_new").val();

switch(varid)
{
case "19":
if((vt<0)||(vt>100))
{
//alert("Value has to be between 0 and 100.");
alert(<?php echo "'".$ValueBetweenZeroAndHundred."'";?>);
		return false;
}
break;
case "13":
case "22":
if((vt<0)||(vt>14))
{
//alert("Value has to be between 0 and 14.");
alert(<?php echo "'".$ValueBetweenZeroAndFourteen."'";?>);
		return false;
}
break;
case "7":
case "24":break;
default:
if(vt<0)
{
//alert("Value can't be less than 0.");
alert(<?php echo "'".$ValueLessThanZero."'";?>);
		return false;
}
  break;
}
	
			
	//Time checking
	if(validatetime_new()==false){
		return false;
	}		

var seldate= $('#date_new').jqxDateTimeInput('getDate'); 


//Send out ajax request to add new value

 $.ajax({
  type: "POST",
  url: "do_edit_addval.php?varid="+varid+"&val="+vt+"&dt="+seldate.getFullYear() + '-' + add_zero((seldate.getMonth()+1)) + '-' + add_zero(seldate.getDate())+"&time="+$("#timepicker_new").val()+"&sid="+siteid+"&mid="+methodid
}).done(function( msg )
 {
  if(msg)
  {  

var row = {date: seldate.getFullYear() + '-' + add_zero((seldate.getMonth()+1)) + '-' + add_zero(seldate.getDate())+' '+$("#timepicker_new").val()+':00', Value: $("#value_new").val(), vid: msg};  
$("#jqxgrid").jqxGrid('addrow', null, row); 
$("#popupWindow_new").jqxWindow('hide');
	plot_chart(); 
  }
  else
  {
	//alert("Error in Database Configuration");
	alert(<?php echo "'".$DatabaseConfigurationError."'"; ?>);
	
	return false;  
  }
});



});




//End of adding a new value

//Export Button

$("#export").jqxButton({ width: '250', height: '25', theme: 'darkblue'});
$("#export").bind('click', function () {

var url='data_export.php?siteid='+siteid+'&varid='+varid+'&meth='+methodid+'&startdate='+date_from_sql+'&enddate='+date_to_sql;

window.open(url,'_blank');

                });

//End of Exporting

//Comparing

//Define the button for comaprision

$("#compare").jqxButton({ width: '250', height: '25', theme: 'darkblue'});
$('#window').jqxWindow({ maxHeight: 800, maxWidth: 800, minHeight: 200, minWidth: 200, height: 480, width: 650, theme: 'darkblue' });
$('#window2').jqxWindow({ maxHeight: 100, maxWidth: 350, minHeight: 100, minWidth: 350, height: 100, width: 350, theme: 'darkblue' });
$('#window3').jqxWindow({ maxHeight: 100, maxWidth: 350, minHeight: 100, minWidth: 350, height: 100, width: 350, theme: 'darkblue' });
$('#window4').jqxWindow({ maxHeight: 100, maxWidth: 350, minHeight: 100, minWidth: 350, height: 100, width: 350, theme: 'darkblue' });
$('#window5').jqxWindow({ maxHeight: 300, maxWidth: 650, minHeight: 300, minWidth: 650, height: 300, width: 650, theme: 'darkblue' });
$('#window').jqxWindow('hide');
$('#window2').jqxWindow('hide');
$('#window3').jqxWindow('hide');
$('#window4').jqxWindow('hide');
$('#window5').jqxWindow('hide');
$("#compare").click(function(){
$("html, body").animate({ scrollTop: 0 }, "slow");
$('#window').jqxWindow('show');
$('#windowContent').load('compare.php', function() {
loadmap();
});





});

//Now Map Loaded. Another Function to open up a new window that will Give them options to select the data to be plotted against the esiting data




//End of Comparing


	
}
</script>

<STYLE type="text/css">
.button a:link { color:#FFF; text-decoration: none}
.button a:visited { color: #FFF; text-decoration: none}
.button a:hover { color: #FFF; text-decoration: none}
.button a:active { color: #FFF; text-decoration: none}
 </STYLE>

</head>
<body background="images/bkgrdimage.jpg">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><?php include "topBanner.php" ; ?></td>
  </tr>
  <tr>
    <td colspan="2" align="right" valign="middle" bgcolor="#3c3c3c"><?php require_once 'header.php'; ?></td>
  </tr>
  <tr>
    <td width="240" valign="top" bgcolor="#f2e6d6"><?php echo "$nav"; ?></td>
    <td valign="middle" bgcolor="#FFFFFF" width="720"><blockquote>
      <p>&nbsp;</p>
      <table width="630" border="0">
        <tr>
          <td colspan="4"><?php  

require_once 'db_config.php';

// get data and store in a json array
$query = "SELECT DISTINCT SiteName, SiteType, Latitude, Longitude FROM sites";
$siteid = $_GET['siteid'];
$query .= " WHERE SiteID=".$siteid;

$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$row = mysql_fetch_array($result, MYSQL_ASSOC);
//echo("<p align='center'><b>Site: </b>".$row['SiteName']."</p>");
echo("<p align='center'><b>$Site </b>".$row['SiteName']."</p>");

?></td>
          </tr>
        <tr>
          <td width="67">&nbsp;</td>
          <td width="239">&nbsp;</td>
          <td width="55">&nbsp;</td>
          <td width="221">&nbsp;</td>
        </tr>
        <tr>
          <!--<td><strong>Variable:</strong></td>-->
    	  <td><strong><?php echo $Variable; ?></strong></td>

          <td><div id="dropdownlist"></div></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <!--<td><div id='typelist_text'><strong>Type:</strong></div></td>-->
          <td><div id='typelist_text'><strong><?php echo $Type; ?> </strong></div></td>

          <td><div id='typelist'></div></td>
          <!--<td><div id='methodlist_text'><strong>Method:</strong></div></td>-->
          <td><div id='methodlist_text'><strong><?php echo $Method; ?></strong></div></td>
          <td><div id='methodlist'></div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4"><div id='daterange'></div></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><div id='fromdatedrop'><div id='jqxDateTimeInput'></div></div></td>
          <td colspan="2"><div id='todatedrop'><div id='jqxDateTimeInputto'></div></div></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <!--<td colspan="4"><div id="loadingtext" class="loading">Please Wait....Data is loading....<br/>-->
           <td colspan="4"><div id="loadingtext" class="loading"><?php echo $PleaseWait; ?><br/>
    </div>
  <div id='jqxtabs'>
    <ul style='margin-left: 20px;'>
      <!--<li>Site Information</li>
      <li>Data Plot</li>
      <li>Data Table</li>-->
      <li><?php echo $SiteInfo; ?></li>
      <li><?php echo $DataPlot; ?></li>
      <li><?php echo $DataTable; ?></li>
      </ul>
    <div>
<?php  


echo("<b>Site: </b>".$row['SiteName']."<br/>");

//Fetch the Site Image and display it here

$query1 = "SELECT picname FROM sitepic";
$query1 .= " WHERE SiteID=".$siteid;

$result1 = mysql_query($query1) or die("SQL Error 1: " . mysql_error());


if(mysql_num_rows($result1)<1)
{


//echo("<br><br>No Images found for this site. <a href='edit_site.php'>Click here to add some</a>");
echo("<br><br>  $NoImages  <a href='edit_site.php'> $ClickHere </a>");
	
}

else
{

$row1 = mysql_fetch_array($result1, MYSQL_ASSOC);
echo("<br><br><img src='imagesite/".$row1['picname']."' width='368' height='250'>");
}



//echo("<br/><br/><b>Type: </b>".$row['SiteType']."<br/><br/><b>Latitude: </b>".$row['Latitude']."<br/><br/><b>Longitude: </b>".$row['Longitude']);
echo("<br/><br/><b>$Type </b>".$row['SiteType']."<br/><br/><b>$Latitude </b>".$row['Latitude']."<br/><br/><b>$Longitude </b>".$row['Longitude']);

$query = "SELECT DISTINCT VariableName FROM seriescatalog";
$siteid = $_GET['siteid'];
$query .= " WHERE SiteID=".$siteid;

$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
//echo("<br/><br/><b>Measurements taken here: </b>");
echo("<br/><br/><b>$Measurements</b>");
$num_rows = mysql_num_rows($result);
$count=1;
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
if($row['VariableName']!="")
{	
	echo($row['VariableName']);
	if($count!=$num_rows)
	{echo "; ";}
}
  $count=$count+1;
	}



?>
 <!--<br/><br/>
Selected the wrong site? No worries! Click <a href="view_main.php" style="color:#00F">here</a> to go back to the map. </div>-->
 <br/><br/>
<?php echo $WrongSite; ?><a href="view_main.php" style="color:#00F"><?php echo $Here; ?></a> <?php echo $GoBack; ?> </div>

    <div>
   
      <div id="container" style="height: 470px"></div>
<!-- Button to compare data values-->
 <!--<input type="button" style=" float:right" value="Compare with other Data Values" id='compare' />-->
  <input type="button" style=" float:right" value="<?php echo $Compare;?>" id='compare' />


      </div>
    <div>
      <div id="jqxgrid"></div>
        <div id="popupWindow">
            <!--<div>Edit</div>-->
            <div><?php echo $Edit; ?></div>
            <div style="overflow: hidden;">
                <table>
                    <tr>
                        <!--<TD colspan="2">You may change the below values. Please hit Save once you are done making the required changes.</td>-->
                        <TD colspan="2"><?php echo $ChangeValues; ?></td>

                    </tr>
                    <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
                    <tr>
                        <!--<td align="right">Date:</td>-->
                        <td align="right"><?php echo $Date; ?></td>

                        <td align="left"><div id="date"</div></td>
                    </tr>
                    <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
                    <tr>
                        <!--<td align="right">Time:</td>-->
                        <td align="right"><?php echo $Time;?></td>
                        <td align="left"> <input type="text" id="timepicker" name="timepicker" onChange="validatetime()" size="10"></td>
                    </tr>
               <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
          
                    <tr>
                        <!--<td align="right">Value:</td>-->
                        <td align="right"><?php echo $Value; ?> </td>
                        <td align="left"><input id="value" onBlur="validatenum()"/></td>
                    </tr>
                    <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
             
                    <tr>
                        <td align="right"></td>
                        <td style="padding-top: 10px;" align="right"><input style="margin-right: 5px;" type="button" id="Save" value="<?php echo $Save;?>" /><input id="delval" type="button" value="<?php echo $Delete;?>" />&nbsp;<input id="Cancel" type="button" value="<?php echo $Cancel; ?>" /></td>
			<!--<td style="padding-top: 10px;" align="right"><input style="margin-right: 5px;" type="button" id="Save" value="Save" /><input id="delval" type="button" value="Delete" />&nbsp;<input id="Cancel" type="button" value="Cancel" /></td>-->
                    </tr>
                </table>
            </div>
       </div>
          <div id="popupWindow_new">
            <!--<div>Add</div>-->
            <div><?php echo $Add; ?></div>
            <div style="overflow: hidden;">
                <table>
                    <tr>
                        <!--<TD colspan="2">Enter the values below. Please hit Save once you are done entering.</td>-->
                        <TD colspan="2"><?php echo $EnterValues; ?></td>
                    </tr>
                    <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
                    <tr>
                        <!--<td align="right">Date:</td>-->
                        <td align="right"><?php echo $Date; ?></td>
                        <td align="left"><div id="date_new"</div></td>
                    </tr>
                    <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
                    <tr>
                        <!--<td align="right">Time:</td>-->
                        <td align="right"><?php echo $Time; ?></td>
                        <td align="left"> <input type="text" id="timepicker_new" name="timepicker_new" onChange="validatetime_new()" size="10"></td>
                    </tr>
               <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
          
                    <tr>
                        <!--<td align="right">Value:</td>-->
                        <td align="right"><?php echo $Value; ?></td>
                        <td align="left"><input id="value_new" onBlur="validatenum_new()"/></td>
                    </tr>
                    <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
             
                    <tr>
                        <td align="right"></td>
                       <!--<td style="padding-top: 10px;" align="right"><input style="margin-right: 5px;" type="button" id="Save_new" value="Save" /><input id="Cancel_new" type="button" value="Cancel" /></td>--> 
                       <td style="padding-top: 10px;" align="right"><input style="margin-right: 5px;" type="button" id="Save_new" value="<?php echo $Save; ?>" /><input id="Cancel_new" type="button" value="<?php echo $Cancel; ?>" /></t>
                    </tr>
                </table>
            </div>
       </div>
         <br/>
      <div style="alignment-adjust: middle; float:right;">
     <?php
      if(isset($_COOKIE['power']))
	  {
		//echo("<input type='button' value='Add new row to the above table' id='addnew' /> <br/>  <br/>");
		echo("<input type='button' value=$AddRow id='addnew' /> <br/>  <br/>");
	  }
      ?>
        <!--<input type="button" value="Download the above data" id='export' />-->
        <input type="button" value="<?php echo $DownloadData;?>" id='export' />
        </div>
      </div>
    </div></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></blockquote>
    <p>&nbsp;</p></td>
  </tr>
    <tr>
    <script src="js/footer.js"></script>
    </tr>
</table>

<div id="window">
 <div id="windowHeader">
 <!--<span>Compare two values</span>-->
 <span><?php echo $CompareTwo; ?></span>
   </div>
 <div style="overflow: hidden;" id="windowContent">
 
 
 </div>
  </div>

<div id="window2">
 <div id="window2Header">
 <!--<span>Compare two values</span>-->
 <span><?php echo $CompareTwo; ?></span>
   </div>
 <div style="overflow: hidden;" id="window2Content">
 
 
 </div>
  </div>
  <div id="window3">
 <div id="window3Header">
 <!--<span>Compare two values</span>-->
 <span><?php echo $CompareTwo; ?></span>
   </div>
 <div style="overflow: hidden;" id="window3Content">
 
 
 </div>
  </div>
   <div id="window4">
 <div id="window4Header">
 <span>Compare two values</span>
   </div>
 <div style="overflow: hidden;" id="window4Content">
 
 
 </div>
  </div>
  
   <div id="window5">
 <div id="window5Header">
 <!--<span>Compare two values</span>-->
 <span><?php echo $CompareTwo; ?></span>
   </div>
 <div style="overflow: hidden;" id="window5Content">
 
 
 </div>
  </div>
  
</body>
</html>