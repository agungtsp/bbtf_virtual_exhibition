<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->layout = 'none';
		
	}
    function index(){
        $cookie_name    = "comet_id";
        $cookie_value   = "";
        setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
        $err            = $this->uri->segment(4);
        $error_login    = get_flash_session('error_login') != '' ? get_flash_session('error_login') : '';
        $data           = array('base_url' => base_url(), 'login'=>'', 'password'=>'', 'error_login'=>$error_login,'news'=>$news);
        $data['error_login_hide']    = get_flash_session('error_login') != '' ? '' : 'hide';
        $success_login    = get_flash_session('success_login') != '' ? get_flash_session('success_login') : '';
        $data['success_login_hide']    = get_flash_session('success_login') != '' ? '' : 'hide';
        $data['success_login']    = get_flash_session('success_login');
        render('login',$data,'login');
    }
    function cek_login(){
        $this->load->model('Auth_model');
        $this->Auth_model->check_login($this->input->post('username'),$this->input->post('password'));
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
        redirect('apps/login');
    }
	
	function login_trouble(){
		$data = array('base_url' => base_url());
		$this->parser->parse('login_trouble.html', $data);
	}

    function register(){
        $error_login              = get_flash_session('error_login') != '' ? get_flash_session('error_login') : '';
        $data['base_url']         = base_url();
        $data['error_login']      = $error_login;
        $data['error_login_hide'] = get_flash_session('error_login') != '' ? '' : 'hide';

        $data['list_negara']      = selectlist2(array(
            'table'    => 'ref_negara',
            'where'    => 'is_delete = 0',
            'selected' => 'ID',
            'id'       => 'code' 
        ));

        $data['list_provinsi']    = selectlist2(array(
            'table'      => 'ref_provinsi',
            'where'      => 'is_delete = 0',
            'name'       => 'provinsi',
            'id'         => 'kode_provinsi',
        ));

        set_flash_session('error_login',"");

        // CSS
        load_css('select2.min.css','template/assets/plugins/select2/dist/css');
        load_css('datepicker.css','template/assets/plugins/bootstrap-datepicker/css');

        // JS
        load_js('select2.min.js','template/assets/plugins/select2/dist/js');
        load_js('bootstrap-datepicker.js','template/assets/plugins/bootstrap-datepicker/js');
        load_js('register.js','assets/js/modules/login');

        render('register',$data,'login');
    }

    function register_process(){
        $post   = purify($this->input->post());
        if($post){
            $userIp= $this->input->ip_address();
            $data_captcha = array();
            $secret= "6Lf8tToUAAAAABgvVfwylUxfqpP38PM9d0OIpLly";
            $responsecaptcha = trim($post['g-recaptcha-response']);
            $url="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$responsecaptcha."&remoteip=".$userIp;
            
            
            $ch = curl_init();
            $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT,$user_agent);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
            $data_captcha = curl_exec($ch);
            curl_close($ch);
            $data_return['error'] = 1;
            
            $status= json_decode($data_captcha,true);
            if(!$status['success'] && IS_DEVELOPMENT==0){
                $data_return['message']  = "Captcha Wajib dicentang.";
            } else {
                $this->form_validation->set_rules('username', '"Nama Lengkap"', 'trim|required'); 
                $this->form_validation->set_rules('userid', '"Username"', 'trim|required'); 
                $this->form_validation->set_rules('email', '"Email"', 'trim|required'); 
                $this->form_validation->set_rules('gender', '"Jenis Kelamin"', 'trim|required');
                $this->form_validation->set_rules('birthdate', '"Tanggal Lahir"', 'trim|required');
                $this->form_validation->set_rules('phone', '"No. Telepon"', 'trim|required'); 
                $this->form_validation->set_rules('userpass', '"Password"', 'trim|required'); 
                $this->form_validation->set_rules('kode_ref_negara', '"Negara"', 'trim|required');
                $this->form_validation->set_rules('id_auth_user_grup', '"Grup Pengguna"', 'trim|required');
                $this->form_validation->set_rules('postal_code', '"Kode Pos"', 'trim|required');

                if (strtoupper($post['kode_ref_negara']) == 'ID') {
                    $this->form_validation->set_rules('kode_ref_provinsi', '"Provinsi"', 'trim|required');
                    $this->form_validation->set_rules('kode_ref_kabupaten', '"Kabupaten"', 'trim|required');
                    $this->form_validation->set_rules('kode_ref_kecamatan', '"Kecamatan"', 'trim|required');
                    $this->form_validation->set_rules('kode_ref_kelurahan', '"Kelurahan"', 'trim|required');
                } else {
                    unset(
                        $post['kode_ref_provinsi'],
                        $post['kode_ref_kabupaten'],
                        $post['kode_ref_kecamatan'],
                        $post['kode_ref_kelurahan']
                    );
                }

                if ($this->form_validation->run() == FALSE){
                    $data_return['message']  = validation_errors(' ','<br> ');
                    $data_return['status'] = 1;
                } else {
                    $post['userpass']  = md5($post['userpass']);
                    $post['birthdate'] = iso_date_custom_format($post['birthdate'],'Y-m-d');

                    $this->db->trans_start();   
                    $cek_code           = db_get_one('auth_user',"userid","(userid = '$post[userid]' or email = '$post[email]') and is_delete = 0 and id_auth_user_grup = '$post[id_auth_user_grup]' $where");
                    if($cek_code){
                        $data_return['error'] = 1;
                        $data_return['message'] =  "Username atau Email $post[userid] sudah digunakan";
                    } else {
                        $data_return['message'] = 'Berhasil mendaftarkan akun, silahkan login';
                        $act            = "Insert User";
                        $this->load->model('UserModel');

                        $this->UserModel->insert($post);
                        
                        $this->db->trans_complete();   
                    }
                    $data_return['error'] = 0;
                }
            }
            
            if($data_return['error']==1){
                set_flash_session('error_login',$data_return['message']);
            } else {
                set_flash_session('success_login',$data_return['message']);
            }
            echo json_encode($data_return);
        }
    }

    function forget_password(){
        // $err                          = $this->uri->segment(4);
        $error_login              = $this->session->flashdata('error_login') != '' ? $this->session->flashdata('error_login') : '';
        $warning                  = $this->session->flashdata('warning') != '' ? $this->session->flashdata('warning') : '';
        $data                     = array('base_url' => base_url(), 
                                         'login'=>'',
                                         'password'=>'',
                                         'error_login'=>$error_login,
                                         'warning'=>$warning,
                                         // 'news'=>$news
                                         );
        $data['error_login_hide'] = $this->session->flashdata('error_login') != '' ? '' : 'hide';
        $data['warning_hide']     = $this->session->flashdata('warning') != '' ? '' : 'hide';
        render('forget_password',$data,'login');
    }
    function send_password(){
        $userIp= $this->input->ip_address();
        $data_captcha = array();
        $secret= "6Lf8tToUAAAAABgvVfwylUxfqpP38PM9d0OIpLly";
        $responsecaptcha = trim($post['g-recaptcha-response']);
        $url="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$responsecaptcha."&remoteip=".$userIp;
        
        
        $ch = curl_init();
        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT,$user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
        $data_captcha = curl_exec($ch);
        curl_close($ch);
        $data_return['error'] = 1;
        
        $status= json_decode($data_captcha,true);
        if(!$status['success'] && IS_DEVELOPMENT==0){
            $this->session->set_flashdata('error_login','Captcha Wajib dicentang.');
        } else {
            $this->load->model('Auth_model');
            $this->Auth_model->send_password($this->input->post('email'));
        }
    }

}