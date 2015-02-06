<?php
// Objects for the database. This will make it much easier to deal with data.
// Added all of the tables, however, I will only incorporate them as I come accross
// code that needs chagned.

/**
  Class DataObject
*/
class dbTools
{
	public static function checkForNULL($value){
		if ($value != null)
			if ($value != "NULL")
				return $value;
		
		return null;
	}
}

class Alias {
	private $value = "";
	public $Text = "";
	public $Capitalized = "";
	public $Plural = "";
	public $PluralCapitalized = "";
	
	function __construct($txt){
		$this->value = strtolower($txt);
		$this->Text = $this->value;
		$this->Capitalized = $this->Capitalize($this->value);
		$this->Plural = $this->Pluralize($this->value);
		$this->PluralCapitalized = $this->Capitalize($this->Plural);
	}
	private function Capitalize($txt){
		return strtoupper(substr($txt,0,1)).substr($txt,1);	
	}
	private function Pluralize($txt){
		return $txt."s";
	}
}

class Source
{
	public $SourceID; // int(11) NOT NULL AUTO_INCREMENT,
	public $Organization; // varchar(255) NOT NULL,
	public $SourceDescription; // text NOT NULL,
	public $SourceLink; // text,
	public $ContactName; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $Phone; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $Email; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $Address; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $City; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $State; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $ZipCode; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $country; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $Citation; // text NOT NULL,
	public $MetadataID; // int(11) NOT NULL DEFAULT '0',
	
	public function Fill($row){
		$this->SourceID = $row["SourceID"];
		$this->Organization = $row["Organization"];
		$this->SourceDescription = $row["SourceDescription"];
		$this->SourceLink = $row["SourceLink"];
		$this->ContactName = $row["ContactName"];
		$this->Phone = $row["Phone"];
		$this->Email = $row["Email"];
		$this->Address = $row["Address"];
		$this->City = $row["City"];
		$this->State = $row["State"];
		$this->ZipCode = $row["ZipCode"];
		$this->Country = $row["country"];
		$this->Citation = $row["Citation"];
		$this->MetadataID = $row["MetadataID"];
	}
}
class Site
{
	public $SiteID; // int(11) NOT NULL AUTO_INCREMENT,
	public $SiteCode; // varchar(50) NOT NULL,
	public $SiteName; // varchar(255) NOT NULL,
	public $Latitude; // double NOT NULL,
	public $Longitude; // double NOT NULL,
	public $LatLongDatumID; // int(11) NOT NULL DEFAULT '0',
	public $SiteType; // varchar(255) DEFAULT NULL,
	public $Elevation; // double DEFAULT NULL,  -- in meters
	public $VerticalDatum; // varchar(255) DEFAULT NULL,
	public $LocalX; // double DEFAULT NULL,
	public $LocalY; // double DEFAULT NULL,
	public $LocalProjectionID; // int(11) DEFAULT NULL,
	public $PosAccuracy; // double DEFAULT NULL,   -- in meters
	public $State; // varchar(255) DEFAULT NULL,
	public $County; // varchar(255) DEFAULT NULL,
	public $country; // varchar(255) DEFAULT NULL,
	public $Comments; // text,
	
	public function Fill($row){
		$this->SiteID = $row["SiteID"];
		$this->SiteCode = $row["SiteCode"];
		$this->SiteName = $row["SiteName"];
		$this->Latitude = $row["Latitude"];
		$this->Longitude = $row["Longitude"];
		$this->LatLongDatumID = $row["LatLongDatumID"];
		$this->SiteType = $row["SiteType"];
		$this->Elevation = $row["Elevation_m"];
		$this->VerticalDatum = $row["VerticalDatum"];
		$this->LocalX = $row["LocalX"];
		$this->LocalY = $row["LocalY"];
		$this->LocalProjectionID = $row["LocalProjectionID"];
		$this->PosAccuracy = $row["PosAccuracy_m"];
		$this->State = $row["State"];
		$this->County = $row["County"];
		$this->country = $row["country"];
		$this->Comments = $row["Comments"];
	}
}
class Variable{
	public $VariableID; // int(11) NOT NULL AUTO_INCREMENT,
	public $VariableCode; // varchar(50) NOT NULL,
	public $VariableName; // varchar(255) NOT NULL,
	public $Speciation; // varchar(255) NOT NULL DEFAULT 'Not Applicable',
	public $VariableunitsID; // int(11) NOT NULL,
	public $SampleMedium; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $ValueType; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $IsRegular; // tinyint(1) NOT NULL DEFAULT '0',
	public $TimeSupport; // double NOT NULL DEFAULT '0',
	public $TimeunitsID; // int(11) NOT NULL DEFAULT '0',
	public $DataType; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $GeneralCategory; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $NoDataValue; // double NOT NULL DEFAULT '0',

	public function Fill($row){
		$this->VariableID = $row["VariableID"];
		$this->VariableCode = $row["VariableCode"];
		$this->VariableName = $row["VariableName"];
		$this->Speciation = $row["Speciation"];
		$this->VariableunitsID = $row["VariableunitsID"];
		$this->SampleMedium = $row["SampleMedium"];
		$this->ValueType = $row["ValueType"];
		$this->IsRegular = $row["IsRegular"];
		$this->TimeSupport = $row["TimeSupport"];
		$this->TimeunitsID = $row["TimeunitsID"];
		$this->DataType = $row["DataType"];
		$this->GeneralCategory = $row["GeneralCategory"];
		$this->NoDataValue = $row["NoDataValue"];
	}
}
class VariableMethod{
	public $VariableID; // varchar(50) NOT NULL,
	public $VariableCode; // varchar(25) NOT NULL,
	public $VariableName; // varchar(50) NOT NULL,
	public $DataType; // varchar(50) NOT NULL,
	public $MethodID; // varchar(50) DEFAULT NULL,
	
	public function Fill($row){
		$this->VariableID = $row["VariableID"];
		$this->VariableCode = $row["VariableCode"];
		$this->VariableName = $row["VariableName"];
		$this->DataType = $row["DataType"];
		$this->MethodID = $row["MethodID"];
	}
}

// all of the CV objects were IDENTICAL.
// So I created a base class to eliminate redundant code.
class CVBase{
	public $Term; // varchar(255) NOT NULL,
	public $Definition; // text,

	public function Fill($row){
		$this->Term = $row["Term"];
		$this->Definition = $row["Definition"];

	}
}

class VerticalDatumCV extends CVBase{	}
class CensorCodeCV extends CVBase{		}
class DataTypeCV extends CVBase{		}
class GeneralCategoryCV extends CVBase{	}
class SampleMediumCV extends CVBase{	}
class SampleTypeCV extends CVBase{		}
class SiteTypeCV extends CVBase{		}
class SpeciatioinCV extends CVBase{		}
class TopicCategoryCV extends CVBase{	}
class ValueTypeCV extends CVBase{		}
class VariableNameCV extends CVBase{	}

class Category{
	public $VariableID; // int(11) NOT NULL,
	public $DataValue; // double NOT NULL,
	public $CategoryDescription; // text NOT NULL,


	public function Fill($row){
		$this->VariableID = $row["VariableID"];
		$this->DataValue = $row["DataValue"];
		$this->CategoryDescription = $row["CategoryDescription"];
	}
}

class DataValue{
	public $ValueID; // int(11) NOT NULL AUTO_INCREMENT,
	public $DataValue; // double NOT NULL,
	public $ValueAccuracy; // double DEFAULT NULL,
	public $LocalDateTime; // datetime NOT NULL,
	public $UTCOffset; // double NOT NULL,
	public $DateTimeUTC; // datetime NOT NULL,
	public $SiteID; // int(11) NOT NULL,
	public $VariableID; // int(11) NOT NULL,
	public $OffsetValue; // double DEFAULT NULL,
	public $OffsetTypeID; // int(11) DEFAULT NULL,
	public $CensorCode; // varchar(50) NOT NULL DEFAULT 'nc',
	public $QualifierID; // int(11) DEFAULT NULL,
	public $MethodID; // int(11) NOT NULL DEFAULT '0',
	public $SourceID; // int(11) NOT NULL,
	public $SampleID; // int(11) DEFAULT NULL,
	public $DerivedFromID; // int(11) DEFAULT NULL,
	public $QualityControlLevelID; // int(11) NOT NULL DEFAULT '0',

	public function bindParams($query, $includeIDAtEnd = false){
		$query->bindParam(1,$this->DataValue, PDO::PARAM_INT);
		$query->bindParam(2,$this->ValueAccuracy, PDO::PARAM_INT);
		$query->bindParam(3,$this->LocalDateTime, PDO::PARAM_STR);
		$query->bindParam(4,$this->UTCOffset, PDO::PARAM_INT);
		$query->bindParam(5,$this->DateTimeUTC, PDO::PARAM_STR);
		$query->bindParam(6,$this->SiteID, PDO::PARAM_INT);
		$query->bindParam(7,$this->VariableID, PDO::PARAM_INT);
		$query->bindParam(8,$this->OffsetValue, PDO::PARAM_INT);
		$query->bindParam(9,$this->OffsetTypeID, PDO::PARAM_INT);
		$query->bindParam(10,$this->CensorCode, PDO::PARAM_STR);
		$query->bindParam(11,$this->QualifierID, PDO::PARAM_INT);
		$query->bindParam(12,$this->MethodID, PDO::PARAM_INT);
		$query->bindParam(13,$this->SourceID, PDO::PARAM_INT);
		$query->bindParam(14,$this->SampleID, PDO::PARAM_INT);
		$query->bindParam(15,$this->DerivedFromID, PDO::PARAM_INT);
		$query->bindParam(16,$this->QualityControlLevelID, PDO::PARAM_INT);	
		if ($includeIDAtEnd)
		{
			$query->bindParam(16,$this->QualityControlLevelID, PDO::PARAM_INT);	
		}
	}
	public function bindParamsNamed($query){
		$query->bindParam(":DataValue",$this->DataValue, PDO::PARAM_INT);
		$query->bindParam(":ValueAccuracy",$this->ValueAccuracy, PDO::PARAM_INT);
		$query->bindParam(":LocalDateTime",$this->LocalDateTime, PDO::PARAM_STR);
		$query->bindParam(":UTCOffset",$this->UTCOffset, PDO::PARAM_INT);
		$query->bindParam(":DateTimeUTC",$this->DateTimeUTC, PDO::PARAM_STR);
		$query->bindParam(":SiteID",$this->SiteID, PDO::PARAM_INT);
		$query->bindParam(":VariableID",$this->VariableID, PDO::PARAM_INT);
		$query->bindParam(":OffsetValue",$this->OffsetValue, PDO::PARAM_INT);
		$query->bindParam(":OffsetTypeID",$this->OffsetTypeID, PDO::PARAM_INT);
		$query->bindParam(":CensorCode",$this->CensorCode, PDO::PARAM_STR);
		$query->bindParam(":QualifierID",$this->QualifierID, PDO::PARAM_INT);
		$query->bindParam(":MethodID",$this->MethodID, PDO::PARAM_INT);
		$query->bindParam(":SourceID",$this->SourceID, PDO::PARAM_INT);
		$query->bindParam(":SampleID",$this->SampleID, PDO::PARAM_INT);
		$query->bindParam(":DerivedFromID",$this->DerivedFromID, PDO::PARAM_INT);
		$query->bindParam(":QualityControlLevelID",$this->QualityControlLevelID, PDO::PARAM_INT);	
	}
}
class DerivedFrom{
	public $DerivedFromID; // int(11) NOT NULL,
	public $ValueID; // int(11) NOT NULL,


	public function Fill($row){
		$this->DerivedFromID = $row["DerivedFromID"];
		$this->ValueID = $row["ValueID"];
	}
}
class GroupDescription{
	public $GroupID; // int(11) NOT NULL AUTO_INCREMENT,
	public $GroupDescription; // text,


	public function Fill($row){
		$this->GroupID = $row["GroupID"];
		$this->GroupDescription = $row["GroupDescription"];
	}
}
class Group{
	public $GroupID; // int(11) NOT NULL,
	public $ValueID; // int(11) NOT NULL,


	public function Fill($row){
		$this->GroupID = $row["GroupID"];
		$this->ValueID = $row["ValueID"];
	}
}
class IsoMetadata{
	public $MetadataID; // int(11) NOT NULL AUTO_INCREMENT,
	public $TopicCategory; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $Title; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $Abstract; // text NOT NULL,
	public $ProfileVersion; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $MetadataLink; // text,


	public function Fill($row){
		$this->MetadataID = $row["MetadataID"];
		$this->TopicCategory = $row["TopicCategory"];
		$this->Title = $row["Title"];
		$this->Abstract = $row["Abstract"];
		$this->ProfileVersion = $row["ProfileVersion"];
		$this->MetadataLink = $row["MetadataLink"];
	}
}
class LabMethod{
	public $LabMethodID; // int(11) NOT NULL,
	public $LabName; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $LabOrganization; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $LabMethodName; // varchar(255) NOT NULL DEFAULT 'Unknown',
	public $LabMethodDescription; // text NOT NULL,
	public $LabMethodLink; // text,


	public function Fill($row){
		$this->LabMethodID = $row["LabMethodID"];
		$this->LabName = $row["LabName"];
		$this->LabOrganization = $row["LabOrganization"];
		$this->LabMethodName = $row["LabMethodName"];
		$this->LabMethodDescription = $row["LabMethodDescription"];
		$this->LabMethodLink = $row["LabMethodLink"];
	}
}
class Method{
	public $MethodID; // int(11) NOT NULL AUTO_INCREMENT,
	public $MethodDescription; // text NOT NULL,
	public $MethodLink; // text,


	public function Fill($row){
		$this->MethodID = $row["MethodID"];
		$this->MethodDescription = $row["MethodDescription"];
		$this->MethodLink = $row["MethodLink"];
	}
}
class User{
	public $FirstName; // varchar(50) NOT NULL,
	public $LastName; // varchar(50) NOT NULL,
	public $UserName; // varchar(25) NOT NULL,
	public $Password; // varchar(100) NOT NULL,
	public $Authority; // enum('admin','teacher','student') NOT NULL


	public function Fill($row){
		$this->FirstName = $row["firstname"];
		$this->LastName = $row["lastname"];
		$this->UserName = $row["username"];
		$this->Password = $row["password"];
		$this->Authority = $row["authority"];
	}
}
class OdmVersion{
	public $VersionNumber; // varchar(50) NOT NULL


	public function Fill($row){
		$this->VersionNumber = $row["VersionNumber"];
	}
}
class OffsetType{
	public $OffsetTypeID; // int(11) NOT NULL AUTO_INCREMENT,
	public $OffsetunitsID; // int(11) NOT NULL,
	public $OffsetDescription; // text NOT NULL,


	public function Fill($row){
		$this->OffsetTypeID = $row["OffsetTypeID"];
		$this->OffsetunitsID = $row["OffsetunitsID"];
		$this->OffsetDescription = $row["OffsetDescription"];
	}
}
class Qualifier{
	public $QualifierID; // int(11) NOT NULL AUTO_INCREMENT,
	public $QualifierCode; // varchar(50) DEFAULT NULL,
	public $QualifierDescription; // text NOT NULL,


	public function Fill($row){
		$this->QualifierID = $row["QualifierID"];
		$this->QualifierCode = $row["QualifierCode"];
		$this->QualifierDescription = $row["QualifierDescription"];
	}
}
class QualityControlLevel{
	public $QualityControlLevelID; // int(11) NOT NULL,
	public $QualityControlLevelCode; // varchar(50) NOT NULL,
	public $Definition; // varchar(255) NOT NULL,
	public $Explanation; // text NOT NULL,


	public function Fill($row){
		$this->QualityControlLevelID = $row["QualityControlLevelID"];
		$this->QualityControlLevelCode = $row["QualityControlLevelCode"];
		$this->Definition = $row["Definition"];
		$this->Explanation = $row["Explanation"];
	}
}
class Sample{
	public $SampleID; // int(11) NOT NULL AUTO_INCREMENT,
	public $SampleType; // varchar(255) NOT NULL,
	public $LabSampleCode; // varchar(50) NOT NULL,
	public $LabMethodID; // int(11) NOT NULL,


	public function Fill($row){
		$this->SampleID = $row["SampleID"];
		$this->SampleType = $row["SampleType"];
		$this->LabSampleCode = $row["LabSampleCode"];
		$this->LabMethodID = $row["LabMethodID"];
	}
}
class SeriesCatalog{
	public $SeriesID; // int(11) NOT NULL AUTO_INCREMENT,
	public $SiteID; // int(11) DEFAULT NULL,
	public $SiteCode; // varchar(50) DEFAULT NULL,
	public $SiteName; // varchar(255) DEFAULT NULL,
	public $SiteType; // varchar(255) DEFAULT NULL,
	public $VariableID; // int(11) DEFAULT NULL,
	public $VariableCode; // varchar(50) DEFAULT NULL,
	public $VariableName; // varchar(255) DEFAULT NULL,
	public $Speciation; // varchar(255) DEFAULT NULL,
	public $VariableunitsID; // int(11) DEFAULT NULL,
	public $VariableunitsName; // varchar(255) DEFAULT NULL,
	public $SampleMedium; // varchar(255) DEFAULT NULL,
	public $ValueType; // varchar(255) DEFAULT NULL,
	public $TimeSupport; // double DEFAULT NULL,
	public $TimeunitsID; // int(11) DEFAULT NULL,
	public $TimeunitsName; // varchar(255) DEFAULT NULL,
	public $DataType; // varchar(255) DEFAULT NULL,
	public $GeneralCategory; // varchar(255) DEFAULT NULL,
	public $MethodID; // int(11) DEFAULT NULL,
	public $MethodDescription; // text,
	public $SourceID; // int(11) DEFAULT NULL,
	public $Organization; // varchar(255) DEFAULT NULL,
	public $SourceDescription; // text,
	public $Citation; // text,
	public $QualityControlLevelID; // int(11) DEFAULT NULL,
	public $QualityControlLevelCode; // varchar(50) DEFAULT NULL,
	public $BeginDateTime; // datetime DEFAULT NULL,
	public $EndDateTime; // datetime DEFAULT NULL,
	public $BeginDateTimeUTC; // datetime DEFAULT NULL,
	public $EndDateTimeUTC; // datetime DEFAULT NULL,
	public $ValueCount; // int(11) DEFAULT NULL,


	public function Fill($row){
		$this->SeriesID = $row["SeriesID"];
		$this->SiteID = $row["SiteID"];
		$this->SiteCode = $row["SiteCode"];
		$this->SiteName = $row["SiteName"];
		$this->SiteType = $row["SiteType"];
		$this->VariableID = $row["VariableID"];
		$this->VariableCode = $row["VariableCode"];
		$this->VariableName = $row["VariableName"];
		$this->Speciation = $row["Speciation"];
		$this->VariableunitsID = $row["VariableunitsID"];
		$this->VariableunitsName = $row["VariableunitsName"];
		$this->SampleMedium = $row["SampleMedium"];
		$this->ValueType = $row["ValueType"];
		$this->TimeSupport = $row["TimeSupport"];
		$this->TimeunitsID = $row["TimeunitsID"];
		$this->TimeunitsName = $row["TimeunitsName"];
		$this->DataType = $row["DataType"];
		$this->GeneralCategory = $row["GeneralCategory"];
		$this->MethodID = $row["MethodID"];
		$this->MethodDescription = $row["MethodDescription"];
		$this->SourceID = $row["SourceID"];
		$this->Organization = $row["Organization"];
		$this->SourceDescription = $row["SourceDescription"];
		$this->Citation = $row["Citation"];
		$this->QualityControlLevelID = $row["QualityControlLevelID"];
		$this->QualityControlLevelCode = $row["QualityControlLevelCode"];
		$this->BeginDateTime = $row["BeginDateTime"];
		$this->EndDateTime = $row["EndDateTime"];
		$this->BeginDateTimeUTC = $row["BeginDateTimeUTC"];
		$this->EndDateTimeUTC = $row["EndDateTimeUTC"];
		$this->ValueCount = $row["ValueCount"];
	}
}
class SitePicture{
	public $Siteid; // varchar(50) NOT NULL,
	public $Picname; // varchar(200) DEFAULT NULL,


	public function Fill($row){
		$this->Siteid = $row["siteid"];
		$this->Picname = $row["picname"];
	}
}
class SpacialReference{
	public $SpatialReferenceID; // int(11) NOT NULL,
	public $SRSID; // int(11) DEFAULT NULL,
	public $SRSName; // varchar(255) NOT NULL,
	public $IsGeographic; // tinyint(1) DEFAULT NULL,
	public $Notes; // text,


	public function Fill($row){
		$this->SpatialReferenceID = $row["SpatialReferenceID"];
		$this->SRSID = $row["SRSID"];
		$this->SRSName = $row["SRSName"];
		$this->IsGeographic = $row["IsGeographic"];
		$this->Notes = $row["Notes"];
	}
}
class Unit{
	public $unitsID; // int(11) NOT NULL AUTO_INCREMENT,
	public $unitsName; // varchar(255) NOT NULL,
	public $unitsType; // varchar(255) NOT NULL,
	public $unitsAbbreviation; // varchar(255) NOT NULL,


	public function Fill($row){
		$this->unitsID = $row["unitsID"];
		$this->unitsName = $row["unitsName"];
		$this->unitsType = $row["unitsType"];
		$this->unitsAbbreviation = $row["unitsAbbreviation"];
	}
}

?>