<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
require APPPATH . 'libraries/JWT.php';
use \Firebase\JWT\JWT;

class Notification extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Service_model', 'model');
        $this->load->model('Motor_user_model');
    }

    function index_get(){
        $post          = purify($this->input->get());
        $kunci         = $this->config->item('thekey'); //secret key for encode and decode
        $headers       = $post['sess_token']; //get token from request header
        $decoded       = JWT::decode($headers, $kunci, array('HS256'));
        $decoded_array = (array) $decoded;

        $data = $this->db->select('data, content, id, create_date, read_date')
            ->where('recipient_id', $decoded_array['admin_id_auth_user'])
            ->order_by('create_date', 'desc')
            ->limit(10)
            ->get('notifications')
            ->result_array();

        if (!empty($data))
        {
            foreach ($data as $key => $value) {
                $value['data'] = $value['data'] ? json_decode($value['data'], TRUE) : [];

                $this->db->update('notifications', ['read_date' => date('Y-m-d H:i:s')], ['id' => $value['id']]);

                $data[$key] = $value;
            }
        }
        
        return $this->set_response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function total_get(){
        $post          = purify($this->input->get());
        $kunci         = $this->config->item('thekey'); //secret key for encode and decode
        $headers       = $post['sess_token']; //get token from request header
        $decoded       = JWT::decode($headers, $kunci, array('HS256'));
        $decoded_array = (array) $decoded;

        $total = $this->db->where('recipient_id', $decoded_array['admin_id_auth_user'])
            ->where('read_date', null)
            ->get('notifications')
            ->num_rows();

        $data['total'] = $total;
        
        return $this->set_response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}