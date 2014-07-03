<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//value given from the add_site.php page
$sid=$_GET["SourceID"];

//All queries go through a translator. 
require_once 'DBTranslator.php';

//find the matching name for the SourceID
$sql_cc ="Select * FROM sources WHERE SourceID=$sid";

$result_cc = transQuery($sql_cc,0,0);

	if (count($result_cc) < 1) {
	//alert("Please reselect the Source.");
	//this might cause a problem in the calling function
	echo($ReSelectSource);
	} 

	else {

		foreach ($result_cc as $row_cc) {

			$sname = $row_cc["Organization"];

		}
	}
echo $sname;	

echo "<script> SendName(sname); </script>";

mysql_close($connection);

?>

<html>
<head>
<script type="text/javascript">

function SendName(sname){

location('add_site.php?SName='+sname,'_self');
}

</script>
</head>
</html>