<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct(){
         parent::__construct();
		 
		 $this->load->library('email');
		 $this->load->helper('date');
		 $this->load->library(array('form_validation'));
         $this->load->helper(array('url','form'));
         $this->load->model('m_account'); //call model
		 $this->load->library('session');
    }
	public function index()
	{
		
		$this->load->view('welcome_message');
	}
	//muksinproject.000webhostapp.com/index.php/welcome/register/
	public function register()
	{
		$this->form_validation->set_rules('nama', 'NAMA','required');
		$this->form_validation->set_rules('email','EMAIL','required');
		$this->form_validation->set_rules('password','PASSWORD','required');
		$this->form_validation->set_rules('phone','PHONE','required');
		if($this->form_validation->run() == FALSE) 
		{
			$this->load->view('welcome_message');
		}
		else
		{

			$data['nama'] =	$this->input->post('nama');
			$data['email'] =	$this->input->post('email');
			$data['password'] =    $this->input->post('password');
			$data['phone'] =    $this->input->post('phone');

			$this->m_account->daftar($data);

			echo "sukses";
		}
		
	}
	public function login()
	{
		$this->form_validation->set_rules('email','EMAIL','required');
		$this->form_validation->set_rules('password','PASSWORD','required');
		if($this->form_validation->run() == FALSE) 
		{
			$this->load->view('welcome_message');
		}
		else
		{

			$data['email'] =	$this->input->post('email');
			$data['password'] =    $this->input->post('password');
			
			$userData = $this->m_account->readUser($data);
			if($userData > 0)
			{
				echo $userData;
				$_SESSION["user"] = $userData;
			}
			else{
				echo "0";
			}
				
		}
	}
	public function confirmLogin()
	{
		if(empty($_SESSION["user"]) )
		{
			echo "0";
		}
		else
		{
			echo $_SESSION["user"];
		}
	}
	public function sendMail() 
	{
		$this->form_validation->set_rules('email','EMAIL','required');
		if($this->form_validation->run() == FALSE) 
		{
			$this->load->view('welcome_message');
		}
		else
		{
			$emailUser =	$this->input->post('email');
			
			date_default_timezone_set('Asia/Jakarta');
 
			$datestring = '%Y%m%d%h%i%s';
			$time = time();
			$currentTime = mdate($datestring, $time);
			$randomCode = rand(1,10);
			$currentCode = $currentTime + $randomCode;
			
			$messageCode = "Your Code Is: " . $currentCode;
			
			$data['email'] = $emailUser;
			$data['kode'] = $currentCode;
			
			$this->m_account->codeChangePassword($data);
			
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://smtp.googlemail.com',
				'smtp_port' => 25,
				'smtp_user' => 'jamilwahyu53@gmail',
				'smtp_pass' => 'echoAlpha',
				'mailtype'  => 'html', 
				'charset'   => 'iso-8859-1'
			);
			
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");

			$this->email->from('jamilwahyu53@gmail.com', 'Yhoora Group');
			$this->email->to($emailUser); 

			$this->email->subject('Change Password');
			$this->email->message($messageCode); 
		
			$result = $this->email->send();
			
			$this->load->view('welcome_message');

		}
    }
	function changePassword()
	{
		$this->form_validation->set_rules('password','PASSWORD','required');
		$this->form_validation->set_rules('kode','KODE','required');
		if($this->form_validation->run() == FALSE) 
		{
			$this->load->view('welcome_message');
		}
		else
		{
			$data['password'] =	$this->input->post('password');
			$data['kode'] =	$this->input->post('kode');
			
			$this->m_account->changePassword($data);
			
			$this->load->view('welcome_message');
		}
	}
	

}
