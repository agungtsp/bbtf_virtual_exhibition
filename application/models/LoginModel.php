<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LoginModel extends CI_Model
{
	function __construct(){
		parent::__construct();
	}
	function check_login($userid,$password){
		$data['ip'] 		= $_SERVER['REMOTE_ADDR'];
		$redir 				= 'apps/login';
		$data_return['error'] = 1;
		if ($userid!='' && $password!=''){
		 $query = $this->db->get_where('auth_user',"(userid = '$userid' or email = '$userid') and is_delete=0");
			//$this->db->where("userid","$userid");
			//$query=$this->db->get("auth_user");
			if ($query->num_rows() > 0){
				$row = $query->row(); 
				$data['id_auth_user'] 	= $row->id_auth_user;
				$userpass = $row->userpass;
				$password = md5($password);
				if ($password == $userpass && $password != "") {
					$this->load->library("session");
					if($row->id_auth_user_grup==1 || $row->id_auth_user_grup==2){
						$data_return['message'] = 'Admin can only login via CMS';
					} else if($row->is_banned==1){
						$data_return['message'] = "Your account in review, please wait and you'll get notif by email";
						$data['activity'] = "Account in review";
					} else {
						$user_sess = array(
							'name'=>$row->full_name,
							'id_auth_user_group'=>$row->id_auth_user_grup,
							'id'=>$row->id_auth_user,
							'id_auth_user'=>$row->id_auth_user,
							'id_ref'=>$row->id_ref,
							'type'=>$row->tipe,
							'id_ref_user_category'=>$row->id_ref_user_category
						);
						$this->load->model('LoginTransactionModel');
						$this->LoginTransactionModel->check_user($user_sess);
						$this->session->set_userdata('MEM_SESS',$user_sess);
						$data_return['message'] = 'Login Success';
						$data['activity'] 		= "Login";
						$data['error'] = 0;
					}
				}
				else {
          			$data_return['message'] = 'The email address or password is incorrect. Please retry';
					$data['activity'] = "Incorrect password";
				}
			} 
			else {
			   $data_return['message'] = 'Email or password incorrect';
			   $data['activity'] = "User not found : $userid";
			}
		}
		else{
			//kalo userid or password or dua2nya kosong
			$data_return['message'] = 'Email and Password is Required';
			redirect('apps/login');
			exit;
		}
		$data['log_date'] =  date('Y-m-d H:i:s');
		$this->db->insert('access_log',$data);
		return $data_return;
	}

	function send_password($email){
        // $data['ip']         = $_SERVER['REMOTE_ADDR'];
        $query = $this->db->get_where('auth_user',"email = '$email'  and is_delete=0");
		$data_return['error'] = 1;
        if ($query->num_rows() > 0){
            $row = $query->row(); 

            $this->load->helper('string');
            $data_now = random_string('alnum',8);
            $newpass = md5($data_now);

            $where['email'] = $row->email;
            $data['userpass'] = $newpass;
            $this->db->update('auth_user',$data,$where);//

            $this->load->library('parser');
            $this->load->helper('mail');
            $this->load->model('model_user','model');
            $emailTmp = $this->getEmailTemplate(1);
            
            $dataEmailContent['userid'] = $row->userid;
            $dataEmailContent['full_name'] = $row->full_name;
            $dataEmailContent['email'] = $row->email;
            $dataEmailContent['password'] = $data_now;
            $emailContent = $this->parser->parse_string($emailTmp['page_content'], $dataEmailContent, TRUE);

            $mail['to'] = $row->email;
            $mail['subject'] = $emailTmp['subject'];
            $mail['content'] = $emailContent;

            sent_mail($mail);
			$data_return['error'] = 0;
			$data_return['message'] = 'A new password has been sent to your e-mail address';
        }else{
            $data_return['message'] = 'The email you entered is incorrect';
            $data['activity'] = "change password not found email : $email";
            $data['log_date'] =  date('Y-m-d H:i:s');
            $this->db->insert('access_log',$data);
        }
		return $data_return;
	}
	function getEmailTemplate($idEmail='')
	{
		if ($idEmail == '') {
			exit('ID Email Required!');
		}
		$where['id'] = $idEmail;
		$emailTmp = $this->db->get_where('email_tmp', $where)->row_array();
		return $emailTmp;
	}

}

