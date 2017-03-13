<?php
// --student
// --school
class School extends Authorized
{
	function __construct()
    {
		parent::__construct();
		$this->load->model('student_m');
		
		// $this->load->form
	}

	public function student_get($id=NULL)
	{
		// if($this->NotAuth()) return ;
        $result = $this->student_m->student($id);
        $data = array('status' => 'OK','data' => $result );

        $this->set_response($data, REST_Controller::HTTP_OK);
    }
}