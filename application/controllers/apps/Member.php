<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Member_model');
	}
	function index(){
		$data['list_user_group'] 		= selectlist2(array('table'=>'auth_user_grup','id'=>'id_auth_user_grup','name'=>'grup','title'=>'All User Group', 'where' => 'id_auth_user_grup in (3, 4)'));
		$data['list_ref_user_category'] 		= selectlist2(array('table'=>'ref_user_category','title'=>'All User Group','selected'=>$data['id_ref_user_category'], 'where' => array("is_delete" => 0)));
		render('apps/member/index',$data,'apps');
	}
	public function add($id=''){
		if($id){
			$data = $this->Member_model->findById($id);
			if(!$data){
				die('404');
			}
			$data['judul']        = 'Edit';
			$data['proses']       = 'Update';
			$data                 = quote_form($data);
			$data['is_edit']      = '';
			$data['is_show_pass'] = 'hidden';
		}
		else{
			$data['judul']              = 'Add';
			$data['proses']             = 'Save';
			$data['userid']             = '';
			$data['full_name']           = '';
			$data['email']              = '';
			$data['password']           = '';
			$data['phone']              = '';
			$data['is_edit']            = 'hidden';
			$data['is_show_pass']       = '';
			$data['id_auth_user']       = '';
			$data['title']        = '';
			$data['city']         = '';
			$data['company']      = '';
			$data['job_title']    = '';
		}

		$data['checked_mr']  = $data['title'] == 'mr' ? 'checked' : '';
		$data['checked_mrs'] = $data['title'] == 'mrs' ? 'checked' : '';
		$data['checked_ms']  = $data['title'] == 'ms' ? 'checked' : '';

		$data['list_user_group'] 		= selectlist2(array('table'=>'auth_user_grup','id'=>'id_auth_user_grup','name'=>'grup','selected'=>$data['id_auth_user_grup'],'title'=>'All User Group', 'where' => "id_auth_user_grup in (3, 4)"));
		$data['list_ref_country'] 		= selectlist2(array('table'=>'ref_country','title'=>'All Country','selected'=>$data['id_ref_country'], 'where' => array("is_delete" => 0)));
		$data['list_ref_user_category'] 		= selectlist2(array('table'=>'ref_user_category','title'=>'All User Group','selected'=>$data['id_ref_user_category'], 'where' => array("is_delete" => 0)));
		load_js('user.js','assets/js/modules/user');

		render('apps/member/add',$data,'apps');
	}
	public function view($id=''){
		if($id){
			$data = $this->Member_model->findById($id);
			$data['img_thumb'] = image($data['img'],'small');
			$data['img_ori'] =image($data['img'],'ori'); 
			if(!$data){
				die('404');
			}
			$data['page_name'] = quote_form($data['page_name']);
			$data['teaser'] = quote_form($data['teaser']);
		}
		render('apps/member/view',$data,'apps');
	}
	function records(){
		$where = 'a.id_auth_user_grup in (3, 4)';
		$data = $this->Member_model->records($where);
		foreach ($data['data'] as $key => $value) {
			// $data['data'][$key]['name'] = quote_form($value['name']);
			$data['data'][$key]['publish_date'] = iso_date($value['publish_date']);
			$data['data'][$key]['banned_title'] = ($value['is_banned']==1) ? 'Aktifkan User' : 'Nonaktifkan User';
			$data['data'][$key]['show_approve'] = ($value['id_auth_user_grup']==3) ? '' : 'hidden';
			$data['data'][$key]['show_disapprove'] = ($value['id_auth_user_grup']==4) ? '' : 'hidden';
			$data['data'][$key]['banned_title'] = ($value['is_banned']==1) ? 'Aktifkan User' : 'Nonaktifkan User';
		}
		render('apps/member/records',$data,'blank');
	}	
	
	function proses($idedit=''){
		$this->layout 			= 'none';
		$post 					= purify(null_empty($this->input->post()));
		$ret['error']			= 1;

		$grp = $post['id_auth_user_grup'];
		if (empty($post['userpass'])) {
			unset($post['userpass']) ;
		}
		else {
			$post['userpass'] = md5($post['userpass']);
		}
		
		$this->form_validation->set_rules('id_auth_user_grup', '"User"', 'required'); 
		$this->form_validation->set_rules('title', '"Title"', 'required'); 
		$this->form_validation->set_rules('full_name', '"Fullname"', 'required'); 
		$this->form_validation->set_rules('email', '"Email"', 'required'); 
		$this->form_validation->set_rules('phone', '"Phone"', 'required'); 
		$this->form_validation->set_rules('city', '"City"', 'required'); 
		$this->form_validation->set_rules('id_ref_country', '"Country"', 'required'); 
		$this->form_validation->set_rules('company', '"Company"', 'required'); 
		$this->form_validation->set_rules('job_title', '"Job"', 'required'); 
		$this->form_validation->set_rules('id_ref_user_category', '"Category"', 'required'); 

		if ($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		}
		else{   
			$this->db->trans_start();   
			$where 				= ($idedit) ? "and id_auth_user not in ($idedit)" : '';
			$cek_code           = db_get_one('auth_user',"userid","(userid = '$post[userid]' or email = '$post[email]') and is_delete = 0 $where");
			if($cek_code){
				$ret['error'] = 1;
				$ret['message'] =  " Account Name $post[userid] already exsist";
			} else {
				
				if($idedit){
					$current_group = db_get_one('auth_user',"id_auth_user_grup",["id_auth_user" => $idedit]);
					auth_update();
					$post['userid'] = $post['email'];
					$ret['message'] = 'Update Success';
					$act			= "Update User Management";
					$this->Member_model->update($post,$idedit);

					// send notification
					// if ($current_group != $post['id_auth_user_grup'])
					// {
					// 	if ($post['id_auth_user_grup'] == 4)
					// 	{
					// 		$notif['title'] = 'Akun terverifikasi';
					// 		$notif['content'] = 'Selamat! Akun Anda telah diverifikasi';
					// 		send_notification($idedit, $notif, id_user());
					// 	}
					// }
				}
				else{
					auth_insert();
					$ret['message'] = 'Insert Success';
					$act			= "Insert User Management";
					$this->Member_model->insert($post);
				}

				detail_log();
				insert_log($act);
				$this->db->trans_complete();
				$ret['error'] = 0;
			}
			set_flash_session('message',$ret['message']);
		}
		echo json_encode($ret);
	}
	function del(){
		auth_delete();
		$id = $this->input->post('iddel');
		$this->Member_model->delete($id);
		detail_log();
		insert_log("Delete User Management");
	}
	function approve(){
		auth_update();
		$id = $this->input->post('iddel');
		$post['id_auth_user_grup'] = 4;
		$ret['message'] = 'Update Success';
		$act			= "Update User Management";
		$this->Member_model->update($post,$id);

		$this->load->library('parser');
		$this->load->helper('mail');
		$this->load->model('model_user','model');
		$emailTmp = $this->getEmailTemplate(3);
		$data = $this->Member_model->findById($id);
		$dataEmailContent['full_name'] = $data['full_name'];
		$dataEmailContent['email'] = $data['email'];
		$emailContent = $this->parser->parse_string($emailTmp['page_content'], $dataEmailContent, TRUE);

		$mail['to'] = $data['email'];
		$mail['subject'] = $emailTmp['subject'];
		$mail['content'] = $emailContent;
		sent_mail($mail);
		detail_log();
		insert_log("Approved User Management");
	}
	function disapprove(){
		auth_update();
		$id = $this->input->post('iddel');
		$post['id_auth_user_grup'] = 3;
		$ret['message'] = 'Update Success';
		$act			= "Update User Management";
		$this->Member_model->update($post,$id);
		detail_log();
		insert_log("Disapprove User Management");
	}

	function getEmailTemplate($idEmail='')
    {
        if ($idEmail == '') {
            exit('ID Email Required!');
        }
        $where['id'] = $idEmail;
        $emailTmp = $this->db->get_where('email_tmp', $where)->row_array();
        return $emailTmp;
    }
}

/* End of file frontend_menu.php */
/* Location: ./application/controllers/apps/frontend_menu.php */