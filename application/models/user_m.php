<?php
class User_m extends MY_Model
{
	
	protected $_table_name = 'users';
	
	function __construct ()
		{
		parent::__construct();
		$this->dbase = $this->load->database("default",true);
	}
	
	public function login ($result)
	{
		$this->db->select('*');
		$this->db->from($this->_table_name);
		$this->db->where(array(
					'user' => $result->user,
					'password' => md5($result->password)
				));
		$user = $this->db->get()->row();
		
		if (count($user)) {
			$admin = FALSE;
			
			if(strtoupper($user->user) === 'ADMIN')
						{
				$admin = TRUE;
			}

			$data = array(
							'name' => $user->name,
							'uid' =>  $result->user,
							'admin' => $admin
			);
			
			$output['token'] = AUTHORIZATION::generateToken($data);
			// 			$this->set_response($output, REST_Controller::HTTP_OK);
			return $output;
		}
		else{
			return FALSE;
		}
		
	}
	
	
	public function register($data){
		$status = FALSE;
		if($this->getUser($data->user) < 1)
				{
			$this->db->insert($this->_table_name,$data);
			$status = TRUE;
		}
		return $status;
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
