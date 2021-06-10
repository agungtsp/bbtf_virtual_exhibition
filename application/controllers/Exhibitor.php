<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Exhibitor extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Ref_exhibitor_category_model');
		$this->load->model('Exhibitor_model');
		$this->load->model('Member_model');
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
		$member = $this->Member_model->findBy(array('a.exhibitor_id'=>$data['id']), 1);
		if($data){
			$data['page_name']         = $data['name'];
			$data['logo_url'] = image($data['logo'],'small');
			$data['booth_design_url'] = image($data['booth_design'],'small');
			$data['list_company_profile'] = get_upload_multifile($data['id'], $data['company_profile'], 0);
			$data['list_company_profile2'] = $data['list_company_profile'];
			$data['list_form_exhibitor'] = get_upload_multifile($data['id'], $data['form_exhibitor'], 0);
			$data['list_poster_product'] = get_upload_multifile($data['id'], $data['poster_product'], 0);
			$data['list_video_product'] = get_upload_multifile($data['id'], $data['video_product'], 0);
			$data['list_brochure_product'] = get_upload_multifile($data['id'], $data['brochure_product'], 0);
			$data['list_brochure_product2'] = $data['list_brochure_product'];
			$data['admin_name'] = $member['full_name'];
			// echo "<pre>";
			// print_r($data);exit();

			$this->Exhibitor_model->update(array('page_hit' => (int)$data['page_hit']+=1), $data['id']);
			render('exhibitor_detail_'.$category,$data);
		} else {
			redirect("404");
		}
	}

	function compress_image($date){
		$this->layout 			= 'none';
		$this->load->library('tinypng', array('api_key' => 'zYXMqss9wqF29hfZ2ZRFNqnSPn7mWtRR'));
		$data = $this->Exhibitor_model->findBy(array("date_create >=" => $date));
		foreach($data as $data_key => $data_value){
			// $this->tinypng->fileCompress(UPLOAD_DIR.'large/'.$data_value['logo'], UPLOAD_DIR.'small/'.$data_value['logo']);
			// $this->tinypng->fileCompress(UPLOAD_DIR.'large/'.$data_value['booth_design'], UPLOAD_DIR.'small/'.$data_value['booth_design']);
			$list_poster_product = get_upload_multifile($data_value['id'], $data_value['poster_product'], 0);
			foreach($list_poster_product as $poster_key => $poster_value){
				$ext = strtolower(end(explode('.',$poster_value['name'])));
				$fname = str_replace(".".$ext, '', $poster_value['name']);
				// echo UPLOAD_DIR.'../../uploads/'.$data_value['id'].'/'. $poster_value['name']."<br>";
				// echo UPLOAD_DIR.'../../uploads/'.$data_value['id'].'/'.$fname.'.'.$ext."<br>";
				$this->tinypng->fileCompress(UPLOAD_DIR.'../../uploads/'.$data_value['id'].'/'. $poster_value['name'], UPLOAD_DIR.'../../uploads/'.$data_value['id'].'/'.$fname.'.'.$ext);
			}
		}
		exit();
		exit();

		exit();
	}
}