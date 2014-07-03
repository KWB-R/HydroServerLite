<?php
	
	function process($key,$parts)
	{
	global $data;
	
	if(array_key_exists($key,$data))
	{
		$index = intval( str_replace("input","",$data[$key]));
		$monthVal = sprintf("%02s", intval($parts[$index-1]));
		return $monthVal;
		
	}	
	else
	{
		//Not found Hence Set to 00
		return "00";
	}
		
	}
	
	function validateDate($date, $format = 'Y-m-d H:i:s')
	{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
	}
	
	//Test Data..Make this get from post otherwise
	
	$data = array(
	"input1"=> "Month","input2"=> "Day","input3"=> "YearA","input4"=> "Hour","input5"=> "Min","fileName"=>"1.csv"
	);
	
	if (!isset($_POST['fileName']))
	die("POST Variable Not Set");
	
	$data = $_POST;

		
	$fileName = $data['fileName'];
	$data = array_flip($data);
	$parts;
	
	//Open the file here and read the LocalDateTime Values and replace them if necessary
	
	$name="uploads/".$fileName;
	
	$handle = fopen($name, "r");
	$tempName = preg_replace('/\..*$/','',$name)."_CTF.csv"; 
	$handle2 = fopen($tempName, "w");
	$flag=0;
	
	
	while (($line = fgetcsv($handle, 5000, ",")) !== FALSE) {
	
	if($flag==0)
	{
	fputcsv($handle2,$line);
	$flag=1;
	continue;
	}
	else
	{
		$line[3] = formatDate($line[3]);
		fputcsv($handle2,$line);
	}}
	
	
	//Close Both the files 
	
	fclose($handle);
	fclose($handle2);
	
	unlink($name);

	if (rename($tempName,$name) != false)	
	{echo "Done Converting";}
	
	function formatDate($presentDate)
	
	{
		global $data;
		$parts = preg_split("/[\s,-\/:]+/", $presentDate);

		//Build the date
		
		$finalDate = "";
		
		//Searching for year : 
		
		if(array_key_exists("YearA",$data))
		{
			$index = intval( str_replace("input","",$data["YearA"]));
			$finalDate.=$parts[$index-1];
			
		}	
		elseif (array_key_exists("YearB",$data))
		{
			//Assuming the century to be 21st
			$index = intval( str_replace("input","",$data["YearB"]));
			$finalDate.="20".$parts[$index-1];
		}
		else
		{
			//Year not found. Hence it will be set to 1970	
			$finalDate.="1970";
		}
		
		//Month, day, hour, min and second Search
		
		$finalDate.= "-".process("Month",$parts)."-";
		$finalDate.= process("Day",$parts)." ";
		$finalDate.= process("Hour",$parts).":";
		$finalDate.= process("Min",$parts).":";
		$finalDate.= process("Sec",$parts);
		
		//Check Final date if its a valid datetime
		
		if(!validateDate($finalDate))
		$finalDate = "0000-00-00 00:00:00";

		return $finalDate;
	}
?>