<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
require APPPATH . 'libraries/JWT.php';
use \Firebase\JWT\JWT;

class Forum extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Forum_model', 'model');
        $this->load->model('Forum_comment_model', 'comment_model');
    }
    
    function process_post(){
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

            // cek jika belum menjadi user terverifikasi
            $admin_id_auth_user_group = db_get_one('auth_user', "id_auth_user_grup", ['id_auth_user' => $decoded_array['admin_id_auth_user']]);
            if ($admin_id_auth_user_group != 4)
            {
                $ret['msg'] = 'Akun Anda belum terverifikasi.';
                return $this->set_response($ret, REST_Controller::HTTP_BAD_REQUEST);
            }

            $this->form_validation->set_rules('id_forum_category', '"Kategori"', 'required'); 
            $this->form_validation->set_rules('title', '"Judul"', 'required');
            $this->form_validation->set_rules('description', '"Deskripsi"', 'required');
            if($this->form_validation->run() == FALSE){
                $ret['message']  = validation_errors(' ',' ');
            }
            else{
                $this->db->trans_start();

                $idedit = "";
                if($post['id']){
                    $idedit = db_get_one($this->model->table, "id", md5field("id")."='".$post['id']."' AND is_delete = 0 AND id_auth_user = ".$decoded_array['admin_id_auth_user']);
                    if (!$idedit)
                    {
                        $ret['msg'] = 'Data tidak ditemukan';
                        return $this->set_response($ret, REST_Controller::HTTP_NOT_FOUND);
                    }
                    unset($post['id']);
                }

                $post['id_status_publish'] = @$post['id_status_publish'] ? $post['id_status_publish'] : 1; 
                
                if($idedit){
                    $ret['msg'] = 'Data berhasil diperbaharui';
                    $this->model->update($post,$idedit);
                }
                else{
                    $post['id_auth_user'] = $decoded_array['admin_id_auth_user'];
                    $ret['msg'] = 'Data berhasil ditambahkan.';
                    $this->model->insert($post);
                }
                $this->db->trans_complete();
                $ret['error'] = 0;
            }
        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        return $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function records_post(){
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        $post = purify($post);
        $kunci = $this->config->item('thekey'); //secret key for encode and decode
        $headers = $post['sess_token']; //get token from request header
        try {
            $decoded = JWT::decode($headers, $kunci, array('HS256'));
            $decoded_array = (array) $decoded;

            if (!@$post['is_public'] && !@$post['is_detail']) {
                $where['a.id_auth_user'] = $decoded_array['admin_id_auth_user'];
            }
            else {
                $where['id_status_publish'] = 2;
            }

            if($post['id']){
                if (@$post['is_detail'] == 1)
                {
                    $where[md5field("a.id").' = '] = $post['id'];
                    $data = $this->model->findBy($where,1); // get detail with all join table
                }
                else {
                    $where[md5field("a.id").' = '] = $post['id'];
                    $data = $this->db->get_where($this->model->tableAs, $where)->row_array();
                }
                if (!$data)
                {
                    $ret['error'] = 1;
                    $ret['msg'] = 'Data tidak ditemukan';
                    return $this->set_response($ret, REST_Controller::HTTP_NOT_FOUND);
                }
                $ret['data'] = $data;
            } else {
                $data = $this->model->records($where, 0, 1);
                
                foreach ($data['data'] as $key => $value) {
                    $data['data'][$key]['total_comment'] = $this->comment_model->findBy(['id_forum' => $value['id']], 2);
                    $data['data'][$key]['id'] = md5plus($value['id']);
                }
                $ret['result']['data'] = $data['data'];
                $ret['result']['paging'] = $data['paging'];
            }
        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        return $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function get_category_list_get(){
        $ret['data'] = selectlist2([
            'table' => 'forum_category',
            'title' => 'Pilih Kategori',
            'where' => [
                'is_delete'    => 0
            ]
        ]);
        return $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function delete_post(){
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        $post = purify($post);
        $kunci = $this->config->item('thekey'); //secret key for encode and decode
        $headers = $post['sess_token']; //get token from request header
        try {
            $decoded = JWT::decode($headers, $kunci, array('HS256'));
            $decoded_array = (array) $decoded;

            $this->form_validation->set_rules('id', '"ID"', 'required'); 
            if($this->form_validation->run() == FALSE){
                $ret['msg']  = validation_errors(' ',' ');
            } else { 
                $id = db_get_one($this->model->table, "id", md5field("id")."='".$post['id']."' AND is_delete = 0 AND id_auth_user = ".$decoded_array['admin_id_auth_user']);

                if (!$id)
                {
                    $ret['msg'] = 'Data tidak ditemukan';
                    return $this->set_response($ret, REST_Controller::HTTP_NOT_FOUND);
                }
                $this->model->delete($id);
                $ret['msg'] = "Data berhasil dihapus.";
            }
        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        return $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function get_data_comment_post(){
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        $post = purify($post);
        $kunci = $this->config->item('thekey'); //secret key for encode and decode
        $headers = $post['sess_token']; //get token from request header
        $forum = $this->db->select('id', 'id_auth_user')
            ->get_where($this->model->table, md5field("id")."='".$post['id']."'")
            ->row();
        $id_forum = $forum->id;
        try {
            $decoded = JWT::decode($headers, $kunci, array('HS256'));
            $decoded_array = (array) $decoded;
            $this->db->order_by("create_date", "asc");
            $data['comment'] = $this->comment_model->findBy(array("id_forum"=>$id_forum));
            foreach ($data['comment'] as $key => $value) {
                $is_admin              = (!in_array((int)$value['id_auth_user_grup'], [3,4])) ? 1 : 0;
                $is_user_comment       = $value['user_id_create'] == $decoded_array['admin_id_auth_user'] ? 1 : 0;
                $is_user_forum         = $forum->id_auth_user == $decoded_array['admin_id_auth_user'] ? 1 : 0;
                $data['comment'][$key]['id'] = md5plus($value['id']);
                $data['comment'][$key]['full_name'] = $value['full_name'];
                $data['comment'][$key]['content'] = $value['content'];
                $data['comment'][$key]['create_date'] = $value['create_date'];
                $data['comment'][$key]['is_admin_display'] = $is_admin ? "" : "d-none";
                $data['comment'][$key]['is_admin'] = $is_admin ? "Admin" : "";
                $data['comment'][$key]['is_actionable'] = (($is_user_comment || $is_user_forum) && !$is_admin) ? 1 : 0;
            }
            $ret = $data;
        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function get_data_comment_count_post(){
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        $post = purify($post);
        $kunci = $this->config->item('thekey'); //secret key for encode and decode
        $headers = $post['sess_token']; //get token from request header
        $id_forum = db_get_one($this->model->table, "id", md5field("id")."='".$post['id']."'");
        try {
            $decoded = JWT::decode($headers, $kunci, array('HS256'));
            $decoded_array = (array) $decoded;
            $this->db->order_by("create_date", "asc");
            $ret['total_comment'] = $this->comment_model->findBy(array("id_forum"=>$id_forum), 2);
        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function process_comment_post(){
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        $post = purify($post);
        $kunci = $this->config->item('thekey'); //secret key for encode and decode
        $headers = $post['sess_token']; //get token from request header
        $ret['error']           = 1;
        try {
            $decoded = JWT::decode($headers, $kunci, array('HS256'));
            $decoded_array = (array) $decoded;

            // cek jika belum menjadi user terverifikasi
            $admin_id_auth_user_group = db_get_one('auth_user', "id_auth_user_grup", ['id_auth_user' => $decoded_array['admin_id_auth_user']]);
            if ($admin_id_auth_user_group != 4)
            {
                $ret['msg'] = 'Akun Anda belum terverifikasi.';
                return $this->set_response($ret, REST_Controller::HTTP_BAD_REQUEST);
            }

            $this->form_validation->set_rules('content', '"Komentar"', 'required'); 
            if($this->form_validation->run() == FALSE){
                $ret['msg']  = validation_errors(' ',' ');
            }
            else{
                $forum = $this->db->select('id', 'id_auth_user')
                    ->get_where($this->model->table, md5field("id")."='".$post['id']."'")
                    ->row();
                $id_forum = $forum->id;

                $is_user_forum = $forum->id_auth_user == $decoded_array['admin_id_auth_user'] ? 1 : 0;
                $this->db->trans_start();   
                $post['id_forum'] = $id_forum;
                $data_post['is_read_admin']  = 0;
                if ($is_user_forum)
                {
                    $data_post['is_read_member'] = 0;
                }
                $this->model->update($data_post,$id_forum);
                unset($post['sess_token']);
                $ret['msg'] = 'Komentar berhasil dikirim.';
                $post['user_id_create'] = $decoded_array['admin_id_auth_user'];
                $this->comment_model->insert($post);
                $this->db->trans_complete();
                $ret['error'] = 0;
            }
        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function delete_comment_post(){
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        $post = purify($post);
        $kunci = $this->config->item('thekey'); //secret key for encode and decode
        $headers = $post['sess_token']; //get token from request header
        try {
            $decoded = JWT::decode($headers, $kunci, array('HS256'));
            $decoded_array = (array) $decoded;

            $this->form_validation->set_rules('id', '"ID"', 'required'); 
            if($this->form_validation->run() == FALSE){
                $ret['msg']  = validation_errors(' ',' ');
            } else {
                $this->db->select('a.id, b.id_auth_user as forum_id_auth_user, a.user_id_create as comment_id_auth_user, c.id_auth_user_grup as comment_id_auth_user_grup');
                $this->db->join($this->model->table." b", "b.id = a.id_forum");
                $this->db->join('auth_user c',"c.id_auth_user = a.user_id_create",'left');
                $comment = $this->db->get_where($this->comment_model->tableAs, md5field("a.id")."='".$post['id']."' AND a.is_delete = 0")->row();
                $id      = $comment->id;
                if (!$id)
                {
                    $ret['msg'] = 'Data tidak ditemukan';
                    return $this->set_response($ret, REST_Controller::HTTP_NOT_FOUND);
                }

                // cek kepemilikan data
                $is_admin        = (!in_array((int)$comment->comment_id_auth_user_grup, [3,4])) ? 1 : 0;
                $is_user_forum   = $comment->forum_id_auth_user == $decoded_array['admin_id_auth_user'] ? 1 : 0;
                $is_user_comment = $comment->comment_id_auth_user == $decoded_array['admin_id_auth_user'] ? 1 : 0;
                if (!$is_user_forum && !$is_user_comment && $is_admin)
                {
                    $ret['msg'] = 'Anda tidak memiliki hak untuk menghapus data ini.';
                    return $this->set_response($ret, REST_Controller::HTTP_BAD_REQUEST);
                }

                $this->comment_model->delete($id);
                $ret['msg'] = "Data berhasil dihapus.";
            }
        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        return $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}
