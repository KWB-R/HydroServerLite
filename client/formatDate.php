<?php

require_once 'internationalize.php';



$fileName = $_GET['name'];
$name="uploads/".$fileName;


$handle = fopen($name, "r");
$flag=0;


while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {

if($flag==0)
{
$flag=1;
continue;
}
else
{
	$dateTime =  $data[3];
	break;	
}}


$keywords = preg_split("/[\s,-\/:]+/", $dateTime);

//Ask User for each parts definition. 

?>
<style>
div.block{
  overflow:hidden;
}
div.block label{
  width:160px;
  display:block;
  float:left;
  text-align:left;
}
div.block .input{
  margin-left:4px;
  float:left;
}
</style>

Please pick the right variable for each of the parts of the Date Time string. Any value not specified will be assumed to be 0. Also, as of now, we only support numeric input. Dates such as "10 Aug 2010" will not be recognized. <br />Also Note, if after conversion we find an invalid date such as "2012-02-31 12:00:00", it will be set to "0000-00-00 00:00:00"<br /><br/>
Your input Date Time Value: <?php echo $dateTime;?>
<br /><br />
<form id="dateFormat" action="dateFormatter.php" method="POST">

<?php 

//Build The Table
$counter =0;
foreach ($keywords as $word):
	$counter+=1;
	echo '<div class="block">';
		echo '<label>'.$word.'</label>';
		echo '<select  class="input" name="input'.$counter.'">
				<option value="YearA">Year(1991,1992,1993...)</option>
				<option value="YearB">Year(91,92,93...)</option>
				<option value="Month">Month</option>
				<option value="Day">Day</option>
				<option value="Hour">Hour(24 Hour format)</option>
				<option value="Min">Minute</option>
				<option value="Sec">Second</option>
				<option value="None">None</option>
			  </select>';
		echo '</div>';

endforeach;

?>
<input name="fileName" type="text"  value="<?php echo $fileName; ?>" hidden="true"/>
<input name="Button" type="button" class="button" id="submitDateF" value="<?php echo $SubmitData; ?>" style="width: 135px"/>
</form>

</body>




</html>