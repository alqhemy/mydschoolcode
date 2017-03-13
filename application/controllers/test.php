<?php

defined('BASEPATH') OR exit('No direct script access allowed');


// require APPPATH . '/libraries/REST_Controller.php';


class Auth extends Authorized
{
	function __construct()
    {
		parent::__construct();
		$this->load->model('user_m');
	}

	public function login_post()
	{
		
		$req = json_decode($this->security->xss_clean($this->input->raw_input_stream));		
		$login = $this->user_m->login($req) ;
		
		if($login == FALSE){
			$error = '{"status":"failed","error":"User & Password Not match"}';
			$this->set_response(json_decode($error), REST_Controller::HTTP_UNAUTHORIZED);
			return;
			
		}
		$data = array('status' => 'ok','data' =>$login );
		
		$this->set_response($data, REST_Controller::HTTP_OK);
		
		
	}
	
	public function register_post()
	{
		$req = json_decode($this->security->xss_clean($this->input->raw_input_stream));	
		$dat["user"] = $req->user;
		$dat["password"] =  md5($req->password);
		$dat["name"] =  $req->name;
		$ok = $this->user_m->register($dat);

		if($ok){
			$status = array('status' =>"oke" ,'data'=>$dat );
		}else{
			$status = array('status' => 'failed','data' =>'User already exist' );
		}
		
		$this->set_response($status,REST_Controller::HTTP_OK);

	}
}
