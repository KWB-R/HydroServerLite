<?php
class Site extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function displayAll()
	{
	$this->db->select('sites.SiteID,SiteName,SiteCode,Latitude,Longitude, SiteType, sitepic.picname');
	$this->db->from('sites')
			->join('sitepic', 'sites.SiteID=sitepic.siteid', 'left');
	$subQuery1 = $this->db->_compile_select();
	$this->db->_reset_select();
	$this->db->join("($subQuery1)",'f.c2_id = c2.c2_id','left');
	$null = "NULL";
	$val = 0;
	
	$this->db->distinct()
		->select('seriescatalog.SiteID,y.*,sources.Organization, sources.SourceID, sources.SourceLink')
		->from('seriescatalog')
		->join("($subQuery1)", 'y.SiteID=`seriescatalog`.`SiteID` AND `seriescatalog`.`SourceID`=sources.SourceID', 'left outer')
		->where('VariableID !=', $null)
		->where('ValueCount >', $val)
		->order_by("y.SiteName", "asc");
	
	$query = $this -> db -> get();
		
	return $query;

	}
}
?>