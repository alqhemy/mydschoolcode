<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';


class Authorized extends REST_Controller
{
    protected $user;
    protected $auth;
    function __construct()
    {
        parent::__construct();
        $headers = $this->input->request_headers();
        $this->auth = TRUE;
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
                $this->user = $decodedToken;
            }
        }else{
           $this->auth = FALSE;
        }
    }

    protected function NotAuth()
   {
       if($this->auth == FALSE){
            $this->set_response(array("status"=>false,"error"=>"Not Authorize"), 401);
            return TRUE;
       }else{
           return FALSE;
       }
   }
   protected function request(){
       return json_decode($this->security->xss_clean($this->input->raw_input_stream));	
   }

   protected function error($value){
       return array('status' => 'failed','error' =>$value); 
   }

   protected function data($value){
       return array('status' => 'ok','data' =>$value); 
   }
}