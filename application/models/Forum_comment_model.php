<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum_comment_model extends CI_Model{

	var $table 		= 'forum_comment';
	var $tableAs 	= 'forum_comment a';
    
    function __construct(){
       parent::__construct();
    }

	function insert($data){
		$data['create_date'] 	= date('Y-m-d H:i:s');
		$data['user_id_create'] = ($data['user_id_create']) ? $data['user_id_create'] : id_user();
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
		return 	$this->db->get_where($this->tableAs, $where)->row_array();
	}

	function findBy($where,$is_single_row=0){
		$where['a.is_delete'] = 0;
		$this->db->select('a.*, b.full_name, c.name as status_publish, b.id_auth_user_grup');
		$this->db->join('auth_user b',"b.id_auth_user = a.user_id_create",'left');
		$this->db->join('status_publish c',"c.id = a.id_status_publish",'left');
		if($is_single_row==1){
			return 	$this->db->get_where($this->tableAs,$where)->row_array();
		} else if($is_single_row==2){
			return 	$this->db->get_where($this->tableAs,$where)->num_rows();
		} else {
			return 	$this->db->get_where($this->tableAs,$where)->result_array();
		}
	}

 }
