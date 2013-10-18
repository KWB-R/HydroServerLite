<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';
?>
<script type="text/javascript" src="js/jqwidgets/jqxexpander.js"></script>
<script type="text/javascript">
function get_date_c()
{

var url="get_date.php?siteid="+$("#siteidc").val()+"&varid=" + $("#varidc").val()+"&methodid=" + $("#methodidc").val();
$.ajax({
        type: "GET",
	url: url,
	dataType: "xml",
	success: function(xml1) {

//Displaying the Available Dates	
$(xml1).find("dates").each(function()
{
//Displaying the Available Dates
var sitename=String($(this).attr("sitename"));	
var date_from=String($(this).attr("date_from"));
var date_to=String($(this).attr("date_to"));		
//Call the next function to display the data

$('#daterangec').html("");
//$('#daterange').empty();	
//$('#daterangec').prepend('<p><strong>Dates Available:</strong> ' + date_from + ' <strong>to</strong> ' + date_to +'</p>');
$('#daterangec').prepend('<p><strong>'+<?php echo "'".$DatesAvailable."'";?>+'</strong> ' + date_from + ' <strong>'+<?php echo "'".$To."'";?>+'</strong> ' + date_to +'</p>');
$("#c_fromdrop").jqxExpander({ width: '250px', theme: 'darkblue'});
$("#c_todrop").jqxExpander({ width: '250px', theme: 'darkblue'});



$("#jqxDateTimeInputc").jqxDateTimeInput({ width: '250px', height: '25px', formatString: 'd'});
$("#jqxDateTimeInputtoc").jqxDateTimeInput({ width: '250px', height: '25px', formatString: 'd'});



//Restricting the Calendar to those available dates
var year = parseInt(date_from.slice(0,4));
var month = parseInt(date_from.slice(5,7),10);
var day = parseInt(date_from.slice(8,10),10);
month=month-1;
var date1 = new Date();
date1.setFullYear(year, month, day);



//Use Show And Hide Method instead of repeating formation - optimization number 2

$('#jqxDateTimeInputc').jqxDateTimeInput('setDate', date1);
$("#jqxDateTimeInputc").jqxDateTimeInput('setMinDate', new Date(year, month, day));
var year_to = parseInt(date_to.slice(0,4));		
var month_to = parseInt(date_to.slice(5,7),10);
var day_to = parseInt(date_to.slice(8,10),10);	
//month_to=month_to-1;
var date2 = new Date();
date2.setFullYear(year_to, month_to-1, day_to);

$('#jqxDateTimeInputtoc').jqxDateTimeInput('setDate', date2);
$("#jqxDateTimeInputc").jqxDateTimeInput('setMaxDate', new Date(year_to, month_to, day_to)); 
$("#jqxDateTimeInputtoc").jqxDateTimeInput('setMaxDate', new Date(year_to, month_to, day_to)); 
//Plot the Chart with default limits

$("#fromc").val(date1.getFullYear() + '-' + add_zero((date1.getMonth())) + '-' + add_zero(date1.getDate()) + ' 00:00:00');
$("#toc").val(date2.getFullYear() + '-' + add_zero((date2.getMonth()+2)) + '-' + add_zero(date2.getDate()) + ' 00:00:00');


$('#jqxDateTimeInputc').bind('valuechanged', function (event) 
{
	

var date = event.args.date;
var date_select_from=new Date(date);

//Converting to SQL Format for Searching

$("#fromc").val(date_select_from.getFullYear() + '-' + add_zero((date_select_from.getMonth()+1)) + '-' + add_zero(date_select_from.getDate()) + ' 00:00:00');
//Setting the Second calendar's min date to be the date of the first calendar
$("#jqxDateTimeInputtoc").jqxDateTimeInput('setMinDate', date);

});
//Binding An Event To the Second Calendar
$('#jqxDateTimeInputtoc').bind('valuechanged', function (event) {
	
var date = event.args.date;
var date_select_to=new Date(date);
$("#toc").val(date_select_to.getFullYear() + '-' + add_zero((date_select_to.getMonth()+1)) + '-' + add_zero(date_select_to.getDate()) + ' 00:00:00');
});



});
	}
	});
	
}  

$("#all").click(function(){
plot_chart_c();
});
$("#selected").click(function(){
plot_chart_c();
});


function plot_chart_c()
{


var url_test='db_get_data.php?siteid='+$("#siteidc").val()+'&varid='+$("#varidc").val()+'&meth='+$("#methodidc").val()+'&startdate='+$("#fromc").val()+'&enddate='+$("#toc").val();


$.ajax({
  url: url_test,
  type: "GET",
  dataType: "script"
}).done(function( datatest ) {
   

   chart.addSeries({
            name: $('#varnamec').val()+'('+$('#dtc').val()+')'+' Site: '+$('#sitenamec').val(),
            data: data_test
        });
$('#window5').jqxWindow('hide');
$('#jqxtabs').jqxTabs('select', 1); 
$('html, body').animate({scrollTop: $('html, body').height()}, 800);
var newoptions=chart.setOptions({
	
	legend: {
            enabled: true,
        
        }
});

chart=new Highcharts.StockChart(newoptions);   
   
   alert();
});
	
}

</script>




<table width="630" border="0">
        <tr>
          <td colspan="4"></td>
          </tr>
        <tr>
          <!--<td colspan="4">Please Enter a date range to be plotted on the map for comparision or click 'Plot All' to plot all available data.</td>-->
          <td colspan="4"><?php echo $EnterDateRange;?></td>
        </tr>
        <tr>
          <td width="67">&nbsp;</td>
          <td width="239"></td>
          <td width="55">&nbsp;</td>
          <td width="221">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4"><div id='daterangec'></div></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><div id='c_fromdrop'><div>
                <!--Select From Date--><?php echo $FromDate;?></div>
            <div id="jqxDateTimeInputc">
               
            </div></div></td>
          <td colspan="2"><div id='c_todrop'>
          <div>
                <!--Select To Date--><?php echo $ToDate;?></div>
            <div id="jqxDateTimeInputtoc">
               
            </div></div></td>
          </tr>
           <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
          <tr>
          <td>&nbsp;</td>
          <!--<td> <input type="button" value="Plot All" id='all' /></td>-->
          <td> <input type="button" value="<?php echo $PlotAll;?>" id='all' /></td>
          <td>&nbsp;</td>
          <!--<td> <input type="button" value="Plot Selected Range" id='selected' /></td>-->
          <td> <input type="button" value="<?php echo $PlotSelectedRange;?>" id='selected' /></td>
        </tr>
      </table>
 <input style=" visibility:hidden"id="fromc" type="text" disabled/>
  <input style=" visibility:hidden"id="toc" type="text" disabled/>
