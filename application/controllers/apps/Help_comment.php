<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help_comment extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Help_comment_model');
	}
	function index(){
		render('apps/help_comment/index',$data,'apps');
	}
	
	function records(){
		$data = $this->Help_comment_model->records();
		foreach ($data['data'] as $key => $value) {
			$opt = $value['opt'];
			
			if ($opt == 1) {
				$data['data'][$key]['opt'] = 'Ya';
			}
			else{
				$data['data'][$key]['opt'] = 'Tidak';
			}
		}
		render('apps/help_comment/records',$data,'blank');
	}	
}

/* End of file Help_comment.php */
/* Location: ./application/controllers/apps/Help_comment.php */