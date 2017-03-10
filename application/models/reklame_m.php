<?php
class Reklame_m extends MY_Model 
{
	protected $_table_name = 'reklame';
	protected $_primary_key = 'id';
	public $rules = array(
		'nama' => array(
			'field' => 'nama', 
			'label' => 'Nama', 
			'rules' => 'trim|required|xss_clean'),
		'deskripsi' => array(
			'field' => 'deskripsi', 
			'label' => 'Deskripsi Reklame ', 
			'rules' => 'required'),
		'lokasi' => array(
			'field' => 'lokasi', 
			'label' => 'Lokasi Reklame', 
			'rules' => 'trim|required|xss_clean')
	);
	private function _get_query(){
			$this->db->select('
				id,nama,deskripsi,lokasi,latitude,longitude'
			    ,
			    FALSE);
			$this->db->from('reklame');
			
	}

	public function get_datatables()
	{
		$this->_get_query();
		$query = $this->db->get();
		return $query->result();
	}

	
	public function get_by_id($id)
	{
		$this->_get_query();
		$this->db->where('id',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function saveNew($data){
		$this->db->insert($this->_table_name, $data);
		return $this->db->insert_id();
	}

	public function update($data,$id){
		$this->db->where($this->_primary_key, $id);
		$this->db->update($this->_table_name,$data);

	}
	public function getlist(){
		$this->db->select('id,nama,deskripsi,lokasi,latitude,longitude');
		// $this->db->limit($offset,3);
		$result = $this->db->get($this->_table_name);
		// $this->$_row_count = $this->db->
		return $result;
	}
	
	public function count(){
		return $this->db->count_all_results($this->_table_name);
	}		
	
	public function get_new()
	{
		$reklame = new stdClass();
		$reklame->id = '';
		$reklame->deskripsi = '';
		$reklame->nama = '';
		$reklame->lokasi = '';
		$reklame->longitude = '';
		$reklame->latitude = '';
		return $reklame;
	}
	public function getIklan(){
		$this->db->select('r.*',FALSE);
		$this->db->from('reklame r');
		$this->db->where_not_in('t1.id','(select l.idReklame from lelang l where l.status=0)');
		$query = $this->db->get();
		return $query->result();
	}
}
?>