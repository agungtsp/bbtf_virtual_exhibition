<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Meeting_schedule extends CI_Controller {
	function __construct(){
		parent::__construct();

	}
    function index(){
		$data['active_meeting_schedule'] = "active";
		$data['page_name']         = "Meeting Schedule";
		render('meeting_schedule',$data);
	}
	
	function list_data(){
		$data['active_meeting_schedule'] = "active";
		$data['page_name']         = "Meeting Schedule List";
		render('meeting_schedule_list',$data);
	}
}