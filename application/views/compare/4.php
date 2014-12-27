<script type="text/javascript">
function get_methods_c()
{
$('#methodlistc').off()
$('#methodlistc').unbind('valuechanged');
var source122 =
        {
            datatype: "json",
            datafields: [
                { name: 'MethodID' },
                { name: 'MethodDescription' },
            ],
            url: base_url+'methods/getSiteVarJSON?siteid='+$('#siteidc').val()+'&varid='+$('#varidc').val()
        };
//Defining the Data adapter
var dataAdapter122 = new $.jqx.dataAdapter(source122);
//Creating the Drop Down list
$("#methodlistc").jqxDropDownList(
{
	source: dataAdapter122,
	theme: 'darkblue',
	width: 200,
	height: 25,
	displayMember: 'MethodDescription',
	valueMember: 'MethodID'
});
//Binding an Event in case of Selection of Drop Down List to update the varid according to the selection

$('#methodlistc').bind('select', function (event) {
	 
var args = event.args;
var item = $('#methodlistc').jqxDropDownList('getItem', args.index);
//Check if a valid value is selected and process futher to display dates
if (item != null && item.label != 'Please Choose:') {		

$("#methodidc").val(item.value);
$('#window4').jqxWindow('hide');
$('#window5').jqxWindow('show');
$('#window5Content').load(base_url+'datapoint/compare/5', function() {
get_date_c();

});

}

});
	
	
	
}  </script>

<table width="630" border="0">
        <tr>
          <td colspan="4"></td>
          </tr>
        <tr>
          <td colspan="4"><?php echo getTxt('SelectMethodVariable');?></td>
        </tr>
        <tr>
          <td width="113"><strong><?php echo getTxt('Method');?></strong></td>
          <td width="241"><div id="methodlistc"></div></td>
          <td width="24">&nbsp;</td>
          <td width="234">&nbsp;</td>
        </tr>
      </table>
       <input style=" visibility:hidden"id="methodidc" type="text" disabled/>