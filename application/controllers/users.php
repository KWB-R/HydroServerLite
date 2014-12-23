<?php
class Users extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		
		//Check if user is logged in from the helper and load the view depending on that. 

		if(isLoggedIn())
		{
			
		}
		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('welcome',$data);
	}
}
?>