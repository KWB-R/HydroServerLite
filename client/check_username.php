<?php

include "db_config.php";

//received username value from registration page
$username = $_POST["username"]; 
if(isset($_POST["username"]))

{
  
  $query = "Select username FROM moss_users WHERE  username = '$username'"; 
  //check username in db
  $result =  @mysql_query($query,$connect)or die("Error" .mysql_error());
  
  $username_exist = mysql_num_rows($result); //records count
  
  //if returned value is more than 0, username is not available
  if($username_exist>0) {
      echo('<img src="images/not-available.png" />');
  }else{
      echo('<img src="images/available.png" />');
  }
}
?>