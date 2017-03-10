<?php
class Lelang_m extends MY_Model
{
	protected $_table_name = 'lelang';
	// protected $_primary_key = 'id';
	protected $_count = 0;
	public $rules = array(
		'nilai' => array(
			'field' => 'nilai',
			'label' => 'Nilai Lelang ',
			'rules' => 'trim|max_length[50]|xss_clean')
	);

	private function _queryLelang()
	{
		$this->db->select('
		t0.id,t0.kode,t0.nilai,t0.kualifikasi,t0.evaluasi,t0.publish,t0.gambar,t0.rks,
		t1.nama,t1.deskripsi,t1.lokasi,
		t2.dokumen,t2.mulai,t2.selesai,
		t3.deskripsi tahapan,t3.id kode_tahap');
		$this->db->from('lelang as t0');
		$this->db->join('reklame as t1','t1.id=t0.idReklame','left');
		$this->db->join('tahapan as t2','t2.idLelang=t0.id','left');
		$this->db->join('ref_tahap as t3','t3.id=t2.idTahap','left');
	}
	#get data lelang yang aktif
	public function getLelang(){
		// $this->db->where('now() between t2.mulai and t2.selesai');
		$this->db->select('
		t0.id,t0.kode,t0.nilai,t0.kualifikasi,t0.evaluasi,t0.publish,t0.gambar,t0.rks,
		t1.nama,t1.deskripsi,t1.lokasi');
		$this->db->from('lelang as t0');
		$this->db->join('reklame as t1','t1.id=t0.idReklame','left');
		// $this->db->join('ref_tahap as t3','t3.id=t2.idTahap','left');
		// $this->db->where('t2.idTahap = 1');
		$this->db->where('t0.status = 0');
		$this->db->where('t0.publish = 1');
		$query = $this->db->get();
		return $query->result();
	}
	#get data lelang yang aktif
	public function getLelangList(){
		$this->db->select('
		t0.id,t0.kode,t0.nilai,t0.kualifikasi,t0.evaluasi,t0.publish,t0.gambar,t0.rks,
		t1.nama,t1.deskripsi,t1.lokasi');
		$this->db->from('lelang as t0');
		$this->db->join('reklame as t1','t1.id=t0.idReklame','left');
		// $this->db->where('now() between t2.mulai and t2.selesai');
		$this->db->where('t0.status < 1');
		$this->db->where('(select count(*) from tahapan where idlelang=t0.id) > 0');
		$this->db->order_by('t0.datetime','desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function getEvaluasiList($from=NULL,$to=NULL){
		$this->db->select('
		t0.id,t0.kode,t0.nilai,t0.kualifikasi,t0.evaluasi,t0.publish,t0.gambar,t0.rks,
		t1.nama,t1.deskripsi,t1.lokasi');
		$this->db->from('lelang as t0');
		$this->db->join('reklame as t1','t1.id=t0.idReklame','left');
		// $this->db->where('now() between t2.mulai and t2.selesai');
		if($from != NULL){
			$this->db->where("t0.datetime between '".$from."' and '".$to."'");
			$this->db->where('t0.status = 1');
		}else{
			$this->db->where('t0.status = 0');
		}
		$this->db->where('t0.publish',1);
		if($from != NULL){

		}
		$this->db->order_by('t0.datetime','desc');
		$query = $this->db->get();
		return $query->result();
	}

	#get data lelang yang aktif
	public function getDaftar(){
		$this->_queryLelang();
		$this->db->where('t2.id is null');
		$query = $this->db->get();
		return $query->result();
	}

	public function getLelangById($id){
		$this->_queryLelang();
		$this->db->where('t0.id',$id);
		$query = $this->db->get();
		return $query->row();
	}
	#get kualifikasi lelang
	public 	function getKualifikasi($value)
	{
		# code...
		$this->db->select('t0.idLelang,t0.idkualifikasi,t1.deskripsi');
		$this->db->from('kualifikasi_lelang as t0');
		$this->db->join('ref_kualifikasi as t1','t1.id=t0.idKualifikasi','left');
		$this->db->where('t0.idLelang',$value);
		$query = $this->db->get();
		return $query->result();

	}
	public function getReklame()
	{
		$this->db->select('r.*');
		$this->db->from('reklame r');
		$this->db->where('r.id not in (select l.idReklame from lelang l where l.status=0)');
		$query = $this->db->get();
		return $query->result();
	}
	public function update($data,$id){
		$this->db->where($this->_primary_key, $id);
		$this->db->update($this->_table_name,$data);

	}
	public function getCountPeserta($id){
		$this->db->where('idLelang',$id);
		$this->db->from('peserta');
		$result = $this->db->count_all_results();
		return $result;
	}
	public function getCountPesertaID($id,$idPeserta){
		$this->db->where('idUser',$idPeserta);
		$this->db->where('idLelang',$id);
		$this->db->from('peserta');
		$result = $this->db->count_all_results();
		return $result;
	}
	public function getEvaluasiPeserta($id){
		$this->db->select('p.*,u.company,u.address,u.telp,u.email,u.kontak,u.kontak_telp,u.kontak_email,
		r.deskripsi statusPeserta');
		$this->db->from('lelang l');
		$this->db->join('peserta p','p.idLelang = l.id','left');
		$this->db->join('datuser u','u.userid = p.idUser','left');
		$this->db->join('ref_peserta r','r.id=p.status','left');
		$this->db->where('l.id',$id);
		$query = $this->db->get();
		return $query->result();

	}
	public function getInfoPeserta($idLelang,$idPeserta){
		$this->db->select('t.profile,dokumen,penawaran');
		$this->db->where('idUser',$idPeserta);
		$this->db->where('idLelang',$idLelang);
		$this->db->from('peserta t');
		$result = $this->db->get();
		return $result->row();
	}

	public function getInfoPenjelasan($idLelang){
		$this->db->select('t.user,t.pesan,t1.username');
		$this->db->where('idLelang',$idLelang);
		$this->db->from('penjelasan t');
		$this->db->join('datuser t1','t1.userid=t.idUser','left'); 
		$query = $this->db->get();
		return $query->result();
	}

	public function getTahapStatus($idLelang,$tahap)
	{
		$this->db->select('count(*) as statusLelang from tahapan t');
		$this->db->where('now() > t.selesai and t.idTahap='.$tahap.' and t.idlelang='."$idLelang");
		$status = $this->db->get()->row()->statusLelang;
		return $status;
	}

	public function getHistoryPeserta($id){
		$this->db->select('
		t0.id,t0.kode,t0.nilai,t0.kualifikasi,t0.evaluasi,t0.publish,
		t1.nama,t1.deskripsi,t1.lokasi');
		$this->db->from('lelang as t0');
		$this->db->join('reklame as t1','t1.id=t0.idReklame','left');
		$this->db->join('peserta as t2','t2.idLelang=t0.id','left');
		$this->db->where('t0.status = 1');
		$this->db->where('t0.publish',1);
		$this->db->where('t2.idUser',$id);
		$this->db->order_by('t0.datetime','desc');
		$query = $this->db->get();
		return $query->result();
	}
};
