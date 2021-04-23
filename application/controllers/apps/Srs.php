<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Srs extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Srs_model');
	}
	function index(){
		render('apps/srs/index',$data,'apps');
	}
	public function add($id=''){
		if($id){
			
			$data = $this->Srs_model->findById($id);
			if(!$data){
				die('404');
			}
		
			$data['judul']        = 'Edit';
			$data['proses']       = 'Update';
			$data['publish_date'] = iso_date($data['publish_date']);
			$data = quote_form($data);
		}
		else{
			$data['judul']            = 'Add';
			$data['proses']           = 'Simpan';
			$data['teaser']           = '';
			$data['page_content']     = '';
			$data['name']             = '';
			$data['id']               = '';
		}
		$imagemanager				= imagemanager('img',$img_thumb);
		$data['img']				= $imagemanager['browse'];
		$data['imagemanager_config']= $imagemanager['config'];
		render('apps/srs/add',$data,'apps');
	}
	
	function records(){
		$data = $this->Srs_model->records();
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['modify_date'] = iso_date($value['modify_date']);
		}
		render('apps/srs/records',$data,'blank');
	}	

	function proses($idedit=''){
		$this->layout 			= 'none';
		$post 					= null_empty($this->input->post());

		$this->form_validation->set_rules('name', '"name"', 'required'); 
		$this->form_validation->set_rules('page_content', '"page_content"', 'required');

		if ($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		} else {   
			$this->db->trans_start();   
			if($idedit){
				auth_update();
				$ret['message'] = 'Update Success';
				$act			= "Update Software Requirement Specifications";
				$this->Srs_model->update($post,$idedit);
			}
			detail_log();
			insert_log($act);
			$this->db->trans_complete();
			$this->session->set_flashdata('message',$ret['message']);
			$ret['error'] = 0;
		}
		echo json_encode($ret);
	}
}

/* End of file Srs.php */
/* Location: ./application/controllers/apps/Srs.php */