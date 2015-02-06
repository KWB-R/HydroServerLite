<?php
class MY_Config extends CI_Config
{
  function __construct()
   {
    	parent::__construct();
   }

  function site_url($uri = '')
	{

		$temp = "";
		if(defined('BASEURL2'))
			$temp = BASEURL2;

		
		if ($uri == '')
		{
			return $this->slash_item('base_url').$this->item('index_page').'/'.$temp;
		}

		if ($this->item('enable_query_strings') == FALSE)
		{
			$suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
			return $this->slash_item('base_url').$this->slash_item('index_page').$temp.$this->_uri_string($uri).$suffix;
		}
		else
		{
			return $this->slash_item('base_url').$this->slash_item('index_page').$temp.'?'.$this->_uri_string($uri);
		}
	}
}
?>