<?php
class Help_comment_model extends  CI_Model{
	var $table   = 'help_comment';
	var $tableAs = 'help_comment a';
	var $tableTo = 'help b';
    function __construct(){
       parent::__construct();
	   
    }
	function records($where=array(),$isTotal=0){
	 	query_grid($alias,$isTotal);
		$this->db->select("a.*, a.message as comment, b.name");
		$this->db->join($this->tableTo,'b.id = a.id_help');
		
		$query = $this->db->get_where($this->tableAs,$where);

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
		$data['date'] 	= date('Y-m-d H:i:s');
		$this->db->insert($this->table,array_filter($data));
		return $this->db->insert_id();
	}
	function update($data,$id){
		$where['id'] 			= $id;
		$data['user_create_id'] = id_user();
		$data['date_modify'] 	= date('Y-m-d H:i:s');
		$this->db->update($this->table,$data,$where);
		return $id;
	}
	function delete($id){
		$data['is_delete'] = 1;
		$this->update($data,$id);
	}
	function findById($id){
		$where['a.id'] = $id;
		$where['is_delete'] = 0;
		return 	$this->db->get_where($this->table.' a',$where)->row_array();
	}
	function findBy($where,$is_single_row=0){
		$where['a.is_delete'] = 0;
		$where['a.id_status_publish'] = 2;
		$this->db->select('a.*, a.uri_path as link');
		//$this->db->join('module b','b.id = a.id_module','left');
		if($is_single_row==1){
			return 	$this->db->get_where($this->tableAs,$where)->row_array();
		}
		else{
			return 	$this->db->get_where($this->tableAs,$where)->result_array();
		}
	} 
	
}
