<?php

// model for working with the series catalog
class SeriesCatalog_Model extends CI_Model
{
	//gets the time range of the series in the series catalog
	//input: SiteID, VariableID, MethodID, SourceID, QualityControlLevelID
	//returns: an array with the seriesID, BeginDateTime, BeginDateTimeUTC,
	//         EndDateTime, EndDateTimeUTC, ValueCount
	//returns 0 is the series is not found
	function get_series_timerange($site, $variable, $method, $source, $qc) {
		$ci = &get_instance();
		$ci->db->select("SeriesID, BeginDateTime, EndDateTime, ValueCount");
		$ci->db->where("SiteID", $site);
		$ci->db->where("VariableID", $variable);
		$ci->db->where("SourceID", $source);
		$ci->db->where("MethodID", $method);
		$ci->db->where("QualityControlLevelID", $qc);
		$query = $ci->db->get("seriescatalog");
		if (!$query) {
			return 0;
		} else if ($query->num_rows() === 0) {
			return 0;
		} else {
			return $query->row();
		}			
	}

//updates an existing record in the SeriesCatalog table
	function update_series($seriesID, $siteID, $variableID, $methodID, $sourceID, $qcID) {
		$ci = &get_instance();
		$ci->db->select("MIN(LocalDateTime) AS BeginDateTime, MIN(DateTimeUTC) AS BeginDateTimeUTC, 
						 MAX(LocalDateTime) AS EndDateTime, MAX(DateTimeUTC) AS EndDateTimeUTC, COUNT(ValueID) AS ValueCount");
		$ci->db->where("SiteID", $siteID);
		$ci->db->where("VariableID", $variableID);
		$ci->db->where("MethodID", $methodID);
		$ci->db->where("SourceID", $sourceID);
		$ci->db->where("QualityControlLevelID", $qcID);
		$query = $ci->db->get("datavalues");
		if (!$query) {
			return 0;
		} else if ($query->num_rows() === 0) {
			return 0;
		} else {
			$result = $query->row();
			//print_r($result);
			$update_data = array('BeginDateTime'=> $result->BeginDateTime,
			'BeginDateTimeUTC'=> $result->BeginDateTimeUTC,
			'EndDateTime'=> $result->EndDateTime,
			'EndDateTimeUTC'=> $result->EndDateTimeUTC,
			'ValueCount'=> $result->ValueCount);
			$ci->db->where('SeriesID', $seriesID);
			$ci->db->update('seriescatalog', $update_data);
			return $ci->db->affected_rows();
		}		
	}
	
	function insert_series($siteID, $variableID, $methodID, $sourceID, $qcID) {
		$ci = &get_instance();
		$sql= "SELECT dv.SiteID, s.SiteCode, s.SiteName, s.SiteType, dv.VariableID, v.VariableCode, 
           v.VariableName, v.Speciation, v.VariableUnitsID, u.UnitsName AS VariableUnitsName, v.SampleMedium, 
           v.ValueType, v.TimeSupport, v.TimeUnitsID, u1.UnitsName AS TimeUnitsName, v.DataType, 
           v.GeneralCategory, dv.MethodID, m.MethodDescription, dv.SourceID, so.Organization, 
           so.SourceDescription, so.Citation, dv.QualityControlLevelID, qc.QualityControlLevelCode, dv.BeginDateTime, 
           dv.EndDateTime, dv.BeginDateTimeUTC, dv.EndDateTimeUTC, dv.ValueCount 
FROM  (
SELECT SiteID, VariableID, MethodID, QualityControlLevelID, SourceID, MIN(LocalDateTime) AS BeginDateTime, 
           MAX(LocalDateTime) AS EndDateTime, MIN(DateTimeUTC) AS BeginDateTimeUTC, MAX(DateTimeUTC) AS EndDateTimeUTC, 
		   COUNT(DataValue) AS ValueCount
FROM datavalues WHERE SiteID=$siteID AND VariableID=$variableID AND MethodID=$methodID AND SourceID=$sourceID AND QualityControlLevelID=$qcID) dv
INNER JOIN sites s ON dv.SiteID = s.SiteID 
		   INNER JOIN variables v ON dv.VariableID = v.VariableID 
		   INNER JOIN units u ON v.VariableUnitsID = u.UnitsID 
		   INNER JOIN methods m ON dv.MethodID = m.MethodID 
		   INNER JOIN units u1 ON v.TimeUnitsID = u1.UnitsID 
		   INNER JOIN sources so ON dv.SourceID = so.SourceID 
		   INNER JOIN qualitycontrollevels qc ON dv.QualityControlLevelID = qc.QualityControlLevelID";

		$query = $ci->db->query($sql);
		if (!$query) {
			return 0;
		} else if ($query->num_rows() === 0) {
			return 0;
		} else {
			$result = $query->row();
			if ($ci->db->insert('seriescatalog', $result)) {
				return $ci->db->affected_rows();
			}
		}		
	}
}