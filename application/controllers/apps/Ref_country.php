<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_country extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Ref_country_model');
	}
	function index(){
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));
		render('apps/ref_country/index',$data,'apps');
	}
	public function add($id=''){
		if($id){
			$data = $this->Ref_country_model->findById($id);
           
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
		}

		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));
		render('apps/ref_country/add',$data,'apps');
	}
	function records(){
		$data = $this->Ref_country_model->records();
		render('apps/ref_country/records',$data,'blank');
	}	
	
	function proses($idedit=''){
		$this->layout 			= 'none';
		$post 					= purify(null_empty($this->input->post()));
		$ins_id 				= '';

		$this->form_validation->set_rules('name', '"name"', 'required'); 
		$this->form_validation->set_rules('code', '"code"', 'required'); 
		$this->form_validation->set_rules('id_status_publish', '"status"', 'required');
		if ($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		}
		else{   
			$this->db->trans_start();   
			if($idedit){
				auth_update();
				$ret['message'] = 'Update Success';
				$act			= "Update Country";
				$this->Ref_country_model->update($post,$idedit);
			} else {
				auth_insert();
				$ret['message'] = 'Insert Success';
				$act            = "Insert Country";
				$xDat           = $this->Ref_country_model->insert($post);
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
		$this->Ref_country_model->delete($id);
		detail_log();
		insert_log("Delete Country");
	}
}

/* End of file Ref_country.php */
/* Location: ./application/controllers/apps/Ref_country.php */