<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('UserModel');
		$this->load->model('Store_model');
	}
	function index(){
		$data['start_date'] = "";
		$data['end_date'] = "";
		render('apps/customer/index',$data,'apps');
	}
	function store($id_customer){
		$data = $this->UserModel->findById($id_customer);
		if(!$data){
			die('404');
		}
		$this->session->set_userdata(array("id_customer"=>$id_customer));
		render('apps/customer/store_index',$data,'apps');
	}
	public function add($id=''){
		if($id){
			$data = $this->UserModel->findById($id);
			if(!$data){
				die('404');
			}
			$data['judul']        = 'Edit';
			$data['proses']       = 'Update';
			$data                 = quote_form($data);
			$data['is_edit']      = '';
			$data['is_show_pass'] = 'hidden';
			$data['birthdate']    = iso_date_custom_format($data['birthdate'],'d-m-Y');
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
			$data['gender']             = '';
			$data['postal_code']        = '';
			$data['address']            = '';
			$data['birthdate']          = '';
		}

		$data['checked_male']      = $data['gender'] == 'M' ? 'checked' : '';
		$data['checked_female']    = $data['gender'] == 'F' ? 'checked' : '';

		$data['list_user_group'] 		= selectlist2(array('table'=>'auth_user_grup','id'=>'id_auth_user_grup','name'=>'grup','title'=>'All User Group','selected'=>$data['id_auth_user_grup'], 'where' => array("id_auth_user_grup" => 4)));

		load_js('user.js','assets/js/modules/user');

		render('apps/customer/add',$data,'apps');
	}
	public function store_add($id=''){
		if($id){
			$data = $this->Store_model->findById($id);
			if(!$data){
				die('404');
			}
			$data['id_auth_user'] = $this->session->userdata("id_customer");
			$data['judul']        = 'Edit';
			$data['proses']       = 'Update';
			$data                 = quote_form($data);
			$data['is_edit']      = '';
			$data['is_show_pass'] = 'hidden';
			$data['birthdate']    = iso_date_custom_format($data['birthdate'],'d-m-Y');
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
			$data['store_name']       = '';
			$data['postal_code']       = '';
			$data['address']        = '';
			$data['id']        = '';
			$data['id_auth_user'] = $this->session->userdata("id_customer");
		}

		load_js('user.js','assets/js/modules/user');

		render('apps/customer/store_add',$data,'apps');
	}
	public function view($id=''){
		if($id){
			$data = $this->UserModel->findById($id);
			if(!$data){
				die('404');
			}
			$this->session->set_userdata(array("id_customer"=>$id));
			$data['birthdate'] = iso_date($data['birthdate']);
			$data['expired_date'] = iso_date($data['expired_date']);
			$data['create_date'] = iso_date($data['create_date']);
			if($data['gender']=="M"){
				$gender = "Laki-Laki";
			} else if ($data['gender']=="M"){
				$gender = "Perempuan";
			}
			$data['gender'] = $gender;
		}
		render('apps/customer/view',$data,'apps');
	}
	function records(){
		$where['a.id_auth_user_grup'] = 4;
		if($_GET['search_is_active']){
			if($_GET['search_is_active']==1){
				$where["expired_date >="] = date("Y-m-d");
			} else if($_GET['search_is_active']==2){
				$where["expired_date"] = date("Y-m-d");
			} else if($_GET['search_is_active']==3){
				$where["expired_date <"] = date("Y-m-d");
			}
		} else {
			if($_GET['search_start_date'] && $_GET['search_end_date']){
				$where["expired_date >="] = iso_date($_GET['search_start_date']);
				$where["expired_date <="] = iso_date($_GET['search_end_date']);
			} else if($_GET['search_start_date']){
				$where["expired_date"] = iso_date($_GET['search_start_date']);
			}
		}
		unset($_GET['search_is_active'], $_GET['search_start_date'], $_GET['search_end_date']);
		$data = $this->UserModel->records($where);
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['expired_date'] = iso_date($value['expired_date']);
			$data['data'][$key]['link_whatsapp'] = "https://api.whatsapp.com/send?phone=62".substr($value['phone'],1)."&text=".strip_tags(db_get_one('srs','page_content', array('uri_path'=>'follow-up')))."&source=&data=";


		}
		render('apps/customer/records',$data,'blank');
	}	
	function records_store(){
		$where['id_auth_user'] = $this->session->userdata("id_customer");
		$data = $this->Store_model->records($where);
		foreach ($data['data'] as $key => $value) {
		}
		render('apps/customer/store_records',$data,'blank');
	}	
	
	function store_proses($idedit=''){
		$this->layout 			= 'none';
		load_app_foliopos_database();

		$id_auth_user = $this->session->userdata("id_customer");
		$post 					= purify(null_empty($this->input->post()));
		$ret['error']			= 1;

		$this->form_validation->set_rules('store_name', '"User ID"', 'required'); 

		if ($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		}
		else{   
			$this->db_app_foliopos->trans_start();   
			$where 				= ($idedit) ? "and id not in ($idedit)" : '';
			$cek_code           = db_get_one('auth_user_store',"id","id_auth_user = '$id_auth_user' and is_delete = 0 and store_name = '$post[store_name]' $where");
			if($cek_code){
				$ret['error'] = 1;
				$ret['message'] =  " Store Name $post[store_name] already exsist";
			} else {
				$schema_name = db_get_one('auth_user',"schema_name","id_auth_user = '$id_auth_user'");
				$id_foliopos = $post['id_foliopos'];
				unset($post['id_foliopos']);
				if($idedit){
					auth_update();
					$ret['message'] = 'Update Success';
					$act			= "Update Store Customer";

					$update_foliopos = array(
						'name'           => $post['store_name'],
						'contact_person' => $post['full_name'],
						'email'          => $post['email'],
						'postal_code'    => $post['postal_code'],
						'phone'          => $post['phone'],
						'address'        => $post['address']
					);
					$this->db_app_foliopos->update("$schema_name.store", $update_foliopos, array("id"=>$id_foliopos));
					$this->Store_model->update($post,$idedit);
				}
				else{
					auth_insert();
					$ret['message'] = 'Insert Success';
					$act			= "Insert Store Customer";

					$insert_foliopos = array(
						'name'           => $post['store_name'],
						'contact_person' => $post['full_name'],
						'email'          => $post['email'],
						'postal_code'    => $post['postal_code'],
						'phone'          => $post['phone'],
						'address'        => $post['address'],
						'is_active'      => 1
					);
					$this->db_app_foliopos->insert("$schema_name.store", $insert_foliopos);
					$id_insert_foliopos = $this->db_app_foliopos->insert_id();
					$this->db_app_foliopos->insert("$schema_name.store_register",array('store_id'=>$id_insert_foliopos,'register_name'=>$post['store_name'].' - Cashier','is_active'=>1));

					$post['id_foliopos'] = $id_insert_foliopos;
					$this->Store_model->insert($post);
				}
				detail_log();
				insert_log($act);
				$this->db_app_foliopos->trans_complete();
				$ret['error'] = 0;
			}
			set_flash_session('message',$ret['message']);
		}
		echo json_encode($ret);
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
		
		$this->form_validation->set_rules('userid', '"User ID"', 'required'); 
		$this->form_validation->set_rules('full_name', '"User Name"', 'required'); 
		$this->form_validation->set_rules('email', '"Email"', 'required'); 
		$this->form_validation->set_rules('phone', '"Phone"', 'required'); 
		$this->form_validation->set_rules('id_auth_user_grup', '"User Group"', 'required'); 
		$this->form_validation->set_rules('gender', '"Jenis Kelamin"', 'trim|required');
		$this->form_validation->set_rules('birthdate', '"Tanggal Lahir"', 'trim|required');

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
				$post['birthdate'] = iso_date_custom_format($post['birthdate'],'Y-m-d');
				
				if($idedit){
					auth_update();
					$ret['message'] = 'Update Success';
					$act			= "Update Customer";
					$this->UserModel->update($post,$idedit);
				}
				else{
					auth_insert();
					$ret['message'] = 'Insert Success';
					$act			= "Insert Customer";
					$this->UserModel->insert($post);
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
		$this->UserModel->delete($id);
		detail_log();
		insert_log("Delete User Management");
	}

	function store_del(){
		auth_delete();
		load_app_foliopos_database();
		$id = $this->input->post('iddel');
		$id_auth_user = $this->session->userdata("id_customer");
		$schema_name = db_get_one('auth_user',"schema_name","id_auth_user = '$id_auth_user'");
		$id_foliopos = db_get_one('auth_user_store',"id_foliopos","id = '$id'");;
		$update_foliopos = array(
			'is_delete' => 1
		);
		$this->db_app_foliopos->update("$schema_name.store", $update_foliopos, array("id"=>$id_foliopos));
		$this->Store_model->delete($id);
		detail_log();
		insert_log("Delete User Management");
	}
}

/* End of file Customer.php */
/* Location: ./application/controllers/apps/Customer.php */