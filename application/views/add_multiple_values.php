<?php
/*

$option_block = "";
$option_block3 = "";
$msg = "";
$msg3= "";
$msg4= "";

//add the SourceID's
$sql ="Select distinct SourceID, Organization FROM seriescatalog";

$result = transQuery($sql,0,0);

$num = count($result);
	if ($num < 1) {

	$msg = "<P class= em2> $SorryNoSource Please add a source. Data values cannot be added without a source.</p>";

	} else {

	foreach ($result as $row) {

		$sourceid = $row["SourceID"];
		$sourcename = $row["Organization"];

		$option_block .= "<option value=$sourceid>$sourcename</option>";

		}
	}

//add the Variables
$sql3 ="Select * FROM variables ORDER BY VariableName ASC";

$data = transQuery($sql3,0,1);

$num = count($data);
	if ($num < 1) {

   $msg3 = "<P class=em2>$SorryNoVariable Please add a variable. Data values cannot be added without a source.</em></p>";

	} else {

	foreach ($data as $row3) {
		$typeid = $row3["VariableID"];
		$typename = $row3["VariableName"];
		$datatype = $row3["DataType"];

		$option_block3 .= "<option value=$typeid>$typename ($datatype)</option>";

		}
	}
*/
	HTML_Render_Head();

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
                { name: 'variableid' },
                { name: 'variablename' },
            ],
            url: 'db_get_types.php'
        };				
	
	
		var dataAdapter = new $.jqx.dataAdapter(source);
            $(document).ready(function () {

$("#viewdata").click(function() {
	window.location.href = "details.php?siteid="+glob_siteid;
	
});	


$("#viewdata2").click(function() {
	window.location.href = "add_multiple_values.php";
	
});	

				$("#statusmsg").hide();
	
//Creating the Drop Down list
        $("#VariableID1").jqxDropDownList(
        {
            source: dataAdapter,
            theme: 'darkblue',
            width: 200,
            height: 25,
            selectedIndex: 0,
            displayMember: 'variablename',
            valueMember: 'variableid'
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
                { name: 'methodid' },
                { name: 'methodname' },
            ],
            url: 'getmethods.php?m='+varid
        };				
	
	
		var dataAdapter21 = new $.jqx.dataAdapter(source1);
		
 $("#MethodID1").jqxDropDownList(
        {
            source: dataAdapter21,
            theme: 'darkblue',
            width: 200,
            height: 25,
            selectedIndex: 0,
            displayMember: 'methodname',
            valueMember: 'methodid'
        });		
}

//Create 10 rows in the beginning

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
											<?php echo "'".$Jan."'"; ?>, 
											<?php echo "'".$Feb."'"; ?>, 
											<?php echo "'".$Mar."'"; ?>, 
											<?php echo "'".$Apr."'"; ?>, 
											<?php echo "'".$May."'"; ?>, 
											<?php echo "'".$Jun."'"; ?>, 
											<?php echo "'".$Jul."'"; ?>, 
											<?php echo "'".$Aug."'"; ?>, 
											<?php echo "'".$Sep."'"; ?>, 
											<?php echo "'".$Oct."'"; ?>, 
											<?php echo "'".$Nov."'"; ?>, 
											<?php echo "'".$Dec."'"; ?>], 
										 dayNamesMin: [
											<?php echo "'".$Su."'"; ?>, 
											<?php echo "'".$Mo."'"; ?>, 
											<?php echo "'".$Tu."'"; ?>, 
											<?php echo "'".$We."'"; ?>, 
											<?php echo "'".$Th."'"; ?>, 
											<?php echo "'".$Fr."'"; ?>, 
											<?php echo "'".$Sa."'"; ?>] 
										});
		
		
		$( "#timepicker1" ).timepicker({
			showOn: "focus",
    		showPeriodLabels: false,
			hourText: <?php echo "'".$Hour."'";?>, 
			minuteText: <?php echo "'".$Minute."'"; ?>, 
			closeButtonText: <?php echo "'".$Done."'"; ?>, 
			nowButtonText: <?php echo "'".$Now."'"; ?>, 
			deselectButtonText: <?php echo "'".$Deselect."'"; ?>, 
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


var add_html='<tr><td width="182"><div id="VariableID'+row_no+'"></div></td> <td width="249"><div id="MethodID'+row_no+'"></div></td><td width="60"><center><input type="text" id="datepicker'+row_no+'" class="short" /></center></td><td width="46"><center><input type="text" id="timepicker'+row_no+'" class="short" maxlength="10"></center></td><td width="51"><center><input type="text" id="value'+row_no+'" name="value'+row_no+'" onblur="runa()" class="tiny" maxlength="20"/></center></td></tr>';

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
            displayMember: 'variablename',
            valueMember: 'variableid'
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
                { name: 'methodid' },
                { name: 'methodname' },
            ],
            url: 'getmethods.php?m='+varid
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
            displayMember: 'methodname',
            valueMember: 'methodid'
        });		
}

});


newid="datepicker"+row_no;

	$('#' + newid).datepicker({ dateFormat: "yy-mm-dd",  
								monthNames: [
									<?php echo "'".$Jan."'"; ?>, 
									<?php echo "'".$Feb."'"; ?>, 
									<?php echo "'".$Mar."'"; ?>, 
									<?php echo "'".$Apr."'"; ?>, 
									<?php echo "'".$May."'"; ?>, 
									<?php echo "'".$Jun."'"; ?>, 
									<?php echo "'".$Jul."'"; ?>, 
									<?php echo "'".$Aug."'"; ?>, 
									<?php echo "'".$Sep."'"; ?>, 
									<?php echo "'".$Oct."'"; ?>, 
									<?php echo "'".$Nov."'"; ?>, 
									<?php echo "'".$Dec."'"; ?>], 
								dayNamesMin: [
									<?php echo "'".$Su."'"; ?>, 
									<?php echo "'".$Mo."'"; ?>, 
									<?php echo "'".$Tu."'"; ?>, 
									<?php echo "'".$We."'"; ?>, 
									<?php echo "'".$Th."'"; ?>, 
									<?php echo "'".$Fr."'"; ?>, 
									<?php echo "'".$Sa."'"; ?>] 
									});
	
$('#' + newid).change(function() {
  var result=validatedate(this.id);
});			
	
newid="timepicker"+row_no;		
		
		$('#' + newid).timepicker({
			showOn: "focus",
    		showPeriodLabels: false,
			hourText: <?php echo "'".$Hour."'";?>, 
			minuteText: <?php echo "'".$Minute."'"; ?>, 
			closeButtonText: <?php echo "'".$Done."'"; ?>, 
			nowButtonText: <?php echo "'".$Now."'"; ?>, 
			deselectButtonText: <?php echo "'".$Deselect."'"; ?>, 
		});


}


$('#' + newid).change(function() {
  var result1=validatetime(this.id);
});			

}

function showSites(str){

document.getElementById("txtHint").innerHTML="<a href='#' onClick='show_answer()' border='0'><img src='images/questionmark.png' border='0'></a>";

if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","getsites.php?q="+str,true);
xmlhttp.send();
}
</script>
	<?php HTML_Render_Body_Start(); ?>
<br /><p class="em" align="right"><span class="requiredInstructions"><?php echo getTxt('RequiredFieldsAsterisk'); ?></span></p><?php echo getTxt('msg'); ?>&nbsp;<?php echo getTxt('msg3'); ?>&nbsp;<?php echo getTxt('msg4'); ?>
   <h1><?php echo getTxt('EnterMultipleValuesManually');?></h1>
      <p><?php echo getTxt('EnterDataTableAppears');?>
	<div id="statusmsg"><p class=em2><?php echo getTxt('DataEnteredSuccessfully');?>
        <input type="button" name="viewdata" id="viewdata" value="<?php echo getTxt('ViewDataInputed');?>" />
        <input type="button" name="viewdata2" id="viewdata2" value="<?php echo getTxt('AddMoreData');?>" />
        </p>
      </div>
    
      <FORM METHOD="POST" ACTION="" name="addvalue">
      <table width="450" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="55" valign="top"><strong><?php echo getTxt('Source');?></strong></td>
          <td width="370" valign="top"><select name="SourceID" id="SourceID" onChange="showSites(this.value)"><option value="-1"><?php echo getTxt('SelectEllipsis'); ?></option><?php echo "$option_block"; ?></select><span class="required">*</span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td valign="top"><strong><?php echo getTxt('Site');?></strong></td>
          <td valign="top"><div id="txtHint"><select name="SiteID" id="SiteID"><option value="-1"><?php echo getTxt('SelectEllipsis'); ?></option></select><span class="required">*</span><span class="hint" title="<?php echo "'". getTxt('IfNoSeeSite1')."'";?>  + <?php echo "'". getTxt('ContactSupervisor')."'";?> + <?php echo "'".getTxt('AddIt')."'";?>">?</span></div></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
      </table>
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
      <center><input type="SUBMIT" name="submit" value="<?php echo getTxt('SubmitData');?>" class="button" />&nbsp;&nbsp;<input type="reset" name="Reset" value="<?php echo getTxt('Cancel');?>" class="button" /></center>
        
      </FORM>
      <p>&#8224;<strong><?php echo getTxt('FormattingNotes');?></strong><br />
<span class="em"><?php echo getTxt('FormattingNotesDate');?> <br />
<?php echo getTxt('FormattingNotesTime');?></span><span class="em"><br />
<?php echo getTxt('FormattingNotesValue');?></span><br />
</p>

	<?php HTML_Render_Body_End(); ?>
<script>

    $("form").submit(function() {
      //Validate all fields

//Source and site validation

if(($("#SourceID option:selected").val())==-1)
{
alert(<?php echo "'".$SelectSource."'";?>);
return false;
}

if(($("#SiteID option:selected").val())==-1)
{
alert(<?php echo "'".$SelectSite."'"; ?>);
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

if((item.value==-1)&&(item1.value==-1)&&($('#' + checkid).val()==""))
{
final_rows=row_no-1;
}
else
{
final_rows=row_no;	
}


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
alert(<?php echo "'".$ErrorInRow."'"?>+j+": "+<?php echo "'".$SelectVariableMsg."'"; ?>);
return false;
}

checkid='MethodID'+j;
item = $('#' + checkid).jqxDropDownList('getSelectedItem'); 

if(item.value==-1)
{
alert(<?php echo "'".$ErrorInRow."'"?>+j+": "+<?php echo "'".$SelectMethodMsg."'"; ?>);
return false;
}

checkid='datepicker'+j;
var result=validatedate(checkid);

if(result==false)
{
	
return false;
}

checkid='timepicker'+j;
var result=validatetime(checkid);
if(result==false)
{
return false;
}

//Value Check
checkid='value'+j;

if(validatenum(checkid)==false)
{
	alert(<?php echo "'".$ErrorInRow."'"?>+j+": "+<?php echo "'".$EnterValidValue."'";?>);
return false;
}

	
var vt = $('#' + checkid).val();
checkid='VariableID'+j;
item = $('#' + checkid).jqxDropDownList('getSelectedItem'); 
var tv=item.value;

switch(tv)
{
case "19":
if((vt<0)||(vt>100))
{
alert(<?php echo "'".$ErrorOnRow."'"; ?> +j+"."+<?php echo "'".$ValueBetweenZeroAndHundred."'";?>);
		return false;
}
break;
case "13":
case "22":
if((vt<0)||(vt>14))
{
alert(<?php echo "'".$ErrorOnRow."'";?>+j+". "+<?php echo "'".$ValueBetweenZeroAndFourteen."'";?>);
		return false;
}
break;
case "7":
case "24":break;
default:
if(vt<0)
{
alert(<?php echo "'".$ErrorOnRow."'";?>+j+". "+<?php echo "'".$ValueLessThanZero."'";?>);
		return false;
}
  break;
}



valid_rows=valid_rows+1;

}
	
}
var final_result=1;
//Validation Complete
//Input data


if(valid_rows==0)
{
alert(<?php echo "'".$EnterOneValue."'"; ?>);
return false;	
}
else
{

var ajax_count=0;
for(var j=1;j<=final_rows;j++)
{

var checkid='VariableID'+j;
var item = $('#' + checkid).jqxDropDownList('getSelectedItem'); 

checkid='MethodID'+j;
var item1 = $('#' + checkid).jqxDropDownList('getSelectedItem'); 

checkid='value'+j;

if((item.value==-1)&&(item1==undefined)&&($('#' + checkid).val()==""))
{

}

else

{




var sourceid=$("#SourceID option:selected").val();
var siteid=$("#SiteID option:selected").val();
glob_siteid=siteid;
checkid='VariableID'+j;
item = $('#' + checkid).jqxDropDownList('getSelectedItem'); 
var variableid=item.value;
checkid='MethodID'+j;
item = $('#' + checkid).jqxDropDownList('getSelectedItem'); 
var methodid=item.value;
checkid='value'+j;
var value1=$('#' + checkid).val();
checkid='datepicker'+j;
var date=$('#' + checkid).val();
checkid='timepicker'+j;
var time=$('#' + checkid).val();

var temp_result=-1;

$.ajax({
  type: "POST",
  url: "do_add_multiple.php?SourceID="+sourceid+"&SiteID="+siteid+"&VariableID="+variableid+"&MethodID="+methodid+"&value="+value1+"&datepicker="+date+"&timepicker="+time
}).done(function( msg ) {
  if(msg==1)
  {
	  ajax_count=ajax_count+1;
 if(ajax_count==valid_rows)
 {
	 
	 
	 $("#statusmsg").show(1200);
	 //Clear out all the data inputed. and create two new rows
	 
	  $("#multiple").find("tr:gt(0)").remove();
	

	  return true;
	
 }
  temp_result=1;
  }
  else
  {alert(msg);
	  temp_result=-1;
	   alert(<?php echo "'".$DatabaseConfigurationError."'"; ?>);
  return false;
	  
  }
 });

}

}

}

return false;


    });
</script>
