<?php
class Student_m extends MY_Model
{
	
	protected $_table_name = 'students';

    function __construct ()
		{
		parent::__construct();
		// $this->dbase = $this->load->database("default",true);
	}

    public function student($id=NULL)
    {
        if($id != NULL){
            $this->db->where('id',$id);            
        }
        $query = $this->db->get($this->_table_name);
        
		return $query->result();
    }
	
}
