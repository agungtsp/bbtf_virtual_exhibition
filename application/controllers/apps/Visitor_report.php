<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Visitor_report extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Visitor_report_model');
	}
	function index(){
		$data['list_exhibitor'] = selectlist2(array('table'=>'exhibitor','title'=>'All Exhibitor','selected'=>$data['id_exhibitor']));
		render('apps/visitor_report/index',$data,'apps');
	}

	function records(){
		$data = $this->Visitor_report_model->records($where);
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['create_date'] = iso_date_custom_format($value['create_date'], "d-m-Y H:i:s");
		}
		render('apps/visitor_report/records',$data,'blank');
	}

	function view($id){
		$where_exhibitor['id']  = $id;
		$exhibitor              = $this->db->get_where("exhibitor",$where_exhibitor)->row_array();

		$data['exhibitor_name'] = $exhibitor['name'];
		$user                   = $this->db->get_where("auth_user",array('exhibitor_id'=>$exhibitor['id']))->row_array();
		$data['seller_name']    = $user['full_name'];
		$data['seller_email']   = $user['email'];
		$data['id']   			= $id;
		render('apps/visitor_report/view',$data,'apps');
	}

	function records_view($id){
		$where['id'] = $id;
		$exhibitor = $this->db->get_where("exhibitor",$where)->row_array();
		$where2 = $exhibitor['uri_path'];
		$data = $this->Visitor_report_model->records_view($where2,'',$id);
		render('apps/visitor_report/records_view',$data,'blank');
	}

	function export_excel($id){
		$where['id'] = $id;
		$exhibitor = $this->db->get_where("exhibitor",$where)->row_array();
		$where2 = $exhibitor['uri_path'];
		$data = $this->Visitor_report_model->records_view($where2,'',$id);
		$where_exhibitor['id']  = $id;
		$ex              = $this->db->get_where("exhibitor",$where_exhibitor)->row_array();

		$data['exhibitor_name'] = $ex['name'];
		$user                   = $this->db->get_where("auth_user",array('exhibitor_id'=>$ex['id']))->row_array();
		$data['seller_name']    = $user['full_name'];
		$data['seller_email']   = $user['email'];
		render('apps/visitor_report/export_excel',$data,'blank');
      	export_to_2('Visitor_report.xls');
	}
}

/* End of file frontend_menu.php */
/* Location: ./application/controllers/apps/frontend_menu.php */