<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
require_once 'authorization_check.php';
require_once 'fetchMainConfig.php';
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<title>HydroServer Lite Web Client</title>-->
<title><?php echo $WebClient; ?></title>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script> 


<link rel="stylesheet" href="js/jqwidgets/styles/jqx.base.css" type="text/css" />
<link rel="stylesheet" href="js/jqwidgets/styles/jqx.darkblue.css" type="text/css" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="favicon.ico" >

<script type="text/javascript" src="js/jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxdata.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxbuttons.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxscrollbar.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxlistbox.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxcombobox.js"></script>
<script type="text/javascript" src="js/jqwidgets/jqxdropdownlist.js"></script>

<link href="styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />

<script type="text/javascript">

function show_answerVC(){
//alert("An arbitrary code used by your organization to specify a specific variable record. For example, IDCS- could be used for International Data Collection System.");
alert(<?php echo "'" . $ArbitraryCode . "'" ?>);
}

function show_answerVDef(){
//alert("If creating a new variable, specify the definition here. Variables that already exist within the database are provided with definitions.");
alert(<?php echo "'".$VariableDefinitionMsg."'"; ?>);
}

function show_answerSpec(){
//alert("A code used to identify how the data value is expressed. For example, total phosphorous is expressed as P.");
alert(<?php echo "'".$ValueCode."'"; ?>);
}

function show_answerVUT(){
//alert("The general category for the kind of units your variable has.");
alert(<?php echo "'".$UnitsCategory."'"; ?>);
}

function show_answerUnit(){
//alert("The unit of measurement for this variable.");
alert(<?php echo "'".$UnitsMeasure."'"; ?>);
}

function show_answerUT(){
//alert("The length or volume/time associated with the variable.");
alert(<?php echo "'".$UTAssociated."'"; ?>);
}

function show_answerSM(){
//alert("The medium the sample or observation was taken from or made.");
alert(<?php echo "'".$ObservationMedium."'"; ?>);
}

function show_answerVType(){
//alert("The type of data value being recorded. For example, this variable was measured in the field or part of a simulation, etc.");
alert(<?php echo "'".$DataTypeMsg."'"; ?>);
}

function show_answerROV(){
//alert("Whether the data values are from a regularly sampled time series.");
alert(<?php echo "'".$RegularlySampledTime."'"; ?>);
}

function show_answerTS(){
//alert("Numerical value that indicates the temporal footprint of the data values. 0 indicates instantaneous samples (samples taken at random or irregular intervals). Other values indicate the time over which data values are aggregated. For example, the value was collected every 10 minutes.");
alert(<?php echo "'".$TemporalFootprint."'"; ?>);
}

function show_answerCat(){
//alert("The general scientific category this variable being measured fits into.");
alert(<?php echo "'".$ScientificCategory."'"; ?>);
}

function show_answerMeth(){
//alert("The methods specifically used to collect this variable.");
alert(<?php echo "'".$VariableCollectionMethod."'"; ?>);
}

</script>

<script type="text/javascript">
var unitsid=0;
//Default Parameter
//Can be linked to the config page
var nodatavalue=-9999;

$(document).ready(function(){
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

//Default starter for Variable Code
//	var d_varcode = $default_varcode;

//	$("#VariableCode").val(d_varcode);

//List : Speciation
var selec_ind=0;
var url="getspec.php";

                // prepare the data
                var source =
                {
                    datatype: "json",
                    datafields: [
                        { name: 'specterm' },
                        { name: 'specdef' }
                    ],
                    id: 'id',
                    url: url,
                    async: false
                };
                var dataAdapter = new $.jqx.dataAdapter(source);

                // Create a jqxComboBox
$("#specdata").jqxDropDownList({ selectedIndex: 0, source: dataAdapter, displayMember: "specterm", valueMember: "specdef", width: 250, height: 25, theme: 'darkblue'});
	
$("#specdata").bind('select', function (event) {
var args = event.args;
var item = $('#specdata').jqxDropDownList('getItem', args.index);
    if ((item != null)&&(item.value!="-1")&&(item.value!="-10")) 
{					
 $("#specdef").val(item.value);
 $("#specdef").attr('disabled', true);
 $("#new_spec").hide();
$("#new_spec1").hide();
 }
 
 if(item.value=="-10")
 {
//If user selects other option
	$("#specdef").removeAttr("disabled");	 
	 //$("#specdef").val("Please enter a definition");
	 $("#specdef").val(<?php echo "'".$EnterDefinition."'"; ?>);
//Show the other box
$("#new_spec").show(200);
$("#new_spec1").show(200);



 }
 
  });
  
var url2="getunittype.php";

                // prepare the data
                var source2 =
                {
                    datatype: "json",
                    datafields: [
                        { name: 'unitype' },
                        { name: 'unitid' }
                    ],
                    id: 'id',
                    url: url2,
                    async: false
                };
                var dataAdapter2 = new $.jqx.dataAdapter(source2);

// Create a jqxComboBox for the var unit types
$("#unittype").jqxDropDownList({ selectedIndex: 0, source: dataAdapter2, displayMember: "unitype", valueMember: "unitid", width: 250, height: 25, theme: 'darkblue'});

$("#unittype").bind('select', function (event) {
var args = event.args;
var item = $('#unittype').jqxDropDownList('getItem', args.index);

    if ((item != null)&&(item.value!="-1")&&(item.value!="-10")) 
{					

//Get all the units for that type and display it
$("#newunit").hide();
$("#newunitonly").hide();
$("#unit").show();
$("#unitreq").show();
$("#unittext").show();
var url3="getunitname.php?type="+item.label;

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
	
// Create a jqxComboBox for the var unit types
$("#unit").jqxDropDownList({ selectedIndex: 0, source: dataAdapter3, displayMember: "unit", valueMember: "unitid", width: 250, height: 25, theme: 'darkblue'});

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
$("#unitreq").hide();
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
                        { name: 'smterm' },
                        { name: 'smdef' }
                    ],
                    id: 'id',
                    url: 'getsamplemed.php',
                    async: false
                };
                var dataAdapter4 = new $.jqx.dataAdapter(source4);


$("#samplemedium").jqxDropDownList({ selectedIndex: 0, source: dataAdapter4, displayMember: "smterm", valueMember: "smdef", width: 250, height: 25, theme: 'darkblue'});
	
$("#samplemedium").bind('select', function (event) {
var args = event.args;
var item = $('#samplemedium').jqxDropDownList('getItem', args.index);
    if ((item != null)&&(item.value!="-1")&&(item.value!="-10")) 
{					


 $("#smdef").val(item.value);
 $("#smdef").attr('disabled', true);
 
 $("#smother").hide();
 }
 
 if(item.value=="-10")
 {
	 
//If user selects other option
	$("#smdef").removeAttr("disabled");	 
	 $("#smdef").val(<?php echo "'".$EnterDefinition."'"; ?>);
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
                        { name: 'vtterm' },
                        { name: 'vtdef' }
                    ],
                    id: 'id',
                    url: 'getvt.php',
                    async: false
                };
                var dataAdapter5 = new $.jqx.dataAdapter(source5);


$("#valuetype").jqxDropDownList({ selectedIndex: 0, source: dataAdapter5, displayMember: "vtterm", valueMember: "vtdef", width: 250, height: 25, theme: 'darkblue'});
	
$("#valuetype").bind('select', function (event) {
var args = event.args;
var item = $('#valuetype').jqxDropDownList('getItem', args.index);
    if ((item != null)&&(item.value!="-1")&&(item.value!="-10")) 
{					


 $("#vtdef").val(item.value);
 $("#vtdef").attr('disabled', true);
 
 $("#valuetypenewb").hide();
 }
 
 if(item.value=="-10")
 {
	 
//If user selects other option
	$("#vtdef").removeAttr("disabled");	 
	 $("#vtdef").val(<?php echo "'".$EnterDefinition."'"; ?>);
//Show the other box
$("#valuetypenewb").show(400);



 }
 
  });


//End of Value type list


//Start of isregular
var source7 = [
                    "<?php echo $SelectEllipsis;?>",
					"<?php echo $Regular;?>",
                    "<?php echo $Irregular;?>",
                    "<?php echo $Unknown;?>"
		        ];

                // Create a jqxDropDownList
$("#isreg").jqxDropDownList({ source: source7, selectedIndex: 0, width: '250', height: '25', theme: 'darkblue' });

//End of is regular

//begin time units id

var source8 =
                {
                    datatype: "json",
                    datafields: [
                        { name: 'unit' },
                        { name: 'id' }
                    ],
                    id: 'id',
                    url: 'gettimeunit.php',
                    async: false
                };
                var dataAdapter8 = new $.jqx.dataAdapter(source8);


$("#timeunit").jqxDropDownList({ selectedIndex: 0, source: dataAdapter8, displayMember: "unit", valueMember: "id", width: 250, height: 25, theme: 'darkblue'});
	

//End time units id

//begin Data type list
var source9 =
                {
                    datatype: "json",
                    datafields: [
                        { name: 'dtterm' },
                        { name: 'dtdef' }
                    ],
                    id: 'id',
                    url: 'getdt.php',
                    async: false
                };
                var dataAdapter9 = new $.jqx.dataAdapter(source9);



$("#datatype").jqxDropDownList({ selectedIndex: 0, source: dataAdapter9, displayMember: "dtterm", valueMember: "dtdef", width: 250, height: 25, theme: 'darkblue'});
	
$("#datatype").bind('select', function (event) {
var args = event.args;
var item = $('#datatype').jqxDropDownList('getItem', args.index);
    if ((item != null)&&(item.value!="-1")) 
{					


 $("#dtdef").val(item.value);

 
 }
 
  });

// End of data type list


//begin general category list
var source10 =
                {
                    datatype: "json",
                    datafields: [
                        { name: 'dtterm' },
                        { name: 'dtdef' }
                    ],
                    id: 'id',
                    url: 'getgc.php',
                    async: false
                };
                var dataAdapter10 = new $.jqx.dataAdapter(source10);



$("#gc").jqxDropDownList({ selectedIndex: 0, source: dataAdapter10, displayMember: "dtterm", valueMember: "dtdef", width: 250, height: 25, theme: 'darkblue'});
	
$("#gc").bind('select', function (event) {
var args = event.args;
var item = $('#gc').jqxDropDownList('getItem', args.index);
    if ((item != null)&&(item.value!="-1")) 
{					


 $("#gcdef").val(item.value);

 
 }
 
  });

// End of data type list

//Variable Name list : new option available

var url15="getvarname.php";

                // prepare the data
                var source15 =
                {
                    datatype: "json",
                    datafields: [
                        { name: 'specterm' },
                        { name: 'specdef' }
                    ],
                    id: 'id',
                    url: url15,
                    async: false
                };
                var dataAdapter15 = new $.jqx.dataAdapter(source15);

                // Create a jqxComboBox
$("#varname").jqxDropDownList({ selectedIndex: 0, source: dataAdapter15, displayMember: "specterm", valueMember: "specdef", width: 250, height: 25, theme: 'darkblue'});
	
$("#varname").bind('select', function (event) {
var args = event.args;
var item = $('#varname').jqxDropDownList('getItem', args.index);
    if ((item != null)&&(item.value!="-1")&&(item.value!="-10")) 
{					
 $("#vardef").val(item.value);
 $("#vardef").attr('disabled', true);
 	 $("#newvarnameb").hide();
 }
 
 if(item.value=="-10"){
	 
//If user selects other option
	$("#vardef").removeAttr("disabled");	 
	$("#vardef").val(<?php echo "'".$EnterDefinition."'"; ?>);
//Show the other box
 $("#newvarnameb").show(200);


 }
 
  });

//End of variable list
	});

</script>

<script type="text/javascript">
//The following script is for the Method listbox
	var varmeth="";
	$(document).ready(function(){
	
		var source =
		{
	       	datatype: "json",
		   	datafields: [
        	  	{ name: 'methodname' },
	        	{ name: 'methodid' },
	       	],
    	       	url: 'db_get_methods2.php'
		};			
	
	var dataAdapter = new $.jqx.dataAdapter(source);
        // Create a jqxListBox
        $("#jqxWidget").jqxListBox({source: dataAdapter, theme: 'darkblue', multiple: true, width: 425, height: 300, displayMember: "methodname", valueMember: "methodid"});

	 	$("#jqxWidget").bind('change', function(){
			var items = $("#jqxWidget").jqxListBox('getItems');
			// get selected indexes.
			var selectedIndexes = $("#jqxWidget").jqxListBox('selectedIndexes');
			var selectedItems = [];
			varmeth="";
			// get selected items.
			for(var index in selectedIndexes){
				if(selectedIndexes[index] != -1){
					selectedItems[index] = items[index];
					varmeth+=selectedItems[index].value;
						if(index!=(selectedIndexes.length-1)){
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
    <td colspan="2" align="right" valign="middle" bgcolor="#3c3c3c"><?php include 'header.php'; ?></td>
  </tr>
  <tr>
    <td width="240" valign="top" bgcolor="#f2e6d6"><?php echo "$nav"; ?></td>
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote>
      <p><br /><p class="em" align="right"><!--Required fields are marked with an asterick (*).--><?php echo $RequiredFieldsAsterisk;?></p>
       <div id="msg">
          <p class=em2><!--Variable successfully added!--><?php echo $VariableSuccessfullyAdded;?></p></div>
        <h1><!--Add a New Variable--><?php echo $AddNewVariable;?></h1>
      <form action="" method="post" name="add_var" id="add_var">
        <table width="600" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">&nbsp;</td>
            <td colspan="3" valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td valign="top"><strong><!--Variable Code:--><?php echo $VariableCode;?></strong></td>
          <td colspan="3" valign="top">
          <input type="text" id="var_code" name="VariableCode" value="<?php echo $default_varcode; ?>" size="15" />*&nbsp;<a href="#" onClick="show_answerVC()" border="0"><img src="images/questionmark.png" border="0"></a>&nbsp;<span class='em'><!--(Ex: IDCS-22 or IDCS-22-Avg)--><?php echo $VariableCodeExample;?></span></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td valign="top"><strong><!--Variable Name:--><?php echo $VariableName;?></strong></td>
          <td valign="top"><div id="varname"></div></td>
          <td colspan="2" valign="top">*</td>
          </tr>
        <tbody id="newvarnameb">
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top"><strong><!--New Variable Name:--><?php echo $NewVarName;?></strong>&nbsp;<input type="text" id="newvarname" name="newvarname" value="" size="32" />*</td>
        </tr>
        </tbody>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Variable Definition:--><?php echo $VariableDefinition;?></strong></td>
          <td colspan="3" valign="top"><input name="vardef" type="text" id="vardef" value="" size="60" maxlength="200" />*&nbsp;<a href="#" onClick="show_answerVDef()" border="0"><img src="images/questionmark.png" border="0"></a></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td width="175" valign="top"><strong><!--Speciation:--><?php echo $Speciation;?></strong></td>
          <td valign="top"><div id="specdata"></div></td>
          <td colspan="2" valign="top">*&nbsp;<a href="#" onClick="show_answerSpec()" border="0"><img src="images/questionmark.png" border="0"></a></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>

        <tr id="new_spec">
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top"><strong><!--New Speciation:--><?php echo $NewSpeciation;?></strong>&nbsp;<input type="text" id="other_spec" name="other_spec" value="" size="15" />*</td>
        </tr>
		<tr id="new_spec1">
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
          </tr>
          
        <tr>
          <td valign="top"><strong><!--Speciation Definition:--><?php echo $SpeciationDef;?></strong></td>
          <td colspan="3" valign="top"><input name="specdef" type="text" id="specdef" value="" size="28" maxlength="200" />*&nbsp;</td>
        </tr>
        
        
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" valign="top"><strong><!--Variable Unit Type:--><?php echo $VariableUnitType;?></strong></td>
          <td width="252" valign="top"><div id="unittype"></div></td>
          <td colspan="2" valign="top">*&nbsp;<a href="#" onClick="show_answerVUT()" border="0"><img src="images/questionmark.png" border="0"></a></td>
          </tr>
        <tbody id="unitreq">
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><div id="unittext"><strong><!--Unit:--><?php echo $Unit;?></strong></div></td>
          <td valign="top"><div id="unit"></div></td>
          <td colspan="2" valign="top"><span id="unitreq">*&nbsp;<a href="#" onClick="show_answerUnit()" border="0"><img src="images/questionmark.png" border="0"></a></span></td>
          </tr>
        </tbody>
        <tbody id="newunit">
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top"><span class=em2><!--New Unit Definition:--><?php echo $NewUnitDefinitionColon;?></span></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top"><strong><!--Unit Type:--><?php echo $UnitType;?></strong>&nbsp;<input type="text" id="new_unit_type" name="new_unit_type" value="" size="32" />*<a href="#" onClick="show_answerUT()" border="0"><img src="images/questionmark.png" border="0"></a>&nbsp;</td>
        </tr>
          </tbody>
        <tbody id="newunitonly">
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
      
        
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top"><strong><!--Unit Name:--><?php echo $UnitName;?></strong>&nbsp;<input type="text" id="new_unit_name" name="new_unit_name" value="" size="32" />*</td>
        </tr>
        
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top"><strong><!--Unit Abbreviation:--><?php echo $UnitAbbreviation;?></strong>&nbsp;<input type="text" id="new_unit_abb" name="new_unit_abb" value="" size="32" />*</td>
        </tr>
        </tbody>
    
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Sample Medium: --><?php echo $SampleMedium;?></strong></td>
          <td valign="top"><div id="samplemedium"></div></td>
          <td colspan="2" valign="top">*&nbsp;<a href="#" onClick="show_answerSM()" border="0"><img src="images/questionmark.png" border="0"></a></td>
          </tr>
        <tbody id="smother">
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top"><strong><!--New Sample Medium:--><?php echo $NewSampleMedium;?></strong>&nbsp;<input type="text" id="smnew" name="smnew" value="" size="32" />*</td>
        </tr>
           </tbody>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Sample Med. Definition:--><?php echo $MediumDefinition;?></strong></td>
          <td colspan="3" valign="top"><input name="smdef" type="text" id="smdef" value="" size="60" maxlength="200" />*</td>
        </tr>
        
     
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Value Type:--><?php echo $ValueType;?></strong></td>
          <td valign="top"><div id="valuetype"></div></td>
          <td colspan="2" valign="top">*&nbsp;<a href="#" onClick="show_answerVType()" border="0"><img src="images/questionmark.png" border="0"></a></td>
          </tr>
        <tbody id="valuetypenewb">
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top"><strong><!--Value Type New:--><?php echo $ValueTypeNew;?></strong>&nbsp;<input type="text" id="valuetypenew" name="valuetypenew" value="" size="32" />*</td>
        </tr>
        </tbody>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Value Type Definition:--><?php echo $ValueTypeDefinition;?></strong></td>
          <td colspan="3" valign="top"><input name="vtdef" type="text" id="vtdef" value="" size="60" maxlength="200" />*&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td height="18" valign="top"><strong><!--Regularity of Value:--><?php echo $Regularity;?></strong></td>
          <td valign="top"><div id="isreg"></div></td>
          <td colspan="2" valign="top">*&nbsp;<a href="#" onClick="show_answerROV()" border="0"><img src="images/questionmark.png" border="0"></a></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Time Support:--><?php echo $TimeSupport;?></strong></td>
          <td colspan="3" valign="top"><input type="text" id="tsup" name="tsup" value="<?php echo $default_timesupport; ?>" size="15" />*&nbsp;<a href="#" onClick="show_answerTS()" border="0"><img src="images/questionmark.png" border="0"></a></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Time Unit:--><?php echo $TimeUnit;?></strong></td>
          <td valign="top"><div id="timeunit"></div></td>
          <td colspan="2" valign="top">*&nbsp;</td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Data Type:--><?php echo $DataType;?></strong></td>
          <td valign="top"><div id="datatype"></div></td>
          <td colspan="2" valign="top">*&nbsp;</td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Data Type Definition:--><?php echo $DataTypeDefinition;?></strong></td>
          <!--<td colspan="3"><textarea name="dtdef" cols="45" rows="4" readonly id="dtdef"><!--Please select a data type to view its definition--><!--<?php echo $SelectData;?></textarea>*&nbsp;</td>-->
          <td colspan="3"><textarea name="dtdef" cols="45" rows="4" readonly id="dtdef"><?php echo $SelectData;?></textarea>*&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Category:--><?php echo $Category;?></strong></td>
          <td valign="top"><div id="gc"></div></td>
          <td width="133" valign="top">*&nbsp;<a href="#" onClick="show_answerCat()" border="0"><img src="images/questionmark.png" border="0"></a></td>
          <td width="40" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><strong><!--Category Definition:--><?php echo $CategoryDefinition;?></strong></td>
          <!--<td colspan="3" valign="top"><input name="gcdef" type="text" id="gcdef" value="Please select a category to view its definition" size="60" readonly>*&nbsp;</td>-->
          <td colspan="3" valign="top"><input name="gcdef" type="text" id="gcdef" value="<?php echo $SelectCategory;?>" size="60" readonly>*&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" valign="top"><strong><!--Please select the Method(s) below used by this Variable:--><?php echo $SelectMethods;?></strong> <br>
<!--(Select all that apply by holding the &quot;Ctrl&quot; key down and selecting multiple options):--><?php echo $SelectAllThatApply;?></td>
          </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" valign="top"><div id='jqxWidget'></div></td>
          <td valign="top">*&nbsp;<a href="#" onClick="show_answerMeth()" border="0"><img src="images/questionmark.png" border="0"></a></td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <!--<td colspan="4" valign="top"><input type="SUBMIT" name="submit" value="Add Variable" class="button" /></td>-->
          <td colspan="4" valign="top"><input type="SUBMIT" name="submit" value="<?php echo $AddVariableButton;?>" class="button" /></td>
          </tr>
      </table>
    </FORM></p>
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
if(($("#var_code").val())==""){
		//alert("Please enter a Variable Code!");
		alert(<?php echo "'".$EnterVariableCode."'"; ?>);
		return false;
	}
	
	if(($("#var_code").val().search("^[a-zA-Z0-9_.-]*$"))==-1)
{
		//alert("Invalid Variable code. VaraibleCodes cannot contain any characters other than A-Z (case insensitive), 0-9, period (.), dash (-), and underscore (_).");
		alert(<?php echo "'".$InvalidVariableCode."'"; ?>);
		return false;
	}


//Check variable Name

var checkitem = $('#varname').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1"))
   {
	 //alert("Please select a variable name or select Other/New from the drop down to enter a new variable name!");
	 alert(<?php echo "'".$SelectVariableName."'"; ?>);
		return false;    
   }
   
   if(checkitem.value=="-10")
   {
	//A new selection...need to process it first so that the entry will be valid
	
	//Check if new fields are filled
	if(($("#newvarname").val())==""){
		//alert("Please enter a new variable name!");
		alert(<?php echo "'".$EnterNewVariable."'"; ?>);
		return false;
	}
	
	if(($("#newvarname").val().search("^[a-zA-Z0-9_.-]*$"))==-1)
{
		//alert("Invalid Variable name. Varaible Name cannot contain any characters other than A-Z (case insensitive), 0-9, period (.), dash (-), and underscore (_).");
		alert(<?php echo "'".$InvalidVariableName."'"; ?>);
		return false;
	}

	 
	 
	 if((($("#vardef").val())=="")||(($("#vardef").val())=="Please enter a definition")){
		//alert("Please enter the definition for the new variable");
		alert(<?php echo "'".$EnterDefinitionNewVariable."'"; ?>);
		return false;
	}  
	
	
	//Process the new var name
	
	$.ajax({
  type: "POST",
  url: "do_add_varname.php?varname="+$("#newvarname").val()+"&vardef="+$("#vardef").val()
}).done(function( msg ) {
  if(msg==1)
  {
  }
  else
  {
	  alert(msg);
	  return false;  
  }
 });
	   
   }
  
checkitem = $('#specdata').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1"))
   {
	 //alert("Please select a Speciation or select Other/New from the drop down to enter a new Speciation!");
		return false;
		alert(<?php echo "'".$SelectSpeciation."'"; ?>);    
   }
   
   if(checkitem.value=="-10")
   {
	//A new selection...need to process it first so that the entry will be valid
	
	//Check if new fields are filled
	if(($("#other_spec").val())==""){
		//alert("Please enter a new Speciation!");
		alert(<?php echo "'".$SelectNewSpeciation."'"; ?>);
		return false;
	}

	 if((($("#specdef").val())=="")||(($("#specdef").val())=="Please enter a definition")){
		//alert("Please enter the definition for the NEW Speciation");
		alert(<?php echo "'".$EnterDefinitionNewSpeciation."'"; ?>);
		return false;
	}  
	   
	   $.ajax({
  type: "POST",
  url: "do_add_spec.php?varname="+$("#other_spec").val()+"&vardef="+$("#specdef").val()
}).done(function( msg ) {
  if(msg==1)
  {
  }
  else
  {
	  alert(msg);
	  return false;  
  }
 });
   }
   
 
checkitem = $('#unittype').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1"))
   {
	 //alert("Please select a Variable Unit Type or select Other/New from the drop down to enter a new Unit Type!");
	 alert(<?php echo "'".$SelectVariableUnitType."'"; ?>);
		return false;    
   }

if ((checkitem != null)&&(checkitem.value!="-1")&&(checkitem.value!="-10")) 
   
{
//If type selected...check if unit selected

var unititem = $('#unit').jqxDropDownList('getSelectedItem');

if ((unititem == null)||(unititem.value=="-1"))
   {
	 //alert("Please select a Unit or select Other/New from the drop down to enter a new Unit!");
	 alert(<?php echo "'".$SelectUnit."'"; ?>);
		return false;    
   }


 if(unititem.value=="-10")
   {
	//A new selection...need to process it first so that the entry will be valid
	
	//Check if new fields are filled
	if(($("#new_unit_name").val())==""){
		//alert("Please enter a name for the new Unit!");
		alert(<?php echo "'".$EnterNameNewUnit."'"; ?>);
		return false;
	}

	 if(($("#new_unit_abb").val())==""){
		//alert("Please enter an abbreviation for the new Unit!");
		alert(<?php echo "'".$EnterAbbreviationNewUnit."'"; ?>);
		return false;
	}  
	  //All tests Passed...Write code to inset NEW UNIT 
	  
	   $.ajax({
  type: "POST",
  url: "do_add_unit.php?varname="+$("#new_unit_name").val()+"&vardef="+$("#new_unit_abb").val()+"&vartype="+checkitem.label
}).done(function( msg ) {
  if(msg=="The unit already exists. Cannot Add again. Please select it from the drop down list.")
  {
	  alert(msg);
	  return false;
  }
  else
  {
	unitsid=msg;
	 
  }
 });
	  
	  
   }



}
   if(checkitem.value=="-10")
   {
	//A new selection...need to process it first so that the entry will be valid
	
	//Check if new fields are filled
	if(($("#new_unit_name").val())==""){
		//alert("Please enter a name for the new Unit!");
		alert(<?php echo "'".$EnterNameNewUnit."'"; ?>);
		return false;
	}

	 if(($("#new_unit_abb").val())==""){
		//alert("Please enter an abbreviation for the new Unit!");
		alert(<?php echo "'".$EnterAbbreviationNewUnit."'"; ?>);
		return false;
	}  

 if(($("#new_unit_type").val())==""){
		//alert("Please enter the type of the new unit!");
		alert(<?php echo "'".$EnterTypeNewUnit."'"; ?>);
		return false;
	}  
	   

//add a new unit inlucing a new data type

   $.ajax({
  type: "POST",
  url: "do_add_unit.php?varname="+$("#new_unit_name").val()+"&vardef="+$("#new_unit_abb").val()+"&vartype="+$("#new_unit_type").val()
}).done(function( msg ) {
	var myMsg = new Array();
	myMsg = msg.split("|");
	
	if(myMsg[0] == "false") //the first part of the message is true/false whether we succeeded or not.  =="The unit already exists. Cannot Add again. Please select it from the drop down list.")
	  {
		  alert(myMsg[1]);
		  return false;
	  }
	  else
	  {
		unitsid=msg;
	  }
 });
	  

   }
	
//Check for Sample Medium



checkitem = $('#samplemedium').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1"))
   {
	 //alert("Please select a Sample Medium or select Other/New from the drop down to enter a new Sample Medium!");
	 alert(<?php echo "'".$SelectMedium."'"; ?>);
		return false;    
   }
   
   if(checkitem.value=="-10")
   {
	//A new selection...need to process it first so that the entry will be valid
	
	//Check if new fields are filled
	if(($("#smnew").val())==""){
		//alert("Please enter a new Sample Medium!");
		alert(<?php echo "'".$EnterNewSampleMedium."'"; ?>);
		return false;
	}
	
	if(($("#smnew").val().search("^[a-zA-Z0-9_.-]*$"))==-1)
{
		//alert("Invalid new Sample Medium. Sample Medium cannot contain any characters other than A-Z (case insensitive), 0-9, period (.), dash (-), and underscore (_).");
		alert(<?php echo "'".$InvalidSampleMedium."'"; ?>);
		return false;
	}

	 
	 if((($("#smdef").val())=="")||(($("#smdef").val())=="Please enter a definition")){
		//alert("Please enter the definition for the new Sample Medium");
		alert(<?php echo "'".$EnterDefinitionNewSampleMedium."'"; ?>);
		return false;
	}  
	
	   
	   $.ajax({
  type: "POST",
  url: "do_add_sm.php?varname="+$("#smnew").val()+"&vardef="+$("#smdef").val()
}).done(function( msg ) {
  if(msg==1)
  {
  }
  else
  {
	  alert(msg);
	  return false;  
  }
 });
	   
   }
//End Check SAMPLE MEDIUM

//Check Value type


checkitem = $('#valuetype').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1"))
   {
	 //alert("Please select a Value Type or select Other/New from the drop down to enter a new Value Type!");
	 alert(<?php echo "'".$SelectValueType."'"; ?>);
		return false;    
   }
   
   if(checkitem.value=="-10")
   {
	//A new selection...need to process it first so that the entry will be valid
	
	//Check if new fields are filled
	if(($("#valuetypenew").val())==""){
		//alert("Please enter a new Value Type!");
		alert(<?php echo "'".$EnterNewValueType."'"; ?>);
		return false;
	}
	
	if(($("#valuetypenew").val().search("^[a-zA-Z0-9_.-]*$"))==-1)
{
		//alert("Invalid new Value Type. Value Type cannot contain any characters other than A-Z (case insensitive), 0-9, period (.), dash (-), and underscore (_).");
		alert(<?php echo "'".$InvalidValueType."'"; ?>);
		return false;
	}

	 
	 if((($("#vtdef").val())=="")||(($("#vtdef").val())=="Please enter a definition")){
		//alert("Please enter the definition for the new Value Type");
		alert(<?php echo "'".$EnterDefinitionNewValueType."'";?>);
		return false;
	} 
	
	 
	   $.ajax({
  type: "POST",
  url: "do_add_vt.php?varname="+$("#valuetypenew").val()+"&vardef="+$("#vtdef").val()
}).done(function( msg ) {
  if(msg==1)
  {
  }
  else
  {
	  alert(msg);
	  return false;  
  }
 });
	 
	   
   }

//End Check Value type


checkitem = $('#isreg').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1"))
   {
	 //alert("Please select the Regularity of the value.");
	 alert(<?php echo "'".$SelectRegularity."'"; ?>);
		return false;    
   }


checkitem = $('#timeunit').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1")){
		//alert("Please select the Time unit.");
		alert(<?php echo "'".$SelectTimeUnit."'"; ?>);
		return false;    
   }

checkitem = $('#datatype').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1")){
		//alert("Please select the Data Type.");
		alert(<?php echo "'".$SelectDataTypeMsg."'"; ?>);
		return false;    
   }

checkitem = $('#gc').jqxDropDownList('getSelectedItem');

   if ((checkitem == null)||(checkitem.value=="-1")){
		//alert("Please select the Category.");
		alert(<?php echo "'".$SelectCategoryMsg."'"; ?>);
		return false;    
   }


if(($("#tsup").val())==""){
		//alert("Please enter a Time Support Value! 0 is used to indicate data values that are instantaneous. Other values indicate the time over which the data values are implicitly or explicitly averaged or aggregated.");
		alert(<?php echo "'".$EnterTimeSupportValue."'"; ?>);
		return false;
	}

//Checking ends

var f_vc=$("#var_code").val();

checkitem = $('#varname').jqxDropDownList('getSelectedItem');

	if(checkitem.value=="-10"){
		var f_vn=$("#newvarname").val();
	}else{
		var f_vn=checkitem.label;
	}

checkitem = $('#specdata').jqxDropDownList('getSelectedItem');

	if(checkitem.value=="-10"){
		var f_sp=$("#other_spec").val();
	}else{
		var f_sp=checkitem.label;
	}

checkitem = $('#unittype').jqxDropDownList('getSelectedItem');
unititem = $('#unit').jqxDropDownList('getSelectedItem');

	if((checkitem.value=="-10")||(unititem.value=="-10")){
		var f_un=unitsid;
	}else{
		var f_un=unititem.value;
	}

checkitem = $('#samplemedium').jqxDropDownList('getSelectedItem');

	if(checkitem.value=="-10"){
	   var f_sm=$("#smnew").val();
	}else{
		var f_sm=checkitem.label;
	}

checkitem = $('#valuetype').jqxDropDownList('getSelectedItem');

	if(checkitem.value=="-10"){
		var f_vt=$("#valuetypenew").val();
	}else{
		var f_vt=checkitem.label;
	}

if(varmeth==""){
	//alert("Please select at least one Method!");
	alert(<?php echo "'".$SelectOneMethod."'"; ?>);
	return false;
	}

var isreg=$('#isreg').jqxDropDownList('getSelectedItem').value;
var f_tid=$('#timeunit').jqxDropDownList('getSelectedItem').value;
var f_dt=$('#datatype').jqxDropDownList('getSelectedItem').label;
var f_cat=$('#gc').jqxDropDownList('getSelectedItem').label;

   $.ajax({
  type: "POST",
  url: "do_add_variable.php?varcode="+f_vc+"&varname="+f_vn+"&sp="+f_sp+"&unit="+f_un+"&sm="+f_sm+"&vt="+f_vt+"&isreg="+isreg+"&ts="+$("#tsup").val()+"&tid="+f_tid+"&dt="+f_dt+"&cat="+f_cat+"&nodata="+nodatavalue+"&mid="+varmeth}).done(function( msg ){

	if(msg==1){

	  $("#msg").show(1600);
	  $("#msg").hide(5000);
	 
    $("form").find(':input').each(function(){
              switch(this.type) {
            case 'submit':
                break;
            default:
               $(this).val('');
        }
    });


unitsid=0;
$("#new_spec").hide();
$("#new_spec1").hide();
$("#unit").hide();
$("#unittext").hide();
$("#newunit").hide();
$("#smother").hide();
$("#newunitonly").hide();
$("#valuetypenewb").hide();
$("#newvarnameb").hide();
$("#varname").jqxDropDownList('selectIndex', 0 );
$("#specdata").jqxDropDownList('selectIndex', 0 );
$("#unittype").jqxDropDownList('selectIndex', 0 );
$("#unit").jqxDropDownList('selectIndex', 0 );
$("#samplemedium").jqxDropDownList('selectIndex', 0 );
$("#valuetype").jqxDropDownList('selectIndex', 0 );
$("#isreg").jqxDropDownList('selectIndex', 0 ); 
$("#timeunit").jqxDropDownList('selectIndex', 0 );
$("#datatype").jqxDropDownList('selectIndex', 0 );
$("#gc").jqxDropDownList('selectIndex', 0 );
$("#jqxWidget").jqxListBox('clearSelection');
$("#dtdef").val("Please select a data type to view its definition");
$("#gcdef").val("Please select a category to view its definition");
 $("html, body").animate({ scrollTop: 0 }, "slow")
  }
  else
  {
	  alert(msg);
	  return false;  
  }
 });
return false;
});
	
</script>