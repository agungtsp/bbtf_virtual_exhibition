<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Master extends REST_Controller {

    function __construct()
    {
        parent::__construct();
    }

    public function get_provinsi_get()
    {
        $post = purify($this->input->get());
		$selected = $post['selected'];
		$data['list_data'] = selectlist2(array(
            'table'=>'ref_provinsi',
            'title'=>'Pilih Provinsi',
            'id'=>'kode_provinsi',
            'name'=>'provinsi',
            'order'=>'id_provinsi',
            'selected'=>$selected,
            'is_delete'=>0)
        );
        if (!empty($data))
        {
            $this->set_response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }
    
    public function get_kabupaten_get()
    {
        $post = purify($this->input->get());
		$kd_provinsi_wilayah = $post['code'];
		$data['list_data'] = selectlist2(array(
			'table'=>'ref_kabupaten',
			'title'=>'Pilih Kabupaten/Kota',
			'id'=>'kode_kabupaten',
			'name'=>'kabupatenkota',
			'selected'=>$post['selected'],
			'where'=>array(
				'kode_provinsi'=>$kd_provinsi_wilayah),
			'is_delete'=>0
			)
		);
        if (!empty($data))
        {
            $this->set_response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function get_kecamatan_get()
    {
        $post = purify($this->input->get());
		$kd_kabupaten_wilayah = $post['code'];
		$data['list_data'] = selectlist2(array(
			'table'=>'ref_kecamatan',
			'title'=>'Pilih Kecamatan',
			'id'=>'kode_kecamatan',
			'name'=>'kecamatan',
			'selected'=>$post['selected'],
			'where'=>array(
				'kode_kabupaten'=>$kd_kabupaten_wilayah)
			)
		);
        
        if (!empty($data))
        {
            $this->set_response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function get_kelurahan_get()
    {
        $post = purify($this->input->get());
		$kd_kelurahan = $post['code'];
		$data['list_data'] = selectlist2(array(
			'table'=>'ref_kelurahan',
			'title'=>'Pilih Kelurahan',
			'id'=>'kode_kelurahan',
			'name'=>'kelurahan',
			'selected'=>$post['selected'],
			'where'=>array(
				'kode_kecamatan'=>$kd_kelurahan)
			)
		);
        
        if (!empty($data))
        {
            $this->set_response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }
}
