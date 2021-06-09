<?php

class Page_hit_model extends  CI_Model{

	var $table = 'page_hits';
	var $tableAs = 'page_hits a';
    function __construct(){
       parent::__construct();
    }

	function records($where=array(),$isTotal=0){
		$alias['search_email'] = 'b.email';
		query_grid($alias,$isTotal);
		$this->db->select("a.*, b.email");
		$this->db->join('auth_user b','b.id_auth_user = a.user_create_id');
		$query = $this->db->get_where($this->tableAs,$where);
		if($isTotal==0){
			$data = $query->result_array();
		} else {
			return $query->num_rows();
		}
		$ttl_row = $this->records($where,1);
		return ddi_grid($data,$ttl_row);
	}

	function insert($data){
		$data['create_date'] 	= date('Y-m-d H:i:s');
		$data['user_create_id'] = ($data['user_create_id']) ? $data['user_create_id'] : id_user();
		$this->db->insert($this->table,array_filter($data));
	}

	function delete($id){
		$data['is_delete'] = 1;
		$this->update($data,$id);
	}

}

