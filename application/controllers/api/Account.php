<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
require APPPATH . 'libraries/JWT.php';
use \Firebase\JWT\JWT;

class Account extends REST_Controller {

    function __construct()
    {
        parent::__construct();

    }
    public function check_jwt_get(){
        
        $kunci = $this->config->item('thekey'); //secret key for encode and decode
        $headers ="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhZG1pbl9uYW1lIjoiQWRtaW4iLCJhZG1pbl9pZF9hdXRoX3VzZXJfZ3JvdXAiOiIxIiwiaWQiOiIxIiwiYWRtaW5faWRfYXV0aF91c2VyIjoiMSIsImFkbWluX2lkX3JlZiI6bnVsbCwiYWRtaW5fdHlwZSI6bnVsbCwicHJvZmlsX21pdHJhX2lkIjpudWxsLCJhZG1pbl9pZF9yZWZfdXNlcl9jYXRlZ29yeSI6bnVsbCwiaWF0IjoxNTU4NzU5NjQ5LCJleHAiOjE1NTg3Nzc2NDl9.GlLufevbdDvnBfCtLJszmgrQjei0lXYpRSz1mS_Et2I"; //get token from request header
        try {
            $decoded = JWT::decode($headers, $kunci, array('HS256'));
            $decoded_array = (array) $decoded;
            print_r($decoded);
        } catch (Exception $e) {
            $invalid = ['status' => $e->getMessage()]; //Respon if credential invalid
            print_r($invalid);
        }
    }

    public function change_password_post(){
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        $post = purify($post);
        $kunci = $this->config->item('thekey'); //secret key for encode and decode
        $headers = $post['sess_token']; //get token from request header
        $ret['error'] = 1;
        try {
            $decoded = JWT::decode($headers, $kunci, array('HS256'));
            $decoded_array = (array) $decoded;

            $this->form_validation->set_rules('old_userpass', '"Password lama"', 'trim|required'); 
            $this->form_validation->set_rules('new_userpass', '"Password baru"', 'trim|required');
            $this->form_validation->set_rules('re_userpass', '"Konfirmasi password lama"', 'trim|required'); 
            if ($this->form_validation->run() == FALSE){
                $ret['msg']  = validation_errors(' ',' ');
            } else {
                $id = $decoded_array['admin_id_auth_user']; 
                $old_pass1 = md5($post['old_userpass']);
                $pass1  = $post['new_userpass'];
                $cek_pass           = db_get_one('auth_user',"userpass","(id_auth_user = '$id')");
                if ($cek_pass != $old_pass1){
                    $ret['msg'] =  "Password lama salah.";
                } else {
                    $post_update['userpass'] = md5($pass1);
                    $this->db->update('auth_user',$post_update, "id_auth_user = '$id'");
                    detail_log();
                    insert_log('Update Password');
                    $ret['error'] = 0;
                    $ret['msg'] =  "Password berhasil diubah.";
                }
            }
        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function edit_profile_post(){
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        $post = purify($post);
        $kunci = $this->config->item('thekey'); //secret key for encode and decode
        $headers = $post['sess_token']; //get token from request header
        $ret['error'] = 1;
        try {
            $decoded = JWT::decode($headers, $kunci, array('HS256'));
            $decoded_array = (array) $decoded;

            $this->form_validation->set_rules('full_name', '"Nama Lengkap"', 'trim|required'); 
            $this->form_validation->set_rules('gender', '"Jenis Kelamin"', 'trim|required');
            $this->form_validation->set_rules('phone', '"No. Telepon"', 'trim|required'); 
            $this->form_validation->set_rules('userid', '"Username"', 'trim|required'); 
            $this->form_validation->set_rules('address', '"Alamat"', 'trim|required');
            $this->form_validation->set_rules('kode_ref_provinsi', '"Provinsi"', 'trim|required');
            $this->form_validation->set_rules('kode_ref_kabupaten', '"Kab / Kota"', 'trim|required');
            $this->form_validation->set_rules('kode_ref_kecamatan', '"Kecamatan"', 'trim|required');
            $this->form_validation->set_rules('kode_ref_kelurahan', '"Kelurahan"', 'trim|required');
            if ($this->form_validation->run() == FALSE){
                $ret['msg']  = validation_errors(' ',' ');
            } else {
                $id = $decoded_array['admin_id_auth_user']; 
                $where              = ($id) ? "and id_auth_user not in ($id)" : '';
                $cek_code           = db_get_one('auth_user',"userid","(userid = '$post[userid]') and is_delete = 0 $where");
                if($cek_code){
                    $ret['msg'] =  " Username '$post[userid]' sudah digunakan.";
                } else {
                    $post['birthdate'] = iso_date_custom_format($post['birthdate'],'Y-m-d');
                    $post['sim_expired_date'] = iso_date_custom_format($post['sim_expired_date'],'Y-m-d');
                    unset($post['email'], $post['sess_token']);
                    $this->db->update('auth_user',$post, "id_auth_user = '$id'");
                    detail_log();
                    insert_log('Update Profil Pengguna');
                    $ret['error'] = 0;
                    $ret['msg'] =  "Data berhasil disimpan.";
                }
            }
        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function get_profile_post(){
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        $post = purify($post);
        $kunci = $this->config->item('thekey'); //secret key for encode and decode
        $headers = $post['sess_token']; //get token from request header
        $ret['error'] = 1;
        try {
            $ret['error'] = 0;
            $decoded = JWT::decode($headers, $kunci, array('HS256'));
            $decoded_array = (array) $decoded;
            $user = $this->db->get_where('auth_user', array("id_auth_user"=>$decoded_array['admin_id_auth_user']))->row_array();
            $user['gender_conv'] = "";
            if($user['gender']=="M"){
                $user['gender_conv'] = "Laki-Laki";
            } else if ($user['gender']=="F"){
                $user['gender_conv'] = "Perempuan";
            }

            $user['nama_ref_provinsi']  = $user['kode_ref_provinsi'] ? db_get_one('ref_provinsi','provinsi','kode_provinsi='.$user['kode_ref_provinsi']) : null;
            $user['nama_ref_kabupaten'] = $user['kode_ref_provinsi'] ? db_get_one('ref_kabupaten','kabupatenkota','kode_kabupaten='.$user['kode_ref_kabupaten']) : null;
            $user['nama_ref_kecamatan'] = $user['kode_ref_provinsi'] ? db_get_one('ref_kecamatan','kecamatan','kode_kecamatan='.$user['kode_ref_kecamatan']) : null;
            $user['nama_ref_kelurahan'] = $user['kode_ref_provinsi'] ? db_get_one('ref_kelurahan','kelurahan','kode_kelurahan='.$user['kode_ref_kelurahan']) : null;
           
            $ret['msg'] = $user;
        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function login_post(){
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        $post = purify($post);
        $this->form_validation->set_data($post);
        $this->form_validation->set_rules('username', "Username",'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $ret['error'] = 1;
        if ($this->form_validation->run() == FALSE)
        {
            $ret['msg'] = validation_errors(' ',' ');
        }
        else
        {
            $password = md5($post['password']);
            $username = $post['username'];
            $check = $this->db->get_where('auth_user',"(userid = '$username' or email = '$username') and is_delete=0 and userpass='$password' and id_auth_user_grup in (3,4)")->row_array();
            
            if(!$check) {
                $ret['error'] = 1;
                $ret['msg'] = "Username atau password Anda salah.";
            }
            else {
                $success = 1;
                $message = 'Login';
                // die('???');
                $kunci = $this->config->item('thekey');
                $date = new DateTime();
                $param = array(
                    'admin_name'               => $check['full_name'],
                    'admin_id_auth_user_group' => $check['id_auth_user_grup'],
                    'id'                       => $check['id_auth_user'],
                    'admin_id_auth_user'       => $check['id_auth_user'],
                    'fcm_token'                => @$post['fcm_token'],
                    'iat'                      => $date->getTimestamp(),
                    'exp'                      => $date->getTimestamp() + 60*60*5
                );
                $token = JWT::encode($param,$kunci ); //This is the output token
                
                // add firebase cloud messaging token
                $fcmToken = @$post['fcm_token'];
                if ( $fcmToken )
                {
                    $isFcmTokenExist = $this->db->where('id_auth_user', $check['id_auth_user'])
                        ->where('token', $fcmToken)
                        ->get('auth_user_tokens')
                        ->num_rows();
                        
                    // kalo belum ada, insert baru
                    if ( ! $isFcmTokenExist )
                    {
                        $newFcmToken['id_auth_user'] = $check['id_auth_user'];
                        $newFcmToken['token']        = $fcmToken;
                        $this->db->insert('auth_user_tokens', $newFcmToken);
                    }
                }

                $data = array(
                    'sess_email' => $check['email'],
                    'sess_iat'   => $date->getTimestamp(),
                    'sess_exp'   => $date->getTimestamp() + 60*60*5,
                    'sess_token' => $token
                );
                $ret['error'] = 0;
                $ret['msg'] = "Berhasil Login";
                $ret['data'] = $data;
                // $this->session->set_userdata('ADM_SESS',$data);
            }
        }
        return $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

    }

    public function forgot_password_post()
    {
        $post         = purify($this->input->post());
        $ret['error'] = 1;
        $this->form_validation->set_rules('email', "E-Mail",'trim|required|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $ret['msg'] = validation_errors(' ',' ');
        } else {
            $query = $this->db->get_where('auth_user',"email = '$post[email]'  and is_delete=0");
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
                $ret['error'] = 0;
                $ret['msg']   = "Password baru telah dikirim ke alamat email Anda.";
            }else{
                $ret['msg']   = "Email not found.";
                $data['activity'] = "change password not found email : $email";
                $data['log_date'] =  date('Y-m-d H:i:s');
                $this->db->insert('access_log',$data);
            }
        }
        if (!empty($ret))
        {
            $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
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

    function logout_post(){
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        $post = purify($post);
        $kunci = $this->config->item('thekey'); //secret key for encode and decode
        $headers = $post['sess_token']; //get token from request header
        unset($post['sess_token']);
        $ret['error'] = 1;
       try {
            $decoded = JWT::decode($headers, $kunci, array('HS256'));
            $decoded_array = (array) $decoded;

            if ($decoded_array['fcm_token'])
            {
                $isFcmTokenExist = $this->db->select('id')
                    ->where('id_auth_user', $decoded_array['admin_id_auth_user'])
                    ->where('token', $decoded_array['fcm_token'])
                    ->get('auth_user_tokens');
                
                if ($isFcmTokenExist->num_rows() > 0)
                {
                    $idToken = $isFcmTokenExist->row()->id;
                    $this->db->delete('auth_user_tokens',['id' => $idToken]);
                }
            }

            $ret['error'] = 0;
        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        return $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}
