<?php
class Method extends MY_Model
{
	function __construct()
	{
		parent::__construct();
		$this->tableName = "methods";	
	}
	
	function add($methodname,$methodlink,$varmeth)
	{
		$this->db->set('MethodDescription',$methodname)
			->set('MethodLink',$methodlink);
		$this->db->insert($this->tableName);
		if($this->db->affected_rows()!=1)
		{
			return false;	
		}
		$MethodID = $this->db->insert_id();
		$methodstr=explode(",", $varmeth);
	
		foreach($methodstr as $value){
			$varmeth = $this->getVarMeth($value);
			if (count($varmeth) > 0) {
				$newmethodstr = $varmeth['MethodID'] . "," . $MethodID;
				//Post the new result for the Method in the varmeth table
				$this->updateVarMeth($newmethodstr,$value);
			}
		}
		return true;
	}
	
	function getVarMeth($varid)
	{
		$this->db->select("MethodID")
		->from("varmeth")
		->where('VariableID',$varid);
		$query = $this->db->get();
		
		if($query->num_rows()<1)
		{
			return;	
		}
		$result = $query->result_array();
		return $result[0];
	}	
	
	function updateVarMeth($varmeth,$varid)
	{
		$this->db->set("MethodID",$varmeth)
		->where("VariableID",$varid)
		->update('varmeth');	
	}
	
	function getMethodsByVar($varid)
	{
		$result = $this->getVarMeth($varid);
		$methodstr=array_map('intval', explode(',', $result['MethodID']));

		$query = $this->db->select()
		->from($this->tableName)
		->where_in('MethodID', $methodstr)
		->get();
		return $this->tranResult($query->result_array());	
	}
	
	function getByVarSite($var,$site)
	{
		$this->db->select('MethodID,MethodDescription')
			->from('seriescatalog')
			->where('SiteID',$site)
			->where('VariableID',$var);
		
		$query=$this->db->get();
		return $query->result_array();		
	}
	
	function getEditable()
	{
		$this->db->select()
			->from($this->tableName)
			->where('MethodID >1');
		
		$query=$this->db->get();
		return $query->result_array();		
	}
	
	function getByID($id)
	{
		$this->db->select()
			->from($this->tableName)
			->where('MethodID',$id);
		
		$query=$this->db->get();
		return $query->result_array();		
	}
	function delete($ValueID)
	{
		$this->db
			->where('MethodID',$ValueID)
			->delete($this->tableName);
		$num_del = $this->db->affected_rows();
		return $num_del==1;	
	}
	function updateVarMeth2($MethodID)
	{
		$this->tableName = "varmeth";
		$this->db->select()
				->from($this->tableName);
		
		$query=$this->db->get();
		
		foreach($query->result_array() as $row){
				$varID = $row['VariableID'];
				$methID = $row['MethodID'];
				$parts = explode(',',$methID);	
				foreach ($parts as &$part){
					if(count($parts)==1 && $part==$MethodID){
					  $part = '';
					  $this->db->set('MethodID',$part)
							   ->where('VariableID',$varID)
							   ->update("varmeth");
						return $this->db->affected_rows()>=0;
					  
				  }elseif(count($parts)==2){
					  if ($part==$MethodID){
						  $part = '';
					  $newStr = implode($parts);
					  $this->db->set('MethodID',$newStr)
								->where('VariableID',$varID)
								->update("varmeth");
						return $this->db->affected_rows()>=0; }
				  }else{
					  if($part==$MethodID){
						  $part = '';
					  $newStr = implode(",", array_filter($parts));
					  $this->db->set('MethodID',$newStr)
								->where('VariableID',$varID)
								->update("varmeth");
						return $this->db->affected_rows()>=0;				  
				  }}
				}
				
			}
	}
	function update($MethodID,$methodname,$methodlink)
	{
		$this->db->set('MethodDescription',$methodname)
		->set('MethodLink',$methodlink)
		->where('MethodID',$MethodID)
		->update($this->tableName);
		
		return $this->db->affected_rows()>=0;
		
	}
}
?>