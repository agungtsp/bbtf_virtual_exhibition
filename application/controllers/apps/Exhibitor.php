<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exhibitor extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Exhibitor_model');
		$this->load->model('Tagsmodel');
	}
	function index(){
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));
		$data['list_ref_exhibitor_category'] = selectlist2(array('table'=>'ref_exhibitor_category','title'=>'All Status','selected'=>$data['id_ref_exhibitor_category']));
		render('apps/exhibitor/index',$data,'apps');
	}
	public function add($id=''){
		if($id){
			
			$data = $this->Exhibitor_model->findById($id);
			if(!$data){
				die('404');
			}
		
			$data['judul']        = 'Edit';
			$data['proses']       = 'Update';
			$data['company_profile'] = get_upload_multifile($id, $data['company_profile']);
			$data['form_exhibitor'] = get_upload_multifile($id, $data['form_exhibitor']);
			$data['poster_product'] = get_upload_multifile($id, $data['poster_product']);
			$data['video_product'] = get_upload_multifile($id, $data['video_product']);
			$data['brochure_product'] = get_upload_multifile($id, $data['brochure_product']);
			// $data['publish_date'] = iso_date($data['publish_date']);
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
			// $data['publish_date']     = date('d-m-Y');
			$data['company_profile']      = [];
			$data['form_exhibitor']      = [];
			$data['poster_product']      = [];
			$data['video_product']      = [];
			$data['brochure_product']      = [];
		}

		
		$logo         = image($data['logo'],'large');
		$data['logo'] = imagemanager2('logo',$logo,'','logo',$data['logo']);
		$booth_design         = image($data['booth_design'],'large');
		$data['booth_design'] = imagemanager2('booth_design',$booth_design,'','booth_design',$data['booth_design']);
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','selected'=>$data['id_status_publish']));
		$data['list_category']       = selectlist2(array('table'=>'ref_exhibitor_category','selected'=>$data['id_exhibitor_category']));
		render('apps/exhibitor/add',$data,'apps');
	}
	public function view($id=''){
		if($id){
			$data = $this->Exhibitor_model->findById($id);
			$data['img_thumb'] = image($data['img'],'small');
			$data['img_ori'] =image($data['img'],'ori'); 
			if(!$data){
				die('404');
			}
			$data['page_name'] = quote_form($data['page_name']);
			$data['teaser'] = quote_form($data['teaser']);
		}
		render('apps/exhibitor/view',$data,'apps');
	}
	function records(){
		$data = $this->Exhibitor_model->records();
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['link_preview'] = base_url('exhibitor/'.strtolower($value['category_name']).'/'.$value['uri_path']);
			$data['data'][$key]['show_link_preview'] = ($value['id_status_publish']==1) ? "hidden" : "";
		}
		render('apps/exhibitor/records',$data,'blank');
	}	
	
	
	function proses($idedit=''){
		
		$this->layout 			= 'none';
		$post 					= null_empty($this->input->post());

		$this->form_validation->set_rules('name', '"name"', 'required'); 
		$this->form_validation->set_rules('id_status_publish', '"status"', 'required');
		$max_size = MAX_UPLOAD_SIZE;
		$current_logo = $this->Exhibitor_model->findById($idedit)['logo'];
		if(empty($_FILES['logo']) && !empty(trim($post['logo'])) && trim($post['logo']) == trim($current_logo)){
			$upload['file_name'] = trim($current_logo);
		} else if($_FILES['logo']) {
			$upload = upload_file('logo','large','jpg|png|jpeg',$max_size,UPLOAD_DIR,date('YmdHis'));
		} else {
			$upload = 'Logo is required!';
		}

		$current_booth_design = $this->Exhibitor_model->findById($idedit)['booth_design'];
		if(empty($_FILES['booth_design']) && !empty(trim($post['booth_design'])) && trim($post['booth_design']) == trim($current_booth_design)){
			$booth_design['file_name'] = trim($current_booth_design);
		} else if($_FILES['booth_design']) {
			$booth_design = upload_file('booth_design','large','jpg|png|jpeg',$max_size,UPLOAD_DIR,date('YmdHis'));
		} else {
			$booth_design = 'Booth is required!';
		}

		$where['a.uri_path']		= $post['uri_path'];
		if($idedit){
			$where['a.id !=']		= $idedit;
		}
		$unik 					= $this->Exhibitor_model->findBy($where);
		$ret['error'] = 1;
		if ($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		// }else if(!isset($upload['file_name']) && empty($upload['file_name'])){
			// $ret['message']  = $upload;
		// }else if(!isset($booth_design['file_name']) && empty($booth_design['file_name'])){
			// $ret['message']  = $booth_design;
		}else if($unik){
			$ret['message']	= "Page URL $post[uri_path] already taken";
		}
		else{   
			$this->db->trans_start();  
			unset(
				$post['jfiler-items-exclude-company_profile-0'],
				$post['jfiler-items-exclude-form_exhibitor-0'],
				$post['jfiler-items-exclude-poster_product-0'],
				$post['jfiler-items-exclude-video_product-0'],
				$post['jfiler-items-exclude-brochure_product-0'],
			$post['company_profile'], $post['form_exhibitor'], $post['poster_product'], $post['video_product'], $post['brochure_product']);
			// $post['publish_date'] = iso_date($post['publish_date']);
			if(isset($upload['file_name'])){
				$post['logo'] = $upload['file_name'];
			}
			if(isset($booth_design['file_name'])){
				$post['booth_design'] = $booth_design['file_name'];
			}
			if($idedit){
				auth_update();
				$ret['message'] = 'Update Success';
				$act			= "Update Frontend menu";
				$this->Exhibitor_model->update($post,$idedit);
			}
			else{
				auth_insert();
				$ret['message'] = 'Insert Success';
				$act			= "Insert Frontend menu";
				$idedit = $this->Exhibitor_model->insert($post);
			}
			// start multiple upload
			$data_find = $this->Exhibitor_model->findById($idedit);
			$data_update = [];
			$upload_company_profile = upload_multifile('company_profile',$idedit,'*',$max_size,UPLOAD_FILE_DIR);
			if($upload_company_profile){
				$data_update['company_profile'] = ($data_find['company_profile']) ? $data_find['company_profile'].','.$upload_company_profile : $upload_company_profile;
			}
			$upload_form_exhibitor = upload_multifile('form_exhibitor',$idedit,'*',$max_size,UPLOAD_FILE_DIR);
			if($upload_form_exhibitor){
				$data_update['form_exhibitor'] = ($data_find['form_exhibitor']) ? $data_find['form_exhibitor'].','.$upload_form_exhibitor : $upload_form_exhibitor;
			}
			$upload_poster_product = upload_multifile('poster_product',$idedit,'*',$max_size,UPLOAD_FILE_DIR);
			if($upload_poster_product){
				$data_update['poster_product'] = ($data_find['poster_product']) ? $data_find['poster_product'].','.$upload_poster_product : $upload_poster_product;
			}
			$upload_video_product = upload_multifile('video_product',$idedit,'*',$max_size,UPLOAD_FILE_DIR);
			if($upload_video_product){
				$data_update['video_product'] = ($data_find['video_product']) ? $data_find['video_product'].','.$upload_video_product : $upload_video_product;
			}
			$upload_brochure_product = upload_multifile('brochure_product',$idedit,'*',$max_size,UPLOAD_FILE_DIR);
			if($upload_brochure_product){
				$data_update['brochure_product'] = ($data_find['brochure_product']) ? $data_find['brochure_product'].','.$upload_brochure_product : $upload_brochure_product;
			}
			if($data_update){
				$this->Exhibitor_model->update($data_update,$idedit);
			}
			// end multiple upload 
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
		$this->Exhibitor_model->delete($id);
		detail_log();
		insert_log("Delete Frontend menu");
	}

	function get_callback($id){
		echo db_get_one('module','callback',array('id'=>$id));
	}

	function remove_file(){
		$post = null_empty($this->input->post());
		$data = $this->Exhibitor_model->findById($post['id']);
		if(!$data){
			die('404');
		}
		$data_file = explode(',', $data[$post['type']]);
		if($data_file){
			foreach($data_file as $key => $value){
				if($value==$post['file']){
					unset($data_file[$key]);
				}
			}
		}
		$data_update[$post['type']] = implode(',', $data_file);
		$this->Exhibitor_model->update($data_update,$post['id']);
	}
}

/* End of file Exhibitor.php */
/* Location: ./application/controllers/apps/Exhibitor.php */