<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class About_us extends CI_Controller {
	function __construct(){
		parent::__construct();
	}
    function index(){
    	$this->load->model("HomeModel");
		$data['active_about_us'] = "active";
		$data['page_name']       = "Tentang Kami";

		// $data = "test";
		// // jika $data sama dengan "test", munculkan 1
		// if($data=="test"){
		// 	echo 1;
		// // jika $data sama dengan "berhasil", munculkan 2
		// } else if($data=="berhasil"){
		// 	echo 2;
		// // jika lainnya, munculkan 3
		// } else {
		// 	echo 3;
		// }

		render('about_us',$data);
	}
}