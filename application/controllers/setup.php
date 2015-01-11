<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup extends CI_Controller {
	
	function createConfig()
	{
		if($_POST)
		{
			print_r($_POST);
		}
	}
}
