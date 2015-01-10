<?php
HTML_Render_Head($js_vars);
echo $JS_JQuery;
echo $CSS_JQX;
echo $JS_JQX;
echo $JS_GetTheme;
echo $CSS_Main;?>
<script type="text/javascript">
	  $(document).ready(function () {
		  // prepare the data
		  var data = {};
		  var theme = 'darkblue';
		  var source =
		  {
			  datatype: "json",
			  datafields: [
				   { name: 'SeriesID' },
				   { name: 'SiteID' },
				   { name: 'SiteCode' },
				   { name: 'SiteName' },
				   { name: 'SiteType' },
				   { name: 'VariableID' },
				   { name: 'VariableName' },
				   { name: 'Speciation' },
				   { name: 'VariableunitsID' },
				   { name: 'VariableunitsName' },
				   { name: 'SampleMedium' },
				   { name: 'ValueType' },
				   { name: 'TimeSupport' },
				   { name: 'TimeunitsID' },
				   { name: 'TimeunitsName' },
				   { name: 'DataType' },
				   { name: 'GeneralCategory' },
				   { name: 'MethodID' },
				   { name: 'MethodDescription' },
				   { name: 'SourceID' },
				   { name: 'Organization' },
				   { name: 'SourceDescription' },
				   { name: 'BeginDateTime' },
				   { name: 'EndDateTime' },
				   { name: 'ValueCount' }
			  ],
			  id: 'SeriesID',
			  url: base_url+'series/getJSON',
			  updaterow: function (rowid, rowdata) {
				  
				  // synchronize with the server - send update command
				  console.log(rowdata.SeriesID);
				  $.ajax({
					  dataType: 'json',
					  type:'POST',
					  url: base_url+"series/update",
					  data: rowdata
					  }).done(function(data){
					  if(data.status=="success")
					  {
						window.open(base_url+"series","_self");
					  }
					  else
					  {
						alert(data.reason);  
					  }
			  }).
			  fail(function(data){
				  console.log(data);
			  });
			  }
		  };
		  // initialize jqxGrid
		  $("#jqxgrid").jqxGrid(
		  {

			  selectionmode: 'singlecell',
			  source: source,
			  theme: theme,
			  columnsresize: true,
			  editable: true,
			  columns: [
					{ text: '<?php echo getTxt('SeriesID')?>', editable: false, datafield: 'SeriesID', width:100},
					{ text: '<?php echo getTxt('siteid')?>', datafield: 'SiteID' , width:100},
					{ text: '<?php echo getTxt('SiteCode')?>', editable: false, datafield: 'SiteCode', width:100},
					{ text: '<?php echo getTxt('SiteName')?>', editable: false, datafield: 'SiteName', width:100},
					{ text: '<?php echo getTxt('SiteType')?>', editable: false, datafield: 'SiteType', width:100},
					{ text: '<?php echo getTxt('varid')?>', datafield: 'VariableID', width:100},
					{ text: '<?php echo getTxt('VariableName')?>', editable: false, datafield: 'VariableName', width:100},
					{ text: '<?php echo getTxt('Speciation')?>', editable: false, datafield: 'Speciation', width:100},
					{ text: '<?php echo getTxt('UnitName')?>', editable: false, datafield: 'VariableunitsName', width:100},
					{ text: '<?php echo getTxt('SampleMedium')?>', editable: false, datafield: 'SampleMedium', width:100},
					{ text: '<?php echo getTxt('ValueType')?>', editable: false, datafield: 'ValueType', width:100},
					{ text: '<?php echo getTxt('TimeSupport')?>', editable: false, datafield: 'TimeSupport', width:100},
					{ text: '<?php echo getTxt('TimeUnit')?>', editable: false, datafield: 'TimeunitsName', width:100},
					{ text: '<?php echo getTxt('DataType')?>', editable: false, datafield: 'DataType', width:100},
					{ text: '<?php echo getTxt('Category')?>', editable: false, datafield: 'GeneralCategory', width:100},
					{ text: '<?php echo getTxt('methodid')?>', datafield: 'MethodID', width:100},
					{ text: '<?php echo getTxt('MethodName')?>', editable: false, datafield: 'MethodDescription', width:150},
					{ text: '<?php echo getTxt('sourceid')?>', datafield: 'SourceID', width:100},
					{ text: '<?php echo getTxt('Organization')?>', editable: false, datafield: 'Organization', width:100},
					{ text: '<?php echo getTxt('Description');?>', editable: false, datafield: 'SourceDescription', width:100},
					{ text: '<?php echo getTxt('StartDate');?>', editable: false, datafield: 'BeginDateTime', width:200},
					{ text: '<?php echo getTxt('EndDate');?>', editable: false, datafield: 'EndDateTime', width:200},
					{ text: '<?php echo getTxt('ValueCount')?>', datafield: 'ValueCount', width:100}
				
				]
		  });
	  });
  </script>
<?php
HTML_Render_Body_Start();
genHeading('EditSC',true);?>
<span id="helpBlock" class="help-block">
<?php echo getTxt('SCEditHelp');?>
</span>
<div class="col-md-12"><div id="jqxgrid">
	</div>
	</div>

<div class="col-md-5 col-md-offset-5">
<input type="button" name="update"id="update" value="<?php echo getTxt('UpdateSCButton'); ?>" class="button" style="width: auto" />
</div>
</div>
<?php HTML_Render_Body_End(); ?>
<script>
//Calls a function to validate all fields when the submit button is hit.
$("#update").click(function(){
		$.ajax({
		dataType: "json",
		url: base_url+"series/updateSC"}).done(function(data){
			
				window.open(base_url+"series","_self");
		}).fail(function(data){alert(<?php echo "'".getTxt('ProcessingError')."'"; ?>);console.log(data);});	
});
</script>

