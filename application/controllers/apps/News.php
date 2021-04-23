<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Newsmodel');
		$this->load->model('Newstagsmodel');
		$this->load->model('Tagsmodel');
	}
	function index(){
		$data['list_news_category'] = selectlist2(array('table'=>'news_category','title'=>'All Category','selected'=>$data['id_news_category'],'where'=>array('is_delete'=>0)));
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));
		render('apps/news/index',$data,'apps');
	}

	public function add($id=''){
		if($id){
			$data = $this->Newsmodel->findById($id);
			if(!$data){
				die('404');
			}
			$data['judul']	= 'Edit';
			$data['proses']	= 'Update';
			$data = quote_form($data);
			$data['publish_date'] = iso_date($data['publish_date']);
			$tags = $this->Newstagsmodel->findBy(array('id_news'=>$id));
			foreach ($tags as $key => $value) {
				$tag .=  ','.$value['tags'];
			}
			$data['tags'] 			= substr($tag,1);
		} else {
			$data['last_edited'] = '';
			$data['last_edited_show'] = 'invis';
			$data['judul']			= 'Add';
			$data['proses']			= 'Simpan';
			$data['news_title']		= '';
			$data['uri_path']		= '';
			$data['teaser']			= '';
			$data['page_content']	= '';
			$data['publish_date']	= date('d-m-Y');
			$data['tags'] 			= '';
			$data['id'] 			= '';
			$data['seo_title']			= '';
			$data['meta_description']	= '';
			$data['meta_keywords']		= '';
			$data['title_tags']				= '';
			$data['alt_tags']				= '';
		}
		$img_thumb				= image($data['img'],'small');
		$imagemanager				= imagemanager('img',$img_thumb);
		$data['img']				= $imagemanager['browse'];
		$data['imagemanager_config']= $imagemanager['config'];

		$data['list_news_category'] = selectlist2(array('table'=>'news_category','title'=>'Select Category','selected'=>$data['id_news_category']));
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'Select Status','selected'=>$data['id_status_publish']));
		$data['list_tags'] = selectlist2(array('table'=>'tags','title'=>'','where'=>array('is_delete'=>0, 'id_status_publish'=>2)));
		$next_approval = $this->Newsmodel->approvalLevelGroup + 1;
		render('apps/news/add',$data,'apps');
	}

	function records(){
		$data = $this->Newsmodel->records();
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['news_title'] 		= quote_form($value['news_title']);
			$data['data'][$key]['publish_date'] 	= iso_date($value['publish_date']);
			$data['data'][$key]['modify_date'] 	= iso_date($value['modify_date']);
		}
		render('apps/news/records',$data,'blank');
	}

	function proses($idedit=''){
		$id_user =  id_user();
		$this->layout 			= 'none';
		$post 					= purify($this->input->post());
		$ret['error']			= 1;
		$where['a.uri_path']		= $post['uri_path'];
		if($idedit){
			$where['a.id !=']	= $idedit;
		}
		$unik 					= $this->Newsmodel->findBy($where);
		$this->form_validation->set_rules('id_news_category', '"Category"', 'required'); 
		$this->form_validation->set_rules('news_title', '"Title"', 'required'); 
		$this->form_validation->set_rules('uri_path', '"Page URL"', 'required'); 
		$this->form_validation->set_rules('id_status_publish', '"Status"', 'required'); 
		$this->form_validation->set_rules('teaser', '"Teaser"', 'required'); 
		$this->form_validation->set_rules('page_content', '"Content"', 'required'); 
		if ($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		}
		else if($unik){
			$ret['message']	= "Page URL $post[uri_path] already taken";
		}
		else{   
			$this->db->trans_start();   
			$post['publish_date'] = iso_date($post['publish_date']);
			$tags = $post['tags'];
			unset($post['tags'],$post['send_approval']);
			if($idedit){
				auth_update();
				$ret['message'] = 'Update Success';
				$act			= "Update News";
				if(!$post['img']){
					unset($post['img']);
				}
				$this->Newsmodel->update($post,$idedit);
			}
			else{
				auth_insert();
				$ret['message'] = 'Insert Success';
				$act			= "Insert News";
				$idedit = $this->Newsmodel->insert($post);
			}
			detail_log();
			$news_data = $this->Newsmodel->findById($idedit);
			
			insert_log($act);
			$this->db->trans_complete();
			$this->session->set_flashdata('message',$ret['message']);
			$ret['error'] = 0;
		}
		echo json_encode($ret);
	}
	function del(){
		auth_delete();
		$this->db->trans_start();   
		$id = $this->input->post('iddel');
		$this->Newsmodel->delete($id);
		detail_log();
		insert_log("Delete News");
		$this->db->trans_complete();
	}
}

/* End of file News.php */
/* Location: ./application/controllers/apps/News.php */