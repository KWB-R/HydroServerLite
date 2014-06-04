<?php
//check authority to be here
require_once 'authorization_check.php';

// Need to fix ashtya's tire at 5pm
$badValue = $_GET['MethodID']; // 5 in this example

//connect to server and select data
require_once 'database_connection.php';

$sql_find ="SELECT * FROM varmeth WHERE MethodID !=''";

$result_f = @mysql_query($sql_find,$connection)or die(mysql_error());

	while ($row_f = mysql_fetch_array ($result_f)){

		$v_id = $row_f["VariableID"];
		$m_id = $row_f["MethodID"];

			$parts=explode(",", $m_id);
			
			foreach ($parts as &$part){

				if($parts.length==1 && $part==$badValue){
					$part = '';
					$sql_upd ="UPDATE varmeth SET MethodID=$part WHERE VariableID='$v_id'";
					$result_upd = @mysql_query($sql_upd,$connection)or die(mysql_error());

				}elseif($parts.length==2){
					if ($part==$badValue){
						$part = '';
						};
					$newStr = implode($parts);
					$sql_upd ="UPDATE varmeth SET MethodID='$newStr' WHERE VariableID='$v_id'";
					$result_upd = @mysql_query($sql_upd,$connection)or die(mysql_error());

				}else{
					if($part==$badValue){
						$part = '';
						};
					$newStr = implode(",", array_filter($parts));
					$sql_upd ="UPDATE varmeth SET MethodID='$newStr' WHERE VariableID='$v_id'";
					$result_upd = @mysql_query($sql_upd,$connection)or die(mysql_error());
				};
			};
	};
?>