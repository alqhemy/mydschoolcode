<?php
// require APPPATH . '/libraries/Authorized.php';

class Student Extends CI_Controller
{
	function __construct()
    {
		$this->load->model('student_m');
	}

	public function get()
	{

        $result = $this->student_m->student();
        $data = array('status' => 'OK','data' => $result );

        $this->set_response($data, REST_Controller::HTTP_OK);
    }
}