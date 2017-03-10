<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Authorized extends REST_Controller
{
    protected $user;
    
    protected $auth;
    function __construct()
    {
        parent::__construct();
        $headers = $this->input->request_headers();

        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
                $this->user = $decodedToken;
                $this->auth = TRUE;
            }
        }else{

           $this->auth = FALSE;
        }

        # code...
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
}