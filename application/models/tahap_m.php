<?php

class Tahap_m extends MY_Model
{

	protected $_table_name = 'tahapan';
	protected $_primary_key = 'id';

	function __construct ()
	{
		parent::__construct();
	}
    public $rules = array(
		'mulai' => array(
			'field' => 'mulai',
			'label' => 'Mulai',
			'rules' => 'trim|required|xss_clean'
		),
		'selesai' => array(
			'field' => 'selesai',
			'label' => 'Selesai',
			'rules' => 'trim|required'
		)
	);

    private function _queryTahap()
	{
		$this->db->select('
		t0.id,t0.idTahap,t0.idLelang,t0.mulai mulai,t0.selesai selesai,t0.dokumen,t0.keterangan,t0.status,
		t1.deskripsi tahapan',FALSE);
		$this->db->from('tahapan as t0');
		$this->db->join('ref_tahap as t1','t1.id=t0.idTahap','left');

	}
    public function getdatatable($value)
    {
       $this->_queryTahap();
       $this->db->where('t0.idLelang',$value);
	   $this->db->order_by('t0.mulai','asc');
	   
       $query = $this->db->get();
       return $query->result();
    }

    public function getbyID($value)
    {
       $this->_queryTahap();
       $this->db->where('t0.id',$value);
       $query = $this->db->get();
       return $query->row();
    }

    public function count($value)
    {
       $this->_queryTahap();
       $this->db->where('t0.idLelang',$value);
       return $this->db->count_all_results();
    }
	public function update($data,$id){
		$this->db->where($this->_primary_key, $id);
		$this->db->update($this->_table_name,$data);

	}
	public function getTahapRef(){
		$query = $this->db->get('ref_tahap');
		return $query->result();
	}

	public function getdatatableReport($value)
    {
       $this->_queryTahap();
       $this->db->where('t0.idLelang',$value);
	   $this->db->order_by('t1.id','asc');
	   
       $query = $this->db->get();
       return $query->result();
    }
}
