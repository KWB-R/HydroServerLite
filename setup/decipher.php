<?php
	//Decipherer and checker for DB settings. 
	class Cipher {
    private $securekey, $iv;
    function __construct($textkey) {
        $this->securekey = hash('sha256',$textkey,TRUE);
        $this->iv = mcrypt_create_iv(32);
    }
    function encrypt($input) {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->securekey, $input, MCRYPT_MODE_ECB, $this->iv));
    }
    function decrypt($input) {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->securekey, base64_decode($input), MCRYPT_MODE_ECB, $this->iv));
    }
}

//Check if its a default installation. 
if(isset($_POST['wwdb']))
{

if($_POST['wwdb']=="1")
{
	//Get details from decrypter
	$cipher = new Cipher('transferDB@321');
	$decryptedtext = $cipher->decrypt($_POST['hiddenstuff']);
	$dbDetails = unserialize($decryptedtext);
	$dbhost = $dbDetails['host'];
	$dbUname = $dbDetails['database_username'];
	$dbPassword = $dbDetails['database_password'];
	$dbName = $_POST['ConfigName'];
}
else
{
	$dbhost = $_POST['databasehost'];
	$dbUname = $_POST['databaseusername'];
	$dbPassword = $_POST['databasepassword'];
	$dbName = $_POST['databasename'];
}
}
else
{
	$dbhost = $_POST['databasehost'];
	$dbUname = $_POST['databaseusername'];
	$dbPassword = $_POST['databasepassword'];
	$dbName = $_POST['databasename'];
}
?>