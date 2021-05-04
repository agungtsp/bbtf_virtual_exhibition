<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Exhibition extends CI_Controller {
	function __construct(){
		parent::__construct();

	}
	function index($category){
		$data['active_exhibition'] = "active";
		$data['page_name']         = "Exhibitor";
		if(in_array($category, ['silver','gold','platinum'])){
			render('exhibitor_list_'.$category,$data);
		} else {
			redirect("404");
		}
	}

}