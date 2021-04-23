<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_article extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->model('Category_article_model');
		$this->load->model('Tagsmodel');
	}

	function index(){
		render('apps/category_article/index',$data,'apps');
	}

	function add($id=''){
		if($id){
			$data = $this->Category_article_model->findById($id);
			 
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
			$data['teaser']             = '';
			$data['page_content']       = '';
			$data['seo_title']          = '';
			$data['meta_description']   = '';
			$data['meta_keywords']      = '';
		}

		render('apps/category_article/add',$data,'apps');
	}

	function records(){
		$data = $this->Category_article_model->records();
		render('apps/category_article/records',$data,'blank');
	}

	function proses($idedit=''){
		$this->layout 			= 'none';
		$post 					= purify($this->input->post());
		$ret['error']			= 1;
		$where['a.uri_path'] 	= $post['uri_path'];

		if($idedit){
			$where['a.id !=']	= $idedit;
		}

		$unik = $this->Category_article_model->findBy($where);
		$this->form_validation->set_rules('name', '"Name"', 'required'); 
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
				$act			= "Update Category of Article";
				$this->Category_article_model->update($post,$idedit);
			}
			else{
				auth_insert();
				$ret['message'] = 'Insert Success';
				$act			= "Insert Category of Article";
				$this->Category_article_model->insert($post);
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
		$this->Category_article_model->delete($id);
		detail_log();
		insert_log("Delete Category of Article");
	}
	
}

/* End of file Category_article.php */
/* Location: ./application/controllers/apps/Category_article.php */