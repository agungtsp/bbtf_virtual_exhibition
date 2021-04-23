<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Tutorial extends REST_Controller {

    function __construct()
    {
        parent::__construct();

    }

    public function list_menu_post()
    {
        $this->db->select("id, name");
        $this->db->order_by("id", "asc");
        $list_menu = $this->db->get_where("help_category a", array("is_delete"=>0))->result_array();
        foreach ($list_menu as $key => $value) {
            $this->db->order_by("name", "asc");
            $this->db->select("id, name, uri_path");
            $list_child = $this->db->get_where("help", array("id_help_category"=>$value['id'],"is_delete"=>0))->result_array();
            if($list_child){
                foreach ($list_child as $key_child => $value_child) {
                    $list_menu[$key]['child'][] = array(
                        "name" => $value_child['name'],
                        "uri_path" => $value_child['uri_path']
                    ); 
                }
            } else {
                unset($list_menu[$key]);
            }
        }
        if (!empty($list_menu))
        {
            $this->set_response($list_menu, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Data tidak ditemukan'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

}
