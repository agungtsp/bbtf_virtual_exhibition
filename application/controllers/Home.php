<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Ref_exhibitor_category_model');
	}
    function index(){
		$data['list_exhibitor_category'] = $this->Ref_exhibitor_category_model->records()['data'];
		foreach($data['list_exhibitor_category'] as $key => $value){
			$data['list_exhibitor_category'][$key]['logo_url'] = image($value['logo'],'large');
		}
		$data['page_name']              = "Home";
		$data['active_home']            = "active";
		render("home", $data);
    }
}