<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum_category extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->model('Forum_category_model');
	}

	function index(){
		render('apps/forum_category/index',$data,'apps');
	}

	function add($id=''){
		if($id){
			$data = $this->Forum_category_model->findById($id);
			 
			if(!$data){
				die('404');
			}
			$data 			= quote_form($data);
			$data['judul']	= 'Edit';
			$data['proses']	= 'Update';
		}
		else{
			$data['judul']		= 'Add';
			$data['proses']		= 'Save';
			$data['id']			= '';
			$data['name'] 		= '';
			$data['uri_path'] 	= '';
			$data['description']             = '';
		}

		render('apps/forum_category/add',$data,'apps');
	}

	function records(){
		$data = $this->Forum_category_model->records();
		render('apps/forum_category/records',$data,'blank');
	}

	function proses($idedit=''){
		$this->layout 			= 'none';
		$post 					= purify($this->input->post());
		$ret['error']			= 1;
		$where['a.uri_path'] 	= $post['uri_path'];

		if($idedit){
			$where['a.id !=']	= $idedit;
		}

		$unik = $this->Forum_category_model->findBy($where);
		$this->form_validation->set_rules('uri_path', '"URI Path"', 'required'); 
		$this->form_validation->set_rules('name', '"Name"', 'required'); 
		if($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		}
		else if($unik){
			$ret['message']	= "Page URL $post[uri_path] already taken";
		}
		else{   
			$this->db->trans_start();   
			if($idedit){
				auth_update();
				$ret['message'] = 'Update Success';
				$act			= "Update Category of Forum";
				$this->Forum_category_model->update($post,$idedit);
			}
			else{
				auth_insert();
				$ret['message'] = 'Insert Success';
				$act			= "Insert Category of Forum";
				$this->Forum_category_model->insert($post);
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
		$this->Forum_category_model->delete($id);
		detail_log();
		insert_log("Delete Category of Forum");
	}
	
}

/* End of file Forum_category.php */
/* Location: ./application/controllers/apps/Forum_category.php */