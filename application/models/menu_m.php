<?php
class Menu_m extends MY_Model
{
	
	protected $_table_name = 'menus';
	protected $_order_by = 'name';
	protected $dbase ;
	public $rules = array(
		'menu' => array(
			'field' => 'name', 
			'label' => 'Menu', 
			'rules' => 'trim|required|xss_clean'
		), 
		'url' => array(
			'field' => 'slug', 
			'label' => 'Url', 
			'rules' => 'trim|required'
		),
		'parent' => array(
			'field' => 'parent', 
			'label' => 'Parent', 
			'rules' => 'trim|required'
		),
		'order' => array(
			'field' => 'number', 
			'label' => 'Oder', 
			'rules' => 'trim|required'
		),
		'group' => array(
			'field' => 'level', 
			'label' => 'group', 
			'rules' => 'trim|required'
		)
	);

	function __construct ()
	{
		parent::__construct();
		// $this->dbase = $this->load->database('userconf',TRUE);
	}

	public function getMenu($parent=NULL){
		$this->db->select('id,name,slug,number,level');
		$this->db->from('menus');
		// $this->_filter = 'parent = '.$parent;
		
		$this->_filter = array('parent' => $parent);
		$this->db->where('level > 0');
		$this->db->where($this->_filter);
		$result = $this->db->get();
		return $result;
	}
	public function get_new(){
		$menu = new stdClass();
		$menu->name = '';
		$menu->slug = '';
		$menu->parent = '';
		$menu->level = '';
		$menu->number = '';
		return $menu;
	}

	
}