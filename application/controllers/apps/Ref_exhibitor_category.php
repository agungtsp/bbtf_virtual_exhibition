<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_exhibitor_category extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Ref_exhibitor_category_model');
	}
	function index(){
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));
		render('apps/ref_exhibitor_category/index',$data,'apps');
	}
	public function add($id=''){
		if($id){
			$data = $this->Ref_exhibitor_category_model->findById($id);
           
			if(!$data){
				die('404');
			}
			$data['judul']  = 'Edit';
			$data['proses'] = 'Update';
			$data['id']     = $id;
		}
		else{
			$data['last_edited']                  = '';
			$data['last_edited_show']             = 'invis';
			$data['judul']                        = 'Add';
			$data['proses']                       = 'Simpan';
			$data['teaser']                       = '';
			$data['id']     						= '';
			$data['name']                         = '';
			$data['uri_path']                         = '';
		}
		$logo         = image($data['logo'],'large');
		$data['logo'] = imagemanager2('logo',$logo,'','logo',$data['logo']);
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));
		render('apps/ref_exhibitor_category/add',$data,'apps');
	}
	function records(){
		$data = $this->Ref_exhibitor_category_model->records();
		render('apps/ref_exhibitor_category/records',$data,'blank');
	}	
	
	function proses($idedit=''){
		$this->layout 			= 'none';
		$post 					= purify(null_empty($this->input->post()));
		$ins_id 				= '';

		$this->form_validation->set_rules('name', '"name"', 'required'); 
		$this->form_validation->set_rules('id_status_publish', '"status"', 'required');
		$max_size = MAX_UPLOAD_SIZE;
		$current_logo = $this->Ref_exhibitor_category_model->findById($idedit)['logo'];
		if(empty($_FILES['logo']) && !empty(trim($post['logo'])) && trim($post['logo']) == trim($current_logo)){
			$upload['file_name'] = trim($current_logo);
		} else if($_FILES['logo']) {
			$upload = upload_file('logo','large','jpg|png|jpeg',$max_size,UPLOAD_DIR,date('YmdHis'));
		} else {
			$upload = 'Logo is required!';
		}
		$ret['error'] = 1;
		if ($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		}else if(!isset($upload['file_name']) && empty($upload['file_name'])){
			$ret['message']  = $upload;
		}
		else{   
			$this->db->trans_start();   
			$post['logo'] = $upload['file_name'];
			if($idedit){
				auth_update();
				$ret['message'] = 'Update Success';
				$act			= "Update User Category Exhibitor";
				$this->Ref_exhibitor_category_model->update($post,$idedit);
			} else {
				auth_insert();
				$ret['message'] = 'Insert Success';
				$act            = "Insert User Category Exhibitor";
				$xDat           = $this->Ref_exhibitor_category_model->insert($post);
			}
			detail_log();
			insert_log($act);
			$this->db->trans_complete();
			$this->session->set_flashdata('message',$ret['message']);
			$ret['error'] = 0;
		}
		echo json_encode($ret);
	}
	function del(){
		auth_delete();
		$id = $this->input->post('iddel');
		$this->Ref_exhibitor_category_model->delete($id);
		detail_log();
		insert_log("Delete User Category Exhibitor");
	}
}

/* End of file Ref_exhibitor_category.php */
/* Location: ./application/controllers/apps/Ref_exhibitor_category.php */