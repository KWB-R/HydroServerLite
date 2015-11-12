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
		$this->load->library('form_validation');
		$this->load->helper('file');
		$this->load->helper('download');
		$this->load->helper(array('form','url'));
		$this->dontAuth = array('getData','getDataJSON','compare','export');
	}

	private function file_list($d,$x){ 
       foreach(array_diff(scandir($d),array('.','..')) as $f)if(is_file($d.'/'.$f)&&(($x)?@ereg($x.'$',$f):1))$l[]=$f; 
       	return $l;	
	}
	private function adminCheck()
	{
		if (!isAdmin())
		$this->kickOut();
	}
	private function deleteOthers($name,$ext)
	{
		$extensions = array('.txt','.csv','.CSV');
		foreach ($extensions as $extension)
		{
			if ($extension == $ext)
				continue;
			if(file_exists(FCPATH."uploads/".$name.$extension))
			{
				unlink(FCPATH."uploads/".$name.$extension) or die("Unable to update the welcome page. Sorry. Better luck next time.");
			}
		}
	}

	//
	// private function fileUploadHandler() now defined in MY_Controller
	//

	public function edit()
	{
	$this->adminCheck();
	$dbName = substr(BASEURL2, 0, -1);
	
	if($_POST)
	{
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('groupname', 'Name', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_rules('citation', 'Citation', 'trim|required');
			
		$welcome_page = array(
			$this->input->post('title'),
			$this->input->post('groupname'),
			$this->input->post('description'),
			$this->input->post('citation')
		);

		$welcome_page_info = json_encode(array_map('utf8_encode',$welcome_page));

		if(file_exists('./uploads/' .$dbName. '.txt'))
		{
			unlink('./uploads/' .$dbName. '.txt');
		}

		write_file('./uploads/' .$dbName. '.txt', $welcome_page_info,'c+');

		addSuccess(getTxt('SiteSuccessfullyEdited'));

		$this->fileUploadHandler('jpg|csv|CSV', 'custom');
	}
	
	$data=$this->StyleData;
	$data['welcome'] = $this->parseTextFile();
	$this->load->view('edit',$data);
	}
	public function parseTextFile()
	{
	$dbName = substr(BASEURL2, 0, -1);
	$file_url = './uploads/' .$dbName. '.txt';
	if (file_exists($file_url)){
	$file_contents = file_get_contents($file_url);
	$decode_data = json_decode($file_contents);
	$decode_data2 = array_map('utf8_decode', $decode_data);
	return $decode_data2;
	}
	}
	public function installation()
	{	
	//Check if any other installations exist? if No then the first one shall be called 'default'
	$files1 = @$this->file_list(APPPATH.'config/installations','.php');
	$count = count($files1);
	$default=false;
	$encryptedtext="";
	if($count==0)
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
					"German"=>"Deutsch",
					"Dutch"=>"Nederlands",
					"Bulgarian"=>"български",
					"Croatian"=>"Hrvatski",
					"French"=>"Français",
					"Russian"=>"Русский",
					"Tagalog"=>"Tagalog",
					"Czech"=>"Český",
					"Ukranian"=>"Українська",
					"Chinese"=>"中文");
	$langOptions = genOptions($lang);


	//Check if any other installations exist? if No then the first one shall be called 'default'
	$files1 = @$this->file_list(APPPATH.'config/installations','.php');
	$default=false;
	if($count==0)
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
		$data['welcome'] = $this->parseTextFile();
		$data['dbname'] = $this->config->item('database_name'); 
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
