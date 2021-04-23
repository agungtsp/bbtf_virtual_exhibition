<?php
class Help_model extends  CI_Model{
	var $table = 'help';
	var $tableAs = 'help a';
    function __construct(){
       parent::__construct();
	   
    }
	function records($where=array(),$isTotal=0){
		$alias['search_title'] 			= 'a.title';
		$alias['search_name'] 			= 'a.name';
		$alias['search_status_publish'] = 'a.id_status_publish';

	 	query_grid($alias,$isTotal);
		$this->db->select("a.*,d.name as status_publish");
		$this->db->join('status_publish d','d.id = a.id_status_publish');
		$this->db->where('a.is_delete',0);
	
		
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
		$data['date_create'] 	= date('Y-m-d H:i:s');
		$data['user_create_id'] = id_user();
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
	
	function findByUri($uri_path){
		$where['uri_path'] = $uri_path;
		$where['is_delete'] = 0;
		return 	$this->db->get_where($this->table.' a',$where)->row_array();
	}

	function findByuriCategory($uri_path){
		$where['a.uri_path'] = $uri_path;
		$where['a.is_delete'] = 0;
		$this->db->select("a.description, a.id, a.name");	
		$this->db->join('help_category','help_category.id = a.id_support_category');
		return 	$this->db->select('a.description')->get_where($this->table.' a',$where)->row()->description;
		// return 	$this->db->get_where($this->table.' a',$where)->row_array();
	}

	function findIdByuri($uri_path){
		$where['a.uri_path'] = $uri_path;
		$where['a.is_delete'] = 0;
		// $this->db->select("a.description, a.id, a.name");	
		// $this->db->join('help_category','help_category.id = a.id_support_category');
		return 	$this->db->select('a.id')->get_where($this->table.' a',$where)->row()->id;
		// return 	$this->db->get_where($this->table.' a',$where)->row_array();
	}

	function findTags($uri_path){
		$where['uri_path'] = $uri_path;
		$where['is_delete'] = 0;
		return 	$this->db->select('ref_id_tags')->get_where($this->table,$where)->row()->ref_id_tags;
		// $this->db->select("a.description, a.id, a.name");	
		// $this->db->join('tags b','b.id = a.ref_id_tags');
		// return 	$this->db->get_where($this->tableAs,$where)->result_array();
	}

	function findTag2($id){
		$where['is_delete'] = 0;
		$where['id_status_publish'] = 2;
		$this->db->like('ref_id_tags',$id);
		return 	$this->db->get_where($this->table,$where)->result_array();
		// $this->db->select("a.description, a.id, a.name");	
		// $this->db->join('tags b','b.id = a.ref_id_tags');
		// return 	$this->db->get_where($this->tableAs,$where)->result_array();
	}

	function getTags($data=array()){
		$arr = array();
		foreach ($data as $id) {
			$where['id'] = $id;
			$arr[] = $this->db->get_where('tags',$where)->row_array();
		}
		
		return $arr;
	}

	function getRelated($data=array(),$uri_path=''){	
		// $where['uri_path !='] = $uri_path;
		// $this->db->not_like('uri_path', $uri_path);
		foreach ($data as $key => $id) {
			if ($key == 0) {
				$this->db->like('ref_id_tags',$id);
			}
			else{
				$this->db->or_like('ref_id_tags',$id);
			}
		}
		$datX = $this->db->get_where($this->table,$where)->result_array();
		// echo $this->db->last_query();exit;
		// print_r($datX);exit;
		
		return $datX;
	}

	// function getRelated($id='',$uri_path=''){	
	// 	$where['uri_path !='] = $uri_path;
	// 	$this->db->like('ref_id_tags',$id);
	// 	$datX = $this->db->get_where($this->table,$where)->result_array();

	// 	return $datX;
	// }

	
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
	
	function get_all(){
			$where['a.is_delete'] = 0;
			$this->db->select('a.*');
			$where['a.id_status_publish'] = 2;
			return 	$this->db->get_where($this->tableAs,$where);
		}

		
	
 }
