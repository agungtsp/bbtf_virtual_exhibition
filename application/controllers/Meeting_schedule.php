<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Meeting_schedule extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Meeting_model');
	}
    function index(){
		$data['active_meeting_schedule'] = "active";
		$data['page_name']         = "Meeting Schedule";
		$data_meeting = $this->Meeting_model->findBy([]);
		$list_meeting = [];
		foreach($data_meeting as $key => $value){
			if(!isset($list_meeting[$value['start_date']])){
				$list_meeting[$value['start_date']] = array(
					'start_date' => iso_date_custom_format($value['start_date'], "D, d M Y")
				);
			}
			$list_meeting[$value['start_date']]['list_data'][] = array(
				"name" => $value['name'],
				"room_name" => $value['room_name'],
				"meeting_url" => $value['meeting_url'],
				"time" => ($value['start_time'] && $value['end_time']) ? $value['start_time'] . " - ". $value['end_time'] : "All Day",
			);
		}
		$data['list_meeting'] = $list_meeting;
		render('meeting_schedule',$data);
	}
	
	function list_data(){
		$data['active_meeting_schedule'] = "active";
		$data['page_name']         = "Meeting Schedule List";
		$data_meeting = $this->Meeting_model->findBy([]);
		$list_meeting = [];
		foreach($data_meeting as $key => $value){
			if(!isset($list_meeting[$value['start_date']])){
				$list_meeting[$value['start_date']] = array(
					'start_date' => iso_date_custom_format($value['start_date'], "D, d M Y")
				);
			}
			$list_meeting[$value['start_date']]['list_data'][] = array(
				"name" => $value['name'],
				"room_name" => $value['room_name'],
				"meeting_url" => $value['meeting_url'],
				"time" => ($value['start_time'] && $value['end_time']) ? $value['start_time'] . " - ". $value['end_time'] : "All Day",
			);
		}
		$data['list_meeting'] = $list_meeting;
		render('meeting_schedule_list',$data);
	}
}