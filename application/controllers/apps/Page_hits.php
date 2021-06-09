<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page_hits extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Page_hit_model');
	}
	function index(){
		$data['list_user'] 		= selectlist2(array('table'=>'auth_user','id'=>'id_auth_user','name'=>'email','title'=>'All User', 'where' => 'id_auth_user_grup in (3, 4)'));
		render('apps/page_hits/index',$data,'apps');
	}

	function records(){
		$data = $this->Page_hit_model->records($where);
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['create_date'] = iso_date_custom_format($value['create_date'], "d-m-Y H:i:s");
		}
		render('apps/page_hits/records',$data,'blank');
	}	
}

/* End of file frontend_menu.php */
/* Location: ./application/controllers/apps/frontend_menu.php */