<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require __DIR__ . '../../../../vendor/autoload.php';
class Meeting extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Meeting_model');
	}
	function index(){
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));
		$data['list_exhibitor'] = selectlist2(array('table'=>'exhibitor','title'=>'All Exhibitor','selected'=>$data['id_exhibitor']));
		render('apps/meeting/index',$data,'apps');
	}
	public function add($id=''){
		if($id){
			$data = $this->Meeting_model->findById($id);
           
			if(!$data){
				die('404');
			}
			$data['judul']  = 'Edit';
			$data['proses'] = 'Update';
			$data['id']     = $id;
			$data['start_date'] = iso_date($data['start_date']);
			$data['participants']     = json_encode(explode(',', $data['participants']));
		}
		else{
			$data['last_edited']                  = '';
			$data['last_edited_show']             = 'invis';
			$data['judul']                        = 'Add';
			$data['proses']                       = 'Simpan';
			$data['teaser']                       = '';
			$data['id']     						= '';
			$data['name']                         = '';
			$data['start_date']                         = '';
			$data['start_time']                         = '';
			$data['end_time']                         = '';
			$data['uri_path']                         = '';
			$data['room_name']                         = '';
			$data['start_date']     = date('d-m-Y');
			$data['participants']     = json_encode([]);
		}
		$logo         = image($data['logo'],'large');
		$data['logo'] = imagemanager2('logo',$logo,'','logo',$data['logo']);
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));
		$data['list_exhibitor'] = selectlist2(array('table'=>'exhibitor','title'=>'All Exhibitor','selected'=>$data['exhibitor_id']));
		$data['list_participants'] = selectlist2_meeting(array('table'=>'auth_user','no_title'=>1,'name'=>'company','email'=>'email', 'id'=> 'id_auth_user', 'where' => 'id_auth_user_grup = 4 and id_ref_user_category in (2)' ));
		render('apps/meeting/add',$data,'apps');
	}
	function records(){
		$data = $this->Meeting_model->records();
		$data['token_dailyco'] = DAILY_CO_TOKEN;
		render('apps/meeting/records',$data,'blank');
	}	
	
	function proses($idedit=''){
		$this->layout 			= 'none';
		$post 					= purify(null_empty($this->input->post()));
		$ins_id 				= '';
		$this->form_validation->set_rules('name', '"name"', 'required'); 
		$this->form_validation->set_rules('id_status_publish', '"status"', 'required');
		$this->form_validation->set_rules('start_date', '"status"', 'required');
		$this->form_validation->set_rules('room_name', '"status"', 'required');
		$ret['error'] = 1;
		if ($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		}
		else{   
			$this->db->trans_start();   
			$post['start_date'] = iso_date($post['start_date']);
			$post['participants'] = ($post['participants']) ? implode(',', $post['participants']) : null;
			$generate_meeting['name'] = substr($post['room_name'], 0, 25); 
			try {
				$meeting = $this->generateDailyCo($generate_meeting);
				if($meeting){
					$post['meeting_url'] = $meeting->url;
				}
			} catch(Exception $e) {
				// print_r($e);exit();
			}
			if($idedit){
				auth_update();
				$ret['message'] = 'Update Success';
				$act			= "Update User Category Exhibitor";
				$this->Meeting_model->update($post,$idedit);
			} else {
				auth_insert();

				$ret['message'] = 'Insert Success';
				$act            = "Insert User Category Exhibitor";
				$xDat           = $this->Meeting_model->insert($post);
			}
			detail_log();
			insert_log($act);
			$this->db->trans_complete();
			$this->session->set_flashdata('message',$ret['message']);
			$ret['error'] = 0;
		}
		echo json_encode($ret);
	}
	function del(){
		auth_delete();
		$id = $this->input->post('iddel');
		$this->Meeting_model->delete($id);
		detail_log();
		insert_log("Delete User Category Exhibitor");
	}

	private function generateDailyCo($data) {
		$dailyCoAccessToken = DAILY_CO_TOKEN;
	
		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.daily.co']);
	  
		$response = $client->request('POST', '/v1/rooms', [
			"headers" => [
				"Authorization" => "Bearer $dailyCoAccessToken"
			],
			'json' => $data,
		]);
	
		$data = json_decode($response->getBody());
		return $data;
	}
}

/* End of file Ref_exhibitor_category.php */
/* Location: ./application/controllers/apps/Ref_exhibitor_category.php */