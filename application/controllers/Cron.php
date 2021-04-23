<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Voucher_model');
		$this->load->model('Voucher_setting_model');
		$this->load->model('UserModel');
		$this->load->model('Cron_model');
	}

	
	function voucher(){
		$setting = $this->Voucher_setting_model->findById(1);
		$where['a.expired_date']      = date('Y-m-d',strtotime(date('Y-m-d') . "+".$setting['due_date_days']." days"));
		$where['a.id_auth_user_grup'] = 4;
		$where['a.is_send_promotion'] = 0;
		$list_user = $this->UserModel->findBy($where);
		foreach ($list_user as $key => $value) {
			$insert_data = array();
			$insert_data = array(
				"id_auth_user"    => $value['id_auth_user'],
				"voucher_code"    => generate_voucher(),
				"name"            => $setting['name'],
				"description"     => $setting['description'],
				"extend_days"     => $setting['extend_days'],
				"due_extend_date" => date('Y-m-d',strtotime(date('Y-m-d') . "+".$setting['due_extend_days']." days")),
				"user_id_create"  => 1
			);
			$this->Voucher_model->insert($insert_data);

			$update_pelanggan['is_send_promotion'] = 1;
			$this->UserModel->update($update_pelanggan,$value['id_auth_user']);
		}
		// auth_insert();
		// $ret['message'] = 'Insert Success';
		// $act            = "Insert Voucher";
		// $xDat           = $this->Voucher_model->insert($post);
		// $ret['error'] = 0;
		// detail_log();
		// insert_log($act);
		// $this->session->set_flashdata('message',$ret['message']);
		echo json_encode($ret);
	}
}

/* End of file Cron.php */
/* Location: ./application/controllers/Cron.php */