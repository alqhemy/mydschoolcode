<?php
class User_m extends MY_Model
{
	
	protected $_table_name = 'datuser';
	
	function __construct ()
	{
		parent::__construct();
		$this->dbase = $this->load->database("default",true);
	}

	public function login ($user, $pass)
	{
		// $this->_getUsergroup();
		$this->db->select('*');
		$this->db->from('datuser');
		$this->db->where(array(
			'datuser.username' => $user,
			'datuser.userpass' => md5($pass)
		));
		$user = $this->db->get()->row();
		

		if (count($user)) {
			
			$admin = FALSE;
			if(strtoupper($user->username) === 'ADMIN') {
				$admin = TRUE;
			}
			$data = array(
				'name' => $user->username,
				'id' => $user->userid,
				'admin' => $admin
				
			);
		
			$output['token'] = AUTHORIZATION::generateToken($data);
			// $this->set_response($output, REST_Controller::HTTP_OK);
			return $output;
		}else{
			return FALSE;
		}

	}

	public function logout ()
	{
		$this->session->sess_destroy();
	}

	public function loggedin ()
	{
		return (bool) $this->session->userdata('loggedin');
	}
	
	public function admin(){
		return (bool) $this->session->userdata('admin');	
	}
	public function get_new(){
		$user = new stdClass();
		$user->username = '';
		$user->useppass = '';
		$user->userlok = '';
		$user->usergrp = '';
		$user->usercust = '';
		return $user;
	}

	public function hash ($string)
	{
		// return crypt( $string , config_item('encryption_key'));
		return md5($string);
	}
	//manage user data
	
	public function locId(){
		return $this->session->userdata('lokasi');
	}

	private function	_getUsergroup(){
		$this->db->select('*');
		$this->db->from('datuser');
		$this->db->join('tbl_lokasi','tbl_lokasi.fld_lokcd=datuser.userlok');
	}
	public function register($data){
		$this->db->insert('datuser',$data);
	}
	public function sendEmail($to_email)
    {
        $from_email = 'alqhemy@gmail.com'; //change this to yours
        $subject = 'Verify Your Email Address';
        $message = 'Dear User,<br /><br />Please click on the below activation link to verify your email address.<br /><br /> http://www.mydomain.com/user/verify/' . md5($to_email) . '<br /><br /><br />Thanks<br />Mydomain Team';
        
        //configure email settings
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com'; //smtp host name
        $config['smtp_port'] = '465'; //smtp port number
        $config['smtp_user'] = 'alqhemy@gmail.com';
        $config['smtp_pass'] = 'yokosami7501'; //$from_email password
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes
        $this->email->initialize($config);
        
        //send mail
        $this->email->from($from_email, 'gmail.com');
        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->message($message);
        return $this->email->send();
    }

	public function verifyEmailID($value)
	{
		# code...
	
		$this->db->select('count(*) as email from datuser');
		$this->db->where('email',$value);
		$status = $this->db->get()->row()->email;
		
		return $status;
	}

	public function resetPassword($value,$data)
	{
		# code...
		$this->db->where('email',$value);
		$this->db->update('datuser',$data);
		return True;
	
	}

	public function getUserPeserta()
	{
		$this->db->select('company,address,telp,email');
		$this->db->where('usergrp',3);
		$this->db->from('datuser');
		$query = $this->db->get();
		return $query->result();
	}
}