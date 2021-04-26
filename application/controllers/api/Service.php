<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
require APPPATH . 'libraries/JWT.php';
use \Firebase\JWT\JWT;

class Service extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Service_model', 'model');
        $this->load->model('Motor_user_model');
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

            $this->form_validation->set_rules('id_motor_user', '"Motor"', 'required'); 
            $this->form_validation->set_rules('service_date', '"Tanggal Servis"', 'required'); 
            $this->form_validation->set_rules('mileage_km', '"Jarak Tempuh (KM)"', 'required');
            $this->form_validation->set_rules('service_fee', '"Biaya Servis', 'required');
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
                        $ret['msg'] = 'Data not found';
                        return $this->set_response($ret, REST_Controller::HTTP_NOT_FOUND);
                    }
                    unset($post['id']);
                }

                $post['service_date'] = iso_date_custom_format($post['service_date'],'Y-m-d');
                
                if($idedit){
                    $ret['msg'] = 'Data updated successfully';
                    $this->model->update($post,$idedit);
                }
                else{
                    $post['id_auth_user'] = $decoded_array['admin_id_auth_user'];
                    $ret['msg'] = 'Data added successfully.';
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

            $this->db->select("a.*, b.title as motor");
            $this->db->where('a.is_delete',0);
            $this->db->where('a.id_auth_user',$decoded_array['admin_id_auth_user']);
            $this->db->join('motor_user b','b.id = a.id_motor_user');
            if($post['id']){
                $data = $this->db->get_where($this->model->tableAs,  md5field("a.id")."='".$post['id']."'")->row_array();
                if (!$data)
                {
                    $ret['error'] = 1;
                    $ret['msg'] = 'Data not found';
                    return $this->set_response($ret, REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                $this->db->order_by("service_date", "desc");
                $query = $this->db->get($this->model->tableAs)->result_array();
                $data = array();
                foreach ($query as $key => $value) {
                    $totalService = ($value['service_fee'] 
                        + $value['oli_price'] 
                        + $value['aki_price'] 
                        + $value['rem_depan_price']
                        + $value['rem_belakang_price']
                        + $value['ban_depan_price']
                        + $value['ban_belakang_price']
                    );
                    $data[] = array(
                        $value['motor'],
                        iso_date_custom_format($value['service_date'], 'd M Y'),
                        $value['place'],
                        number_format($value['mileage_km'], 0, ',', '.'),
                        "Rp. " . number_format($totalService, 0, ',', '.'),
                        '<a href="service-add.html?id='.md5plus($value['id']).'" title="Ubah Data" class="fa fa-pencil-alt tangan action-form-icon"></a>
                        <a title="Delete Data" href="#" data-id="'.md5plus($value['id']).'" class="fa fa-trash tangan hapus action-form-icon text-danger"></a>'
                    );
                }
            }
            $ret['draw'] = 1;
            $ret['recordsTotal'] = count($data);
            $ret['data'] = $data;
        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        return $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function get_mymotor_list_get(){
        $post          = purify($this->input->get());
        $kunci         = $this->config->item('thekey'); //secret key for encode and decode
        $headers       = $post['sess_token']; //get token from request header
        $decoded       = JWT::decode($headers, $kunci, array('HS256'));
        $decoded_array = (array) $decoded;

        $ret['data'] = selectlist2([
            'table' => $this->Motor_user_model->table,
            'title' => 'Pilih Motor',
            'name'  => 'title',
            'where' => [
                'is_delete'    => 0,
                'id_auth_user' => $decoded_array['admin_id_auth_user']
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
                    $ret['msg'] = 'Data not found';
                    return $this->set_response($ret, REST_Controller::HTTP_NOT_FOUND);
                }
                $this->model->delete($id);
                $ret['msg'] = "Data deleted successfully.";
            }
        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        return $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}
