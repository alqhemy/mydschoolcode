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
		
		$req = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);		
		$login = $this->user_m->login( $req["user"],$req["password"]) ;
		
		if($login == FALSE){
			$error = '{"status":"failed","error":"User & Password Not match"}';
			$this->set_response(json_decode($error), REST_Controller::HTTP_UNAUTHORIZED);
			return;
			
		}
		$data["status"] = "ok";
		$data["data"] = $login;
		$this->set_response($data, REST_Controller::HTTP_OK);
		
		
	}
	
	public function register_post()
	{
		$req = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);	
		$data= array('token' => $req["uid"],'user'=>$req["user"]);

		$ok = $this->user_m->register($data);
		if($ok){
			$status = array('status' => 'ok','data'=>$data );
		}else{
			$status = array("status" => "failed" , "error"=>"User already exist" );
		}
		
		$this->set_response(json_encode($status),REST_Controller::HTTP_OK);

	}
	
	
}
