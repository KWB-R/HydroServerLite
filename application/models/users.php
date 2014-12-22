<?php
class Users extends CI_Model
{
 function login($username, $password)
 {
   $this -> db -> select('firstname, authority');
   $this -> db -> from('moss_users');
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
}
?>