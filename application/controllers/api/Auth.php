<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Auth extends REST_Controller {

    function __construct()
    {
        parent::__construct();
    }

    public function register_post()
    {
        $post         = purify($this->input->post());
        $ret['error'] = 1;
        $this->form_validation->set_rules('email', "E-Mail",'trim|required|valid_email');
        $this->form_validation->set_rules('full_name', "Nama Lengkap",'trim|required');
        $this->form_validation->set_rules('userid', "Username",'trim|required|min_length[3]');
        $this->form_validation->set_rules('password', "Kata Sandi",'trim|required|min_length[6]');
        $this->form_validation->set_rules('password_confirmation', "Konfirmasi Kata Sandi",'trim|required|matches[password]');
        if ($this->form_validation->run() == FALSE) {
            $ret['msg'] = validation_errors(' ',' ');
        } else {
            $postEmail  = strtolower($post['email']);
            $postUserid = strtolower($post['userid']);
            $query = $this->db->get_where('auth_user',"LOWER(email) = '$postEmail' or LOWER(userid) = '$postUserid' and is_delete=0");
            if (! $query->num_rows())
            {
                $password = md5($post['password']);

                $data['email']              = $post['email'];
                $data['full_name']          = $post['full_name'];
                $data['userid']             = $post['userid'];
                $data['userpass']           = $password;
                $data['id_auth_user_grup'] = 3; //belum terkonfirmasi
                $data['create_date']        = date("Y-m-d H:i:s");
                $this->db->insert('auth_user',$data);

                $this->load->library('parser');
                $this->load->helper('mail');
                $this->load->model('model_user','model');
                $emailTmp = $this->getEmailTemplate(5);
                
                $dataEmailContent['fullname'] = $post['full_name'];
                $emailContent = $this->parser->parse_string($emailTmp['page_content'], $dataEmailContent, TRUE);

                $mail['to'] = $post['email'];
                $mail['subject'] = $emailTmp['subject'];
                $mail['content'] = $emailContent;

                sent_mail($mail);
                $ret['error'] = 0;
                $ret['msg']   = "Selamat! Anda berhasil bergabung. Silahkan Login!";
            }else{
                if ($query->num_rows() > 1)
                {
                    $ret['msg'] = "Email sudah terdaftar.";
                    $ret['msg'] .= "<br>Username sudah digunakan.";
                }
                else {
                    $row        = $query->row();
                    $ret['msg'] = $postUserid == strtolower($row->userid) ? "Username sudah digunakan." : "Email sudah terdaftar.";
                }
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

}
