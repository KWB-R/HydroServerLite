<?php

//check authority to be here
require_once 'authorization_check.php';

//connect to server and select database
require_once 'database_connection.php';

require_once 'data_access_layer.php';

require_once "_html_parts.php";
HTML_Render_Head("OffSet Types");

echo $CSS_Main;

HTML_Render_Body_Start();

//#type $offset OffsetType
foreach (DAL::Get()->AllOffsetTypes() as $offset)
{
	echo $offset->OffsetDescription;
}

?>
<h2>Add Offset Type</h2>
<form>
	<ul>
	<li>
		<label for="ddlUnitID" >Associated Unit</label>
		<select name="ddlUnitID">
		<?php
		//#type $unit Unit
		foreach (DAL::Get()->AllUnits() as $unit)
		{
			echo "<option value=\"".$unit->unitsID."\">".
				$unit->unitsName." (".$unit->unitsAbbreviation.")</option>";
		}
		?>
		</select>
	</li>
	<li>
		<label for="txtDescription" >Description</label>
		<textarea type="text" name="txtDescription"></textarea>
	</li>
	<li>
	<input type="submit" value="Add"></input>
	</li>
	</ul>
</form>
<?php

HTML_Render_Body_End();

?>