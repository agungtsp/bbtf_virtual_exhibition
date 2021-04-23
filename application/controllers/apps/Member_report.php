<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_report extends CI_Controller {
	function __construct(){
		parent::__construct();
	}
	function index(){
		$today = date("Y-m-d");
		$data['start_date'] = date( "d-m-Y", strtotime("$today -7 days"));
		$data['end_date'] = date("d-m-Y");
		render('apps/member_report/index',$data,'apps');
	}

	function report(){
		$post                 = $this->input->post();
		$data                 = array();
		$data_user_unverified = array();
		$data_user_verified   = array();
		$all_user_unverified  = 0;
		$all_user_verified    = 0;
		$date_range = date_range(iso_date($post['start_date']), iso_date($post['end_date']));
		foreach ($date_range as $key => $value) {
			$this->db->where("id_auth_user_grup in (3)");
			$data_user_unverified[$key] = $this->db->get_where("auth_user", 
				array(
					"is_delete"=>0,
					"date(create_date)" => $value
				)
			)->num_rows();
			$all_user_unverified += $data_user_unverified[$key];
			$this->db->where("id_auth_user_grup in (4)");
			$data_user_verified[$key] = $this->db->get_where("auth_user", 
				array(
					"is_delete"=>0,
					"date(create_date)" => $value
				)
			)->num_rows();
			$all_user_verified += $data_user_verified[$key];
			$date_range_conv[] = iso_date($value);
		}
		$data['data_user_unverified'] = $data_user_unverified;
		$data['data_user_verified']   = $data_user_verified;
		$data['all_user_verified']    = $all_user_verified;
		$data['all_user_unverified']  = $all_user_unverified;

		$data['date'] = $date_range_conv;
		echo json_encode($data);
	}
}

/* End of file Customer_report.php */
/* Location: ./application/controllers/apps/Customer_report.php */