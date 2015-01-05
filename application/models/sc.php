<?php
class Sc extends MY_Model
{
	function __construct()
	{
		parent::__construct();
		$this->tableName = "seriescatalog";	
	}
	
	function getDateRange($siteID,$varID,$methodID)
	{
		$this->db->select("BeginDateTime,EndDateTime")
		->from($this->tableName)
		->where("SiteID",$siteID)
		->where("VariableID",$varID)
		->where("MethodID",$methodID);
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
	
	function getSourceBySite($SiteID)
	{
		$this->db->select('SourceID')
			->from($this->tableName)
			->where('SiteID',$SiteID);
		$query = $this->db->get();
		return $query->result_array();				
	}
	
	function add($sc)
	{
		
		$this->db->insert($this->tableName,$sc);
		return $this->db->affected_rows() ==1;	
	}
	
	function delSite($siteID)
	{
		$this->db->delete($this->tableName, array('SiteID' => $siteID)); 
		return $this->db->affected_rows()==1;
	}
	
	function updateSite($series,$siteID)
	{
		$this->db->where('SiteID',$siteID)
		->update($this->tableName,$series);
		return $this->db->affected_rows()>=0;
	}
	
	function get($seriesID)
	{
		$this->db->where('SeriesID',$seriesID);
		$query=$this->db->Get($this->tableName);
		return $query->result_array();
	}
	
	function update($series,$seriesID)
	{
		$this->db->where('SeriesID',$seriesID);
		$this->db->update($this->tableName,$series);
		return $this->db->affected_rows()>=0;
	}
}
?>