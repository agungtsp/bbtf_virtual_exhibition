<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_article_model extends CI_Model{

	var $table 		= 'news_category';
	var $tableAs 	= 'news_category a';
    
    function __construct(){
       parent::__construct();
    }

	function records($where=array(),$isTotal=0){
		$alias['search_category_article'] 			= 'a.name';
		$alias['search_uri_path'] 					= 'a.uri_path';

	 	query_grid($alias,$isTotal);
		$this->db->select("a.*");
		$this->db->where('a.is_delete',0);
		$query = $this->db->get($this->tableAs);

		if($isTotal==0){
			$data = $query->result_array();
		}
		else{
			return $query->num_rows();
		}

		$ttl_row = $this->records($where,1);
		return ddi_grid($data,$ttl_row);
	}

	function insert($data){
		$post['controller'] 	= "article";
		$data['create_date'] 	= date('Y-m-d H:i:s');
		$data['user_id_create'] = id_user();
		$this->db->insert($this->table,array_filter($data));
		$id = $this->db->insert_id();
		return $id;
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
		$this->update($data,$id,1);
	}

	function findById($id){
		$where['a.id'] = $id;
		$where['is_delete'] = 0;
		return 	$this->db->get_where($this->table.' a',$where)->row_array();
	}

	function findBy($where,$is_single_row=0){
		$where['is_delete'] = 0;
		$this->db->select('*');
		if($is_single_row==1){
			return 	$this->db->get_where($this->tableAs,$where)->row_array();
		}
		else{
			return 	$this->db->get_where($this->tableAs,$where)->result_array();
		}
	}

 }
