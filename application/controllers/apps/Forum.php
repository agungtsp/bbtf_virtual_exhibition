<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->model('Forum_model');
		$this->load->model("Forum_comment_model");
	}

	function index(){
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'Pilih Status','selected'=>$data['id_status_publish']));
		$data['list_forum_category'] = selectlist2(array('table'=>'forum_category','title'=>'Pilih Kategori','selected'=>$data['id_forum_category'],'where'=>array('is_delete'=>0)));
		render('apps/forum/index',$data,'apps');
	}

	function add($id=''){
		if($id){
			$data = $this->Forum_model->findById($id);
			 
			if(!$data){
				die('404');
			}
			$data 			= quote_form($data);
			$data['judul']	= 'Edit';
			$data['proses']	= 'Update';

			$data['list_files'] = [];
			$list_files         = [];
			if ($data['files']) {
				if (strpos($data['files'],',') !== false) {
	                $list_files = explode(',',rtrim($data['files'],','));
	            } else {
	                $list_files[] = $data['files'];
	            }
			}
			if (!empty($list_files)) {
				$numx = 0;
				foreach ($list_files as $key => $value) {
					$data['list_files'][$key]['files_num'] = $numx;
					$data['list_files'][$key]['files_link'] = file_request_features($value);
					$data['list_files'][$key]['files_name'] = $value;
					$numx++;
				}
			}
		}
		else{
			$data['judul']       = 'Add';
			$data['proses']      = 'Save';
			$data['id']          = '';
			$data['name']        = '';
			$data['title']    = '';
			$data['description'] = '';
			$data['list_files']  = array();
		}
		$data['session_id_user'] = id_user();
		$data['list_auth_user'] = selectlist2(array('table'=>'auth_user','title'=>'=== Pilih Pelanggan ===','selected'=>$data['id_auth_user'],'where'=>array('is_delete'=>0, 'id_auth_user_grup'=>4),'id'=>'id_auth_user','name'=>'full_name'));
		$data['list_forum_category'] = selectlist2(array('table'=>'forum_category','title'=>'Pilih Kategori','selected'=>$data['id_forum_category'],'where'=>array('is_delete'=>0)));
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'Pilih Status','selected'=>$data['id_status_publish']));

		render('apps/forum/add',$data,'apps');
	}

	function records(){
		$data = $this->Forum_model->records();
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['create_date'] = iso_date_time($value['create_date']);
			$data['data'][$key]['is_read_admin'] = ($value['is_read_admin']==1) ? "Sudah" : "Belum";
		}
		render('apps/forum/records',$data,'blank');
	}

	function proses($idedit=''){
		$this->layout 			= 'none';
		$post 					= purify($this->input->post());
		$ret['error']			= 1;
		$this->form_validation->set_rules('description', '"Deskripsi"', 'required'); 
		$this->form_validation->set_rules('title', '"Judul"', 'required'); 
		if($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		}
		else{   
			$this->db->trans_start();   
			$max_size = MAX_UPLOAD_SIZE;
			$upload_dir = UPLOAD_REQUEST_FEATURES_DIR;
			$post['files']    = '';
			if (!empty($post['file_saved'])) {
				$post['files'] .= implode(',', $post['file_saved']).',';
			}
			if (!empty($_FILES['file_multi'])) {
				// jpg|png|jpeg|txt|pdf|
				$upload_file_multi = upload_multifile('file_multi','','*',$max_size,$upload_dir,date('YmdHis'));
				foreach ($upload_file_multi as $key => $value) {
					$post['files'] .= $value['file_name'].',';
				}
			}
			unset($post['file_multi'], $post['file_saved']);
			if($idedit){
				auth_update();
				$ret['message'] = 'Update Success';
				$act			= "Update Forum";
				$this->Forum_model->update($post,$idedit);
			}
			else{
				auth_insert();
				$ret['message'] = 'Insert Success';
				$act			= "Insert Forum";
				$this->Forum_model->insert($post);
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
		$this->Forum_model->delete($id);
		detail_log();
		insert_log("Delete Category of Forum");
	}

	function proses_followup($idfollowup=''){
		$this->layout 			= 'none';
		$post 					= purify($this->input->post());
		$ret['error']			= 1;
		$this->form_validation->set_rules('content', '"Balasan"', 'required'); 
		if($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		}
		else{   
			$this->db->trans_start();   
			auth_insert();
			$post['id_forum'] = $idfollowup;
			$data_post['is_read_member']  = 0;
			if($post['id_status_publish']){
				$data_post['id_status_publish'] = $post['id_status_publish'];
			}
			$this->Forum_model->update($data_post,$idfollowup);
			unset($data_post['id_status_publish']);
			$ret['message'] = 'Insert Success';
			$act			= "Insert Comment of Forum";
			$this->Forum_comment_model->insert($post);
			$this->db->trans_complete();
			$this->session->set_flashdata('message',$ret['message']);
			$ret['error'] = 0;
		}
		echo json_encode($ret);
	}


	function view($id=''){
		if($id){
			$data = $this->Forum_model->findById($id);
			$this->Forum_model->update(array("is_read_admin"=>1), $id);
			if(!$data){
				die('404');
			}
			$data 			= quote_form($data);
			$data['judul']	= 'Edit';
			$data['proses']	= 'Update';

			$data['list_files'] = [];
			$list_files         = [];
			if ($data['files']) {
				if (strpos($data['files'],',') !== false) {
	                $list_files = explode(',',rtrim($data['files'],','));
	            } else {
	                $list_files[] = $data['files'];
	            }
			}
			$data['create_date'] = iso_date_time($data['create_date']);
			$data['description'] = html_entity_decode($data['description']);
			if (!empty($list_files)) {
				$numx = 0;
				foreach ($list_files as $key => $value) {
                    $files_link = file_request_features($value);
                    if($files_link){
						$data['list_files'][$key]['files_num'] = $numx;
						$data['list_files'][$key]['files_link'] = $files_link;
						$data['list_files'][$key]['files_name'] = $value;
						$numx++;
					}
				}
			}
			

			$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'Pilih Status'));
			render('apps/forum/view',$data,'apps');
		} else {
			die('404');
		}
	}

	function del_followup(){
		auth_delete();
		$id = $this->input->post('iddel');
		$this->Forum_comment_model->delete($id);
		detail_log();
		insert_log("Delete Comment of Forum");
	}

	function get_data_followup($id_followup){
		$this->db->order_by("create_date", "asc");
		$data['followup'] = $this->Forum_comment_model->findBy(array("id_forum"=>$id_followup));
		foreach ($data['followup'] as $key => $value) {
			$data['followup'][$key]['followup_id'] = $value['id'];
			$data['followup'][$key]['followup_full_name'] = $value['full_name'];
			$data['followup'][$key]['followup_content'] = $value['content'];
			$data['followup'][$key]['followup_create_date'] = iso_date_time($value['create_date']);
			$data['followup'][$key]['followup_status_publish'] = $value['status_publish'];
			$data['followup'][$key]['is_admin_display'] = ($value['id_auth_user_grup']!=4) ? "" : "hidden";
			$data['followup'][$key]['followup_status_publish_display'] = ($value['status_publish']) ? "" : "hidden";
			$data['followup'][$key]['is_admin'] = ($value['id_auth_user_grup']!=4) ? "Admin" : "";
		}
		echo $this->parser->parse('apps/forum/data_comment.html', $data, true);
	}
	function get_data_followup_count($id_followup){
		$this->db->order_by("create_date", "asc");
		$this->layout = "blank";
		$data['total_followup'] = $this->Forum_comment_model->findBy(array("id_forum"=>$id_followup), 2);
		echo json_encode($data);
	}
	
}

/* End of file Forum.php */
/* Location: ./application/controllers/apps/Forum.php */