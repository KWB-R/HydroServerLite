<?php

class Wfs_Model extends CI_Model
{
	public function get_features( $siteID, $variableID )
	{
		$this->db->select('*');
		$this->db->where('seriescatalog.VariableID', $variableID);
		$this->db->where('sites.SiteID', $siteID);
		$this->db->from('seriescatalog');
		$this->db->join('sites', 'sites.SiteID = seriescatalog.SiteID');

		$result = $this->db->get();
		return $result->row();		
	}
	
	public function get_sites()
	{
		//$this->db->limit(1);
		$this->db->order_by('SiteID', 'Asc');
		$result = $this->db->get('sites');
		return $result->result();
	}
	
	public function get_variables()
	{
		$this->db->order_by('VariableID', 'Asc');
		$result = $this->db->get('variables');
		return $result->result();
	}
	
	public function check_features( $features )
	{
		if( $features->BeginDateTimeUTC == '1950-01-01 00:00:00')
		{
			$features->BeginDateTimeUTC = '';
		}
		
		if( $features->EndDateTimeUTC == '1952-08-01 00:00:00' )
		{
			$features->EndDateTimeUTC = '';
		}
		return $features;
	}
	
	// Methods for Server response/requests delivery
	function makeExceptionReport($value)
	{
		ob_get_clean();
		ob_start();

		echo '<ServiceExceptionReport
		version="1.2.0"
		xmlns="http://www.opengis.net/ogc"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.opengis.net/ogc http://wfs.plansystem.dk:80/geoserver/schemas//wfs/1.0.0/OGC-exception.xsd">
		<ServiceException>';
			 if (is_array($value)) {
				 print_r($value);
			 } else {
				 print $value;
			 }
			 echo '</ServiceException>
			</ServiceExceptionReport>';
			 $data = ob_get_clean();
			 echo $data;
			 logfile::write($data);
			 die();
	}
	
	function altUseCdataOnStrings($value) {
		if (!is_numeric($value) && ($value)) {
			//$value = "<![CDATA[".$value."]]>";
			$value = str_replace("&","&#38;",$value);
			$result = $value;
		}
		else {
			$result = $value;
		}
		return $result;
	}
	
	function drop_namespace($tag) {
		//$tag = html_entity_decode($tag);
		//$tag = gmlConverter::oneLineXML($tag);
		$tag = preg_replace('/ xmlns(?:.*?)?=\".*?\"/',"",$tag); // Remove xmlns with "
		$tag = preg_replace('/ xmlns(?:.*?)?=\'.*?\'/',"",$tag); // Remove xmlns with '
		$tag = preg_replace('/ xsi(?:.*?)?=\".*?\"/',"",$tag); // remove xsi:schemaLocation with "
		$tag = preg_replace('/ xsi(?:.*?)?=\'.*?\'/',"",$tag); // remove xsi:schemaLocation with '
		$tag = preg_replace('/ cs(?:.*?)?=\".*?\"/',"",$tag);  //
		$tag = preg_replace('/ cs(?:.*?)?=\'.*?\'/',"",$tag);
		$tag = preg_replace('/ ts(?:.*?)?=\".*?\"/',"",$tag);
		$tag = preg_replace('/ decimal(?:.*?)?=\".*?\"/',"",$tag);
		$tag = preg_replace('/ decimal(?:.*?)?=\'.*?\'/',"",$tag);
		$tag = preg_replace("/[\w-]*:(?![\w-]*:)/", "", $tag);// remove any namespaces
		return ($tag);
	}
	
	function drop_all_namespaces($tag) {

		$tag = preg_replace("/[\w-]*:/", "", $tag);// remove any namespaces
		return ($tag);
	}
	
	function drop_last_chrs($str, $no) {
		$strLen=strlen($str);
		return substr($str, 0, ($strLen)-$no);
	}

	function drop_first_chrs($str, $no) {
		$strLen=strlen($str);
		return substr($str, $no, $strLen);
	}
	
	function genBBox($XMin, $YMin, $XMax, $YMax) {
		global $depth;
		global $tables;
		global $db;
		global $srs;

		writeTag("open", "gml", "boundedBy", null, True, True);
		$depth++;
		writeTag("open", "gml", "Box", array("srsName"=>"EPSG:".$srs), True, True);
		$depth++;
		writeTag("open", "gml", "coordinates", array("decimal"=>".", "cs"=>",", "ts"=>" "), True, False);
		print $XMin.",".$YMin." ".$XMax.",".$YMax;
		writeTag("close", "gml", "coordinates", null, False, True);
		$depth--;
		writeTag("close", "gml", "Box", null, True, True);
		$depth--;
		writeTag("close", "gml", "boundedBy", null, True, True);
	}
}