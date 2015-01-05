<?php
header('Content-Type: text/html; charset=utf-8');
HTML_Render_Head($js_vars);
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
var siteid=<?php echo $SiteID;?>;
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
var flag=0;
var methodid;
var chart="";
var displayVar;
//Time Validation Script
function  validatetime(){

var strval = $("#timepicker").val();

//Minimum and maximum length is 5, for example, 01:20
	if(strval.length < 5 || strval.length > 5){
		alert(<?php echo "'".getTxt('InvalidTimeFive')."'";?>);
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
		alert(<?php echo "'".getTxt('InvalidTimeHoursTwo')."'";?>);
		return false;
		}
	if(horval < 0){
		alert(<?php echo "'".getTxt('InvalidTimeHoursZeros')."'";?>);		
		return false;
		}
	else if(horval > 23){
		alert(<?php echo "'".getTxt('InvalidTimeHoursTwentyThree')."'";?>);
		return false;
		}

	//Checking minutes

 	//minimum length for minutes is 2, for example, 59
	if(minval.length != 2){
		alert(<?php echo "'".getTxt('InvalidTimeMinutesTwo')."'";?>);
	return false;
	} 
	if(minval < 0){
		alert(<?php echo "'".getTxt('InvalidTimeMinutesZeros')."'";?>);
		return false;
		}   
	else if(minval > 59){
		alert(<?php echo "'".getTxt('InvalidTimeMinutesFiftyNine')."'";?>);
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
			alert (<?php echo "'".getTxt('InvalidCharacterNumbers')."'"; ?>);
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
		alert(<?php echo "'".getTxt('InvalidTimeFive')."'";?>);
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
		alert(<?php echo "'".getTxt('InvalidTimeHoursTwo')."'";?>);
		return false;
		}
	if(horval < 0){
		alert(<?php echo "'".getTxt('InvalidTimeHoursZeros')."'";?>);		
		return false;
		}
	else if(horval > 23){
		alert(<?php echo "'".getTxt('InvalidTimeHoursTwentyThree')."'";?>);
		return false;
		}

	//Checking minutes

 	//minimum length for minutes is 2, for example, 59
	if(minval.length != 2){
		alert(<?php echo "'".getTxt('InvalidTimeMinutesTwo')."'";?>);
	return false;
	} 
	if(minval < 0){
		alert(<?php echo "'".getTxt('InvalidTimeMinutesZeros')."'";?>);
		return false;
		}   
	else if(minval > 59){
		alert(<?php echo "'".getTxt('InvalidTimeMinutesFiftyNine')."'";?>);
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
   		  alert(<?php echo "'".getTxt('EnterNumberValue')."'";?>);

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
		    alert(<?php echo "'".getTxt('EnterValidNumberValue')."'";?>);
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
   		  alert(<?php echo "'".getTxt('EnterNumberValue')."'";?>);
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
		    alert(<?php echo "'".getTxt('EnterValidNumberValue')."'";?>);
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
                { name: 'VariableID' },
                { name: 'VariableName' },
            ],
            url: base_url+'variable/getSiteJSON?siteid='+siteid
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
            selectedIndex: 0,
            displayMember: 'VariableName',
            valueMember: 'VariableID'
        });

$('#dropdownlist').bind('select', function (event) {
var args = event.args;
var item = $('#dropdownlist').jqxDropDownList('getItem', args.index);
//Check if a valid value is selected and process futher to display dates
if (item != null) {
//Clear the Box
$('#daterange').html("");
varname=item.value;
displayVar=item.label;
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
                { name: 'DataType' },
				{ name: 'display' },
            ],
            url: base_url+'variable/getTypes?siteid='+siteid+'&varname='+varname
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
            displayMember: 'display',
            valueMember: 'DataType'
        });

//Binding an Event in case of Selection of Drop Down List to update the varid according to the selection
$("#typelist").jqxDropDownList('selectIndex', 0 ); 
$('#typelist').bind('select', function (event) {
	 
var args = event.args;
var item = $('#typelist').jqxDropDownList('getItem', args.index);
//Check if a valid value is selected and process futher to display dates
if (item != null) {		
datatype=item.value;
displayType = item.label;
update_var_id();}
});
}

//End of create_var_list function	
function update_var_id()
{	
$.ajax({
  type: "GET",
  url: base_url+"variable/updateVarID?siteid="+siteid+"&varname="+varname+"&type="+datatype,
//Processing The Dates
    success: function(data) {
	varid=data;
	//Now We have the VariableID, We call the dates function
	//Filter by methods available for that specific selection of variable and site
	get_methods();
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
                { name: 'MethodID' },
                { name: 'MethodDescription' },
            ],
            url: base_url+'methods/getSiteVarJSON?siteid='+siteid+'&varid='+varid
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
            displayMember: 'MethodDescription',
            valueMember: 'MethodID'
        });
$("#methodlist").jqxDropDownList('selectIndex', 0 );
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

var url=base_url+"series/getDateJSON?siteid="+siteid+"&varid=" + varid+"&methodid=" + methodid;
$.ajax({
    type: "GET",
	url: url,
	dataType: "json",
	success: function(result) {
//Displaying the Available Dates
date_from=String(result.BeginDateTime);
date_to=String(result.EndDateTime);		
//Call the next function to display the data

$('#daterange').html("");
$('#daterange').prepend('<p><strong>'+<?php echo "'".getTxt('DatesAvailable')."'";?>+'</strong> ' + date_from + ' <strong>'+<?php echo "'".getTxt('To')."'";?>+'</strong> ' + date_to +'</p>');

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

//If the month is 0 or 13 it causes issues. We need to keep it between 1 and 12. 

var monthBegin = date1.getMonth();
if (monthBegin == 0) { monthBegin=1;}

var monthEnd =  date2.getMonth()+2;
if (monthEnd > 12) { monthEnd=12;}

date_from_sql=date1.getFullYear() + '-' + add_zero(monthBegin) + '-' + add_zero(date1.getDate()) + ' 00:00:00';
date_to_sql=date2.getFullYear() + '-' + add_zero(monthEnd) + '-' + add_zero(date2.getDate()) + ' 00:00:00';
$("#fromdatedrop").jqxDropDownButton('setContent', <?php echo "'".getTxt('SelectStart')."'";?> );
$("#todatedrop").jqxDropDownButton('setContent', <?php echo "'".getTxt('SelectEnd')."'";?> );

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
});}
});
} //End of function get_dates
	
function plot_chart()
{
var unit_yaxis="unit";
//Adding a Unit Fetcher! Author : Rohit Khattar ChangeDate : 4/11/2013
if (varid != -1)
{
$.ajax({
  type: "GET",
  dataType: "json",
  url: base_url+"variable/getUnit?varid="+varid
}).done(function( msg ) {
  unit_yaxis = msg[0].unitA;
});
}

//Chaning Complete Data loading technique..need to create a php page that will output javascript...
var url_test=base_url+'datapoint/getData?siteid='+siteid+'&varid='+varid+'&meth='+methodid+'&startdate='+date_from_sql+'&enddate='+date_to_sql;
$.ajax({
  url: url_test,
  type: "GET",
  dataType: "script"
}).done(function( datatest ) {
   
var date_chart_from=glob_df.getFullYear() + '-' + add_zero((glob_df.getMonth()+1)) + '-' + add_zero(glob_df.getDate());
var date_chart_to=glob_dt.getFullYear() + '-' + add_zero((glob_dt.getMonth()+1)) + '-' + add_zero(glob_dt.getDate());
 
  
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
	text: <?php echo "'".getTxt('Dataof')."'"; ?>+" "+<?php echo "'".$site['SiteName']."'"; ?>+" "+ <?php echo "'".getTxt('From')."'"; ?>+" "+ date_chart_from +" "+  <?php echo "'".getTxt('To')."'"; ?>+" " + date_chart_to,
		style: {
                fontSize: '12px'
            }
    },
	
	
        credits: {
            enabled: false
        },
	
	 subtitle: {
	text: <?php echo "'".getTxt('ClickDrag')."'"; ?>
    },
	
   xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                 month: '%e.%b / %Y',
                year: '%b.%Y'
            },
			title: {
		text: <?php echo "'".getTxt('TimeMsg')."'"; ?>,
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
		    text: <?php echo "'".getTxt('OneD')."'"; ?>
                },
				{
                    type: 'day',
                    count: 3,
		    		text: <?php echo "'".getTxt('ThreeD')."'"; ?>
                }, {
                    type: 'week',
                    count: 1,
		    		text: <?php echo "'".getTxt('OneW')."'"; ?>
                }, {
                    type: 'month',
                    count: 1,
		   			text: <?php echo "'".getTxt('OneM')."'"; ?>
                }, {
                    type: 'month',
                    count: 6,
		   			text: <?php echo "'".getTxt('SixM')."'"; ?>
                }, {
                    type: 'year',
                    count: 1,
		    		text: <?php echo "'".getTxt('OneY')."'"; ?>
                }, {
                    type: 'all',
		    		text: <?php echo "'".getTxt('All')."'"; ?>
                }],
            selected: 6
            },
	
	
     series: [{
            data: data_test,
			name: displayVar +'('+displayType+')'     
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
var url=base_url+'datapoint/getDataJSON?siteid='+siteid+'&varid='+varid+'&meth='+methodid+'&startdate='+date_from_sql+'&enddate='+date_to_sql;

var source12 =
            {
                datatype: "json",
                datafields: [
                    { name: 'ValueID'},
                    { name: 'DataValue'},
                    { name: 'LocalDateTime'}
                ],
				
                url: url
            };
var dataAdapter12 = new $.jqx.dataAdapter(source12);   

//Adding a Unit Fetcher! Author : Rohit Khattar ChangeDate : 11/4/2013
var unitGrid = "Unit: None";
$.ajax({
 dataType: "json",
 url: base_url+"variable/getUnit?varid="+varid
}).done(function( msg ) {
  unitGrid = msg[0].unitA;
  if (flag==1)    
{
   $("#jqxgrid").jqxGrid(
            {
             
                source: dataAdapter12,
               
                columns: [
				  { text: '<?php echo str_replace(':',' ID',getTxt('Value')); ?>', datafield: 'ValueID', width: 90 },
                  { text: '<?php echo getTxt('Date'); ?>', datafield: 'LocalDateTime', width: 200 },
	             { text: '<?php echo str_replace(':','',getTxt('Value')); ?>  (' + unitGrid +')' , datafield: 'DataValue', width: 200} <?php
     if(isLoggedIn())
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
					 
					 var datepart=dataRecord.LocalDateTime.split(' ');
					 
		
					  $('#popupWindow').jqxWindow('show');
                    // $('#date').val(datepart[0]);
					 
					 $('#date').jqxDateTimeInput({ width: '125px', height: '25px', theme: 'darkblue', formatString: 'MM/dd/yyyy', textAlign: 'center' });
                     
					 var dateparts=datepart[0].split('-');
					 $('#date').jqxDateTimeInput('setDate', new Date(dateparts[0], dateparts[1]-1, dateparts[2])); 
					var timepart=datepart[1].split(':')
					$('#timepicker').val(timepart[0]+':'+timepart[1]);
					// $('#timepicker').timepicker('setTime',timepart[0]+':'+timepart[1]);
					
					 
					 $('#value').val(dataRecord.DataValue);
					 vid=dataRecord.ValueID;
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
			  { text: '<?php echo str_replace(':',' ID',getTxt('Value')); ?>', datafield: 'ValueID', width: 90 },
                  { text: '<?php echo getTxt('Date'); ?>', datafield: 'LocalDateTime', width: 200 },
	          { text: '<?php echo str_replace(':','',getTxt('Value')); ?> (' + unitGrid +')', datafield: 'DataValue', width: 200} <?php
      	if(isLoggedIn())
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
					 var datepart=dataRecord.LocalDateTime.split(' ');
					 
		
					 
                    // $('#date').val(datepart[0]);
					 
					 $('#date').jqxDateTimeInput({ width: '125px', height: '25px', theme: 'darkblue', formatString: 'MM/dd/yyyy', textAlign: 'center' });
                     
					 var dateparts=datepart[0].split('-');
					 $('#date').jqxDateTimeInput('setDate', new Date(dateparts[0], dateparts[1]-1, dateparts[2])); 
					var timepart=datepart[1].split(':')
					$('#timepicker').val(timepart[0]+':'+timepart[1]);
					// $('#timepicker').timepicker('setTime',timepart[0]+':'+timepart[1]);
					
					 
					 $('#value').val(dataRecord.DataValue);
					 vid=dataRecord.ValueID;
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

$("#popupWindow").jqxWindow({ width: 300, resizable: false, theme: 'darkblue', isModal: true, autoOpen: false, cancelButton: $("#Cancel"), modalOpacity: 0.01 });
$( "#timepicker" ).timepicker({ showOn: "focus", showPeriodLabels: false });
$("#delval").jqxButton({ theme: 'darkblue' });
$("#Cancel").jqxButton({ theme: 'darkblue' });
$("#Save").jqxButton({ theme: 'darkblue'});
//Delete Value
$("#delval").unbind("click"); //Multiple events are getting binded for some reason. This makes sure that doesn't happen. 
$("#delval").click(function () {
//Send out a delete request		
$.ajax({
	dataType: "json",
	url: base_url+"datapoint/delete/"+vid
}).done(function(result) {
  if(result.status=='success')
  {
//Remove that row from the table
$('#jqxgrid').jqxGrid('deleterow', editrow);  //This one might be having issues.       
$("#popupWindow").jqxWindow('hide');
  }
});
});
// update the edited row when the user clicks the 'Save' button.
$("#Save").unbind("click");
$("#Save").click(function () {
if (editrow >= 0) {		
var seldate= $('#date').jqxDateTimeInput('getDate'); 
var row = {date: seldate.getFullYear() + '-' + add_zero((seldate.getMonth()+1)) + '-' + add_zero(seldate.getDate())+' '+$("#timepicker").val()+':00', Value: $("#value").val(), vid: vid};
var vt = $("#value").val();  
//Validate
if(validatenum()==false){
		return false;
	}
//Time checking
result=validatetime();
	if(result==false){
		return false;
	}		

//Send out an ajax request to update that data field
	 $.ajax({
  dataType: "json",
  url: base_url+"datapoint/edit/"+vid+"?val="+vt+"&dt="+seldate.getFullYear() + '-' + add_zero((seldate.getMonth()+1)) + '-' + add_zero(seldate.getDate())+"&time="+$("#timepicker").val()
}).done(function( msg )
 {
  if(msg.status=='success')
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
  <?php
      if(isLoggedIn())
	  {
		echo('$("#addnew").jqxButton({ width: \'250\', height: \'25\', theme: \'darkblue\'});
$("#addnew").bind(\'click\', function () {
$("#popupWindow_new").jqxWindow(\'show\');
var offset = $("#jqxgrid").offset();
$("#popupWindow_new").jqxWindow({ position: { x: parseInt(offset.left) + 220, y: parseInt(offset.top) + 60} });
$("#date_new").jqxDateTimeInput({ width: \'125px\', height: \'25px\', theme: \'darkblue\', formatString: "MM/dd/yyyy", textAlign: "center" });
$( "#timepicker_new" ).timepicker({ showOn: "focus", showPeriodLabels: false });

 });');
	  }
      ?>


$("#Save_new").unbind("click");
$("#Save_new").bind('click', function () {
var vt = $("#value_new").val();
//Validate
if(validatenum_new()==false){
		return false;
}
//Time checking
if(validatetime_new()==false){
		return false;
}		

var seldate= $('#date_new').jqxDateTimeInput('getDate'); 


//Send out ajax request to add new value

 $.ajax({
  dataType: "json",
  url: base_url+"datapoint/add?varid="+varid+"&val="+vt+"&dt="+seldate.getFullYear() + '-' + add_zero((seldate.getMonth()+1)) + '-' + add_zero(seldate.getDate())+"&time="+$("#timepicker_new").val()+"&sid="+siteid+"&mid="+methodid
}).done(function( msg )
 {
    if(msg.status=='success')
  { 
	$("#popupWindow_new").jqxWindow('hide');
		plot_chart(); 
  }
  else
  {
	alert(<?php echo "'".getTxt('DatabaseConfigurationError')."'"; ?>);
	return false;  
  }
});



});




//End of adding a new value

//Export Button

$("#export").jqxButton({ width: '250', height: '25', theme: 'darkblue'});
$("#export").bind('click', function () {

var url=base_url+'datapoint/export?siteid='+siteid+'&varid='+varid+'&meth='+methodid+'&startdate='+date_from_sql+'&enddate='+date_to_sql;

window.open(url,'_blank');

                });

//End of Exporting

//Comparing

//Define the button for comaprision

$("#compare").jqxButton({ width: '250', height: '25', theme: 'darkblue'});
$('#window').jqxWindow('destroy');
$('#mapOuter').empty();
$('#window').jqxWindow({ maxHeight: 800, maxWidth: 800, minHeight: 200, minWidth: 200, height: 520, width: 720, theme: 'darkblue' });
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
$('#windowContent').load(base_url+'datapoint/compare/1', function() {
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

<?php HTML_Render_Body_Start(); ?>

      <p>&nbsp;</p>
<table width="630" border="0">
	<tr>
	<td colspan="4"><?php  
echo("<p align='center'><b>".getTxt('Site')."</b>".$site['SiteName']."</p>");
?></td>
          </tr>
        <tr>
          <td width="67">&nbsp;</td>
          <td width="239">&nbsp;</td>
          <td width="55">&nbsp;</td>
          <td width="221">&nbsp;</td>
        </tr>
        <tr>
    	  <td><strong><?php echo getTxt('Variable'); ?></strong></td>
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
          <td><div id='typelist_text'><strong><?php echo getTxt('Type'); ?> </strong></div></td>

          <td><div id='typelist'></div></td>
          <td><div id='methodlist_text'><strong><?php echo getTxt('Method'); ?></strong></div></td>
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
           <td colspan="4"><div id="loadingtext" class="loading"><?php echo getTxt('PleaseWait'); ?><br/>
    </div>
  <div id='jqxtabs'>
    <ul style='margin-left: 20px;'>
      <li><?php echo getTxt('SiteInfo'); ?></li>
      <li><?php echo getTxt('DataPlot'); ?></li>
      <li><?php echo getTxt('DataTable'); ?></li>
      </ul>
    <div>
<?php  


echo("<b>".getTxt('Site').": </b>".$site['SiteName']."<br/>");


if($site['picname']==null) {
	if(isLoggedIn()) {
		echo("<br><br>  ".getTxt('NoImages')."  <a href='".site_url('sites/edit/'.$SiteID)."'> ".getTxt('ClickHere')." </a>");	
		}
	else {	
		echo("<br><br> ".getTxt('NoImages'));
	}

} else {
	echo("<br><br><img src='".getImg('imagesite/'.$site['picname'])."' width='368' height='250'>");
}

echo("<br/><br/><b>".getTxt('Type')." </b>".translateTerm($site['SiteType'])."<br/><br/><b>".getTxt('Latitude')." </b>".$site['Latitude']."<br/><br/><b>".getTxt('Longitude')." </b>".$site['Longitude']."<br /><br/><br/><b>".getTxt('Measurements')."</b>");
$num_rows = count($Variables);
$count=1;
foreach($Variables as $var)
{
if($var['VariableName']!="")
{	
	echo($var['VariableName']);
	if($count!=$num_rows)
	{echo "; ";}
}
  $count=$count+1;
}



?>
 <br/><br/>
<?php echo getTxt('WrongSite'); ?><a href="<?php echo site_url('sites/map'); ?>" style="color:#00F"><?php echo ' '.getTxt('Here'); ?></a> <?php echo getTxt('GoBack'); ?> </div>

    <div>
   
      <div id="container" style="height: 470px"></div>
<!-- Button to compare data values-->
  <input type="button" style=" float:right" value="<?php echo getTxt('Compare');?>" id='compare' />


      </div>
    <div>
      <div id="jqxgrid"></div>
        <div id="popupWindow">
            <div><?php echo getTxt('Edit'); ?></div>
            <div style="overflow: hidden;">
                <table>
                    <tr>
                        <TD colspan="2"><?php echo getTxt('ChangeValues'); ?></td>

                    </tr>
                    <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
                    <tr>
                        <td align="right"><?php echo getTxt('Date'); ?></td>

                        <td align="left"><div id="date"</div></td>
                    </tr>
                    <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
                    <tr>
                        <td align="right"><?php echo getTxt('Time');?></td>
                        <td align="left"> <input type="text" id="timepicker" name="timepicker" onChange="validatetime()" size="10"></td>
                    </tr>
               <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
          
                    <tr>
                        <td align="right"><?php echo getTxt('Value'); ?> </td>
                        <td align="left"><input id="value" onBlur="validatenum()"/></td>
                    </tr>
                    <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
             
                    <tr>
                        <td align="right"></td>
                        <td style="padding-top: 10px;" align="right"><input style="margin-right: 5px;" type="button" id="Save" value="<?php echo getTxt('Save');?>" /><input id="delval" type="button" value="<?php echo getTxt('Delete');?>" />&nbsp;<input id="Cancel" type="button" value="<?php echo getTxt('Cancel'); ?>" /></td>
                    </tr>
                </table>
            </div>
       </div>
          <div id="popupWindow_new">
            <div><?php echo getTxt('Add'); ?></div>
            <div style="overflow: hidden;">
                <table>
                    <tr>
                        <TD colspan="2"><?php echo getTxt('EnterValues'); ?></td>
                    </tr>
                    <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
                    <tr>
                        <td align="right"><?php echo getTxt('Date'); ?></td>
                        <td align="left"><div id="date_new"</div></td>
                    </tr>
                    <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
                    <tr>
                        <td align="right"><?php echo getTxt('Time'); ?></td>
                        <td align="left"> <input type="text" id="timepicker_new" name="timepicker_new" onChange="validatetime_new()" size="10"></td>
                    </tr>
               <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
          
                    <tr>
                        <td align="right"><?php echo getTxt('Value'); ?></td>
                        <td align="left"><input id="value_new" onBlur="validatenum_new()"/></td>
                    </tr>
                    <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
             
                    <tr>
                        <td align="right"></td>
                       <td style="padding-top: 10px;" align="right"><input style="margin-right: 5px;" type="button" id="Save_new" value="<?php echo getTxt('Save'); ?>" /><input id="Cancel_new" type="button" value="<?php echo getTxt('Cancel'); ?>" /></t>
                    </tr>
                </table>
            </div>
       </div>
         <br/>
      <div style="alignment-adjust: middle; float:right;">
     <?php
	if(isLoggedIn())
	  {
		echo("<input type='button' value='".getTxt('AddRow')."' id='addnew' /> <br/>  <br/>");
	  }
      ?>
        <input type="button" value="<?php echo getTxt('DownloadData');?>" id='export' />
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
      </table>

<div id="window">
 <div id="windowHeader">
 <span><?php echo getTxt('CompareTwo'); ?></span>
   </div>
 <div style="overflow: hidden;" id="windowContent">
 </div>
  </div>
<div id="window2">
 <div id="window2Header">
 <span><?php echo getTxt('CompareTwo'); ?></span>
   </div>
 <div style="overflow: hidden;" id="window2Content">
 </div>
  </div>
  <div id="window3">
 <div id="window3Header">
 <span><?php echo getTxt('CompareTwo'); ?></span>
   </div>
 <div style="overflow: hidden;" id="window3Content">
 </div>
  </div>
   <div id="window4">
 <div id="window4Header">
 <span><?php echo getTxt('CompareTwo'); ?></span>
   </div>
 <div style="overflow: hidden;" id="window4Content">
 </div>
  </div>
   <div id="window5">
 <div id="window5Header">
 <span><?php echo getTxt('CompareTwo'); ?></span>
   </div>
 <div style="overflow: hidden;" id="window5Content">
 </div>
  </div>
	<?php HTML_Render_Body_End(); ?>
