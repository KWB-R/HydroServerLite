<script type="text/javascript">
function loadsitecomp1()
{ 
var item = $("#dropdownlistc").jqxDropDownList('getSelectedItem');
source =
        {
            datatype: "json",
            datafields: [
                { name: 'DataType' },
            ],
            url: base_url+'variable/getTypes?siteid='+$('#siteidc').val()+'&varname='+item.label
        };
//Defining the Data adapter
dataAdapter = new $.jqx.dataAdapter(source);

//Creating the Drop Down list
$("#typelistc").jqxDropDownList(
{
	source: dataAdapter,
	theme: 'darkblue',
	width: 200,
	height: 25,
	displayMember: 'DataType',
	valueMember: 'DataType'
});

$('#typelistc').bind('select', function (event) {
var args = event.args;
var item = $('#typelistc').jqxDropDownList('getItem', args.index);
//Check if a valid value is selected and process futher to display dates
if ((item != null)&&(item.label != 'Please Choose:')) {
	$('#dtc').val(item.label);
	var item1 = $("#dropdownlistc").jqxDropDownList('getSelectedItem');
$.ajax({
  type: "GET",
   url: base_url+"variable/updateVarID?siteid="+$('#siteidc').val()+"&varname="+item1.label+"&type="+item.label,
//Processing The Dates
    success: function(data) {

$('#varidc').val(data);
$('#window3').jqxWindow('hide');
$('#window4').jqxWindow('show');
$('#window4Content').load(base_url+'datapoint/compare/4', function() {
get_methods_c();
});

	}
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
          <td colspan="4">Please select a Data Type for the variable</td>
        </tr>
        <tr>
          <td width="113"><strong>Data Type:</strong></td>
          <td width="241"><div id="typelistc"></div></td>
          <td width="24">&nbsp;</td>
          <td width="234">&nbsp;</td>
        </tr>
      </table>
      <input style=" visibility:hidden"id="varidc" type="text" disabled/>
      <input style=" visibility:hidden"id="dtc" type="text" disabled/>