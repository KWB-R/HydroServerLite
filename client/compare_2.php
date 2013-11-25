<?php

//This is required to get the international text strings dictionary
require_once 'internationalize.php';

require_once 'db_config.php';
$query = "SELECT SiteName FROM sites WHERE SiteID=".$_GET['sid'];
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$row = mysql_fetch_array($result, MYSQL_ASSOC);
?>

<script type="text/javascript">

function loadsitecomp()
{
//Script to populate fields
source =
        {
            datatype: "json",
            datafields: [
                { name: 'variableid' },
                { name: 'variablename' },
            ],
            url: 'db_get_variablelist.php?siteid='+$('#siteidc').val()
        };
//Defining the Data adapter
dataAdapter = new $.jqx.dataAdapter(source);
//Creating the Drop Down list
        $("#dropdownlistc").jqxDropDownList(
        {
            source: dataAdapter,
            theme: 'darkblue',
            width: 200,
            height: 25,
            selectedIndex: 0,
            displayMember: 'variablename',
            valueMember: 'variableid'
        });
$('#dropdownlistc').bind('select', function (event) {
var args = event.args;
var item = $('#dropdownlistc').jqxDropDownList('getItem', args.index);
//Check if a valid value is selected and process futher to display dates
if ((item != null)&&(item.label != "Please select a variable")) {
	$('#varnamec').val(item.label);
$('#window2').jqxWindow('hide');
$('#window3').jqxWindow('show');
$('#window3Content').load('compare_3.php', function() {
loadsitecomp1();
});
}
});
}
  </script>

<table width="630" border="0">
        <tr>
          <td colspan="4"></td>
          </tr>
        <tr>
          <td colspan="4"><!--Please select a Variable--><?php echo $SelectVariable;?></td>
        </tr>
        <tr>
          <td width="67"><strong><!--Variable:--><?php echo $Variable;?></strong></td>
          <td width="239"><div id="dropdownlistc"></div></td>
          <td width="55">&nbsp;</td>
          <td width="221">&nbsp;</td>
        </tr>
      </table>
      <input style=" visibility:hidden"id="siteidc" type="text" disabled/>
        <input style=" visibility:hidden"id="sitenamec" value="<?php echo $row['SiteName'];?>"type="text" disabled/>
         <input style=" visibility:hidden"id="varnamec" type="text" disabled/>