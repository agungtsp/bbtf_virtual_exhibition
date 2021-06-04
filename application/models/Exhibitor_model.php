<?php
class Exhibitor_model extends  CI_Model{
	var $table = 'exhibitor';
	var $tableAs = 'exhibitor a';
    function __construct(){
       parent::__construct();
	   
    }
	function records($where=array(),$isTotal=0){
		$alias['search_title'] 			= 'a.title';
		$alias['search_name'] 			= 'a.name';
		$alias['search_status_publish'] = 'a.id_status_publish';
		$alias['search_ref_exhibitor_category'] = 'a.id_exhibitor_category';
		
	 	query_grid($alias,$isTotal);
		$this->db->select("a.*,d.name as status_publish, e.name as category_name");
		$this->db->join('status_publish d','d.id = a.id_status_publish');
		$this->db->join('ref_exhibitor_category e','e.id = a.id_exhibitor_category');
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
		$this->db->join('ref_exhibitor_category','ref_exhibitor_category.id = a.id_exhibitor_category');
		return 	$this->db->select('a.description')->get_where($this->table.' a',$where)->row()->description;
		// return 	$this->db->get_where($this->table.' a',$where)->row_array();
	}

	function findIdByuri($uri_path){
		$where['a.uri_path'] = $uri_path;
		$where['a.is_delete'] = 0;
		// $this->db->select("a.description, a.id, a.name");	
		// $this->db->join('ref_exhibitor_category','ref_exhibitor_category.id = a.id_exhibitor_category');
		return 	$this->db->select('a.id')->get_where($this->table.' a',$where)->row()->id;
		// return 	$this->db->get_where($this->table.' a',$where)->row_array();
	}

	
	function findBy($where,$is_single_row=0){
		$where['a.is_delete'] = 0;
		$where['a.id_status_publish'] = 2;
		$this->db->select('a.*, a.uri_path as link, ref_exhibitor_category.name as category_name');
		$this->db->order_by('ordinal_number', 'asc');
		//$this->db->join('module b','b.id = a.id_module','left');
		$this->db->join('ref_exhibitor_category','ref_exhibitor_category.id = a.id_exhibitor_category');
		if($is_single_row==1){
			return 	$this->db->get_where($this->tableAs,$where)->row_array();
		}
		else{
			$list_data = $this->db->get_where($this->tableAs,$where)->result_array();
			foreach($list_data as $key => $value){
				$list_data[$key]['logo_url'] = image($value['logo'],'small');
				$list_data[$key]['booth_design_url'] = image($value['booth_design'],'small');
			}
			return $list_data;
		}
	} 
	
	function get_all(){
			$where['a.is_delete'] = 0;
			$this->db->select('a.*');
			$where['a.id_status_publish'] = 2;
			return 	$this->db->get_where($this->tableAs,$where);
		}

		
	
 }
