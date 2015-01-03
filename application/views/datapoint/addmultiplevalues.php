<?php
HTML_Render_Head($js_vars);
?>
<script type="text/javascript">
//For loading language variables that are required in javascript.
    phpVars = {};
<?php  
        echo 'phpVars.NoMethodsVariable="' . getTxt('NoMethodsVariable') . '";';
		echo 'phpVars.NoSitesSource="' . getTxt('NoSitesSource') . '";';
		echo 'phpVars.SelectVariable="' . getTxt('SelectVariable') . '";';
		echo 'phpVars.SelectSite="' . getTxt('SelectSite2') . '";';
?>
</script>
<?php
echo $JS_FormValidation;
echo $CSS_JQuery_UI;
echo $JS_JQuery;
echo $JS_JQueryUI;
echo $JS_Forms;
echo $JS_GetTheme;
echo $JS_JQX;
echo $CSS_JQStyles;
echo $CSS_JQX;
echo $CSS_Main;
?>
<script type="text/javascript">
var glob_siteid=1;
var row_no=1;
var row_id=new Array;
row_id[0]="VariableID1";
var source =
        {
            datatype: "json",
            datafields: [
                { name: 'VariableID' },
                { name: 'VariableName' },
            ],
            url: base_url+'variable/getAllJSON'
        };				
var dataAdapter = new $.jqx.dataAdapter(source);
$(document).ready(function () {
//Creating the Drop Down list
$("#VariableID1").jqxDropDownList(
{
	source: dataAdapter,
	theme: 'darkblue',
	width: 200,
	height: 25,
	selectedIndex: 0,
	displayMember: 'VariableName',
	valueMember: 'VariableID'
});	
$('#VariableID1').bind('select', function (event) {
var args = event.args;
var item = $('#VariableID1').jqxDropDownList('getItem', args.index);
if ((item != null)&&(item.label != "Select..")) {	
//Create a another jqery list for methods
var varid=item.value;
var source1 =
        {
            datatype: "json",
            datafields: [
                { name: 'MethodID' },
                { name: 'MethodDescription' },
            ],
            url: base_url+'methods/getMethodsJSON?var='+varid
        };				
var dataAdapter21 = new $.jqx.dataAdapter(source1);
$("#MethodID1").jqxDropDownList(
        {
            source: dataAdapter21,
            theme: 'darkblue',
            width: 200,
            height: 25,
            selectedIndex: 0,
            displayMember: 'MethodDescription',
            valueMember: 'MethodID'
        });		
}

for(var i=1;i<2;i++)
{
addnew('value'+i);	
}
});
runa();
});
			
function runa(){
$('input[id^="value"]').click(function() {
  addnew(this.id);
});
$('input[id^="value"]').change(function() {
  addnew(this.id);
});			
$('input[id^="datepicker"]').change(function() {
  var result=validatedate(this.id);
});			
$('input[id^="timepicker"]').change(function() {
  var result1=validatetime(this.id);
});			
}
	$(function() {

		$( "#datepicker1" ).datepicker({ dateFormat: "yy-mm-dd",
										 monthNames: [
											<?php echo "'".getTxt('Jan')."'"; ?>, 
											<?php echo "'".getTxt('Feb')."'"; ?>, 
											<?php echo "'".getTxt('Mar')."'"; ?>, 
											<?php echo "'".getTxt('Apr')."'"; ?>, 
											<?php echo "'".getTxt('May')."'"; ?>, 
											<?php echo "'".getTxt('Jun')."'"; ?>, 
											<?php echo "'".getTxt('Jul')."'"; ?>, 
											<?php echo "'".getTxt('Aug')."'"; ?>, 
											<?php echo "'".getTxt('Sep')."'"; ?>, 
											<?php echo "'".getTxt('Oct')."'"; ?>, 
											<?php echo "'".getTxt('Nov')."'"; ?>, 
											<?php echo "'".getTxt('Dec')."'"; ?>], 
										 dayNamesMin: [
											<?php echo "'".getTxt('Su')."'"; ?>, 
											<?php echo "'".getTxt('Mo')."'"; ?>, 
											<?php echo "'".getTxt('Tu')."'"; ?>, 
											<?php echo "'".getTxt('We')."'"; ?>, 
											<?php echo "'".getTxt('Th')."'"; ?>, 
											<?php echo "'".getTxt('Fr')."'"; ?>, 
											<?php echo "'".getTxt('Sa')."'"; ?>] 
										});
		
		
		$( "#timepicker1" ).timepicker({
			showOn: "focus",
    		showPeriodLabels: false,
			hourText: <?php echo "'".getTxt('Hour')."'";?>, 
			minuteText: <?php echo "'".getTxt('Minute')."'"; ?>, 
			closeButtonText: <?php echo "'".getTxt('Done')."'"; ?>, 
			nowButtonText: <?php echo "'".getTxt('Now')."'"; ?>, 
			deselectButtonText: <?php echo "'".getTxt('Deselect')."'"; ?>, 
		});
		
	});





function addnew(value_id_new)
{

var value_id='value'+row_no;

if(value_id_new==value_id)
{

row_no=row_no+1;
row_id.push("VariableID"+row_no);


var newid=row_id[row_id.length-1];


var add_html='<tr><td width="182"><div id="VariableID'+row_no+'"></div></td> <td width="249"><div id="MethodID'+row_no+'"></div></td><td width="60"><center><input type="text" id="datepicker'+row_no+'" name="datepicker'+row_no+'" class="short" /></center></td><td width="46"><center><input type="text" name="timepicker'+row_no+'" id="timepicker'+row_no+'" class="short" maxlength="10"></center></td><td width="51"><center><input type="text" id="value'+row_no+'" name="value'+row_no+'" onblur="runa()" class="tiny" maxlength="20"/></center></td></tr>';

$('#multiple tr:last').after(add_html);

//Implement required javascript functions


//Creating the Drop Down list

     $('#' + newid).jqxDropDownList(
        {
            source: dataAdapter,
            theme: 'darkblue',
            width: 200,
            height: 25,
            selectedIndex: 0,
            displayMember: 'VariableName',
            valueMember: 'VariableID'
        });		
				

$('#' + newid).bind('select', function (event) {
var args = event.args;
var item = $(this).jqxDropDownList('getItem', args.index);
if ((item != null)&&(item.label != "Select..")) 
{
//Create a another jqery list for methods
var varid=item.value;

var source1 =
        {
            datatype: "json",
            datafields: [
                { name: 'MethodID' },
                { name: 'MethodDescription' },
            ],
            url: base_url+'methods/getMethodsJSON?var='+varid
        };				
	
	
		var dataAdapter21 = new $.jqx.dataAdapter(source1);
			
var tempid='MethodID'+newid.slice(10, newid.length);
 
 $('#' + tempid).jqxDropDownList(
        {
            source: dataAdapter21,
            theme: 'darkblue',
            width: 200,
            height: 25,
            selectedIndex: 0,
            displayMember: 'MethodDescription',
            valueMember: 'MethodID'
        });		
}

});


newid="datepicker"+row_no;

	$('#' + newid).datepicker({ dateFormat: "yy-mm-dd",  
								monthNames: [
									<?php echo "'".getTxt('Jan')."'"; ?>, 
									<?php echo "'".getTxt('Feb')."'"; ?>, 
									<?php echo "'".getTxt('Mar')."'"; ?>, 
									<?php echo "'".getTxt('Apr')."'"; ?>, 
									<?php echo "'".getTxt('May')."'"; ?>, 
									<?php echo "'".getTxt('Jun')."'"; ?>, 
									<?php echo "'".getTxt('Jul')."'"; ?>, 
									<?php echo "'".getTxt('Aug')."'"; ?>, 
									<?php echo "'".getTxt('Sep')."'"; ?>, 
									<?php echo "'".getTxt('Oct')."'"; ?>, 
									<?php echo "'".getTxt('Nov')."'"; ?>, 
									<?php echo "'".getTxt('Dec')."'"; ?>], 
								dayNamesMin: [
									<?php echo "'".getTxt('Su')."'"; ?>, 
									<?php echo "'".getTxt('Mo')."'"; ?>, 
									<?php echo "'".getTxt('Tu')."'"; ?>, 
									<?php echo "'".getTxt('We')."'"; ?>, 
									<?php echo "'".getTxt('Th')."'"; ?>, 
									<?php echo "'".getTxt('Fr')."'"; ?>, 
									<?php echo "'".getTxt('Sa')."'"; ?>] 
									});
	
$('#' + newid).change(function() {
  var result=validatedate(this.id);
});			
	
newid="timepicker"+row_no;		
		
		$('#' + newid).timepicker({
			showOn: "focus",
    		showPeriodLabels: false,
			hourText: <?php echo "'".getTxt('Hour')."'";?>, 
			minuteText: <?php echo "'".getTxt('Minute')."'"; ?>, 
			closeButtonText: <?php echo "'".getTxt('Done')."'"; ?>, 
			nowButtonText: <?php echo "'".getTxt('Now')."'"; ?>, 
			deselectButtonText: <?php echo "'".getTxt('Deselect')."'"; ?>, 
		});


}


$('#' + newid).change(function() {
  var result1=validatetime(this.id);
});			

}
</script>
<?php HTML_Render_Body_Start(); 
genHeading('EnterMultipleValuesManually',true);	
echo getTxt('EnterDataTableAppears');
$attributes = array('class' => 'form-horizontal', 'name' =>'addvalue');
echo form_open('datapoint/addmultiplevalues', $attributes);
genSelect('Source',"SourceID","SourceID",$sourcesOptions,'SelectEllipsis',true,'onChange="showSites(this.value)"');
genSelectH('Site',"SiteID","SiteID",'',getTxt('IfNoSeeSite1').' '.getTxt('ContactSupervisor').' '.getTxt('AddIt'),'SelectElipsis',true);
?>
      <table width="600" border="1" cellpadding="0" cellspacing="0" id="multiple">
        <tr>
          <td width="182"><center><strong><?php echo getTxt('Variable');?>&nbsp;*</strong></center></td>
          <td width="249"><center><strong><?php echo getTxt('Method');?>&nbsp;*</strong>&nbsp;<span class="hint" title="<?php echo "'". getTxt('IfNoSeeMethod1')."'";?> + <?php echo "'". getTxt('ContactSupervisor')."'";?> + <?php echo "'". getTxt('AddIt')."'";?>">?</span></center></td>
          <td width="60"><center><strong><?php echo getTxt('Date');?>&nbsp;*</strong>&#8224;</center></td>
          <td width="46"><center><strong><?php echo getTxt('Time');?>&nbsp;</strong>&#8224;</center></td>
          <td width="51"><center><strong><?php echo getTxt('Value');?>&nbsp;</strong>&#8224;</center></td>
          </tr>
        <tr>
          <td width="182" bgcolor="#0099FF">&nbsp;</td>
          <td width="249" bgcolor="#0099FF">&nbsp;</td>
          <td width="60" bgcolor="#0099FF">&nbsp;</td>
          <td width="46" bgcolor="#0099FF">&nbsp;</td>
          <td width="51" bgcolor="#0099FF">&nbsp;</td>
          </tr>
        <tr>
          <td width="182">
     
           <div id="VariableID1"></div>
            
            </td>
          <td width="249"><div id="MethodID1"></div></td>
          <td width="60"><center><input type="text" id="datepicker1" name="datepicker1"  class="short" maxlength="12"/></center></td>
          <td width="46"><center><input type="text" id="timepicker1" name="timepicker1"  class="short" maxlength="10"></center></td>
          <td width="51"><center><input type="text" id="value1" name="value1" onblur="runa()"  class="tiny" maxlength="20"/></center></td>
          </tr>
      
        
       
      </table>
      
      <br/>
      <center>
      <input type="hidden" id="finalRows" name="finalRows"/>
      <input type="SUBMIT" name="submit" value="<?php echo getTxt('SubmitData');?>" class="button" />&nbsp;&nbsp;<input type="reset" name="Reset" value="<?php echo getTxt('Cancel');?>" class="button" /></center>
        
      </FORM>
      <p>&#8224;<strong><?php echo getTxt('FormattingNotes');?></strong><br />
<span class="em"><?php echo getTxt('FormattingNotesDate');?> <br />
<?php echo getTxt('FormattingNotesTime');?></span><span class="em"><br />
<?php echo getTxt('FormattingNotesValue');?></span><br />
</p>
</div>
	<?php HTML_Render_Body_End(); ?>
<script>

$("form").submit(function() {
//Validate all fields
//Source and site validation
if(($("#SourceID option:selected").val())==-1)
{
alert(<?php echo "'".getTxt('SelectSource')."'";?>);
return false;
}
if(($("#SiteID option:selected").val())==-1)
{
alert(<?php echo "'".getTxt('SelectSite')."'"; ?>);
return false;
}

//Setup form validation for all the rows in which value is present

//First check if there is any data in the last row

var final_rows;
var valid_rows=0;
var checkid='VariableID'+row_no;
var item = $('#' + checkid).jqxDropDownList('getSelectedItem'); 

checkid='MethodID'+row_no;
var item1 = $('#' + checkid).jqxDropDownList('getSelectedItem'); 

checkid='value'+row_no;
if((item.value==-1)||(item1.value==-1)||($('#' + checkid).val()==""))
{
final_rows=row_no-1;
}
else
{
final_rows=row_no;	
}
$("#finalRows").val(final_rows);

//Now we start validating each row
for(var j=1;j<=final_rows;j++)
{
var checkid='VariableID'+j;
var item = $('#' + checkid).jqxDropDownList('getSelectedItem'); 
checkid='MethodID'+j;
var item1 = $('#' + checkid).jqxDropDownList('getSelectedItem'); 
checkid='value'+j;
if((item.value==-1)&&(item1.value==-1)&&($('#' + checkid).val()==""))
{
}
else
{
//first check if variable is selected or not
checkid='VariableID'+j;
item = $('#' + checkid).jqxDropDownList('getSelectedItem'); 

if(item.value==-1)
{
alert(<?php echo "'".getTxt('ErrorInRow')."'";?>+j+": "+<?php echo "'".getTxt('SelectVariableMsg')."'"; ?>);
return false;
}

checkid='MethodID'+j;
item = $('#' + checkid).jqxDropDownList('getSelectedItem'); 

if(item.value==-1)
{
alert(<?php echo "'".getTxt('ErrorInRow')."'";?>+j+": "+<?php echo "'".getTxt('SelectMethodMsg')."'"; ?>);
return false;
}

checkid='datepicker'+j;
if(validatedate(checkid)==false)
{
return false;
}

checkid='timepicker'+j;
if(validatetime(checkid)==false)
{
return false;
}

//Value Check
checkid='value'+j;

if(validatenum(checkid)==false)
{
	alert(<?php echo "'".getTxt('ErrorInRow')."'";?>+j+": "+<?php echo "'".getTxt('EnterValidValue')."'";?>);
return false;
}
valid_rows=valid_rows+1;
}
}
var final_result=1;

if(valid_rows==0)
{
alert(<?php echo "'".getTxt('EnterOneValue')."'"; ?>);
return false;	
}
return true;
});
</script>
