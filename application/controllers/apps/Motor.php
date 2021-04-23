<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Motor extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Motor_model');
	}
	function index(){
		$data['list_motor_merek'] = selectlist2(array('table'=>'motor_merek','title'=>'Select Merek','selected'=>$data['id_motor_merek'],'where'=>array('id_status_publish'=>2)));
		$data['list_motor_jenis'] = selectlist2(array('table'=>'motor_jenis','title'=>'Select Jenis','selected'=>$data['id_motor_jenis'],'where'=>array('id_status_publish'=>2)));
		$data['list_motor_tipe'] = selectlist2(array('table'=>'motor_tipe','title'=>'Select Tipe','selected'=>$data['id_motor_tipe'],'where'=>array('id_status_publish'=>2)));
		$data['list_motor_model'] = selectlist2(array('table'=>'motor_model','title'=>'Select Model','selected'=>$data['id_motor_model'],'where'=>array('id_status_publish'=>2)));
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));
		render('apps/motor/index',$data,'apps');
	}

	public function add($id=''){
		if($id){
			$data = $this->Motor_model->findById($id);
			if(!$data){
				die('404');
			}
			$data['judul']	= 'Edit';
			$data['proses']	= 'Update';
			$data = quote_form($data);
		} else {
			$data['last_edited'] = '';
			$data['last_edited_show'] = 'invis';
			$data['judul']			= 'Add';
			$data['proses']			= 'Simpan';
			$data['news_title']		= '';
			$data['uri_path']		= '';
			$data['teaser']			= '';
			$data['page_content']	= '';
			$data['id'] 			= '';
			$data['seo_title']			= '';
			$data['meta_description']	= '';
			$data['meta_keywords']		= '';
		}
		$img_thumb				= image($data['img'],'small');
		$imagemanager				= imagemanager('img',$img_thumb);
		$data['img']				= $imagemanager['browse'];
		$data['imagemanager_config']= $imagemanager['config'];

		$data['list_motor_merek'] = selectlist2(array('table'=>'motor_merek','title'=>'Select Merek','selected'=>$data['id_motor_merek'],'where'=>array('id_status_publish'=>2)));
		$data['list_motor_jenis'] = selectlist2(array('table'=>'motor_jenis','title'=>'Select Jenis','selected'=>$data['id_motor_jenis'],'where'=>array('id_status_publish'=>2)));
		$data['list_motor_tipe'] = selectlist2(array('table'=>'motor_tipe','title'=>'Select Tipe','selected'=>$data['id_motor_tipe'],'where'=>array('id_status_publish'=>2)));
		$data['list_motor_model'] = selectlist2(array('table'=>'motor_model','title'=>'Select Model','selected'=>$data['id_motor_model'],'where'=>array('id_status_publish'=>2)));
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'Select Status','selected'=>$data['id_status_publish']));
		$next_approval = $this->Motor_model->approvalLevelGroup + 1;
		render('apps/motor/add',$data,'apps');
	}

	function records(){
		$data = $this->Motor_model->records();
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['name'] 		= quote_form($value['name']);
			$data['data'][$key]['modify_date'] 	= iso_date($value['modify_date']);
		}
		render('apps/motor/records',$data,'blank');
	}

	function proses($idedit=''){
		$id_user =  id_user();
		$this->layout 			= 'none';
		$post 					= purify($this->input->post());
		$ret['error']			= 1;
		$where['a.uri_path']		= $post['uri_path'];
		if($idedit){
			$where['a.id !=']	= $idedit;
		}
		$unik 					= $this->Motor_model->findBy($where);
		$this->form_validation->set_rules('id_motor_merek', '"Merek"', 'required'); 
		$this->form_validation->set_rules('id_motor_model', '"Model"', 'required'); 
		$this->form_validation->set_rules('id_motor_jenis', '"Jenis"', 'required'); 
		$this->form_validation->set_rules('id_motor_tipe', '"Tipe"', 'required'); 
		$this->form_validation->set_rules('name', '"Nama"', 'required'); 
		$this->form_validation->set_rules('uri_path', '"Page URL"', 'required'); 
		$this->form_validation->set_rules('id_status_publish', '"Status"', 'required'); 
		$this->form_validation->set_rules('teaser', '"Teaser"', 'required'); 
		$this->form_validation->set_rules('page_content', '"Content"', 'required'); 
		if ($this->form_validation->run() == FALSE){
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
				$act			= "Update Motor";
				if(!$post['img']){
					unset($post['img']);
				}
				$this->Motor_model->update($post,$idedit);
			}
			else{
				auth_insert();
				$ret['message'] = 'Insert Success';
				$act			= "Insert Motor";
				$idedit = $this->Motor_model->insert($post);
			}
			detail_log();
			$news_data = $this->Motor_model->findById($idedit);
			
			insert_log($act);
			$this->db->trans_complete();
			$this->session->set_flashdata('message',$ret['message']);
			$ret['error'] = 0;
		}
		echo json_encode($ret);
	}
	function del(){
		auth_delete();
		$this->db->trans_start();   
		$id = $this->input->post('iddel');
		$this->Motor_model->delete($id);
		detail_log();
		insert_log("Delete Motor");
		$this->db->trans_complete();
	}
}

/* End of file Motor.php */
/* Location: ./application/controllers/apps/Motor.php */