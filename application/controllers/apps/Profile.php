<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Profile extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->layout = 'none';
		
	}
	function index(){
		$this->load->model('Model_user');
		$this->data['disabled'] = 'disabled';

		$user    = $this->data['id_auth_user'];
		$dt_user = $this->db->get_where('auth_user', "id_auth_user = '$user'")->row_array();
		
		$this->data['userid']      = $dt_user['userid'];
		$this->data['full_name']    = $dt_user['full_name'];
		$this->data['grup_select'] = selectlist2(array('table'=>'auth_user_grup','id'=>'id_auth_user_grup','name'=>'grup','selected'=>$dt_user['id_auth_user_grup']));
		$this->data['email']       = $dt_user['email'];
		$this->data['phone']       = $dt_user['phone'];
		
		$data['checked_male']      = $dt_user['gender'] == 'M' ? 'checked' : '';
		$data['checked_female']    = $dt_user['gender'] == 'F' ? 'checked' : '';

		$data['postal_code']        = $dt_user['postal_code'];
		$data['address']            = $dt_user['address'];
		$data['birthdate']          = iso_date_custom_format($dt_user['birthdate'],'d-m-Y');
		
		load_js('profile.js','assets/js/modules/profile');

		render('apps/system/profile',$data,'apps');
	}
	
	function proses(){
		$post  = purify($this->input->post());
		$id    = $post['id_auth_user'];
		$email = $post['email'];
        
        $this->form_validation->set_rules('full_name', '"Nama Lengkap"', 'trim|required'); 
        // $this->form_validation->set_rules('email', '"Email"', 'trim|required'); 
        $this->form_validation->set_rules('gender', '"Jenis Kelamin"', 'trim|required');
        $this->form_validation->set_rules('phone', '"No. Telepon"', 'trim|required'); 
        $this->form_validation->set_rules('postal_code', '"Kode Pos"', 'trim|required');

		unset ($post['id_auth_user']);
		
		if ($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		} else {
			$where 				= ($id) ? "and id_auth_user not in ($id)" : '';
			$cek_code           = db_get_one('auth_user',"userid","(userid = '$post[userid]') and is_delete = 0 $where");
			if($cek_code){
				$ret['error'] = 1;
				$ret['message'] =  " Account Name $post[userid] already exsist";
			} else {
	            $post['birthdate'] = iso_date_custom_format($post['birthdate'],'Y-m-d');
	            unset($post['email']);
				$this->db->update('auth_user',$post, "id_auth_user = '$id'");
				detail_log();
				insert_log('Update Profil Pengguna');
				$ret['error'] = 0;
				$ret['message'] =  " Update Success";
			}
		}
		echo json_encode($ret);
	}

}

