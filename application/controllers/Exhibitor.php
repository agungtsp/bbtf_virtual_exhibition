<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Exhibitor extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Ref_exhibitor_category_model');
		$this->load->model('Exhibitor_model');
	}
	function index(){
		$data['active_exhibitor'] = "active";
		$data['page_name']         = "Exhibitor";

		$data['list_exhibitor_platinum'] = $this->Exhibitor_model->findBy(array('id_exhibitor_category'=>3));
		$data['list_exhibitor_silver']   = $this->Exhibitor_model->findBy(array('id_exhibitor_category'=>1));
		$data['list_exhibitor_gold']     = $this->Exhibitor_model->findBy(array('id_exhibitor_category'=>2));
		// print_r($data);exit();
		render('exhibitor',$data);
	}

	function detail($category, $url){
		$data = $this->Exhibitor_model->findBy(array('a.uri_path'=>$url), 1);
		$data['active_exhibitor'] = "active";
		if($data){
			$data['page_name']         = $data['name'];
			$data['logo_url'] = image($data['logo'],'large');
			$data['booth_design_url'] = image($data['booth_design'],'large');
			$data['list_company_profile'] = get_upload_multifile($data['id'], $data['company_profile'], 0);
			$data['list_company_profile2'] = $data['list_company_profile'];
			$data['list_form_exhibitor'] = get_upload_multifile($data['id'], $data['form_exhibitor'], 0);
			$data['list_poster_product'] = get_upload_multifile($data['id'], $data['poster_product'], 0);
			$data['list_video_product'] = get_upload_multifile($data['id'], $data['video_product'], 0);
			$data['list_brochure_product'] = get_upload_multifile($data['id'], $data['brochure_product'], 0);
			$data['list_brochure_product2'] = $data['list_brochure_product'];

			$this->Exhibitor_model->update(array('page_hit' => (int)$data['page_hit']+=1), $data['id']);
			render('exhibitor_detail_'.$category,$data);
		} else {
			redirect("404");
		}
	}
}