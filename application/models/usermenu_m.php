<?php
class Usermenu_m extends MY_Model
{
	
	protected $_table_name = 'datuser';
	protected $_order_by = 'name';
	protected $dbase ;
	public $set_rules = array(
		'username' => array(
			'field' => 'username', 
			'label' => 'User', 
			'rules' => 'trim|required|xss_clean'
		), 
		'userpass' => array(
			'field' => 'userpass', 
			'label' => 'Password', 
			'rules' => 'trim'
		),
		'usergrp' => array(
			'field' => 'usergrp', 
			'label' => 'Group', 
			'rules' => 'trim'
		),
		'userlok' => array(
			'field' => 'userlok', 
			'label' => 'Lokasi', 
			'rules' => 'trim'
		),
		'usercust' => array(
			'field' => 'usercust', 
			'label' => 'Customer', 
			'rules' => 'trim'
		),

	);

	function __construct ()
	{
		parent::__construct();
		$this->_primary_key = 'userid';
		$this->dbase = $this->load->database("default",true);
	}

	private function _get_datatables_query()
	{	
		$this->db->select(
			't0.userid id,
			t0.username username,
			t3.fld_custnm customer,
			t2.grp grup,
			t1.fld_loknm lokasi',
			FAlSE);
		$this->db->from('datuser as t0');
		$this->db->join('tbl_lokasi as t1','t1.fld_lokcd=t0.userlok','left');
		$this->db->join('group as t2','t2.id=t0.usergrp','left');
		$this->db->join('tbl_customer as t3','t3.fld_custkd=t0.usercust','left');
		if($_POST['search']['value'])
			$this->db->like('t0.username', $_POST['search']['value']) ;
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1);
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->_table_name);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->_table_name);
		$this->db->where('userid',$id);
		$query = $this->db->get();

		return $query->row();
	}

	// public function save($data)
	// {
	// 	$this->db->insert($this->_table_name, $data);
	// 	return $this->db->insert_id();
	// }

	public function update($where, $data)
	{
		$this->db->update($this->_table_name, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('userid', $id);
		$this->db->delete($this->_table_name);
	}
	
	public function get_new(){
		$user = new stdClass();
		$user->username = '';
		$user->userlok = '';
		$user->userpass = '';
		$user->usergrp = '';
		$user->usercust = '';
		return $user;
	}

	public function hash ($string)
	{
		// return crypt( $string , config_item('encryption_key'));
		return md5($string);
	}
}