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
	
}
?>