<?php
//connect to database
mysql_connect('DATABASE_HOST', 'DATABASE_USERNAME', 'DATABASE_PASSWORD');
mysql_select_db('DATABASE_NAME');

//get the username
$username = mysql_real_escape_string($_POST['username']);

//mysql query to check for username
$result = mysql_query('select username from users where username = "'. $username .'"');

//Final authentication for username
if(mysql_num_rows($result)>0){
	echo 0;
}else{
	echo 1;
}

?>