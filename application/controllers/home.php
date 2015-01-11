<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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


class Home extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
	}


	public function installation()
	{	
	//Check if any other installations exist? if No then the first one shall be called 'default'
	$files1 = scandir(APPPATH.'config/installations');
	$count = count($files1);
	$default=false;
	$encryptedtext="";
	if(($count==2)&&(in_array('.', $files1))&&(in_array('..', $files1)))
	{
		//No other Setup Exists
		$default=true;
	}
	if(!$default)
	{	

		//The default database details need to be passed to the setup script. Using encryption here as it will he a hidden field in the form. 
		$cipher = new Cipher('transferDB@321');

		$databaseDetails = array(
				"host"=>$this->config->item('database_host'),
				"database_username"=>$this->config->item('database_username'),
				"database_password"=>$this->config->item('database_password')
			);
	
		$encryptedtext = $cipher->encrypt(serialize($databaseDetails));


		//Check if multiple installations are allowed. 
		if(!$this->config->item('multiInstall'))
		{
			$data['errorMsg']="Your Server administrator has disabled Multiple installations on this Server.";
			$this->load->view('templates/apierror',$data);	
			return;
		}

	}

	//List of languages to be shown. 

	$lang = array(	"English"=>"English",
					"Spanish"=>"Español",
					"Italian"=>"Italiano",
					"Portuguese"=>"Portugués",
					"German"=>"Alemán",
					"Dutch"=>"Nederlands",
					"Bulgarian"=>"български",
					"Croatian"=>"Hrvatski",
					"French"=>"Français",
					"Russian"=>"Русский",
					"Tagalog"=>"Tagalog",
					"Czech"=>"Český",
					"Ukranian"=>"Українська");
	$langOptions = genOptions($lang);

	//Check if any other installations exist? if No then the first one shall be called 'default'
	$files1 = scandir(APPPATH.'config/installations');
	$default=false;
	if(count($files1)==2 && in_array('.', $files1) && in_array('..', $files1))
	{
		//No other Setup Exists
		$default=true;
	}
	
	$_SESSION['setup'] = true;	
	$data=$this->StyleData;
	$data['langOptions']=$langOptions;
	$data['default']=$default;
	$data['hiddenStuff']=$encryptedtext;
	$this->load->view('edit_mainconfig',$data);	
	}

	public function successinstall()
	{

		unset($_SESSION['setup']);
		$db = $this->input->get('db');
		$data=$this->StyleData;
		$data['db']=$db;
		$this->load->view('successinstall',$data);	
	}

	public function index()
	{
		$data=$this->StyleData;
		$data['multi']=false;
		if(BASEURL2 == "default/")
		{
			if($this->config->item('multiInstall') && isLoggedIn())
			{
				$data['multi']=true;
			}
		}

		//List of CSS to pass to this view
		
		$this->load->view('welcome',$data);
	}
	
	public function help()
	{
		$data=$this->StyleData;
		$this->load->view('help',$data);	
	}
	
	public function changeLang()
	{
		changeLang($this->input->post('lang'),$this->input->post('disp'));
	}
	
	protected function authenticate()
	{
		//Home is open access
	}
}
