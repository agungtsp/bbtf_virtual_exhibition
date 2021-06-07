<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Ref_exhibitor_category_model');
		$this->load->model('Member_model');
	}
    function index(){
		$data['list_exhibitor_category'] = $this->Ref_exhibitor_category_model->records()['data'];
		$where = 'id_ref_user_category = 1';
		$dataMember = $this->Member_model->findBy($where);
		foreach($data['list_exhibitor_category'] as $key => $value){
			$data['list_exhibitor_category'][$key]['logo_url'] = image($value['logo'],'large');
		}
		foreach($dataMember as $key2 => $value2){
			$exhibitor = $this->db->get_where('exhibitor', array('id' => $value2['exhibitor_id']))->row_array();
			if($exhibitor){
			$data['list_member'][$key2]['full_name'] = $value2['full_name'];
			$data['list_member'][$key2]['user_avatar'] = image($exhibitor['logo'], 'large');
			}
		}
		$data['page_name']              = "Home";
		$data['active_home']            = "active";
		render("home", $data);
    }
}