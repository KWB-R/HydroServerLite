<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| SeriesCatalog Controller
|--------------------------------------------------------------------------
|
| 
*/
class Banner extends MY_Controller {
	
	function __construct()
	{
		$this->dontAuth = array();
		parent::__construct();
		$this->load->library('form_validation');
	}
	
	private function deleteOthers($name,$ext)
	{
		$extensions = array('.gif','.jpg','.png','.jpeg');
		foreach ($extensions as $extension)
		{
			if ($extension == $ext)
				continue;
			if(file_exists(FCPATH."uploads/".$name.$extension))
			{
				unlink(FCPATH."uploads/".$name.$extension) or die("Unable to Remove other banners. Top Banner might not work as expected.");
			}
		}
	}

	public function add()
	{	


		if($_POST)
		{
			$dbName = substr(BASEURL2, 0, -1);
			$name = 'topBanner'.$dbName;
			//Processing the SiteImage. 
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size']	= '1024';
			$config['max_width']  = '0';
			$config['max_height']  = '0';
			$config['file_name']  = $name;
			$config['overwrite']  = TRUE;
			
			$this->load->library('upload', $config);
	
			if ( ! $this->upload->do_upload('banner'))
			{
				addError(getTxt('FailMoveFile').$this->upload->display_errors());
			}
			else
			{	
				$uploaddata = $this->upload->data();
				$ext = $uploaddata['file_ext'];
				//Delete any other files. 
				$this->deleteOthers($name,$ext);
				addSuccess(getTxt('SiteSuccessfullyEdited'));				
			}	
		}


		$data=$this->StyleData;
		$this->load->view('banner/add',$data);	
	}
	
	
}
