<?php
$default_timesupport = "";
HTML_Render_Head($js_vars,getTxt('ChangeVariable'));
echo $CSS_Main;
echo $JS_JQuery;
echo $CSS_JQStyles;
echo $CSS_JQX;
echo $JS_JQX;
?>
<script type="text/javascript">
var unitsid=0;
//Default Parameter
//Can be linked to the config page
var nodatavalue=-9999;

	$(document).ready(function(){
		$("#del").click(function(){

			$.ajax({
			dataType: "json",
			url: base_url+"variable/delete/"+$('#varid').val()+"?ui=1"}).done(function(msg){
				if(msg.status=="success")
				{
						window.open(base_url+"variable/edit/","_self");
						return true;
				}
				else{
					alert(<?php echo "'".getTxt('ProcessingError')."'"; ?>);
					return false;
				}
				}).fail(function(data){alert(<?php echo "'".getTxt('ProcessingError')."'"; ?>);console.log(data);});
		});

		
	$("#msg").hide();
	$("#new_spec").hide();
	$("#new_spec1").hide();
	$("#unit").hide();
	$("#unitreq").hide();
	$("#unittext").hide();
	$("#newunit").hide();
	$("#smother").hide();
	$("#newunitonly").hide();
	$("#valuetypenewb").hide();
	$("#newvarnameb").hide();
	$("#edit").hide();

//Create a list of variables

var source19 =
        {
            datatype: "json",
            datafields: [
                { name: 'VariableID' },
                { name: 'VarNameMod' },
            ],
            url: base_url+'variable/getAllJSON2'
        };				
		
	
		var dataAdapter19 = new $.jqx.dataAdapter(source19);
        $("#VariableID").jqxDropDownList(
        {
            source: dataAdapter19,
            theme: 'darkblue',
            width: '94%',
            height: 25,
            displayMember: 'VarNameMod',
            valueMember: 'VariableID'
        });		
				

$('#VariableID').bind('select', function (event){
var args = event.args;
var sel=<?php echo "'".getTxt('SelectEllipsis')."'"; ?>;
var item = $('#VariableID').jqxDropDownList('getItem', args.index);
if(item.label == "Select...." || item.label == sel)
{
 item.label=sel;
}
if ((item != null)&&(item.label != sel)){
//Now populate the fields with the data for this variable.
//First send out an ajax request to fetch all data for this field

$.ajax({
	dataType:'json',
	url: base_url+"variable/getWithUnit?varid="+item.value}).done(function( msg ){
		msg=msg[0];
		if(msg.VariableID){
			//Analyze the data recieved and post it into the form
			$("#varid").val(msg.VariableID);//Var ID
			$("#var_code").val(msg.VariableCode);
	
			var items1 = $("#varname").jqxDropDownList('getItems');
			for(var a=0;a<items1.length;a++){
		
				var item1=items1[a];	
		
				if (item1.originalItem.Term==msg.VariableName){
					$("#varname").jqxDropDownList('selectIndex', item1.index );
					break;	
				}
		}
	
	items1 = $("#specdata").jqxDropDownList('getItems');
	for(var a=0;a<items1.length;a++){
		
		var item1=items1[a];		

		if (item1.originalItem.Term==msg.Speciation){
			$("#specdata").jqxDropDownList('selectIndex', item1.index );
			break;	
		}
	}

	items1 = $("#unittype").jqxDropDownList('getItems');
	for(var a=0;a<items1.length;a++)
	{
		var item1=items1[a];	
		if (item1.value==msg.unitsType)
		{
			$("#unittype").jqxDropDownList('selectIndex', item1.index );
			break;	
		}
	}
	
	items1 = $("#unit").jqxDropDownList('getItems');
	for(var a=0;a<items1.length;a++)
	{	
		var item1=items1[a];		
		if (item1.value==msg.VariableunitsID)
		{
			$("#unit").jqxDropDownList('selectIndex', item1.index );
			break;	
		}
	}

	
	
	items1 = $("#samplemedium").jqxDropDownList('getItems');
	for(var a=0;a<items1.length;a++)
	{
		
	var item1=items1[a];	
		
	if (item1.originalItem.Term==msg.SampleMedium)
	{
		$("#samplemedium").jqxDropDownList('selectIndex', item1.index );
		break;	
	}
	}
	
		items1 = $("#valuetype").jqxDropDownList('getItems');
	for(var a=0;a<items1.length;a++)
	{
		
	var item1=items1[a];	
		
	if (item1.originalItem.Term==msg.ValueType)
	{
		$("#valuetype").jqxDropDownList('selectIndex', item1.index );
		break;	
	}
	}
	
	
	if(msg.IsRegular==1)
	{
		$("#isreg").jqxDropDownList('selectIndex', 1 );
	}
	else
	{
		$("#isreg").jqxDropDownList('selectIndex', 2 );
	}
	
	$("#tsup").val(msg.TimeSupport);
	
	items1 = $("#timeunit").jqxDropDownList('getItems');
	for(var a=0;a<items1.length;a++)
	{
		
	var item1=items1[a];	
		
	if (item1.value==msg.TimeunitsID)
	{
		$("#timeunit").jqxDropDownList('selectIndex', item1.index );
		break;	
	}
	}
	
	
	
	items1 = $("#datatype").jqxDropDownList('getItems');
	for(var a=0;a<items1.length;a++)
	{
		
	var item1=items1[a];	
		
	if (item1.originalItem.Term==msg.DataType)
	{
		$("#datatype").jqxDropDownList('selectIndex', item1.index );
		break;	
	}
	}
	
		items1 = $("#gc").jqxDropDownList('getItems');
	for(var a=0;a<items1.length;a++)
	{
		
	var item1=items1[a];	
		
	if (item1.originalItem.Term==msg.GeneralCategory)
	{
		$("#gc").jqxDropDownList('selectIndex', item1.index );
		break;	
	}
	}
	
	//Populate the Methods List with existing methods in the database.
	
	//Fetch the varmeth entry
$("#jqxWidget").jqxListBox('clearSelection'); 	
$.ajax({
  dataType:'json',
  url: base_url+"methods/getMethodsJSON?var="+$("#varid").val()
}).done(function(methods) {
  if(methods.length>0)
  {
 items1 = $("#jqxWidget").jqxListBox('getItems');
 for(var i=0;i<methods.length;i++)
{
 	method = methods[i];
	for(var a=0;a<items1.length;a++)
	{	
	var item1=items1[a];		
		if (item1.value==method.MethodID)
		{
			$("#jqxWidget").jqxListBox('selectIndex', item1.index );
			break;
		}
	}
  }
  } 
});

	$("#edit").show();
  }
  else
  {
	  alert(<?php echo "'".getTxt('Error')."'"; ?>);
	  return false;  
  }
 });


}
});


//List : Speciation
var selec_ind=0;
var url=base_url+"variable/getTable/speciationcv";
// prepare the data
var source =
{
	datatype: "json",
	datafields: [
		{ name: 'Term' },
		{ name: 'Definition' },
		{ name: 'displayTerm' },
		{ name: 'displayDef' }
	],
	id: 'id',
	url: url,
	async: false
};
var dataAdapter = new $.jqx.dataAdapter(source);
console.log(dataAdapter);
// Create a jqxComboBox for Speciation
$("#specdata").jqxDropDownList({ selectedIndex: 0, source: dataAdapter, displayMember: "displayTerm", valueMember: "Term", width: '94%', height: 25, theme: 'darkblue'});

$("#specdata").bind('select', function (event) {
var args = event.args;
var item = $('#specdata').jqxDropDownList('getItem', args.index);
    if ((item != null)&&(item.originalItem.Definition!="-1")&&(item.originalItem.Definition!="-10")) 
{
 $("#specdef").val(item.originalItem.displayDef);
 $("#specdef").attr('disabled', true);
 $("#new_spec").hide();
$("#new_spec1").hide();
 }
 
 if(item.originalItem.Definition=="-10")
 {
//If user selects other option
	$("#specdef").removeAttr("disabled");	 
	 $("#specdef").val(<?php echo "'".getTxt('EnterDefinition')."'"; ?>);
//Show the other box
$("#new_spec").show(200);
$("#new_spec1").show(200);



 }
 
  });
  
var url2=base_url+"variable/getUnitTypes";

                // prepare the data
                var source2 =
                {
                    datatype: "json",
                    datafields: [
                        { name: 'unitype' },
                        { name: 'unitid' },
						 { name: 'orgtype' }
                    ],
                    id: 'id',
                    url: url2,
                    async: false
                };
                var dataAdapter2 = new $.jqx.dataAdapter(source2);

// Create a jqxComboBox for the var unit types. 
$("#unittype").jqxDropDownList({ selectedIndex: 0, source: dataAdapter2, displayMember: "unitype", valueMember: "orgtype", width: '94%', height: 25, theme: 'darkblue'});

$("#unittype").bind('select', function (event) {
var args = event.args;
var item = $('#unittype').jqxDropDownList('getItem', args.index);

if ((item != null)&&(item.originalItem.unitid!="-1")&&(item.originalItem.unitid!="-10")) 
{					

//Get all the units for that type and display it
$("#newunit").hide();
$("#newunitonly").hide();
$("#unit").show();
$("#unittext").show();
var url3=base_url+"variable/getUnitsByType?type="+item.originalItem.orgtype;

                // prepare the data
                var source3 =
                {
                    datatype: "json",
                    datafields: [
                        { name: 'unit' },
                        { name: 'unitid' }
                    ],
                    id: 'id',
                    url: url3,
                    async: false
                };
                var dataAdapter3 = new $.jqx.dataAdapter(source3);
	
// Create a jqxComboBox for the var unit types (this is for the units box that shows up once a variable type has been selected
$("#unit").jqxDropDownList({ selectedIndex: 0, source: dataAdapter3, displayMember: "unit", valueMember: "unitid", width: '94%', height: 25, theme: 'darkblue'});

$("#unit").bind('select', function (event) {
var args = event.args;
var item = $('#unit').jqxDropDownList('getItem', args.index);

  if ((item != null)&&(item.value!="-1")&&(item.value!="-10")) 
{					
$("#newunitonly").hide();
$("#newunit").hide();


}

if (item.value=="-10") 
{					
//Show the other box and other details required
$("#newunitonly").show(400);


}

});
}

if (item.value=="-10") 
{					

$("#unit").hide();
$("#unittext").hide();

//Show the other box and other details required
$("#newunit").show(400);
$("#newunitonly").show(400);


}

 
});


//Sample Medium List 
 var source4 =
{
	datatype: "json",
	datafields: [
		{ name: 'Term' },
		{ name: 'Definition' },
		{ name: 'displayTerm' },
		{ name: 'displayDef' }
	],
	id: 'id',
	url: base_url+'variable/getTable/samplemediumcv',
	async: false
};
var dataAdapter4 = new $.jqx.dataAdapter(source4);


$("#samplemedium").jqxDropDownList({ selectedIndex: 0, source: dataAdapter4, displayMember: "displayTerm", valueMember: "Term", width: '94%', height: 25, theme: 'darkblue'});
	
$("#samplemedium").bind('select', function (event) {
var args = event.args;
var item = $('#samplemedium').jqxDropDownList('getItem', args.index);
    if ((item != null)&&(item.originalItem.Definition!="-1")&&(item.originalItem.Definition!="-10")) 
{					


 $("#smdef").val(item.originalItem.displayDef);
 $("#smdef").attr('disabled', true);
 
 $("#smother").hide();
 }
 
 if(item.originalItem.Definition=="-10")
 {
	 
//If user selects other option
	$("#smdef").removeAttr("disabled");	 
	 $("#smdef").val(<?php echo "'".getTxt('EnterDefinition')."'"; ?>);
//Show the other box
$("#smother").show(400);



 }
 
  });

//End of Sample Medium list

//Value type list
var source5 =
                {
                    datatype: "json",
                   datafields: [
		{ name: 'Term' },
		{ name: 'Definition' },
		{ name: 'displayTerm' },
		{ name: 'displayDef' }
	],
                    id: 'id',
                    url: base_url+'variable/getTable/valuetypecv',
                    async: false
                };
                var dataAdapter5 = new $.jqx.dataAdapter(source5);


$("#valuetype").jqxDropDownList({ selectedIndex: 0, source: dataAdapter5, displayMember: "displayTerm", valueMember: "Term", width: '94%', height: 25, theme: 'darkblue'});
	
$("#valuetype").bind('select', function (event) {
var args = event.args;
var item = $('#valuetype').jqxDropDownList('getItem', args.index);
      if ((item != null)&&(item.originalItem.Definition!="-1")&&(item.originalItem.Definition!="-10")) 
{					


 $("#vtdef").val(item.originalItem.displayDef);
 $("#vtdef").attr('disabled', true);
 
 $("#valuetypenewb").hide();
 }
 
 if(item.originalItem.Definition=="-10")
 {
	 
//If user selects other option
	$("#vtdef").removeAttr("disabled");	 
	 $("#vtdef").val(<?php echo "'".getTxt('EnterDefinition')."'"; ?>);
//Show the other box
$("#valuetypenewb").show(400);



 }
 
  });


//End of Value type list


//Start of isregular
var source7 = [
                    "<?php echo getTxt('SelectEllipsis');?>",
					"<?php echo getTxt('Regular');?>",
                    "<?php echo getTxt('Irregular');?>",
                    "<?php echo getTxt('Unknown');?>"
		        ];

                // Create a jqxDropDownList
$("#isreg").jqxDropDownList({ source: source7, selectedIndex: 0, width: '94%', height: '25', theme: 'darkblue' });

//End of is regular

//begin time units id

var source8 =
                {
                    datatype: "json",
                    datafields: [
                        { name: 'unit' },
                        { name: 'unitid' }
                    ],
                    id: 'id',
                    url: base_url+"variable/getUnitsByType?type=Time&noNew=1",
                    async: false
                };
                var dataAdapter8 = new $.jqx.dataAdapter(source8);


$("#timeunit").jqxDropDownList({ selectedIndex: 0, source: dataAdapter8, displayMember: "unit", valueMember: "unitid", width: '94%', height: 25, theme: 'darkblue'});
	

//End time units id

//begin Data type list
var source9 =
                {
                    datatype: "json",
                    datafields: [
						{ name: 'Term' },
						{ name: 'Definition' },
						{ name: 'displayTerm' },
						{ name: 'displayDef' }
					],
                    id: 'id',
                    url: base_url+'variable/getTable/datatypecv?noNew=1',
                    async: false
                };
                var dataAdapter9 = new $.jqx.dataAdapter(source9);
$("#datatype").jqxDropDownList({ selectedIndex: 0, source: dataAdapter9, displayMember: "displayTerm", valueMember: "Term", width: '94%', height: 25, theme: 'darkblue'});
	
$("#datatype").bind('select', function (event) {
var args = event.args;
var item = $('#datatype').jqxDropDownList('getItem', args.index);
    if ((item != null)&&(item.originalItem.Definition!="-1")) 
{					


 $("#dtdef").val(item.originalItem.displayDef);

 
 }
 
  });

// End of data type list


//begin general category list
var source10 =
                {
                    datatype: "json",
                    datafields: [
						{ name: 'Term' },
						{ name: 'Definition' },
						{ name: 'displayTerm' },
						{ name: 'displayDef' }
					],
                    id: 'id',
                    url: base_url+'variable/getTable/generalcategorycv?noNew=1',
                    async: false
                };
                var dataAdapter10 = new $.jqx.dataAdapter(source10);



$("#gc").jqxDropDownList({ selectedIndex: 0, source: dataAdapter10, displayMember: "displayTerm", valueMember: "Term", width: '94%', height: 25, theme: 'darkblue'});
	
$("#gc").bind('select', function (event) {
var args = event.args;
var item = $('#gc').jqxDropDownList('getItem', args.index);
    if ((item != null)&&(item.originalItem.Definition!="-1")) 
{					


 $("#gcdef").val(item.originalItem.displayDef);

 
 }
 
  });

// End of data type list

//Variable Name list : new option available

var url15=base_url+"variable/getTable/variablenamecv";

// prepare the data
var source15 =
{
	datatype: "json",
	datafields: [
		{ name: 'Term' },
		{ name: 'Definition' },
		{ name: 'displayTerm' },
		{ name: 'displayDef' }
	],
	id: 'id',
	url: url15,
	async: false
};
var dataAdapter15 = new $.jqx.dataAdapter(source15);
$("#varname").jqxDropDownList({ selectedIndex:0,source: dataAdapter15, displayMember: "displayTerm", valueMember: "Term", width: '94%', height: 25, theme: 'darkblue'});
$("#varname").bind('select', function (event) {
var args = event.args;
var item = $('#varname').jqxDropDownList('getItem', args.index);
     if ((item != null)&&(item.originalItem.Definition!="-1")&&(item.originalItem.Definition!="-10"))  
{					
 $("#vardef").val(item.originalItem.displayDef);
 $("#vardef").attr('disabled', true);
 	 $("#newvarnameb").hide();
 }
 
 if(item.originalItem.Definition=="-10")
	 {
//If user selects other option
	$("#vardef").removeAttr("disabled");	 
	$("#vardef").val(<?php echo "'".getTxt('EnterDefinition')."'"; ?>);
//Show the other box
 $("#newvarnameb").show(200);


 }
 
  });

//End of variable list


	});

</script>
<script type="text/javascript">
//The following script is for the Method listbox
	$(document).ready(function(){
	
		var source =
		{
	       	datatype: "json",
		   	datafields: [
        	  	{ name: 'MethodDescription' },
	        	{ name: 'MethodID' },
	       	],
    	       	url: base_url+'methods/getJSON'
		};			
	
	var dataAdapter = new $.jqx.dataAdapter(source);
        // Create a jqxListBox
        $("#jqxWidget").jqxListBox({source: dataAdapter, theme: 'darkblue', multiple: true, width: '94%', height: 300, displayMember: "MethodDescription", valueMember: "MethodID"});
	});
</script>
<?php HTML_Render_Body_Start(); 
genHeading('EditVariable',true);
?>
 <p><?php echo getTxt('PleaseSelect'); ?></p>
<p  class='em'><strong><?php echo getTxt('Note'); ?></strong><?php echo getTxt('TryingToDelete'); ?></p>
<?php $attributes = array('class' => 'form-horizontal', 'name' => 'add_var', 'id' => 'add_var');
	echo form_open('variable/edit', $attributes);
genDropLists('Variable','VariableID','VariableID',true);
?>
<div id="edit">
<?php
genInput('VariableID','varid', 'varid', false);
genInputH('VariableCode','var_code', 'VariableCode',getTxt('VariableCodeInfo'), true);

genDropLists('VariableName','varname','varname',true);

echo '<div id="newvarnameb">';
genInput('NewVarName','NewVarName', 'NewVarName', true);
echo '</div>';
genInputH('VariableDefinition','vardef', 'vardef',getTxt('VariableDefinitionMsg'), true);


genDropListsH('Speciation','specdata','specdata',getTxt('ValueCode'),true);

echo '<div id="new_spec">';
genInput('NewSpeciation','other_spec', 'other_spec', true);
echo '</div>';
genInput('SpeciationDef','specdef', 'specdef', true);


genDropListsH('VariableUnitType','unittype','unittype',getTxt('UnitsCategory'),true);
echo '<div id="unittext">';
genDropListsH('Unit','unit', 'unit',getTxt('UnitsMeasure'),true);
echo '</div>';

echo '<div id="newunit">';
echo '<span class=em2>'.getTxt('NewUnitDefinitionColon').'</span>';
//genDropLists('NewUnitDefinitionColon','NewUnitDefinitionColon', 'NewUnitDefinitionColon', true);
genInputH('UnitType','new_unit_type', 'new_unit_type', getTxt('UTAssociated'),true);
echo '</div>';

echo '<div id="newunitonly">';
genInput('UnitName','new_unit_name', 'new_unit_name', true);
genInput('UnitAbbreviation','new_unit_abb', 'new_unit_abb', true);
echo '</div>';

genDropListsH('SampleMedium','samplemedium', 'samplemedium',getTxt('ObservationMedium'),true);

echo '<div id="smother">';
genInput('NewSampleMedium','smnew', 'smnew', true);
echo '</div>';
genInput('MediumDefinition','smdef', 'smdef', true);

genDropListsH('ValueType','valuetype', 'valuetype',getTxt('DataTypeMsg'),true);

echo '<div id="valuetypenewb">';
genInput('ValueTypeNewColon','valuetypenew', 'valuetypenew', true);
echo '</div>';

genInput('ValueTypeDefinition','vtdef', 'vtdef', true);

genDropListsH('Regularity','isreg', 'isreg',getTxt('RegularlySampledTime'),true);
genInputH('TimeSupport','tsup', 'tsup',getTxt('TemporalFootprint'), true, "value='".$default_timesupport."'");
genDropLists('TimeUnit','timeunit', 'timeunit', true);
genDropLists('DataType','datatype', 'datatype', true);
?>
<div class="form-group">
        <label class="col-sm-2 control-label"><?php echo getTxt('DataTypeDefinition');?></label>
        <div class="col-sm-9">
     	   <textarea type="text" cols="45" rows="4" class="form-control" id="dtdef" name="dtdef" readonly><?php echo getTxt('SelectData');?></textarea><span class="required"/></div>             
</div>
<?php
genDropListsH('Category','gc', 'gc',getTxt('ScientificCategory'),true);
genInput('CategoryDefinition','gcdef', 'gcdef', true, " readonly");
genDropListsH('SelectMethods','jqxWidget', 'jqxWidget',getTxt('VariableCollectionMethod'),true);
?>
<div class="col-md-5 col-md-offset-5">
         
       <input type="SUBMIT" name="submit" value="<?php echo getTxt('SaveEdits');?>" class="button"/>
       <input type="reset" name="Reset" value="<?php echo getTxt('Cancel'); ?>" class="button" style="width: auto" />
       <input type="button" id="del" name="del" value="<?php  echo getTxt('DeleteVariables'); ?>" class="button" />
</div></div>
</form>
</div>
<?php HTML_Render_Body_End(); ?>


<script>
function addHidden(name)
{
	var selectedItem = $('#'+name).jqxDropDownList('getSelectedItem');
	$('<input>').attr({
    type: 'hidden',
    id: name+'_val',
    name: name+'_val',
	value: selectedItem.label
}).appendTo('#add_var');
}
//Calls a function to validate all fields when the submit button is hit.
$("#add_var").submit(function(){

	if(($("#var_code").val())==""){
		alert(<?php echo "'".getTxt('EnterVariableCode')."'"; ?>);
		return false;
	}
	
	if(($("#var_code").val().search("^[a-zA-Z0-9_.-]*$"))==-1){
		//alert("Invalid Variable code. VaraibleCodes cannot contain any characters other than A-Z (case insensitive), 0-9, period (.), dash (-), and underscore (_)."
		alert(<?php echo "'".getTxt('InvalidVariableCode')."'"; ?>);
		return false;
	}

//Check variable Name

var checkitem = $('#varname').jqxDropDownList('getSelectedItem');
addHidden('varname');
	if ((checkitem == null)||(checkitem.value=="-1")){
		//alert("Please select a variable name or select Other/New from the drop down to enter a new variable name!");
		alert(<?php echo "'".getTxt('SelectVariableName')."'"; ?>);
		return false;    
	}
   
	if(checkitem.value=="-10"){ // A new selection...need to process it first so that the entry will be valid
	
		//Check if new fields are filled
		if(($("#newvarname").val())==""){
			//alert("Please enter a new variable name!");
			alert(<?php echo "'".getTxt('EnterNewVariable')."'"; ?>);
			return false;
		}
	
		if(($("#newvarname").val().search("^[a-zA-Z0-9_.-]*$"))==-1){
			//alert("Invalid Variable name. Varaible Name cannot contain any characters other than A-Z (case insensitive), 0-9, period (.), dash (-), and underscore (_).");
			alert(<?php echo "'".getTxt('InvalidVariableName')."'"; ?>);
			return false;
		}

	 	if((($("#vardef").val())=="")||(($("#vardef").val())=="Please enter a definition")){
			//alert("Please enter the definition for the new variable");
			alert(<?php echo "'".getTxt('EnterDefinitionNewVariable')."'"; ?>);
			return false;
		}  
	
	}
addHidden('specdata');  
checkitem = $('#specdata').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1"))
   {
	 //alert("Please select a Speciation or select Other/New from the drop down to enter a new Speciation!");
	 alert(<?php echo "'".getTxt('SelectSpeciation')."'"; ?>);
		return false;    
   }
   
   if(checkitem.value=="-10")
   {
	//A new selection...need to process it first so that the entry will be valid
	
	//Check if new fields are filled
	if(($("#other_spec").val())==""){
		//alert("Please enter a new Speciation!");
		alert(<?php echo "'".getTxt('SelectNewSpeciation')."'"; ?>);
		return false;
	}

	 if((($("#specdef").val())=="")||(($("#specdef").val())=="Please enter a definition")){
		//alert("Please enter the definition for the NEW Speciation");
		alert(<?php echo "'".getTxt('EnterDefinitionNewSpeciation')."'"; ?>);
		return false;
	}  
   }
   
addHidden('unittype');
checkitem = $('#unittype').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1")){
	 //alert("Please select a Variable Unit Type or select Other/New from the drop down to enter a new Unit Type!");
	 alert(<?php echo "'".getTxt('SelectVariableUnitType')."'"; ?>);
		return false;    
   }

if ((checkitem != null)&&(checkitem.value!="-1")&&(checkitem.value!="-10")){
//If type selected...check if unit selected
addHidden('unit');
	var unititem = $('#unit').jqxDropDownList('getSelectedItem');

	if ((unititem == null)||(unititem.value=="-1")){
		//alert("Please select a Unit or select Other/New from the drop down to enter a new Unit!");
		alert(<?php echo "'".getTxt('SelectUnit')."'"; ?>);
		return false;    
	}


	if(unititem.value=="-10"){ // A new selection...need to process it first so that the entry will be valid
	
		//Check if new fields are filled
		if(($("#new_unit_name").val())==""){
			//alert("Please enter a name for the new Unit!");
			alert(<?php echo "'".getTxt('EnterNameNewUnit')."'"; ?>);
			return false;
		}

		if(($("#new_unit_abb").val())==""){
			//alert("Please enter an abbreviation for the new Unit!");
			alert(<?php echo "'".getTxt('EnterAbbreviationNewUnit')."'"; ?>);
			return false;
		}
	}
}
	if(checkitem.value=="-10"){ //A new selection...need to process it first so that the entry will be valid
	
		//Check if new fields are filled
		if(($("#new_unit_name").val())==""){
			//alert("Please enter a name for the new Unit!");
			alert(<?php echo "'".getTxt('EnterNameNewUnit')."'"; ?>);
			return false;
		}

		if(($("#new_unit_abb").val())==""){
			//alert("Please enter an abbreviation for the new Unit!");
			alert(<?php echo "'".getTxt('EnterAbbreviationNewUnit')."'"; ?>);
			return false;
		}  

		if(($("#new_unit_type").val())==""){
			//alert("Please enter the type of the new unit!");
			alert(<?php echo "'".getTxt('EnterTypeNewUnit')."'"; ?>);
			return false;
		}  
	}
	
//Check for Sample Medium
addHidden('samplemedium');
checkitem = $('#samplemedium').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1")){
	 //alert("Please select a Sample Medium or select Other/New from the drop down to enter a new Sample Medium!");
	 alert(<?php echo "'".getTxt('SelectMedium')."'"; ?>);
		return false;    
   }
   
   if(checkitem.value=="-10"){
	//A new selection...need to process it first so that the entry will be valid
	
	//Check if new fields are filled
	if(($("#smnew").val())==""){
		//alert("Please enter a new Sample Medium!");
		alert(<?php echo "'".getTxt('EnterNewSampleMedium')."'"; ?>);
		return false;
	}
	
	if(($("#smnew").val().search("^[a-zA-Z0-9_.-]*$"))==-1){
		//alert("Invalid new Sample Medium. Sample Medium cannot contain any characters other than A-Z (case insensitive), 0-9, period (.), dash (-), and underscore (_).");
		alert(<?php echo "'".getTxt('InvalidSampleMedium')."'"; ?>);
		return false;
	}

	 
	 if((($("#smdef").val())=="")||(($("#smdef").val())=="Please enter a definition")){
		//alert("Please enter the definition for the new Sample Medium");
		alert(<?php echo "'".getTxt('EnterDefinitionNewSampleMedium')."'"; ?>);
		return false;
	}  
	
   }
//End Check SAMPLE MEDIUM

//Check Value type

addHidden('valuetype');
checkitem = $('#valuetype').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1")){
		//alert("Please select a Value Type or select Other/New from the drop down to enter a new Value Type!");
		alert(<?php echo "'".getTxt('SelectValueType')."'"; ?>);
		return false;    
   }
   
   if(checkitem.value=="-10")
   {
	//A new selection...need to process it first so that the entry will be valid
	
	//Check if new fields are filled
	if(($("#valuetypenew").val())==""){
		//alert("Please enter a new Value Type!");
		alert(<?php echo "'".getTxt('EnterNewValueType')."'"; ?>);
		return false;
	}
	
	if(($("#valuetypenew").val().search("^[a-zA-Z0-9_.-]*$"))==-1){
		//alert("Invalid new Value Type. Value Type cannot contain any characters other than A-Z (case insensitive), 0-9, period (.), dash (-), and underscore (_)."
		alert(<?php echo "'".getTxt('InvalidValueType')."'"; ?>);
		return false;
	}

	 
	 if((($("#vtdef").val())=="")||(($("#vtdef").val())=="Please enter a definition")){
		//alert("Please enter the definition for the new Value Type");
		alert(<?php echo "'".getTxt('EnterDefinitionNewValueType')."'";?>);
		return false;
	} 	   
   }

//End checking of Value Type


checkitem = $('#isreg').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1")){
		//alert("Please select the Regularity of the value.");
		alert(<?php echo "'".getTxt('SelectRegularity')."'"; ?>);
		return false;    
   }

checkitem = $('#timeunit').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1")){
		//alert("Please select the Time unit.");
		alert(<?php echo "'".getTxt('SelectTimeUnit')."'"; ?>);
		return false;    
   }
addHidden('datatype');
checkitem = $('#datatype').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1")){
		//alert("Please select the Data Type.");
		alert(<?php echo "'".getTxt('SelectDataTypeMsg')."'"; ?>);
		return false;    
   }
addHidden('gc');
checkitem = $('#gc').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1")){
		//alert("Please select the Category.");
		alert(<?php echo "'".getTxt('SelectCategoryMsg')."'"; ?>);
		return false;    
   }

//Checking ends
//Controller takes over from this point onwards.
return true;
});
	
</script>