<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Help_model');
		$this->load->model('Tagsmodel');
	}
	function index(){
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));
		render('apps/help/index',$data,'apps');
	}
	public function add($id=''){
		if($id){
			
			$data = $this->Help_model->findById($id);
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
			$data['uri_path']         = '';
			$data['teaser']           = '';
			$data['page_content']     = '';
			$data['class_name']       = '';
			$data['name']             = '';
			$data['img']              = '';
			$data['extra_param']      = '';
			$data['id']               = '';
			$data['teaser']           = '';
			$data['title']            = '';
			$data['image']            = '';
			$data['seo_title']        = '';
			$data['meta_description'] = '';
			$data['meta_keywords']     = '';
			$data['description']      = '';
			$data['publish_date']     = date('d-m-Y');
		}
		
		$imagemanager                = imagemanager('image',$img_thumb);
		$data['image']               = $imagemanager['browse'];
		$data['imagemanager_config'] = $imagemanager['config'];
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','selected'=>$data['id_status_publish']));
		$data['list_category']       = selectlist2(array('table'=>'help_category','selected'=>$data['id_help_category']));
		$data['list_tags']			 = selectlist('tags','id','name',null,explode(',', $data['ref_id_tags']));
		render('apps/help/add',$data,'apps');
	}
	public function view($id=''){
		if($id){
			$data = $this->Help_model->findById($id);
			$data['img_thumb'] = image($data['img'],'small');
			$data['img_ori'] =image($data['img'],'ori'); 
			if(!$data){
				die('404');
			}
			$data['page_name'] = quote_form($data['page_name']);
			$data['teaser'] = quote_form($data['teaser']);
		}
		render('apps/help/view',$data,'apps');
	}
	function records(){
		$data = $this->Help_model->records();
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['publish_date'] = iso_date($value['publish_date']);
		}
		render('apps/help/records',$data,'blank');
	}	
	
	
	function proses($idedit=''){
		$this->layout 			= 'none';
		$post 					= null_empty($this->input->post());
		if ($post['ref_id_tags']) {
			$tag_id = array();
			foreach ($post['ref_id_tags'] as $value) {
				if (is_numeric($value)) { 
					$tag_id[] = $value;
				}
				else{
					$cek = $this->Tagsmodel->fetchRow(array('name'=>strtolower($value)));
					if (!$cek) {
						$tag_id[] = $this->Tagsmodel->insert(array('name'=>$value,'uri_path'=>url_title($value)));
					}
					else{
						$tag_id[] = $cek['id'];
					}
				}
			}
			$post['ref_id_tags'] = implode(',', $tag_id);
		}
		else{
			$post['ref_id_tags'] = '';
		}

		$this->form_validation->set_rules('name', '"name"', 'required'); 
		$this->form_validation->set_rules('id_status_publish', '"status"', 'required');

		if($post['id_help_type'] == 2){
			$this->form_validation->set_rules('extra_param', '"URL"', 'required'); 
		}
		if ($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		}
		$where['uri_path']		= $post['uri_path'];
		if($idedit){
			$where['a.id !=']		= $idedit;
		}
		$unik 					= $this->Help_model->findBy($where);
		if($unik){
			$ret['message']	= "Page URL $post[uri_path] already taken";
		}
		else{   
			$this->db->trans_start();   
				$post['publish_date'] = iso_date($post['publish_date']);
				if($idedit){
					auth_update();
					$ret['message'] = 'Update Success';
					$act			= "Update Frontend menu";
					$this->Help_model->update($post,$idedit);
				}
				else{
					auth_insert();
					$ret['message'] = 'Insert Success';
					$act			= "Insert Frontend menu";
					$this->Help_model->insert($post);
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
		$this->Help_model->delete($id);
		detail_log();
		insert_log("Delete Frontend menu");
	}

	function get_callback($id){
		echo db_get_one('module','callback',array('id'=>$id));
	}
}

/* End of file Help.php */
/* Location: ./application/controllers/apps/Help.php */