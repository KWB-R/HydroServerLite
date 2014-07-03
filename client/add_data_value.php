<?php

//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
require_once 'authorization_check.php';

//connect to server and select database
require_once 'database_connection.php';

//add the SourceID's
$sql ="Select distinct SourceID, Organization FROM seriescatalog";

$result = @mysql_query($sql,$connection)or die(mysql_error());

$num = @mysql_num_rows($result);
	if ($num < 1) {

    //$msg = "<P><em2>Sorry, no Sources available.</em></p>";
	$msg = "<P><em2> $SorryNoSource </em></p>";

	} else {

	while ($row = mysql_fetch_array ($result)) {

		$sourceid = $row["SourceID"];
		$sourcename = $row["Organization"];

		$option_block .= "<option value=$sourceid>$sourcename</option>";

		}
	}

//add the Variables
$sql3 ="Select * FROM variables ORDER BY VariableName ASC";

$result3 = @mysql_query($sql3,$connection)or die(mysql_error());

$num = @mysql_num_rows($result3);
	if ($num < 1) {

    //$msg3 = "<P><em2>Sorry, there are no Variables.</em></p>";
	$msg3 = "<P><em2>$SorryNoVariable</em></p>";

	} else {

	while ($row3 = mysql_fetch_array ($result3)) {

		$typeid = $row3["VariableID"];
		$typename = $row3["VariableName"];
		$datatype = $row3["DataType"];

		$option_block3 .= "<option value=$typeid>$typename ($datatype)</option>";

		}
	}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<title>HydroServer Lite Web Client</title>-->
<title><?php echo $WebClient; ?></title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="favicon.ico" >

<script type="text/javascript">
function show_answer(){
/*alert("If you do not see your SITE listed here," + '\n' + "please contact your supervisor and ask them" + '\n' + "to add it before entering data.");*/
alert(<?php echo "'". $IfNoSeeSite1."'";?> + '\n' + <?php echo "'". $ContactSupervisor."'";?> + '\n' + <?php echo "'". $AddIt."'";?>);
}

function show_answer2(){
/*alert("If you do not see your METHOD listed here," + '\n' + "please contact your supervisor and ask them" + '\n' + "to add it before entering data.");*/
alert(<?php echo "'". $IfNoSeeMethod1."'";?> + '\n' + <?php echo "'". $ContactSupervisor."'";?> + '\n' + <?php echo "'". $AddIt."'";?>);
}
</script>

<script src="js/datevalidation.js"></script>
<script src="js/timevalidation.js"></script>
<script src="js/numbervalidation.js"></script>

<link rel="stylesheet" href="styles/jqstyles/jquery.ui.all.css">
<link rel="stylesheet" href="styles/jqstyles/jquery.ui.timepicker.css">
<script src="js/jquery-1.7.2.js"></script>
<script src="js/ui/jquery.ui.core.js"></script>
<script src="js/ui/jquery.ui.widget.js"></script>
<script src="js/ui/jquery.ui.datepicker.js"></script>
<script src="js/ui/jquery.ui.timepicker.js"></script>
<link rel="stylesheet" href="styles/jqstyles/demos.css">

<link href="styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />

<script type="text/javascript">

	$(document).ready(function(){
		$("#msg").hide();
	});

//Date Validation Script Begins
function validatedate(dateid) {

var value2 = $('#' + dateid).val();
//Removing all space
var value = value2.replace(" ",""); 

//minimum length is 10. example 2012-05-31
if(value.length != 10){
	
	//alert("Invalid date length. Date format should be YYYY-MM-DD");
	alert(<?php echo "'". $InvalidDateLength."'";?>);
	return false;
	}
if (isDate(value,dateid) == false){
	return false;
	}
return true;
}

//Check the length of each segment to ensure it is correct. The order is yyyy-mm-dd by default.
function isDate(value,dateid) {
    try {
        
        var YearIndex = 0;
        var MonthIndex = 1;
        var DayIndex = 2;
 
        value = value.replace("/","-").replace(".","-"); 
        var SplitValue = value.split("-");
        var OK = true;

		//Check the length of the year
		if (OK && SplitValue[YearIndex].length != 4){
			//alert("Please enter the correct length for the YEAR.");
			alert(<?php echo "'". $PleaseCorrectLengthYear."'";?>);
            OK = false;
			return OK;
        }
		
		//Check the length of the month
        if (OK && SplitValue[MonthIndex].length != 2){
			//alert("Please enter the correct length for the MONTH.");
			alert(<?php echo "'".$PleaseCorrectLengthMonth."'";?>);
            OK = false;
			return OK;
        }
		
		//Check the length of the day
        if (SplitValue[DayIndex].length != 2){
			//alert("Please enter the correct length for the DAY.");
			alert(<?php echo "'".$PleaseCorrectLengthDay."'";?>);
            OK = false;
			return OK;
        }
		if ((SplitValue[DayIndex] == "00") || (SplitValue[MonthIndex] == "00")){
			//alert("Incorrect date. You cannot enter 00.");
			alert(<?php echo "'".$IncorrectDateZeros."'";?>);
			OK = false;
			return OK;
		}		
		
        if (OK) {
            var Year = parseInt(SplitValue[YearIndex], 10);
            var Month = parseInt(SplitValue[MonthIndex], 10);
            var Day = parseInt(SplitValue[DayIndex], 10);
 
            if (OK = ((Year > 1900) && (Year <= new Date().getFullYear()))) {
				
                if (OK = (Month <= 12 && Month > 0)) {
                    var LeapYear = (((Year % 4) == 0) && ((Year % 100) != 0) || ((Year % 400) == 0));
 
                    if (Month == 2) {
						
                        OK = LeapYear ? Day <= 29 : Day <= 28;
                    }
                    else {
                        if ((Month == 4) || (Month == 6) || (Month == 9) || (Month == 11)) {
                            OK = (Day > 0 && Day <= 30);
                        }
                        else {
                      
							OK = (Day > 0 && Day <= 31);
							
                        }
                    }
                }
            }
        }
		if (OK == false){
			//alert("Incorrect date range.");
			alert(<?php echo "'".$IncorrectDateRange."'";?>);
		}
        return OK;
    }
    catch (e) {
        return false;
    }
}
//Date Validation script ends

//Time Validation Script Begins
function  validatetime(timeid){
var strval = $('#' + timeid).val();

//Minimum and maximum length is 5, for example, 01:20
	if(strval.length < 5 || strval.length > 5){
		//alert("Invalid time. Time format should be five characters long and formatted HH:MM");
		alert(<?php echo "'".$InvalidTimeFive."'";?>);
	return false;
	}

	//Removing all space
	strval = trimAllSpace(strval);
	$('#' + timeid).val(strval)

	//Split the string
	var newval = strval.split(":");
	var horval = newval[0]
	var minval = newval[1];

	//Checking hours

	//minimum length for hours is two digits, for example, 12
	if(horval.length != 2){
		//alert("Invalid time. Hours format should be two digits long.");
		alert(<?php echo "'".$InvalidTimeHoursTwo."'";?>);
		return false;
		}
	if(horval < 0){
		//alert("Invalid time. Hours cannot be less than 00.");
		alert(<?php echo "'".$InvalidTimeHoursZeros."'";?>);
		return false;
		}
	else if(horval > 23){
		//alert("Invalid time. Hours cannot be greater than 23.");
		alert(<?php echo "'".$InvalidTimeHoursTwentyThree."'";?>);
		return false;
		}

	//Checking minutes

 	//minimum length for minutes is 2, for example, 59
	if(minval.length != 2){
		//alert("Invalid time. Minutes format should be two digits long.");
		alert(<?php echo "'".$InvalidTimeMinutesTwo."'";?>);
	return false;
	} 
	if(minval < 0){
		//alert("Invalid time. Minutes cannot be less than 00.");
		alert(<?php echo "'".$InvalidTimeMinutesZeros."'";?>);
		return false;
		}   
	else if(minval > 59){
		//alert("Invalid time. Minutes cannot be greater than 59.");
		alert(<?php echo "'".$InvalidTimeMinutesFiftyNine."'";?>);
		return false;
		}
	strval = IsNumeric(strval);
	$('#' + timeid).val(strval)
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

//The IsNumeric() function will check whether the user has entered a numeric value or not.
function IsNumeric(strString){ 
    var strValidChars = "0123456789:"; 
    var blnResult = true; 

    //test strString consists of valid characters listed above
    for (i = 0; i < strString.length && blnResult == true; i++) 
    { 
        var strChar = strString.charAt(i); 
        if (strValidChars.indexOf(strChar) == -1) 
        {
			//alert ("Invalid character. You may only use numbers.");
			alert (<?php echo "'".$InvalidCharacterNumbers."'";?>);
			strString = strString.replace(strString[i],"");
            blnResult = false;
        } 
     }
	return strString;
}
//Time Validation Script Ends

//Number validatin script
function validatenum(valid) {
var v = $('#' + valid).val();
var Value = isValidNumber(v, valid);
return Value;
}

function isValidNumber(val, valid){
      if(val==null || val.length==0){
  		  //alert("Please enter a number in the Value box");
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
       		    //alert("Please enter a valid number in the Value box");
				alert(<?php echo "'".$EnterValidNumberValue."'";?>);
			    return false;
            	}
      }
	  return true;
}
//Number Validation script ends
</script>


<script>
	$(function() {
		//$( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd", constrainInput: false, showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true, monthNames: ["january", "february", "march", "april", "may", "june", "july", "august", "september", "octuber", "novumber", "dicembre"]});
		$( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd", constrainInput: false, showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true, monthNames: [<?php echo "'".$Jan."'"; ?>, <?php echo "'".$Feb."'"; ?>, <?php echo "'".$Mar."'"; ?>, <?php echo "'".$Apr."'"; ?>, <?php echo "'".$May."'"; ?>, <?php echo "'".$Jun."'"; ?>, <?php echo "'".$Jul."'"; ?>, <?php echo "'".$Aug."'"; ?>, <?php echo "'".$Sep."'"; ?>, <?php echo "'".$Oct."'"; ?>, <?php echo "'".$Nov."'"; ?>, <?php echo "'".$Dec."'"; ?>], dayNamesMin: [<?php echo "'".$Su."'"; ?>, <?php echo "'".$Mo."'"; ?>, <?php echo "'".$Tu."'"; ?>, <?php echo "'".$We."'"; ?>, <?php echo "'".$Th."'"; ?>, <?php echo "'".$Fr."'"; ?>, <?php echo "'".$Sa."'"; ?>]});

		
		$( "#timepicker" ).timepicker({ showOn: "focus", showPeriodLabels: false, hourText: <?php echo "'".$Hour."'";?>, minuteText: <?php echo "'".$Minute."'"; ?>, closeButtonText: <?php echo "'".$Done."'"; ?>, nowButtonText: <?php echo "'".$Now."'"; ?>, deselectButtonText: <?php echo "'".$Deselect."'"; ?> });
		
	});
</script>

<script type="text/javascript">
function showSites(str){

document.getElementById("txtHint").innerHTML="<select name='SiteID' id='SiteID'><option value=''>Select....</option></select>*&nbsp;<a href='#' onClick='show_answer()' border='0'><img src='images/questionmark.png' border='0'></a>";

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

function showMethods(str){

document.getElementById("txtHint2").innerHTML="*&nbsp;<a href='#' onClick='show_answer()' border='0'><img src='images/questionmark.png' border='0'></a>";

if (str=="")
  {
  document.getElementById("txtHint2").innerHTML="";
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
    document.getElementById("txtHint2").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","getmethods1.php?m="+str,true);
xmlhttp.send();
}

</script>

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
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br /><p class="em" align="right"><!--Required fields are marked with an asterisk(*).--><?php echo $RequiredFieldsAsterisk; ?> </p><?php echo "$msg"; ?>&nbsp;<?php echo "$msg3"; ?>&nbsp;<?php echo "$msg4"; ?>
        <!--<div id="msg"><p class=em2>Value successfully added!</p></div>-->
        <div id="msg"><p class=em2><?php echo $ValueSuccessfully; ?></p></div>
        <!--<h1>Enter a Single Data Value</h1>-->
        <h1><?php echo $EnterSingleDataValue; ?></h1>
        <p>&nbsp;</p>
      <FORM METHOD="POST" ACTION="" name="addvalue" id="addvalue">
        <table width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><strong><!--Source:--> <?php echo $Source; ?></strong></td>
          <td valign="top"><select name="SourceID" id="SourceID" onChange="showSites(this.value)"><option value="-1"><!--Select....--><?php echo $SelectEllipsis; ?></option><?php echo "$option_block"; ?></select>*</td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td valign="top"><strong><!--Site:--><?php echo $Site; ?></strong></td>
          <td valign="top"><div id="txtHint"><select name="SiteID" id="SiteID"><option value="-1"><!--Select....--><?php echo $SelectElipsis; ?></option></select>*&nbsp;<a href="#" onClick="show_answer()" border="0"><img src="images/questionmark.png" border="0"></a></div> 
</td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td width="55" valign="top"><strong><!--Variable:--> <?php echo $Variable; ?></strong></td>
          <td width="370" valign="top"><select name="VariableID" id="VariableID" onChange="showMethods(this.value)"><option value="-1"><!--Select....--><?php echo $SelectElipsis; ?></option><?php echo "$option_block3"; ?></select>*</td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td valign="top"><strong><!--Method:--><?php echo $Method; ?></strong></td>
          <td valign="top"><div id="txtHint2"><select name="MethodID" id="MethodID"><option value="-1"><!--Select....--><?php echo $SelectElipsis; ?></option></select>*&nbsp;<a href="#" onClick="show_answer2()" border="0"><img src="images/questionmark.png" border="0"></a></div></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td width="370" valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td width="55" valign="top"><strong><!--Date:--><?php echo $Date; ?></strong></td>
          <td valign="top"><input type="text" id="datepicker" name="datepicker" onChange="return validateDate()" size="15">*&nbsp;<span class="em"><!--(YYYY-MM-DD format; Ex: 2012-05-04 for 4 May  2012)--><?php echo $DateFormatExample; ?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td width="55" valign="top"><strong><!--Time:--><?php echo $Time; ?></strong></td>
          <td valign="top"><input type="text" id="timepicker" name="timepicker" onChange="return validateTime()" size="10">*&nbsp;<span class="em"><!--(HH:MM, 24 hour  format; Ex: 13:45 for 1:45 pm)--><?php echo $TimeFormatExample; ?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td width="55" valign="top"><strong><!--Value:--><?php echo $Value; ?></strong></td>
          <td valign="top"><input type="text" id="value" name="value" size=10 maxlength=20 onBlur="return validateNum()"/>*&nbsp;<span class="em"><!--(Must be a number; no commas allowed)--><?php echo $NumberNoCommas; ?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td width="55" valign="top">&nbsp;</td>
          <td valign="top"><input type="SUBMIT" name="submit" value= "<?php echo $SubmitData; ?>" class="button" style="width: auto" />&nbsp;&nbsp;<input type="reset" name="Reset" value="<?php echo $Cancel; ?>" class="button" style="width: auto" /></td>
          </tr>
      </table>
    </FORM></p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </blockquote>
    <p></p></td>
  </tr>
  <tr>
    <script src="js/footer.js"></script>
  </tr>
</table>
</body>
</html>

<script>

//Calls a function to validate all fields when the submit button is hit.
$("form").submit(function(){

	if(($("#SourceID option:selected").val())==-1){
		/*alert("Please select a Source!");*/
		alert(<?php echo "'".$SelectSource ."'";?>);
		return false;
	}

	if(($("#SiteID option:selected").val())==-1){
		/*alert("Please select a Site!");*/
		alert(<?php echo "'".$SelectSite."'";?>);
		return false;
	}

	if(($("#VariableID option:selected").val())==-1){
		/*alert("Please select a Variable!");*/
		alert(<?php echo "'".$SelectVariableMsg."'";?>);
		return false;
	}

	if(($("#MethodID option:selected").val())==-1){
		/*alert("Please select a Method!");*/
		alert(<?php echo "'".$SelectMethodMsg."'";?>);
		return false;
	}
	
	//Date checking
	var checkid='datepicker';
	var result=validatedate(checkid);

	if(result==false){
		return false;
	}

	//Time checking
	checkid='timepicker';
	var result=validatetime(checkid);

	if(result==false){
		return false;
	}

	//Value checking
	checkid='value';

	if(validatenum(checkid)==false){
		return false;
	}
	
	
	//Value validation
	
	var vt = $('#' + checkid).val();
	

var tv=$("#VariableID option:selected").val();

switch(tv)
{
case "19":
if((vt<0)||(vt>100))
{
/*alert("Value has to be between 0 and 100.");*/
alert(<?php echo "'".$ValueBetweenZeroAndHundred."'";?>);
		return false;
}
break;
case "13":
case "22":
if((vt<0)||(vt>14))
{
/*alert("Value has to be between 0 and 14.");*/
alert(<?php echo "'".$ValueBetweenZeroAndFourteen."'";?>);
		return false;
}
break;
case "7":
case "24":break;
default:
if(vt<0)
{
/*alert("Value can't be less than 0.");*/
alert(<?php echo "'".$ValueLessThanZero."'";?>);
		return false;
}
  break;
}


//Validation is now complete, so send to the processing page
$.post("do_add_data_value.php", $("#addvalue").serialize(),  function( data ){

		if(data.search("1")!=-1){
			$("#msg").show(1600);
			$("#SourceID").val(-1);
			$("#SiteID").val(-1);
			$("#VariableID").val(-1);
			$("#MethodID").val(-1);
			$("#datepicker").val("");
			$("#timepicker").val("");
			$("#value").val("");
			$("#msg").hide(1000);
			return true;
		}else{
			alert(data);
			/*alert("Error during processing! Please refresh the page and try again.");*/
			alert(<?php echo "'".$ProcessingError."'";?>);
			return false;
			}
	});

return false;
});
	
</script>
