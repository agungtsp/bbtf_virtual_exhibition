<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Member_model');
	}
	function index(){
		$data['list_user_group'] 		= selectlist2(array('table'=>'auth_user_grup','id'=>'id_auth_user_grup','name'=>'grup','title'=>'All User Group', 'where' => 'id_auth_user_grup in (3, 4)'));
		render('apps/member/index',$data,'apps');
	}
	public function add($id=''){
		if($id){
			$data = $this->Member_model->findById($id);
			if(!$data){
				die('404');
			}
			$data['judul']        = 'Edit';
			$data['proses']       = 'Update';
			$data                 = quote_form($data);
			$data['is_edit']      = '';
			$data['is_show_pass'] = 'hidden';
			$data['birthdate']    = iso_date_custom_format($data['birthdate'],'d-m-Y');
		}
		else{
			$data['judul']              = 'Add';
			$data['proses']             = 'Save';
			$data['userid']             = '';
			$data['full_name']           = '';
			$data['email']              = '';
			$data['password']           = '';
			$data['phone']              = '';
			$data['is_edit']            = 'hidden';
			$data['is_show_pass']       = '';
			$data['id_auth_user']       = '';
			$data['gender']             = '';
			$data['postal_code']        = '';
			$data['address']            = '';
			$data['birthdate']          = '';
			$data['kode_ref_provinsi'] = '';
			$data['kode_ref_kabupaten'] = '';
			$data['kode_ref_kecamatan'] = '';
			$data['kode_ref_kelurahan'] = '';
		}

		$data['checked_male']      = $data['gender'] == 'M' ? 'checked' : '';
		$data['checked_female']    = $data['gender'] == 'F' ? 'checked' : '';

		$data['list_user_group'] 		= selectlist2(array('table'=>'auth_user_grup','id'=>'id_auth_user_grup','name'=>'grup','title'=>'All User Group','selected'=>$data['id_auth_user_grup'], 'where' => 'id_auth_user_grup in (3, 4)'));
		$data['list_provinsi'] = selectlist2(array('table'=>'ref_provinsi',
			'title'=>'Pilih Provinsi',
			'id'=>'kode_provinsi',
			'name'=>'provinsi',
			'order'=>'id_provinsi',
			'selected'=>$data['kode_ref_provinsi'],
			'is_delete'=>0)
		);
		load_js('user.js','assets/js/modules/user');

		render('apps/member/add',$data,'apps');
	}
	public function view($id=''){
		if($id){
			$data = $this->Member_model->findById($id);
			$data['img_thumb'] = image($data['img'],'small');
			$data['img_ori'] =image($data['img'],'ori'); 
			if(!$data){
				die('404');
			}
			$data['page_name'] = quote_form($data['page_name']);
			$data['teaser'] = quote_form($data['teaser']);
		}
		render('apps/member/view',$data,'apps');
	}
	function records(){
		$where = 'a.id_auth_user_grup in (3, 4)';
		$data = $this->Member_model->records($where);
		foreach ($data['data'] as $key => $value) {
			// $data['data'][$key]['name'] = quote_form($value['name']);
			$data['data'][$key]['publish_date'] = iso_date($value['publish_date']);
			$data['data'][$key]['banned_title'] = ($value['is_banned']==1) ? 'Aktifkan User' : 'Nonaktifkan User';
		}
		render('apps/member/records',$data,'blank');
	}	
	
	function proses($idedit=''){
		$this->layout 			= 'none';
		$post 					= purify(null_empty($this->input->post()));
		$ret['error']			= 1;

		$grp = $post['id_auth_user_grup'];
		if (empty($post['userpass'])) {
			unset($post['userpass']) ;
		}
		else {
			$post['userpass'] = md5($post['userpass']);
		}
		
		$this->form_validation->set_rules('userid', '"User ID"', 'required'); 
		$this->form_validation->set_rules('full_name', '"User Name"', 'required'); 
		$this->form_validation->set_rules('email', '"Email"', 'required'); 
		$this->form_validation->set_rules('phone', '"Phone"', 'required'); 
		$this->form_validation->set_rules('id_auth_user_grup', '"User Group"', 'required'); 
		$this->form_validation->set_rules('gender', '"Jenis Kelamin"', 'trim|required');
		$this->form_validation->set_rules('birthdate', '"Tanggal Lahir"', 'trim|required');

		if ($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		}
		else{   
			$this->db->trans_start();   
			$where 				= ($idedit) ? "and id_auth_user not in ($idedit)" : '';
			$cek_code           = db_get_one('auth_user',"userid","(userid = '$post[userid]' or email = '$post[email]') and is_delete = 0 $where");
			if($cek_code){
				$ret['error'] = 1;
				$ret['message'] =  " Account Name $post[userid] already exsist";
			} else {
				$post['birthdate'] = iso_date_custom_format($post['birthdate'],'Y-m-d');
				
				if($idedit){
					$current_group = db_get_one('auth_user',"id_auth_user_grup",["id_auth_user" => $idedit]);
					auth_update();
					$ret['message'] = 'Update Success';
					$act			= "Update User Management";
					$this->Member_model->update($post,$idedit);

					// send notification
					if ($current_group != $post['id_auth_user_grup'])
					{
						if ($post['id_auth_user_grup'] == 4)
						{
							$notif['title'] = 'Akun terverifikasi';
							$notif['content'] = 'Selamat! Akun Anda telah diverifikasi';
							send_notification($idedit, $notif, id_user());
						}
					}
				}
				else{
					auth_insert();
					$ret['message'] = 'Insert Success';
					$act			= "Insert User Management";
					$this->Member_model->insert($post);
				}

				detail_log();
				insert_log($act);
				$this->db->trans_complete();
				$ret['error'] = 0;
			}
			set_flash_session('message',$ret['message']);
		}
		echo json_encode($ret);
	}
	function del(){
		auth_delete();
		$id = $this->input->post('iddel');
		$this->Member_model->delete($id);
		detail_log();
		insert_log("Delete User Management");
	}

	function get_kabupaten(){
		$post 	= $this->input->get();
		$kd_provinsi_wilayah = $post['code'];
		$data['list_data'] = selectlist2(array(
			'table'=>'ref_kabupaten',
			'title'=>'Pilih Kabupaten/Kota',
			'id'=>'kode_kabupaten',
			'name'=>'kabupatenkota',
			'selected'=>$post['selected'],
			'where'=>array(
				'kode_provinsi'=>$kd_provinsi_wilayah),
			'is_delete'=>0
			)
		);
		echo json_encode($data);
	}

	function get_kecamatan(){
		$post 	= $this->input->get();
		$kd_kabupaten_wilayah = $post['code'];
		$data['list_data'] = selectlist2(array(
			'table'=>'ref_kecamatan',
			'title'=>'Pilih Kecamatan',
			'id'=>'kode_kecamatan',
			'name'=>'kecamatan',
			'selected'=>$post['selected'],
			'where'=>array(
				'kode_kabupaten'=>$kd_kabupaten_wilayah)
			)
		);
		echo json_encode($data);
	}

	function get_kelurahan(){
		$post 	= $this->input->get();
		$kd_kelurahan = $post['code'];
		$data['list_data'] = selectlist2(array(
			'table'=>'ref_kelurahan',
			'title'=>'Pilih Kelurahan',
			'id'=>'kode_kelurahan',
			'name'=>'kelurahan',
			'selected'=>$post['selected'],
			'where'=>array(
				'kode_kecamatan'=>$kd_kelurahan)
			)
		);
		echo json_encode($data);
	}
}

/* End of file frontend_menu.php */
/* Location: ./application/controllers/apps/frontend_menu.php */