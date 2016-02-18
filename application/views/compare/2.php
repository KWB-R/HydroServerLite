<script type="text/javascript">
function loadsitecomp()
{
//Defining the Variable List
var source =
        {
            datatype: "json",
            datafields: [
                { name: 'VariableID' },
                { name: 'VariableName' },
            ],
            url: base_url+'variable/getSiteJSON?siteid='+$('#siteidc').val()
        };
//Defining the Data adapter
var dataAdapter = new $.jqx.dataAdapter(source);
//Creating the Drop Down list
        $("#dropdownlistc").jqxDropDownList(
        {
            source: dataAdapter,
            theme: 'darkblue',
            width: 100,
            height: 25,
            displayMember: 'VariableName',
            valueMember: 'VariableID'
        });
//Script to populate fields
$('#dropdownlistc').bind('select', function (event) {
var args = event.args;
var item = $('#dropdownlistc').jqxDropDownList('getItem', args.index);
//Check if a valid value is selected and process futher to display dates
if ((item != null)&&(item.label != "Please select a variable")) {
$('#varnamec').val(item.label);
$('#window2').jqxWindow('hide');
$('#window3').jqxWindow('show');
$('#window3Content').load(base_url+'datapoint/compare/3', function() {
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
          <td colspan="4"><?php echo getTxt('SelectVariable');?></td>
        </tr>
        <tr>
          <td width="67"><strong><?php echo getTxt('Variable');?></strong></td>
          <td width="239"><div id="dropdownlistc"></div></td>
          <td width="55">&nbsp;</td>
          <td width="221">&nbsp;</td>
        </tr>
      </table>
      <input style=" visibility:hidden"id="siteidc" type="text" disabled/>
      <input style=" visibility:hidden"id="sitenamec" value="<?php echo $SiteName;?>"type="text" disabled/>
       <input style=" visibility:hidden"id="varnamec" type="text" disabled/>