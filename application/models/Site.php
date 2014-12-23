<?php
class Site extends CI_Model
{
	public function displayAll()
	{
	/*$this->db->select('sites.SiteID,SiteName,SiteCode,Latitude,Longitude, SiteType, sitepic.picname');
	$this->db->from('sites')
			->join('sitepic', 'sites.SiteID=sitepic.siteid', 'left');
	$subQuery1 = $this->db->_compile_select();
	$this->db->_reset_select();
	$null = "NULL";
	$val = 0;
	
	$this->db->distinct()
		->select('seriescatalog.SiteID,y.*,sources.Organization, sources.SourceID, sources.SourceLink')
		->from('seriescatalog')
		->join("($subQuery1)", 'y.SiteID=`seriescatalog`.`SiteID` AND `seriescatalog`.`SourceID`=sources.SourceID', 'left outer')
		->where('VariableID !=', $null)
		->where('ValueCount >', $val)
		->order_by("y.SiteName", "asc");*/
		
	
	$query = "SELECT DISTINCT `seriescatalog`.`SiteID`,y.*,
	sources.Organization, sources.SourceID, sources.SourceLink
	FROM `seriescatalog` 
	LEFT OUTER JOIN ((SELECT sites.SiteID,SiteName,SiteCode,Latitude,Longitude, SiteType, sitepic.picname from sites 
	LEFT JOIN (sitepic)
	ON (sites.SiteID=sitepic.siteid)) y,sources) 
	ON (y.SiteID=`seriescatalog`.`SiteID` AND `seriescatalog`.`SourceID`=sources.SourceID) 
	WHERE `VariableID` is not null and ValueCount>0
	ORDER BY y.SiteName";
	
	$result = $this->db->query($query);
		
	return $result;

	}
}
?>