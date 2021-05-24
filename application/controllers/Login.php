<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {
	function __construct(){
		parent::__construct();

		$this->load->model('LoginModel');
	}
    function index(){
		$data['active_login_user'] = "active";
		$data['page_name']         = "Login";
		$data['list_ref_country'] 		= selectlist2(array('table'=>'ref_country','title'=>'All Country','selected'=>$data['id_ref_country'], 'where' => array("is_delete" => 0)));
		render('login_user',$data, 'main_login');
	}

	function check_login() {
		$post         = purify($this->input->post());
		$ret['error'] = 1;
		$this->form_validation->set_rules('email', "Email",'trim|required|valid_email');
		$this->form_validation->set_rules('password', "Password",'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$ret['message'] = validation_errors(' ',' ');
		} else {
			$ret = $this->LoginModel->check_login($post['email'], $post['password']);
		}
		
		echo json_encode($ret);
	}

	function forgot_password() {
		$post         = purify($this->input->post());
		$ret['error'] = 1;
		$this->form_validation->set_rules('email', "Email",'trim|required|valid_email');
		if ($this->form_validation->run() == FALSE) {
			$ret['message'] = validation_errors(' ',' ');
		} else {
			$ret = $this->LoginModel->send_password($post['email']);
		}
		
		echo json_encode($ret);
	}

	function signup(){
		$this->load->model('Member_model');
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
		
		$this->form_validation->set_rules('title', '"Title"', 'required'); 
		$this->form_validation->set_rules('full_name', '"Fullname"', 'required'); 
		$this->form_validation->set_rules('email', '"Email"', 'required'); 
		$this->form_validation->set_rules('phone', '"Phone"', 'required'); 
		$this->form_validation->set_rules('city', '"City"', 'required'); 
		$this->form_validation->set_rules('id_ref_country', '"Country"', 'required'); 
		$this->form_validation->set_rules('company', '"Company"', 'required'); 
		$this->form_validation->set_rules('job_title', '"Job"', 'required'); 
		$this->form_validation->set_rules('id_ref_user_category', '"Category"', 'required'); 

		if (!in_array($post['id_ref_user_category'], [3,5,6])){
			$ret['message']  = "Please enter data correctly";
		} else if ($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		}
		else{   
			$this->db->trans_start();   
			$cek_code           = db_get_one('auth_user',"userid","(email = '$post[email]') and is_delete = 0");
			if($cek_code){
				$ret['message'] =  " Email $post[userid] already exsist";
			} else {				
				$ret['message'] = "Your account success to register. Please wait admin to verify your account and you'll get notify by email";
				$post['id_auth_user_grup'] = 3;
				$post['userid'] = $post['email'];
				unset($post['reuserpass']);
				$this->Member_model->insert($post);
				$this->db->trans_complete();
				$ret['error'] = 0;
			}
		}
		echo json_encode($ret);
	}

	function logout(){
		$data['ip'] 		    = $_SERVER['REMOTE_ADDR'];
    	$data['activity']       = "Logout";
    	$data['id_auth_user']   = $this->data['id_auth_user'];
    	$data['log_date'] =  date('Y-m-d H:i:s');
        $this->db->insert('access_log',$data);
        $this->session->sess_destroy();
        $this->load->model('LoginTransactionModel');
        $this->LoginTransactionModel->update($data['id_auth_user'],array('lock_date'=>$data['log_date'],'is_active'=>2),array('ip_address'=>$data['ip']));
        redirect('login');
	}
}