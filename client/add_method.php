<?php

//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
require_once 'authorization_check.php';

//redirect anyone that is not an administrator
if ($_COOKIE[power] !="admin"){
	header("Location: index.php?state=pass2");
	exit;	
	}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<title>HydroServer Lite Web Client</title>-->
<title><?php echo $WebClient; ?></title>

<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="favicon.ico" >

<link rel="stylesheet" href="js/jqwidgets/styles/jqx.base.css" type="text/css" />
<link rel="stylesheet" href="js/jqwidgets/styles/jqx.darkblue.css" type="text/css" />
<script type="text/javascript" src="js/gettheme.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxdata.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxbuttons.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxscrollbar.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxlistbox.js"></script>
<link href="styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />


<script type="text/javascript">
	var varmeth="";
	$(document).ready(function(){
	

	$("#msg").hide();
	
	var source =
	{
       	datatype: "json",
	   	datafields: [
          	{ name: 'variablename' },
	        { name: 'variableid' },
       	],
           	url: 'db_get_types.php'
	};			
	
	var dataAdapter = new $.jqx.dataAdapter(source);
                // Create a jqxListBox
                $("#jqxWidget").jqxListBox({source: dataAdapter, theme: 'darkblue', multiple: true, width: 400, height: 300, displayMember: "variablename", valueMember: "variableid"});

	 $("#jqxWidget").bind('change', function () {
					var items = $("#jqxWidget").jqxListBox('getItems');
					// get selected indexes.
var selectedIndexes = $("#jqxWidget").jqxListBox('selectedIndexes');
var selectedItems = [];
varmeth="";
// get selected items.
for (var index in selectedIndexes) {
if (selectedIndexes[index] != -1) {
selectedItems[index] = items[index];
varmeth+=selectedItems[index].value;
if(index!=(selectedIndexes.length-1))
{
varmeth+=",";	
}
}
}
	
                });
	});
</script>
</head>

<body background="images/bkgrdimage.jpg">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><?php include "topBanner.php" ; ?></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#3c3c3c" align="right" valign="middle" ><?php require_once 'header.php'; ?></td>
  </tr>
  <tr>
    <td width="240" valign="top" bgcolor="#f2e6d6"><?php echo "$nav"; ?></td>
    <!--<td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br /><p class="em" align="right">Required fields are marked with an asterick (*).</p><div id="msg"><p class=em2>Method successfully added!</p></div>-->
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br /><p class="em" align="right"><?php echo $RequiredFieldsAsterisk; ?></p><div id="msg"><p class=em2><?php echo $MethodSuccessfully;?></p></div>
      <!--<h1>Add a New Method</h1>-->
      <h1><?php echo $AddNewMethod;?></h1>
      <p>&nbsp;</p>
      <FORM METHOD="POST" ACTION="" name="addmethod" id="addmethod">
        <table width="620" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <!--<td width="80" valign="top"><strong>Method Name:</strong></td>-->
          <td width="80" valign="top"><strong><?php echo $MethodName; ?></strong></td>
          <!--<td colspan="2" valign="top"><input type="text" id="MethodDescription" name="MethodDescription" size=20 maxlength=100"/>*&nbsp;<span class="em">(Ex: YSI DO 200 Meter)</span></td>-->
          <td colspan="2" valign="top"><input type="text" id="MethodDescription" name="MethodDescription" size=20 maxlength=100"/>*&nbsp;<span class="em"><?php echo $ExampleMethodName;?></span></td>
        </tr>
        <tr>
          <td width="80" valign="top">&nbsp;</td>
          <td width="260" valign="top">&nbsp;</td>
          <td width="280" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <!--<td valign="top"><strong>Method Link:</strong></td>-->
          <td valign="top"><strong><?php echo $MethodLinkColon;?></strong></td>
          <!--<td colspan="2" valign="top"><input type="text" id="MethodLink" name="MethodLink" size=20 maxlength=200"/>&nbsp;<span class="em">(Optional; Ex: http://www.ysi.com/productsdetail.php?DO200-35)</span></td>-->
          <td colspan="2" valign="top"><input type="text" id="MethodLink" name="MethodLink" size=20 maxlength=200"/>&nbsp;<span class="em"><?php echo $ExMethodLink;?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <!--<td colspan="3" valign="top"><strong>Please select the Variable(s) below used by this method:</strong> <br>
            (Select all that apply by holding the &quot;Ctrl&quot; key down and selecting multiple options):</td>-->
            <td colspan="3" valign="top"><strong><?php echo $SelectVariablesBelow1;?><br>
            <?php echo $SelectAllThatApply;?></td>
          </tr>
        <tr>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" valign="top"><div id='jqxWidget'></div></td>
          <td valign="top">*</td>
          </tr>
        <tr>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <!--<td colspan="3" valign="top"><input type="SUBMIT" name="submit" value="Add Method" class="button" style="width: 135px"/></td>-->
          <td colspan="3" valign="top"><input type="SUBMIT" name="submit" value="<?php echo $AddMethodButton;?>" class="button" style="width: auto"/></td>
          </tr>
      </table></FORM>
      <p>&nbsp;</p>
    </blockquote>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <script src="js/footer.js"></script>
  </tr>
</table>

<script>
//Calls a function to validate all fields when the submit button is hit.
$("form").submit(function(){

	if(($("#MethodDescription").val())==''){
		alert("Please enter a Method Name!");
		return false;
	}

	
	if(varmeth=="")
	{alert("Please select at least one variable!");
		return false;
	}

$.post("do_add_method.php?varmeth="+varmeth, $("#addmethod").serialize(),  function( data ){
			if(data==1){
				$("#msg").show(1600);
				$("#MethodDescription").val("");
				$("#MethodLink").val("");
				$("#jqxWidget").jqxListBox('clearSelection');
				$("#msg").hide(1000);
				return true;
			}else{
				alert("Error in database configuration!");
				return false;
				}
		});


return false;
});

</script>



</body>
</html>
