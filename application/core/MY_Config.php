<?php
class MY_Config extends CI_Config
{
  function __construct()
   {
    	parent::__construct();
   }

  function site_url($uri = '')
	{
		
		if ($uri == '')
		{
			return $this->slash_item('base_url').$this->item('index_page').'/'.BASEURL2;
		}

		if ($this->item('enable_query_strings') == FALSE)
		{
			$suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
			return $this->slash_item('base_url').$this->slash_item('index_page').BASEURL2.$this->_uri_string($uri).$suffix;
		}
		else
		{
			return $this->slash_item('base_url').$this->slash_item('index_page').BASEURL2.'?'.$this->_uri_string($uri);
		}
	}
}
?>