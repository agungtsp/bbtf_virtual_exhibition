<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum_model extends CI_Model{

	var $table 		= 'forum';
	var $tableAs 	= 'forum a';
    
    function __construct(){
       parent::__construct();
    }

	function records($where=array(),$isTotal=0, $isApi=0){
		$alias['search_title'] = 'a.title';

		if ($isApi == 1) {
			query_grid_api($alias,$isTotal);
		}
		else{
			query_grid($alias,$isTotal);
		}
		 
		$this->db->select("a.*,b.name as status_publish,c.name as forum_category, d.full_name, d.email, d.phone, e.grup as user_group");
		$this->db->where('a.is_delete',0);
		$this->db->join('status_publish b',"b.id = a.id_status_publish",'left');
		$this->db->join('forum_category c',"c.id = a.id_forum_category",'left');
		$this->db->join('auth_user d',"d.id_auth_user = a.id_auth_user",'left');
		$this->db->join('auth_user_grup e',"d.id_auth_user_grup = e.id_auth_user_grup",'left');

		$query = $this->db->get_where($this->tableAs, $where);
		if($isTotal==0){
			$data = $query->result_array();
		}
		else{
			return $query->num_rows();
		}

		$ttl_row = $this->records($where,1, $isApi);

		if ($isApi == 1) return ddi_grid_api($data, $ttl_row);
		return ddi_grid($data,$ttl_row);
	}

	function insert($data){
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
		$this->db->select("a.*,b.name as status_publish,c.name as forum_category, d.email, d.full_name, d.phone");
		$this->db->join('status_publish b',"b.id = a.id_status_publish",'left');
		$this->db->join('forum_category c',"c.id = a.id_forum_category",'left');
		$this->db->join('auth_user d',"d.id_auth_user = a.id_auth_user",'left');

		return 	$this->db->get_where($this->table.' a', $where)->row_array();
	}

	function findBy($where,$is_single_row=0){
		$where['a.is_delete'] = 0;
		$this->db->select("a.*,b.name as status_publish,c.name as forum_category, d.email, d.full_name, d.phone");
		$this->db->join('status_publish b',"b.id = a.id_status_publish",'left');
		$this->db->join('forum_category c',"c.id = a.id_forum_category",'left');
		$this->db->join('auth_user d',"d.id_auth_user = a.id_auth_user",'left');
		if($is_single_row==1){
			return 	$this->db->get_where($this->tableAs,$where)->row_array();
		}
		else{
			return 	$this->db->get_where($this->tableAs,$where)->result_array();
		}
	}

 }
