<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends Authorized
{
	function __construct()
    {
		parent::__construct();
		$this->load->model('user_m');
	}

	public function login_post()
	{
		$login = $this->user_m->login($this->request()) ;
		if($login == FALSE){
			$this->set_response($this->error('User & Password Not match'), 
				REST_Controller::HTTP_UNAUTHORIZED);
			return;
		}
		$this->set_response($this->data($login), REST_Controller::HTTP_OK);
		
	}
	
	public function register_post()
	{
		$req = $this->request();
		$password = $req->password;
		$req->password =  md5($req->password);
		if($this->user_m->register($req)){
			$User = new stdClass;
			$User->user = $req->user ;
			$User->password = $password;
			$status = $this->user_m->login($User) ;
		}else{
			$status = $this->error('User already exisst');
		}
		
		$this->set_response($status,REST_Controller::HTTP_OK);

	}
	
}
