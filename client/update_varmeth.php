<?php
//check authority to be here
require_once 'authorization_check.php';

$badValue = $_GET['MethodID']; 

//connect to server and select data
require_once 'database_connection.php';

$sql_find ="SELECT * FROM varmeth WHERE MethodID !=''";

$result_f = transQuery($sql_find,0,0);

	foreach ($result_f as $row_f) {

		$v_id = $row_f["VariableID"];
		$m_id = $row_f["MethodID"];

			$parts=explode(",", $m_id);
			
			foreach ($parts as &$part){

				if($parts.length==1 && $part==$badValue){
					$part = '';
					$sql_upd ="UPDATE varmeth SET MethodID=$part WHERE VariableID='$v_id'";
					$result_upd = transQuery($sql_upd,0,-1);

				}elseif($parts.length==2){
					if ($part==$badValue){
						$part = '';
						};
					$newStr = implode($parts);
					$sql_upd ="UPDATE varmeth SET MethodID='$newStr' WHERE VariableID='$v_id'";
					$result_upd = transQuery($sql_upd,0,-1);

				}else{
					if($part==$badValue){
						$part = '';
						};
					$newStr = implode(",", array_filter($parts));
					$sql_upd ="UPDATE varmeth SET MethodID='$newStr' WHERE VariableID='$v_id'";
					$result_upd = transQuery($sql_upd,0,-1);
				};
			};
	};
?>