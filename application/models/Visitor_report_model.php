<?php

class Visitor_report_model extends  CI_Model{

	var $table = 'exhibitor';
	var $tableAs = 'exhibitor a';
    function __construct(){
       parent::__construct();
    }

	function records($where=array(),$isTotal=0){
		$alias['search_name'] = 'a.name';
		query_grid($alias,$isTotal);
		$this->db->select("a.*");
		$query = $this->db->get_where($this->tableAs,$where);
		if($isTotal==0){
			$data = $query->result_array();
			foreach($data as $key => $value){
				$asd = $this->db->order_by("id", "desc")->get_where("page_hits","url like '%".strtolower($value['uri_path'])."%'")->row_array();
				$count = $this->db->get_where("page_hits","url like '%".strtolower($value['uri_path'])."%' and user_create_id is not null")->num_rows();
				$data[$key]['url'] = ($asd) ? $asd['url'] : "";
				$data[$key]['count'] = ($count) ? $count : "";
			}
		} else {
			return $query->num_rows();
		}
		$ttl_row = $this->records($where,1);
		return ddi_grid($data,$ttl_row);
	}

	function records_view($where=array(),$isTotal=0,$id=''){
		query_grid($alias,$isTotal);
		$this->db->select("a.*, b.*");
		$this->db->join('auth_user b','b.id_auth_user = a.user_create_id');
		$query = $this->db->get_where("page_hits a","url like '%".strtolower($where)."%'");
		if($isTotal==0){
			$data = $query->result_array();
			foreach($data as $key => $value){
				$country = db_get_one('ref_country','name',array('id'=>$value['id_ref_country']));
				$data[$key]['country'] = ($country) ? $country : "";
			}
		} else {
			return $query->num_rows();
		}
		
		$ttl_row = $this->records_view($where,1);
		return ddi_grid($data,$ttl_row,5,$id);
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

