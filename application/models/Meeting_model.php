<?php

class Meeting_model extends  CI_Model{

	var $table = 'meeting';
	var $tableAs = 'meeting a';
    function __construct(){
       parent::__construct();
    }

	function records($where=array(),$isTotal=0){
		$alias['search_status_publish'] = 'a.id_status_publish';
		$alias['search_exhibitor_id'] = 'a.exhibitor_id';
		
		$alias['search_name'] = 'a.name';
		$alias['search_start_date'] = "date_format(a.start_date,'%d-%m-%Y')";
	 	query_grid($alias,$isTotal);
		$this->db->select("a.*,d.name as status_publish, e.name as exhibitor_name");
		$this->db->join('status_publish d','d.id = a.id_status_publish');
		$this->db->join('exhibitor e','e.id = a.exhibitor_id', 'left');
		$this->db->where('a.is_delete',0);
		$query = $this->db->get_where($this->tableAs,$where);
		if($isTotal==0){
			$data = $query->result_array();
			foreach($data as $key => $value){
				if ($value['participants']) {
					$where_in = ' and id_auth_user in ('.$value['participants'].')';
					$data[$key]['list_participants'] = selectlist2_meeting(array('table'=>'auth_user','no_title'=>1,'name'=>'company','email'=>'email', 'id'=> 'id_auth_user', 'where' => 'id_auth_user_grup = 4 and id_ref_user_category in (2)'.$where_in));
				} else {
					$data[$key]['list_participants'] = '';
				}
				$schedule = iso_date($value['start_date']);
				// $schedule .= ($value['start_time'] && $value['end_time']) ? " From ". $value['start_time'] . " To " . $value['end_time'] : ' For All Day';
				$data[$key]['schedule_date'] = $schedule;
				$data[$key]['start_date'] = iso_date($value['start_date']);
			}
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

	function findById($id){
		$where['a.id'] = $id;
		$where['is_delete'] = 0;
		return 	$this->db->get_where($this->table.' a',$where)->row_array();
	}

	function findBy($where,$is_single_row=0){
		$where['a.is_delete'] = 0;
		$where['a.id_status_publish'] = 2;
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

