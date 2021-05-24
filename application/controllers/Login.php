<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {
	function __construct(){
		parent::__construct();

		$this->load->model('LoginModel');
	}
    function index(){
		$data['active_login_user'] = "active";
		$data['page_name']         = "Login";
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
}