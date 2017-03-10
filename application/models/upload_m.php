<?php 

class Upload_m extends MY_Model 
{

	protected $_table_name = 'upload';
	protected $_primary_key = 'id';
	
	public $rules = array(
		'nomor' => array(
			'field' => 'id', 
			'label' => 'Kode Referensi ', 
			'rules' => 'trim|max_length[50]|xss_clean'
		), 
		'desc' => array(
			'field' => 'deskripsi', 
			'label' => 'Deskripsi', 
			'rules' => 'trim|max_length[100]|xss_clean'
		)
	);


	function __construct ()
	{
		parent::__construct();
	}

	public function getlist(){
		$this->db->select('id,deskripsi,file,status');
		// $this->db->limit($limit,$offset);
		$result = $this->db->get($this->_table_name);
		// $this->$_row_count = $this->db->
		return $result;
	}
	
	public function get_new()
	{
		$referensi = new stdClass();
		$referensi->id = '';
		$referensi->deskripsi = '';
		return $referensi;
	}

}