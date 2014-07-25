<?php
require_once 'main_config.php';
require_once 'database_connection.php';
/* #######################################################
This file should contain all database connections.
Doing so will make it easier to debug DB issue, because
everything will be here that connects to the database.
####################################################### */

class ListOrder{
	const None = 0;
	const Ascending = 1;
	const Descending = 2;
	const Asc = 1;
	const Desc = 2;
}

// check to see if PDO for MySQL is enabled on the server.
if (!in_array("mysql",PDO::getAvailableDrivers())){
	echo "<script>alert('MySQL PDO is not enabled');</script>";
}

class DAL
{
	// storing singleton
	private static $inst;
	
	private function __construct(){}
	
	// get singleton
	public static function Get(){
		if (!self::$inst){
			self::$inst = new DAL();
		}
		return self::$inst;
	}
	
	function showError($str){
		echo errorMessage($str);	
	}
	function errorMessage($str){
		return "<p class=\"error\">$str</p>";
	}
	function successMessage($str){
		return "<p class=\"success\">$str</p>";
	}

	public function _DatabaseObject(){
		// Would be better if the port and address were seperate variables.
		$serverAddressParts = explode(":",DATABASE_HOST);
		$serverAddress = $serverAddressParts[0];
		$serverAddressPort = "3306";
		if (count($serverAddressParts) > 1) $serverAddressPort = $serverAddressParts[1];
		$serverDatabase = DATABASE_NAME;
		$dataSourceName = "mysql:host=$serverAddress;port=$serverAddressPort;dbname=$serverDatabase";
		return new PDO($dataSourceName, DATABASE_USERNAME, DATABASE_PASSWORD);
	}

	function AllSources(){
		$retArray = array();
		try{
			$conn =  $this->_DatabaseObject();
			$qry = $conn->prepare("SELECT * FROM sources");
			$qry->execute();
			$retArray = $qry->fetchALL(PDO::FETCH_CLASS,"Source");
		}catch(PDOException $e){
			showError($e->getMessage());
		}
		return $retArray;
	}
	function AllSites(){
		$retArray = array();
		try{
			$conn =   $this->_DatabaseObject();
			$qry = $conn->prepare("SELECT * FROM sites");
			$qry->execute();
			$retArray = $qry->fetchALL(PDO::FETCH_CLASS,"Site");
		}catch(PDOException $e){
			showError($e->getMessage());
		}
		return $retArray;
	}
	function Site($id){
		$retObj = new Site();
		try{
			$conn =   $this->_DatabaseObject();
			$qry = $conn->prepare("SELECT * FROM sites WHERE SiteID = ?");
			$qry->bindParam(1,$id,PDO::PARAM_INT);
			$qry->setFetchMode(PDO::FETCH_INTO,$retObj);
			$qry->execute();
			$retObj = $qry->fetch(PDO::FETCH_INTO);
		}catch(PDOException $e){
			showError($e->getMessage());
		}
		return $retObj;
	}
	function Source($id){
		$retObj = new Source();
		try{
			$conn =   $this->_DatabaseObject();
			$qry = $conn->prepare("SELECT * FROM sources WHERE SourceID = ?");
			$qry->bindParam(1,$id,PDO::PARAM_INT);
			$qry->setFetchMode(PDO::FETCH_INTO,$retObj);
			$qry->execute();
			$retObj = $qry->fetch(PDO::FETCH_INTO);
		}catch(PDOException $e){
			showError($e->getMessage());
		}
		return $retObj;
	}
	function Sites($src){
		$retArray = array();
		try{
			$conn =   $this->_DatabaseObject();
			$qry = $conn->prepare("SELECT DISTINCT s.* FROM seriescatalog sc JOIN sites s ON s.SiteID = sc.SiteID WHERE SourceID=:id ORDER BY SiteName ASC");
			$qry->execute(array("id" => $src->SourceID));
			$retArray = $qry->fetchALL(PDO::FETCH_CLASS,"Site");
		}catch(PDOException $e){
			showError($e->getMessage());
		}
		return $retArray;
	}
	function AllVariables($ListOrder = 0){
		$retArray = array();
		$orderText = "";
		if ($ListOrder != null && $ListOrder != ListOrder::None){
			$orderText = " ORDER BY `VariableName` ";
			if ($ListOrder == ListOrder::Ascending){
				$orderText .= "ASC";
			}
			elseif($ListOrder == ListOrder::Descending ){
				$orderText .= "DESC";
			}
			else //$ListOrder == ListOrder::None
			{
				$orderText = "";
			}
		}
		try{
			$conn =   $this->_DatabaseObject();
			$qry = $conn->prepare("SELECT * FROM variables" . $orderText);
			$qry->execute();
			$retArray = $qry->fetchALL(PDO::FETCH_CLASS,"Variable");
		}catch(PDOException $e){
			showError($e->getMessage());
		}
		return $retArray;
	}

	function Variable($objID){
		$retObj = new Variable();
		try{
			$conn =   $this->_DatabaseObject();
			$qry = $conn->prepare("SELECT * FROM variables WHERE VariableID = ?");
			$qry->bindParam(1,$objID,PDO::PARAM_INT);
			$qry->setFetchMode(PDO::FETCH_INTO,$retObj);
			$qry->execute();
			$retObj = $qry->fetch(PDO::FETCH_INTO);
		}catch(PDOException $e){
			showError($e->getMessage());
		}
		return $retObj;
	}

	function AllUnits(){
		$retArray = array();
		try{
			$conn =   $this->_DatabaseObject();
			$qry = $conn->prepare("SELECT * FROM units");
			$qry->execute();
			$retArray = $qry->fetchALL(PDO::FETCH_CLASS,"Unit");
		}catch(PDOException $e){
			showError($e->getMessage());
		}
		return $retArray;
	}
	function Unit($objID){
		$retObj = new Unit();
		try{
			$conn =   $this->_DatabaseObject();
			$qry = $conn->prepare("SELECT * FROM units WHERE unitsID = ?");
			$qry->bindParam(1,$objID,PDO::PARAM_INT);
			$qry->setFetchMode(PDO::FETCH_INTO,$retObj);
			$qry->execute();
			$retObj = $qry->fetch(PDO::FETCH_INTO);
		}catch(PDOException $e){
			showError($e->getMessage());
		}
		return $retObj;
	}
	
	// This gets Methods.
	// These are attached to datavalues
	// These also go through an associative entity called "varmeth" that binds
	//    this list to the variable table.
	// I am not sure where and how labmethods are ever used or called.
	function AllMethods(){
		$retArray = array();
		try{
			$conn =   $this->_DatabaseObject();
			$qry = $conn->prepare("SELECT * FROM methods");
			$qry->execute();
			$retArray = $qry->fetchALL(PDO::FETCH_CLASS,"Method");
		}catch(PDOException $e){
			showError($e->getMessage());
		}
		return $retArray;
	}
	function Methods($variable){
		$retArray = array();
		// this will need to reference the varmeth table.
		// varmeth table does not contain actual keys,
		// the methodID field actually contains a list of comma separated keys.
		/// Therefore,this has has to be parsed before we can get the actual methods.
		try{
			$conn =   $this->_DatabaseObject();
			$qry1 = $conn->prepare("SELECT MethodID FROM varmeth 
										 WHERE VariableID='".$variable->VariableID."'");
			$qry1->execute();
			$tmpArray = array();
			$tmpArray = $qry1->fetchALL(PDO::FETCH_ASSOC);
			
			foreach ($tmpArray as $tmpList){
				$arr = $tmpList;
				foreach($arr as $methIDList){
					$methIDs = explode(",",$methIDList);
					foreach($methIDs as $methID){
						$retArray[] = $this->Method($methID);
					}
				}
			}
			
			
		}catch(PDOException $e){
			showError($e->getMessage());
		}
		return $retArray;
	}

	function Method($objID){
		$retObj = new Method();
		try{
			$conn =   $this->_DatabaseObject();
			$qry = $conn->prepare("SELECT * FROM methods WHERE MethodID = ?");
			$qry->bindParam(1,$objID,PDO::PARAM_INT);
			$qry->setFetchMode(PDO::FETCH_INTO,$retObj);
			$qry->execute();
			$retObj = $qry->fetch(PDO::FETCH_INTO);
		}catch(PDOException $e){
			showError($e->getMessage());
		}
		return $retObj;
	}
	function AddDataValue($dataValue){
		$newID = -1;
		try{
			$conn =  $this->_DatabaseObject();
			$query = $conn->prepare(
				"INSERT INTO `datavalues`(`DataValue`, `ValueAccuracy`, `LocalDateTime`, `UTCOffset`, "
				."`DateTimeUTC`, `SiteID`, `VariableID`, `OffsetValue`, `OffsetTypeID`, `CensorCode`, "
				."`QualifierID`, `MethodID`, `SourceID`, `SampleID`, `DerivedFromID`, `QualityControlLevelID`) "
				."VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			//#type $dataValue DataValue
			$dataValue->bindParams($query);
			$query->execute();
			if ($query->errorCode()!= 0)
			{
				$errors = $query->errorInfo();
				var_dump($errors);
			}
			$newID = $conn->lastInsertId();
			$dataValue->ValueID = $newID;
		}catch(PDOException $e){
			echo $e->getMessage();
			$newID = -2;
		}
		return $newID;
	}
	function updateDataValue($dataValue){
		$conn =  $this->_DatabaseObject();
		$query = $conn->prepare("INSERT INTO `datavalues`(`DataValue`, `ValueAccuracy`, `LocalDateTime`, `UTCOffset`, "
			."`DateTimeUTC`, `SiteID`, `VariableID`, `OffsetValue`, `OffsetTypeID`, `CensorCode`, "
			."`QualifierID`, `MethodID`, `SourceID`, `SampleID`, `DerivedFromID`, `QualityControlLevelID`) "
			."VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		//#type $dataValue DataValue
		$dataValue->prePare($query);
	}
	function AllOffsetTypes(){
		$retArray = array();
		try{
			$conn =   $this->_DatabaseObject();
			$qry = $conn->prepare("SELECT * FROM offsettypes");
			$qry->execute();
			$retArray = $qry->fetchALL(PDO::FETCH_CLASS,"OffsetTYpe");
		}catch(PDOException $e){
			showError($e->getMessage());
		}
		return $retArray;
	}
}
