<?php
class User_m extends MY_Model
{
	
	protected $_table_name = 'users';
	
	function __construct ()
	{
		parent::__construct();
		$this->dbase = $this->load->database("default",true);
	}

	public function login ($uid, $pass)
	{
		$this->db->select('*');
		$this->db->from($this->_table_name);
		$this->db->where(array(
			'user' => $uid,
			'token' => $pass
		));
		$user = $this->db->get()->row();

		if (count($user)) {
			$admin = FALSE;
			if(strtoupper($uid) === 'ADMIN') {
				$admin = TRUE;
			}
			$data = array(
				'name' => $uid,
				'uid' => $pass,
				'admin' => $admin
				
			);
		
			$output['token'] = AUTHORIZATION::generateToken($data);
			// $this->set_response($output, REST_Controller::HTTP_OK);
			return $output;
		}else{
			return FALSE;
		}

	}

	public function hash ($string)
	{
		// return crypt( $string , config_item('encryption_key'));
		return md5($string);
	}
	//manage user data
	
	private function	_getUsergroup(){
		$this->db->select('*');
		$this->db->from($this->_table_name);
		$this->db->join('tbl_lokasi','tbl_lokasi.fld_lokcd=datuser.userlok');
	}
	public function register($data){
		$status = FALSE;
		if($this->getUser($data["user"]) < 1)
		{
			$this->db->insert($this->_table_name,$data);
			$status = TRUE;
		}
		return $status;
	}

	public function resetPassword($value,$data)
	{
		# code...
		$this->db->where('email',$value);
		$this->db->update($this->_table_name,$data);
		return True;
	
	}

	public function getUser($email)
	{
		$this->db->select('users');
		$this->db->where('user',$email);
		$this->db->from($this->_table_name);
		$result = $this->db->count_all_results();
		return $result;
	}
}