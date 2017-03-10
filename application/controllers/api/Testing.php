<?php
// require APPPATH . '/libraries/REST_Controller.php';


class Testing extends Authorized {
	
	
	function __construct(){
		
		parent::__construct();
		
		$this->load->model('lelang_m');
		
		
	}
	
	
	public function lelang_get()
	{
		
		// 		if($this->NotAuth()) return ;
		
		
		$data = array();
		
		$data["status"] = true;
		
		$data["data"] = $this->lelang_m->getlelang();
		
		$this->response($data,200);
		
		
	}
	
	
	public function lelang_list_get()
	{
		
		if($this->NotAuth()) return ;
		
		
		$data = array();
		
		$data["status"] = true;
		
		$data["data"] = $this->lelang_m->getLelangList();
		
		$this->response($data,200);
		
		
	}
	
	
}
