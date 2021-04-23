<?php
class Motor_model extends  CI_Model{
	var $table = 'motor';
	var $tableAs = 'motor a';
	function __construct(){
	   parent::__construct();
	}
	function records($where=array(),$isTotal=0){
		$grup = $this->session->userdata['ADM_SESS']['admin_id_auth_user_group'];
		$alias['search_uri_path'] = 'a.uri_path';
		$alias['search_status_publish'] = 'g.id';
		$alias['search_motor_merek'] = 'c.id';
		$alias['search_motor_model'] = 'd.id';
		$alias['search_motor_tipe'] = 'e.id';
		$alias['search_motor_jenis'] = 'f.id';
		$alias['search_id'] = 'a.id';

		query_grid($alias,$isTotal);
		
		$this->db->select('a.*, b.full_name, g.name as status ,c.name as merek, d.name as model, e.name as tipe, f.name as jenis');
		$this->db->where('a.is_delete',0);
		$this->db->join('auth_user b',"b.id_auth_user = a.user_id_create",'left');
		$this->db->join('motor_merek c','c.id = a.id_motor_merek');
		$this->db->join('motor_model d','d.id = a.id_motor_model');
		$this->db->join('motor_tipe e','e.id = a.id_motor_tipe');
		$this->db->join('motor_jenis f','f.id = a.id_motor_jenis');
		$this->db->join('status_publish g',"g.id = a.id_status_publish",'left');

		$query = $this->db->get($this->tableAs);
		if($isTotal==0){
			$data = $query->result_array();
			print_r($this->db->last_query());
		}
		else{
			return $query->num_rows();
		}

		$ttl_row = $this->records($where,1);
		
		return ddi_grid($data,$ttl_row);
	}
	function insert($data){
		$data['create_date'] 	= date('Y-m-d H:i:s');
		$data['user_id_create'] = $data['user_id_create'] ? $data['user_id_create'] : id_user();
		$this->db->insert($this->table,array_filter($data));
		return $this->db->insert_id();
	}
	function update($data,$id){
		$where['id'] = $id;
		$data['user_id_modify'] = id_user();
		$data['modify_date'] 	= date('Y-m-d H:i:s');
		$this->db->update($this->table,$data,$where);
		return $id;
	}
	function delete($id){
		$data['is_delete'] = 1;
		$this->update($data,$id);
	}
	function findById($id){
		$where['a.id'] = $id;
		$where['a.is_delete'] = 0;
		$this->db->select('a.*, b.full_name, c.name as merek, d.name as model, e.name as tipe, f.name as jenis');
		$this->db->join('auth_user b','b.id_auth_user = a.user_id_create');
		$this->db->join('motor_merek c','c.id = a.id_motor_merek');
		$this->db->join('motor_model d','d.id = a.id_motor_model');
		$this->db->join('motor_tipe e','e.id = a.id_motor_tipe');
		$this->db->join('motor_jenis f','f.id = a.id_motor_jenis');

		return 	$this->db->get_where($this->tableAs,$where)->row_array();
	}
	function findBy($where,$is_single_row=0){
		$where['a.is_delete'] = 0;
		$this->db->select('a.*, b.full_name, c.name as merek, d.name as model, e.name as tipe, f.name as jenis');
		$this->db->join('auth_user b','b.id_auth_user = a.user_id_create');
		$this->db->join('motor_merek c','c.id = a.id_motor_merek');
		$this->db->join('motor_model d','d.id = a.id_motor_model');
		$this->db->join('motor_tipe e','e.id = a.id_motor_tipe');
		$this->db->join('motor_jenis f','f.id = a.id_motor_jenis');
		if($is_single_row==1){
			return $this->db->get_where($this->tableAs,$where)->row_array();
		}
		else{
			return $this->db->get_where($this->tableAs,$where)->result_array();
		}
	} 

	function fetchRow($where) {
		return $this->findBy($where,1);
	}

	//fungsi get_all untuk menampilkan news pada controller/news.php
	function get_all($where=array()){
		$where['a.is_delete'] = 0;
		$this->db->select('a.*');
		return 	$this->db->get_where($this->tableAs,$where);
	}

 }
