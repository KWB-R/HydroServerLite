<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Users Controller
|--------------------------------------------------------------------------
|
| 
*/
class User extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('users','',TRUE);
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		$this->add();	
	}
	
	private function authDropDown()
	{
		return 	"<option value=admin>".getTxt('Administrator')."</option><option value=teacher>".getTxt('Teacher')."</option><option value=student>".getTxt('Student')."</option>";
	}
	
	private function adminCheck()
	{
		if (!isAdmin())
		$this->kickOut();
	}
	
	private function getUserList()
	{
		$authLevel=0;
		if (isAdmin()){
			  $authLevel = 2;			
			  }
			  elseif (isTeacher()){
			  $authLevel = 1;
			  }
		  elseif (isStudent()){
			  $this->kickOut();
			  }
		//Get list of usernames. 
		$users = $this->users->getUsers($authLevel);
		
		if(count($users)<1)
		{
			addWarning(getTxt('SorryNoUsers'));	
		}
		$option_block="";
		foreach ($users as $row) {
			$users = $row["username"];
			$option_block .= "<option value=$users>$users</option>";
		}	
		return $option_block;
	}
	
	public function add()
	{		
		$selection="";
		//Display the appropriate user authority to add depending on the user's authority //@TODO
		  if (isAdmin()){
			  $selection = $this->authDropDown();			
			  }
			  elseif (isTeacher()){
			  $selection = "<select class=\"form-control\" name=authority id=authority><option value=>".getTxt('SelectEllipsis')."</option><option value=teacher>".getTxt('Teacher')."</option><option value=student>".getTxt('Student')."</option></select>";
			  }
		  elseif (isStudent()){
				$this->kickOut();
			  }
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$data['selection']=$selection;
		$this->load->view('users/add',$data);
	}
	
	public function checkUname()
	{
		$uname = $this->input->post('username');
		$result = $this->users->checkUsername($uname);
		
		if($result)
		{
			echo('<img src="'.getImg('not-available.png').'" />');	
		}
		else
		{
			echo('<img src="'.getImg('available.png').'" />');		
		}
	}
	
	public function doadd()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('firstname', 'First Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('authority', 'Authority', 'trim|required');
		
		if ($this->form_validation->run() == FALSE)
		{
			  $errors = validation_errors();
			  if(!empty($errors))
			  {addError($errors);}
		}
		else
		{
			$result=$this->users->add($this->input->post('username'),$this->input->post('password'),$this->input->post('firstname'),$this->input->post('lastname'),$this->input->post('authority'));
			
			if($result==1)
			{
				addSuccess(getTxt('Congrats')." ".$this->input->post('username').". ".getTxt('AddAnother'));
			}
			
		}
		$this->add();
		
	}
	
	public function edit()
	{	
		$this->adminCheck();
		if($_POST)
		{
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('authority', 'Authority', 'trim|required');	
		}
		if ($this->form_validation->run() == FALSE)
		{
			  $errors = validation_errors();
			  if(!empty($errors))
			  {addError($errors);}
		}
		else
		{
			$result=$this->users->changeAuth($this->input->post('username'),$this->input->post('authority'));
			if($result)
			{
				addSuccess(getTxt('CongratulationsChanged')." ".$this->input->post('username'));	
			}
			else
			{
				addError(getTxt('ProcessingError'));
			}
		}
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$data['option_block']=$this->getUserList();
		$data['selection']=$this->authDropDown();
		$this->load->view('users/changeauthority',$data);
	}
	
	public function changepass()
	{
		if($this->input->post('username')&&$this->input->post('password'))
		{
			//Form Execution
			$result=$this->users->changePassword($this->input->post('username'),$this->input->post('password'));
			if($result)
			{
				addSuccess(getTxt('CongratulationsChangedPassword')." ".$this->input->post('username'));	
			}
			else
			{
				addError(getTxt('ProcessingError'));
			}
		}
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$data['option_block']=$this->getUserList();
		$this->load->view('users/changepassword',$data);	
	}
	
	public function changeownpass()
	{
		if(isStudent())
		$this->kickOut();
		
		if($_POST)
		{
			$this->form_validation->set_rules('password1', 'New Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Confirm Password', 'trim|required|xss_clean');	
		}
		if ($this->form_validation->run() == FALSE)
		{
			  $errors = validation_errors();
			  if(!empty($errors))
			  {addError($errors);}
		}
		else
		{
			$username = getSessionUser();
			$result=$this->users->changePassword($username,$this->input->post('password'));
			if($result)
			{
				addSuccess(getTxt('CongratulationsChangedPassword')." ".$username);	
			}
			else
			{
				addError(getTxt('ProcessingError'));
			}
		}
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$this->load->view('users/change_yourpassword',$data);	
	}
	
	public function delete()
	{	
		if(isStudent())
		$this->kickOut();
		if($_POST)
		{
			$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		}
		if ($this->form_validation->run() == FALSE)
		{
			  $errors = validation_errors();
			  if(!empty($errors))
			  {addError($errors);}
		}
		else
		{
			$uname = $this->input->post('username');
			$result = $this->users->removeUser($uname);
			if($result)
			{
				addSuccess(getTxt('Congrats')." ".$this->input->post('username'));	
			}
			else
			{
				addError(getTxt('ProcessingError'));
			}
		}
		
		//List of CSS to pass to this view
		$data=$this->StyleData;
		$data['option_block']=$this->getUserList();
		$this->load->view('users/delete',$data);	
	}
	
	
}
