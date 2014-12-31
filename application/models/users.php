<?php
class Users extends MY_Model
{
	function __construct()
	{
		parent::__construct();
		$this->tableName = "moss_users";	
	}
	function add($username, $password, $fname, $lname, $auth)
	{
	  $this->db->set('username', $username);
	  $this->db->set('password', 'PASSWORD("'.$password.'")', FALSE);
	  $this->db->set('firstname', $fname);
	  $this->db->set('lastname', $lname);
	  $this->db->set('authority', $auth);
	  $this->db->insert($this->tableName);
	  $num_inserts = $this->db->affected_rows();
	  return $num_inserts;
	}
	
	function checkUsername($username)
	{
	  $this->db->select('username')
		  ->from($this->tableName)
		  ->where('username', $username);
		  
	  $query = $this->db->get();
	  return $query->num_rows()>=1;	
	}	

	function getUsers($authAccess=0)
	{
		//AuthAccess is responsible for filtering on authority levels
		//0 for public
		//1 for teacher
		//2 for admin
		
		$sql ="Select username FROM moss_users WHERE (authority ='student') ORDER BY username";	
		
		$this->db->select('username')
		  ->from($this->tableName)
		  ->where('authority','student')
		  ->order_by('username');
		  
		if($authAccess==2)
		{
			$this->db->or_where('authority','teacher');
		}
		$query = $this->db->get();
		return $query->result_array();
		
	}
	
	function changePassword($username,$password)
	{
		$this->db->set('password', 'PASSWORD("'.$password.'")', false)
		->where('username', $username);
		$query = $this->db->update($this->tableName);
	  	return $query>=1;	
	}
	function changeYourPassword($username,$password)
	{
		$this->db->set('password', 'PASSWORD("'.$password.'")', false)
		->where('username', $username);
		$query = $this->db->update($this->tableName);
	  	return $query>=1;	
	}
	function changeAuth($username,$auth)
	{
		$this->db->set('authority',$auth)
		->where('username', $username);
		$query = $this->db->update($this->tableName);
	  	return $query>=1;	
	}
	
	function login($username, $password)
	{
	 $this -> db -> select('firstname, authority');
	 $this -> db -> from($this->tableName);
	 $this -> db -> where('username', $username);
	 $this -> db -> where('password', 'PASSWORD("'.$password.'")', false);
	 $this -> db -> limit(1);
	 
	 $query = $this -> db -> get();
	
	 if($query -> num_rows() == 1)
	 {
	   return $query->result();
	 }
	 else
	 {
	   return false;
	 }
	}
	
	function removeUser($uname)
	{

		$this->db
			->where('username',$uname)
			->delete($this->tableName);
		$num_del = $this->db->affected_rows();
		return $num_del==1;	
	
	}
}
?>