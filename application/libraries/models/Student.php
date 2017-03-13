<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Authorized extends Authorized
{
    function __construct()
    {
		parent::__construct();
		$this->load->model('student_m');
		// $this->load->form
	}

	public function test_get()
	{

        $result = $this->student_m->student();
        $data = array('status' => 'OK','data' => $result );

        $this->set_response($data, REST_Controller::HTTP_OK);
    }

}