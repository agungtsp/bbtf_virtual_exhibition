<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Exhibition extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Ref_exhibitor_category_model');
		$this->load->model('Exhibitor_model');
	}
	function index($category){
		$category = $this->Ref_exhibitor_category_model->findBy(array('uri_path'=>$category), 1);
		if($category){
			$data['active_exhibition'] = "active";
			$data['page_name']         = "Exhibition ". $category['name'];
			$data['logo_category_url'] = image($category['logo'],'large');
			$data['category_url'] = $category['uri_path'];
			$data['list_exhibitor'] = $this->Exhibitor_model->findBy(array('id_exhibitor_category'=>$category['id']));
			render('exhibitor_list',$data);
		} else {
			redirect("404");
		}
	}

}