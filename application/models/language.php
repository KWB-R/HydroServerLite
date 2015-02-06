<?php
class Language extends CI_Model
{
	
	function __construct()
	{
		$this->DB1 = 
		
		$mysqlserver="worldwater.byu.edu";
		$mysqlusername="langreader";
		$mysqlpassword="readHSLlang@9";
		$this->DB1 =@mysqli_connect($mysqlserver, $mysqlusername, $mysqlpassword);
		$dbname = 'hydroserver_translation';
		@mysqli_select_db($this->DB1, $dbname);
		
	}
	function getTerms($lang)
	{
		$sql = "SELECT * FROM hydroserver_translation.translations_by_language";
		$terms = mysqli_query($this->DB1, $sql);
		return $terms;
	}
}
?>