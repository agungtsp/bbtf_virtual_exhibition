<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Exhibitor extends CI_Controller {
	function __construct(){
		parent::__construct();

	}
	function index(){
		$data['active_exhibitor'] = "active";
		$data['page_name']         = "Exhibitor";
		render('exhibitor',$data);
	}

	function detail($category, $url){
		$data['active_exhibitor'] = "active";
		$data['page_name']         = "Exhibitor Detail";
		if(in_array($category, ['silver','gold','platinum'])){
			render('exhibitor_detail_'.$category,$data);
		} else {
			redirect("404");
		}
	}
}