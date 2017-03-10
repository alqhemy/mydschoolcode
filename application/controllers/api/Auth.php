<?php

defined('BASEPATH') OR exit('No direct script access allowed');


// require APPPATH . '/libraries/REST_Controller.php';


class Auth extends Authorized
{
	
	
	public function login_post()
	{
		
		$this->load->model('user_m');
		
		
		$stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
		
		$req = json_decode($stream_clean,true);
		
		
		$login = $this->user_m->login( $req["user"],$req["password"]) ;
		
		
		if($login == FALSE){
			$error = '{"status":"failed","error":"User & Password Not match"}';
			$this->set_response(json_decode($error), REST_Controller::HTTP_UNAUTHORIZED);
			
			return;
			
		}
		
		
		$this->set_response($login, REST_Controller::HTTP_OK);
		
		
	}
	
	
	public function token_get(){
		
		if($this->NotAuth()) return ;
		
		
		$this->response($this->user,200);
		
		
	}
	
	
}
