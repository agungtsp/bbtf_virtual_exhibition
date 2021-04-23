<?php

class Help_category_model extends  CI_Model{

	var $table = 'help_category';
	var $tableAs = 'help_category a';
    function __construct(){
       parent::__construct();
    }

	function records($where=array(),$isTotal=0){
		$alias['search_status_publish'] = 'a.id_status_publish';
	 	query_grid($alias,$isTotal);
		$this->db->select("a.*,d.name as status_publish");
		$this->db->join('status_publish d','d.id = a.id_status_publish');
		$this->db->where('a.is_delete',0);
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
		$data['user_create_id'] = id_user();
		$this->db->insert($this->table,array_filter($data));
	}

	function update($data,$id){
		$where['id'] 			= $id;
		$data['user_modify_id'] = id_user();
		$data['date_modify'] 	= date('Y-m-d H:i:s');
		$this->db->update($this->table,$data,$where);
	}

	function delete($id){
		$data['is_delete'] = 1;
		$this->update($data,$id);
	}

	function cari($cari){
		$this->db->select("a.*,b.name as sub_name,b.uri_path as url, b.description as content_text");
		$this->db->join('help b','b.id_help_category = a.id');
		$this->db->like('a.name',$cari);
		$this->db->or_like('b.description',$cari);
		$this->db->or_like('b.name',$cari);
		$data = $this->db->get($this->tableAs)->result_array();
		return $data;
	}

	function findById($id){
		$where['a.id'] = $id;
		$where['is_delete'] = 0;
		return 	$this->db->get_where($this->table.' a',$where)->row_array();
	}

	function findBy($where,$is_single_row=0){
		$where['a.is_delete'] = 0;
		$this->db->select('a.*');
		if($is_single_row==1){
			return 	$this->db->get_where($this->tableAs,$where)->row_array();
		} else {
			return 	$this->db->get_where($this->tableAs,$where)->result_array();
		}
	} 

	function get_all($where=array()){
		$this->db->select('a.*');
		$where['a.is_delete']         = 0;
		$where['a.id_status_publish'] = 2;
		return 	$this->db->get_where($this->tableAs,$where);
	}

	function findByUri($uri_path){
		$where['uri_path'] = $uri_path;
		$where['is_delete'] = 0;
		return 	$this->db->get_where($this->table.' a',$where)->row_array();
	}

 }

