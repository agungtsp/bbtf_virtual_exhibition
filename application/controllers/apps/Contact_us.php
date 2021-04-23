<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_us extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('ContactUsModel');
		$this->load->model('Contactusreplymodel');
	}
	function index(){
		render('apps/contact_us/index',$data,'apps');
	}
	function records(){
		$data = $this->ContactUsModel->records();
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['create_date'] = iso_date_time($value['create_date']);
		}
		render('apps/contact_us/records',$data,'blank');
	}
	function detail($id){
		$data                = $this->ContactUsModel->findById($id);
		$data['create_date'] = iso_date_time($data['create_date']);
		
		render('apps/contact_us/detail',$data,'blank');
	}
	function del(){
		auth_delete();
		$id = $this->input->post('iddel');
		$this->ContactUsModel->reject($id);
		detail_log();
		insert_log("Reject Contact Us");
	}
	function reply(){
		$post = $this->input->post();
		if($post){
			$contactus  = $this->ContactUsModel->findById($post['id_contact_us']);
			$data['message']  = $post['message'];
			$data['name']     = $contactus['fullname'];
			$data['question'] = $contactus['message'];
			$data['date']     = iso_date_time($contactus['create_date']);
			$sent = sent_email_by_category(4,$data,$contactus['email']);
			if($sent['error']  === 0){
				$this->Contactusreplymodel->insert($post);
			} else {
				$ret['error'] =  1;
				$ret['message'] = '';
			}
			echo json_encode($sent);
		}
	}
}

/* End of file Contact_us.php */
/* Location: ./application/controllers/apps/Contact_us.php */