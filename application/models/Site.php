<?php
class Site extends MY_Model
{
	function __construct()
	{
		parent::__construct();
		$this->tableName = "sites";	
	}
	function displayAll($checkAll)
	{	
	$this->db
	    ->distinct()
		->select('sites.SiteID, sites.SiteCode,sites.SiteName, Latitude, Longitude, sites.SiteType, sitepic.picname, seriescatalog.SourceID, sources.Organization, sources.SourceID, sources.SourceLink')
		->from('seriescatalog')
		->join('sites', 'sites.SiteID=seriescatalog.SiteID', 'left')
		->join('sitepic', 'sites.SiteID=sitepic.siteid', 'left')
		->join('sources', 'seriescatalog.SourceID=sources.SourceID', 'left')
		->order_by("sites.SiteName");
	
	if($checkAll !=1)
	{
		$this->db
		->where('ValueCount >',0)
		->where('VariableID is not null');
	}
	
	$result = $this->db->get();
		
	return $this->tranResult($result->result_array());

	}
	
	function searchSite($lat,$long,$rad,$checkAll=0)
	{
		$this->db
		->distinct()
		->select('sites.SiteID, sites.SiteCode,sites.SiteName, Latitude, Longitude, sites.SiteType, ( 3959 * acos( cos( radians('.$lat.') ) * cos( radians( Latitude ) ) * cos( radians( Longitude ) - radians('.$long.') ) + sin( radians('.$lat.') ) * sin( radians( Latitude ) ) ) ) AS distance, sitepic.picname, seriescatalog.SourceID, sources.Organization, sources.SourceID, sources.SourceLink')
		->from($this->tableName)
		->join('seriescatalog', 'sites.SiteID=seriescatalog.SiteID', 'left')
		->join('sitepic', 'sites.SiteID=sitepic.siteid', 'left')
		->join('sources', 'seriescatalog.SourceID=sources.SourceID', 'left')
		->having('distance <', $rad)
		->order_by("distance", "asc");
		
		if($checkAll !=1)
		{
			$this->db
			->where('seriescatalog.ValueCount >',0)
			->where('VariableID is not null');
		}
		
		$query = $this->db->get();
		return $this->tranResult($query->result_array());
	}
	
	function getSitebySource($sourceid)
	{
		$this->db->distinct()
			->select('SiteID, SiteName')
			->from('seriescatalog')
			->where('SourceID',$sourceid)
			->order_by("SiteName");
		$query = $this->db->get();
		return $this->tranResult($query->result_array());	
	}
	
	function getSite($siteID)
	{
		$this->db->select()
			->from($this->tableName)
			->where($this->tableName.'.SiteID',$siteID)
			->join('sitepic', $this->tableName.'.SiteID=sitepic.siteid', 'left');
		$query = $this->db->get();
		return $this->tranResult($query->result_array());	
	}
	
	function getSiteTypes()
	{
		$query = $this->db->get('sitetypecv');
		return $this->tranResult($query->result_array());	
	}
	function getVD()
	{
		$query = $this->db->get('verticaldatumcv');
		return $this->tranResult($query->result_array());	
	}
	function getSR()
	{
		$query = $this->db->get('spatialreferences');
		return $this->tranResult($query->result_array());	
	}
	function add($site)
	{
	  $this->db->insert($this->tableName,$site);
	  return $this->db->insert_id();
	}
	function addPic($name,$siteID)
	{
		$this->db->delete('sitepic', array('siteid' => $siteID)); 
		$this->db->set('siteid',$siteID)
		->set('picname',$name)
		->insert('sitepic');
	}
	function delete($siteID)
	{
		$this->db->delete('sitepic', array('siteid' => $siteID)); 
		$this->db->delete($this->tableName, array('SiteID' => $siteID)); 
		return $this->db->affected_rows()==1;
	}
	function update($site,$id)
	{
		$this->db->where('SiteID',$id)
		->update($this->tableName,$site);
		return $this->db->affected_rows()>=0;	
	}
	

}
?>