<?php
class Newsmodel extends  CI_Model{
	var $table = 'news';
	var $tableAs = 'news a';
	function __construct(){
	   parent::__construct();
	   $this->load->model('model_user');
	   $user = $this->model_user->findById(id_user());
	   $this->approvalLevelGroup = $user['approval_level'];
    
	}
	function records($where=array(),$isTotal=0){
		$grup = $this->session->userdata['ADM_SESS']['admin_id_auth_user_group'];
		$alias['search_uri_path'] = 'a.uri_path';
		$alias['search_status_publish'] = 'c.id';
		$alias['search_id'] = 'a.id';
		$alias['search_news_category'] = 'b.id';
		// $ttl_row = $this->db->get($this->tableAs)->num_rows();

	 	query_grid($alias,$isTotal);
		$this->db->select("a.*,b.name as news_category,c.name as status,d.full_name");
		$this->db->where('a.is_delete',0);
		$this->db->join('news_category b',"b.id = a.id_news_category",'left');
		$this->db->join('status_publish c',"c.id = a.id_status_publish",'left');
		$this->db->join('auth_user d',"d.id_auth_user = a.user_id_create",'left');

		$query = $this->db->get($this->tableAs);
		if($isTotal==0){
			$data = $query->result_array();
		}
		else{
			return $query->num_rows();
		}

		$ttl_row = $this->records($where,1);
		
		return ddi_grid($data,$ttl_row);
	}
	function insert($data){
		$data['create_date'] 	= date('Y-m-d H:i:s');
		$data['user_id_create'] = $data['user_id_create'] ? $data['user_id_create'] : id_user();
		$this->db->insert($this->table,array_filter($data));
		return $this->db->insert_id();
	}
	function update($data,$id){
		$where['id'] = $id;
		$data['user_id_modify'] = id_user();
		$data['modify_date'] 	= date('Y-m-d H:i:s');
		$this->db->update($this->table,$data,$where);
		return $id;
	}
	function delete($id){
		$data['is_delete'] = 1;
		$this->update($data,$id);
	}
	function findById($id){
		$where['a.id'] = $id;
		$where['a.is_delete'] = 0;
		$this->db->select('a.*, b.full_name, c.name as category,c.uri_path as uri_path_category');
		$this->db->join('auth_user b','b.id_auth_user = a.user_id_create');
		$this->db->join('news_category c','c.id = a.id_news_category');

		return 	$this->db->get_where($this->tableAs,$where)->row_array();
	}
	function findBy($where,$is_single_row=0){
		$where['a.is_delete'] = 0;
		$this->db->select('a.*, b.name as category,b.uri_path as uri_path_category');
		$this->db->join('news_category b','b.id = a.id_news_category');
		if($is_single_row==1){
			return $this->db->get_where($this->tableAs,$where)->row_array();
		}
		else{
			return $this->db->get_where($this->tableAs,$where)->result_array();
		}
	} 

	function fetchRow($where) {
		return $this->findBy($where,1);
	}

	function getNewsByCategory($kategori,$limit=4,$controller){
		if($controller=='qanew'){
		    // $where['is_experts'] = 1;
		    // $where['is_qa'] = 1;
		}else if($controller=='expert'){
		    // $where['is_experts'] = 1;
		    // $where['is_qa'] = 0;
		} else {
		    $where['b.uri_path'] = $kategori;
		}
		$where['approval_level']  = 100;
		$where['id_status_publish'] = 2;
		$where['publish_date <='] = date('Y-m-d');
		$this->db->limit(8);
		$this->db->order_by('a.publish_date','desc');
		return $this->findBy($where);
	}
	function newsCounter($id_news){
		$this->db->query("update $this->table set hits = hits + 1 where id = $id_news");

	}

	function getPopularTopic($idTags){
		$where['a.is_delete'] = 0;
		$where['publish_date <='] = date('Y-m-d');
		
		$this->db->select('distinct b.id_news,tags_count');
		$this->db->join('news_tags b', "b.id_tags = a.id");

		
		$data = $this->db->get_where('tags a',$where)->result_array();
		foreach ($data as $value) {
			$id_news[] = $value['id_news'];
		}
		$id_news = array_unique($id_news);
		foreach ($id_news as $key => $id_news) {
			$ret[$key] = $this->fetchRow(array('a.id'=>$id_news));
			if($key==9) break;
		}
		return $ret;
	}
	function getNewsByTags($id_tags,$page){

		$where['a.is_delete'] = 0;
		$where['c.is_delete'] = 0;
		$where['a.id_status_publish'] = 2;
		$where['a.approval_level'] = 100;
		$where['c.id_tags'] = $id_tags;
		$where['publish_date <='] = date('Y-m-d');
		$this->db->select('a.*,b.name as category,b.uri_path as uri_path_category');
		$this->db->join('news_category b','b.id = a.id_news_category');
		$this->db->join('news_tags c','c.id_news = a.id');
		if($page === 'all'){
			$this->db->where($where);
			$this->db->from($this->tableAs);
			return $this->db->count_all_results();

		}
		else{
			$this->db->limit(PAGING_PERPAGE,$page);
			$this->db->order_by('publish_date','desc');
			return $this->db->get_where($this->tableAs,$where)->result_array();
		}

	}

	function getArtikelTerkait($id_news,$id_tags){

		$where['a.is_delete'] 			= 0;
		$where['a.id_status_publish']	= 2;
		$where['a.approval_level']		= 100;
		$where['a.publish_date <='] = date('Y-m-d');
		$this->db->select('distinct a.news_title,a.id,a.img,a.uri_path,a.teaser,b.name as category,b.uri_path as uri_path_category,a.publish_date');
		$this->db->join('news_category b','b.id = a.id_news_category');
		$this->db->join('news_tags c','c.id_news = a.id');
		$this->db->limit(4);
		$this->db->where_in('id_tags',$id_tags);
		$this->db->where('id_news !=',$id_news);
		$this->db->order_by('publish_date','desc');
		return $this->db->get_where($this->tableAs,$where)->result_array();

	}

	//fungsi get_all untuk menampilkan news pada controller/news.php
	function get_all($where=array()){
		$where['a.is_delete'] = 0;
		$this->db->select('a.*');
		return 	$this->db->get_where($this->tableAs,$where);
	}


	//fungsi getNewsByPath untuk menampilkan news pada controller/news.php
	function getNewsByPath($param=array()) {
		$this->db->select("*");
		$this->db->where(array(
			'is_delete' => 0, 
			// 'id_status_publish' => 2, 
			// 'id_news_category' => $param['id_news_category'],
			'uri_path' => $param['uri_path'],
			));
		$data = $this->db->get('news')->row_array();


		return $data;
	}

	//fungsi listNews untuk menampilkan news pada controller/news.php
	function listNews($param) {
		$param['offset'] = ($param['offset']) ? 0 : $param['offset'];
		$this->db->select("*");
	

		$this->db->where(array(
			// 'approval_level' => 100, 
			'is_delete' => 0, 
			'id_status_publish' => 2, 
			// 'publish_date <=' => date('Y-m-d'), 
			'id_news_category' => $param['news_category_id']
			));
		$this->db->order_by('publish_date','desc');
		// exit("cek". $param['offset']);
		
		if ($param['result'] == 'all') {
			$this->db->limit($param['limit'], $param['offset']);
			$data = $this->db->get('news')->result_array();
		}elseif ($param['result'] == 'total') {
			$data = $this->db->get('news')->num_rows();
		}
		return $data;

	}

	//fungsi get_news_last_date untuk menampilkan post news dengan ketentuan insert tanggal terakhir
	function get_news_last_date(){
		$where['is_delete'] = 0;
		$where['id_status_publish'] = 2;
		$this->db->where('publish_date <=', date('Y-m-d h:i:s'));
		$this->db->order_by('publish_date','desc');
		$this->db->limit(1);
		return 	$this->db->get_where($this->table,$where);
	}

	//fungsi get_news_popular untuk menampilkan popular post news
	function get_news_popular($where=array()){
		$where['a.is_delete'] = 0;
		$where['a.id_status_publish'] = 2;
		$this->db->where('a.publish_date <=', date('Y-m-d h:i:s'));
		$this->db->order_by('a.hits','desc');
		$this->db->limit(5);
		return 	$this->db->get_where($this->tableAs,$where);
	}

	//fungsi get_news_related untuk menampilkan related post news
	function get_news_related($where=array()){
		$where['a.is_delete'] = 0;
		$where['a.id_status_publish'] = 2;
		$this->db->select('a.*');
		$this->db->where('a.publish_date <=', date('Y-m-d h:i:s'));
		$this->db->limit(3);
		return 	$this->db->get_where($this->tableAs,$where);
	}

 }
