<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @file
 * using for general need
 */

function alamat(){
	 $CI=& get_instance();
	 return $CI->load->view('front/alamat.html');
}


/**
 * list option utk combo box / select list pada grid
 * @author Linda Hermawati 
 * @param $tbl nama tabel
 * @param $id primary key tabel
 * @param $name nama field tabel yg digunakan utk list
 * @param @where (optional) where query tabel
 * @param $terpilih (optional) list terpilih (selected)
 * @param $title (optional) title, default -----------------
 * @return string list option combo box val1:Name 1;val2:Name 2;
 *
 */
function select_grid($tbl,$id='id',$name='name',$where='',$terpilih='',$title='-------'){
	 $CI=& get_instance();
	 $CI->load->database();
	 $list = $CI->db->select("$id , $name")->get_where($tbl,"$id is not null $where order by $name asc")->result_array();
	 $opt = ":select";
	 foreach($list as $l){
				$selected = ($terpilih == $l[$id]) ? 'selected' : '';
				$opt .= ";".$l[$id].":".$l[$name];
	 }
	 return $opt;
}

/**
 * fungsi untuk membuat generate passwrd
 * @author Linda Hermawati 
 * @param $password password
 * @param $panjang untuk menentukan berapa panjang karakter dari password
 * @param $character karakter yang di random
 * @param @where (optional) where query tabel
 * @param $terpilih (optional) list terpilih (selected)
 * @return string untuk password siswa dan orang tua
 *
 */
function generatePassword() {  
    $character = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  
    $password = "";  
	 $panjang = 6;  
	 for($i=0;$i<$panjang;$i++) {
		  $password .= $character[rand(0, 63)];  
	 }  
    return $password;  
}

function null_empty($array){
	 if(is_array($array)){
		  foreach($array as $id => $val){
				$ret[$id] = ($val || $val == '0') ? $val : null;
		  }
		  return $ret;
	 }
}
function id_user($data='id_auth_user'){
	 $CI 			= get_instance();
	 $user_sess = $CI->session->userdata('ADM_SESS'); 
	 $field = 'admin_'.$data;
	 return $user_sess[$field];
}

function group_id(){
	 $CI 			= get_instance();
	 $user_sess 	= $CI->session->userdata('ADM_SESS'); 
	 return $user_sess['admin_id_auth_user_group'];
}
function company_id(){
	 $CI 			= get_instance();
	 return db_get_one('auth_user','company_id',"id_auth_user = ".id_user());
}
/**
 *render untuk merge template dengan content
 *@param $view file name
 *@param $data array data sent to view
 */
function render($view,$data='',$layout='main', $ret=false){
	$CI=& get_instance();
	$data['base_url']                 = $CI->baseUrl;
	$data['url_static_content']       = base_url();
	$data['url_upload_content']       = base_url();
	 if($layout=='apps'){
		$data['is_enable_export_excel'] = (group_id() == 1 or group_id() == 4 or group_id() == 5) ? '' : 'invis';
	 }
	 $data['list_member'] = json_encode($data['list_member']);
	 if($layout=='main'){
		if($user = get_user_session()){
			$data['user_email'] = $user['email'];
			$data['user_full_name'] = $user['full_name'];
			$data['user_avatar'] = '';
			if($user['id_ref_user_category']==1){
				$exhibitor = $CI->db->get_where('exhibitor', array('id' => $user['exhibitor_id']))->row_array();
				if($exhibitor){
					$data['user_avatar'] = image($exhibitor['logo'], 'large');
				}
			}
			if ($user['id_ref_user_category']!=1 && $user['id_ref_user_category']!=2) {
	 			$data['type'] = "hidden";
			}
			
			$data['base64_user_email'] = str_replace("=", "", base64_encode($user['email']));
		} else {
			redirect(base_url()."login");
		}
	 }
	 if(!$CI->data['js_file']){
		  $data['js_file'] = '';
	 }
 	if(!$CI->data['css_file']){
		  $data['css_file'] = '';
	 }
	 if(!$CI->data['template_jquery']){
		  $data['template_jquery'] = '';
	 }
	 if($CI->uri->segment(2)){
	 	$data['product_category'] = $CI->uri->segment(2);
	 }
	//  print_r($CI->uri->segment(1));exit();
	$data['guid'] = '';
	$data['group_name'] = '';
	$data['general'] = '';
	if($CI->uri->segment(1) == "exhibitor" && $CI->uri->segment(2) && $CI->uri->segment(3)){
		$data['base64_guid'] = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $data['name']));
		$data['group_name'] = $data['name'];
		$data['admin_name'] = $data['admin_name'];
		$data['cometchat-js'] = base_url('asset/js/cometchat-group.js');
	} else if ($CI->uri->segment(1) == "meeting-room") {
	} else if ($CI->uri->segment(1) == "") {
		$data['general'] = "general";
		$data['cometchat-js'] = base_url('asset/js/cometchat-public.js');
	} else {
		$data['cometchat-js'] = base_url('asset/js/cometchat-public.js');
	}
	if (($CI->uri->segment(1) == "exhibitor" && $CI->uri->segment(2) == "")||$CI->uri->segment(1) == ""){
		$data['search_box'] = "";
		// $data['list_exhibitor'] = $CI->db->get_where('exhibitor', array('is_delete' => 0))->row_array();
		$data['list_exhibitor'] = selectlist2_exhibitor(array('table'=>'exhibitor','url'=>'uri_path','category'=>'id_exhibitor_category','logo'=>'logo','title'=>'Search Exhibitor', 'where' => 'is_delete = 0'));
	} else {
		$data['search_box'] = "hidden";
	}
	 $data['show_logout'] = (get_user_session()) ? "" : "hidden";
	 $data['app_name'] = APP_NAME;
	 $data['language']		=  LANGUAGE;
	 $data['this_year'] = date('Y');
	 $data['signin'] = '';
	 $data['signout'] = 'hide';
	 $data['breadcrumb'] = breadcrumb();
	 $data['page_title'] = generate_title();
	 $data['current_url'] = current_url();
	 $data['is_https'] = (IS_HTTPS) ? 1 : 0;
	 $data['hidden_pic'] = (id_user("id_auth_user_group")==6) ? "hidden" : "";
	 $lang = $CI->lang->language;
	 if(!$data['page_name']){
		  $data['page_name'] = generate_title();
	 }
	$data['cometchat_appid'] = COMETCHAT_APPID;
	$data['comethat_authKey'] = COMETCHAT_AUTHKEY;
	$data['english_view'] = LANGUAGE == 'english' ? '' : 'hidden';
	$data['indonesia_view'] = LANGUAGE == 'english' ? '' : 'hidden';
	if($layout=='main' && substr_count(current_url(), "asset") <= 0 and substr_count(current_url(), "ajax") <= 0){
		$CI->load->model('Page_hit_model');
		$user_session = $CI->session->userdata('USER_SESS');
		$data_hit['activity'] = 'page_view';
		$data_hit['url'] = str_replace(base_url(), '', current_url());
		$data_hit['user_create_id'] = ($user_session) ? $user_session['id'] : null;
		$data_hit['ip'] = $CI->input->ip_address();
		$CI->Page_hit_model->insert($data_hit);
	}
    // end
	 if(is_array($data)){
		  $CI->data = array_merge($CI->data,$data);
	 }
	 if(!$layout){
		  $CI->parser->parse($view.'.html', $CI->data);
	 }
	 else{
		  $CI->data['content'] = $CI->parser->parse($view.'.html', $CI->data,true);
		  if($ret==true){
			   return $CI->parser->parse("layout/$layout.html",$CI->data,true);
		  }
		  else{
	 		  $CI->parser->parse("layout/$layout.html",$CI->data);
		  }
	 }
	 
}
function list_month($selected=''){
	 $bulan = array(1=>'January','February','March','April','May','June','July','August','September','October','November','December');
	 foreach($bulan as $key => $bln){
		  $terpilih = ($selected == $key) ? 'selected' : '';
		  $opt .= "<option value=\"$key\" $terpilih>$bln</option>";
	 }
	 return $opt;
}
function list_year($selected='',$len=10){
	 $this_year 		= date('Y');
	 $selected			= ($selected == '') ? $this_year : $selected;
	 $year_bef 			= (int)$this_year - $len;
	 $year_aft			= (int)$this_year + $len;
	 $year = range($year_bef,$year_aft);
	 foreach($year as $y){
		  $terpilih = ($selected == $y) ? 'selected' : '';
		  $opt .= "<option $terpilih value=\"$y\">$y</option>";
	 }
	 return $opt;
}

/**
 * Export data to excel/csv/txt
 * @author Siti Hasuna <sh.hanaaa@gmail.com>
 * @param $fname nama file
 */
function export_to($fname, $html){
	$fname = str_replace(".xls", ".csv", $fname);
	$fname = str_replace(' ','_',$fname);
	
	$html = str_get_html($html);

	header('Content-type: application/ms-excel');
	header('Content-Disposition: attachment; filename='.$fname);

	$fp = fopen("php://output", "w");

	foreach($html->find('tr') as $element) {
	  $td = array();
	  foreach( $element->find('th') as $row) {
	    if (strpos(trim($row->class), 'actions') === false && strpos(trim($row->class), 'checker') === false) {
	      $td [] = $row->plaintext;
	    }
	  }
	  if (!empty($td)) {
	    fputcsv($fp, $td);
	  }

	  $td = array();
	  foreach( $element->find('td') as $row) {
	    if (strpos(trim($row->class), 'actions') === false && strpos(trim($row->class), 'checker') === false) {
	      $td [] = $row->plaintext;
	    }
	  }
	  if (!empty($td)) {
	    fputcsv($fp, $td);
	  }
	}

	fclose($fp);
}
/**
 * Add nomor urut
 * @author Siti Hasuna <sh.hanaaa@gmail.com>
 * @param $array datanya
 * @return array dengan tambahan element id urut
 */
function set_nomor_urut($array,$nomor=0){
	 $datas = array();
	 foreach($array as $n =>  $data){
		  $datas[$n]				= $data;
		  $datas[$n]['nomor'] 	= ++$nomor;
	 }
	 return $datas;
}


/**
 * Generate Format Date Time dari mysql style ke format standart atau sebaliknya
 * @author Siti Hasuna <sh.hanaaa@gmail.com>
 * @param $datetime date time format
 * @param $mark (optional) separator date, default -
 * @return string format date time
 */
function iso_date_time($datetime,$mark='-'){
	 if(!$datetime) return;
	 list($date,$time) = explode(' ', $datetime);
	 list($thn,$bln,$tgl) = explode('-',$date);
	 return $tgl.$mark.$bln.$mark.$thn.' '.substr($time, 0,8);
}
/**
 * Generate Format Date dari mysql style ke format standart atau sebaliknya
 * @author Siti Hasuna <sh.hanaaa@gmail.com>
 * @param $datetime date format
 * @param $mark (optional) separator date, default -
 * @return string format date
 */
function iso_date($date,$mark='-'){
	 if(!$date) return;
	 list($thn,$bln,$tgl) = explode($mark,$date);
	 $tgl = explode(' ', $tgl);
	 return $tgl[0].$mark.$bln.$mark.$thn;
}



function generate_time($time,$mark='.'){
	 if(!$time) return;
	 list($jam,$menit) = explode(':',$time);
	 return $jam.$mark.$menit;
}
/**
 * list option utk combo box / select list
 * @author Siti Hasuna <sh.hanaaa@gmail.com>
 * @param $tbl nama tabel
 * @param $id primary key tabel
 * @param $name nama field tabel yg digunakan utk list
 * @param @where (optional) where query tabel
 * @param $terpilih (optional) list terpilih (selected)
 * @param $title (optional) title, default -----------------
 * @return string list option combo box <option value='val1>Name 1</option><option value='val2>Name 2</option>...
 *
 */
function select($tbl,$id='id',$name='name',$where='',$terpilih='',$title=''){
	 $CI=& get_instance();
	 $CI->load->database();
	 $list = $CI->db->select("$id , $name")->get_where($tbl,"$id is not null $where order by $name asc")->result_array();
	 $opt = "<option value=''>select</option>";
	 foreach($list as $l){
				$selected = ($terpilih == $l[$id]) ? 'selected' : '';
				$opt .= "<option $selected value='$l[$id]'> $l[$name]</option>";
	 }
	 return $opt;
}
/**
 *
 * function select versi 2 - list option utk combo box / select list
 * @author Siti Hasuna <sh.hanaaa@gmail.com>
 * @param $tbl nama tabel
 * @param $id primary key tabel
 * @param $name nama field tabel yg digunakan utk list
 * @param $where (optional) where query tabel
 * @param $selected (optional) item selected
 * @param $title (optional) title, default -----------------
 * @return string list option combo box <option value='val1>Name 1</option><option value='val2>Name 2</option>...
 *
 */
function selectlist($tbl,$id='id',$name='name',$where=null,$selected='',$title='select',$order=''){
	 $CI=& get_instance(); 
	 $CI->load->database();
	 $or = (empty($order) ? $id : $order);
	 $CI->db->order_by($or,'asc');
	 $list = $CI->db->select("$id , $name")->get_where($tbl,$where)->result_array();
	 $opt = "<option value=''>$title</option>";

	 if (is_array($selected)) {
	 	 foreach($list as $l){
	 	 		$terpilih = '';
	 	 		foreach ($selected as $slc) {
					$cek = ($slc == $l[$id]) ? 'selected' : '';

					if ($cek != '') {
						$terpilih	= $cek;
					}
	 	 		}
				$opt .= "<option $terpilih value='$l[$id]'> $l[$name]</option>";
		 }
	 }
	 else{
		 foreach($list as $l){
					$terpilih = ($selected == $l[$id]) ? 'selected' : '';
					$opt .= "<option $terpilih value='$l[$id]'> $l[$name]</option>";
		 }
	 }
	 return $opt;
}
/**
 * fungsi untuk menambah hari dalam format y-m-d. contoh : add_date('2012-01-01', 3) // return 2012-01-04
 * @author Siti Hasuna <sh.hanaaa@gmail.com>
 * @param $dateSql tanggal dalam format sql (y-m-d)
 * @param $jmlHari jumlah hari yg ditambahkan
 * @return date
 *
 */

function add_date($dateSql,$jmlHari){
	 $sql = "SELECT DATE_ADD('$dateSql', INTERVAL $jmlHari DAY) as tanggal";
	 $CI=& get_instance();
	 return $CI->db->query($sql)->row()->tanggal;
}
/**
 * fungsi mendapatkan data hasil query dalam bentuk string (1 field saja yg return)
 * @author Siti Hasuna <sh.hanaaa@gmail.com>
 * @param $table nama tabel
 * @param $field nama kolom
 * @param $where (optional) where kondisi
 * @return string
 *
 */
function db_get_one($table,$field,$where=''){
	 $CI=& get_instance();
	 if($where != ''){
		$data = $CI->db->select($field)->get_where($table,$where)->row();
	 	return $data ? $data->$field : null;
	}
	else{
		$CI->db->select($field)->get($table)->row();
		return $data ? $data->$field : null;
	}
	 
}
/**
 * Javascript Alert Function
 * @author Siti Hasuna <sh.hanaaa@gmail.com>
 * @param $alert_message alert message yg ditampilkan dalam dialog box
 * @return string javascript <script>alert(message)</script>
 */
function alert($alert_message){
	 if($alert_message != ''){
	 	 return "<script>$(document).ready(function(){notify('$alert_message','success')})</script>";
	 }
}

/**
 * Untuk mendapatkan data via url seperti $_GET
 * @author Siti Hasuna <sh.hanaaa@gmail.com>
 * @param $keyword string contoh http://example.com/id/1/name/example ;get('id') return 1; get('name') return example
 *	@param $return_if_null (optional) return value if keyword is null
 * @return string

 */
function get($keyword,$return_if_null=''){
	 $arr 	= array('http://','https://','https://www.','http://www.');
	 $host	= str_replace($arr,'',base_url());
	 $host 	= array($host,'apps/');
	 $uri 	= explode('/',str_replace($host,'',$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL']));
	 foreach ($uri as $key => $val){
		if($key > 1){
			if($key % 2 == 0){
				if($val != ''){
					$data[$val] = $uri[$key+1];
				}
			}
		}
	 }
	 return ($data[$keyword]=='') ? $return_if_null : $data[$keyword];
}
/**
 *generate angka 0 didepan variabel contoh : 0000001, 0000123
 *@param $var number variable angka dibelakang
 *@param $len jumlah digit yg diinginkan
 *@example zero_first(1,3) return 001; zero_first(12,5) return 00012;
 */
function zero_first($var,$len){
	return sprintf("%0{$len}s",$var);
}
/**
 *Show array data
 */
function debugvar ($datadebug){
	 echo "<pre>";
	 print_r ($datadebug);
	 echo "</pre>";
}

function cek_file_size($file_size, $max_size=2097152){
	 if ($file_size > $max_size || $file_size =='') {
		  die('Error, Max File Size Is :' .($max_size/1024).' Kb');
	 };
}
function cek_req($field,$title){
	 $img		= "<img src='".base_url()."assets/images/error.gif'>";
	 $CI=& get_instance();
	 if($field==''){
			$err = "$img $title !<br>";
	 }
	 return $err;
	 
}

function button_name($idedit){
	 $CI=& get_instance();
	 if($idedit){
		  $proses 						= 'Update';
		  $btn							= 'Update';
	 }
	 else{
		  $proses 						= 'Add';
		  $btn							= 'Simpan';
	 }
	 $CI->data['button'] 			= $btn;
	 $CI->data['proses'] 			= $proses;
	 $CI->data['idedit'] 			= $idedit;
}
function clear_html($html){
	 $html =  str_replace("\n","",$html);
	 $html =  str_replace("\r"," ",$html);
	 return str_replace ("	",'',(trim(strip_tags($html))));
}

function download_button($dir,$file,$id,$link=true,$alias=''){
	 $alias = ($alias=='') ? $file : $alias;
	 if($file){
		  $CI=& get_instance();
		  $files = base64_encode($id.'_'.$file);
		  $dir = base64_encode($dir);
		  $form_name = rand(0,999999999);
		  $form  = "<form method='post' action='".base_url()."apps/home/download' name='f$form_name' id='f$form_name'>";
		  $form .= "<input type='hidden' name='dir' value='$dir'>";
		  $form .= "<input type='hidden' name='file' value='$files'>";
		  //$form .= "<input type='submit' value='$file'>";
		  $form .= ($link==true) ? "<a href='javascript:document.f$form_name.submit()'>$alias</a>" : '';
		  $form .= '</form>';
		  //$f['form_link'] = "<a href='javascript:document.f$form_name.submit()'>$file</a>";
		  //$f['form']		= $form;
		  if($link==true){
				return $form;
		  }
		  else{
				$CI->data['form'] .= $form;
				return "<a href='javascript:document.f$form_name.submit()'>$alias</a>";
		  }
		  return $form;
	 }
}

function upload($tmp,$path,$desc=''){
	 $CI=& get_instance();
	 $ext								= strtolower(end(explode('.',$path)));
	 $fname							= end(explode('/',$path));
	 //cek_file_size(filesize($tmp));
	 move_uploaded_file($tmp,$path);
	 if($ext == 'pdf' || $ext == 'doc'){
		  $end 						= (strlen($path)-3);
		  $txt						= substr($path,0,$end).'txt';
		  if($ext=='pdf'){
				exec(" \"C:\xpdf\bin32\pdftotext.exe\" \"$path\" ");
		  }
		  else if($ext=='doc'){
				exec(" \"C:\antiword\antiword.exe\" \"$path\"  > \"$txt\" ");
		  }
		  $data['content'] 		= file_get_contents($txt);
		  $data['description'] 	= $desc;
		  $data['path'] 			= path($path);
		  $data['file_name'] 	= $fname;
		  $data['file_type'] 	= $ext;
		  $data['adv_search'] 	= $CI->uri->segment(2);		  
		  $CI->db->insert('content_file',$data);
		  unlink($txt);
	 }
	 
	 //echo $tmp;
	 //echo $exp;
}
// relative path buat simpen ke tabel content_file
function path($path){
	 return str_replace(UPLOAD_DIR,'',$path);
}

function delete_content_file($path){
	 $CI=& get_instance();
	 unlink($path);	 
	 $CI->db->delete('content_file',"path = '".path($path)."'");
}
function export_to_pdf($fname){
	 if(get('token')==''){
		 $url 			=  current_url().'/token/'. md5(date('dmy') . '1qazxsw2');
		 $token =  '/token/'.md5(date('dmy') . '1qazxsw2');
		 $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		 $url = str_replace('?',$token.'?',$url);
		 //echo $url;
		 //exit;
		 $temp_file 	= UPLOAD_DIR.'tmp/'.rand(1,99999).'.pdf';
		 exec(" \"C:/Program Files (x86)/wkhtmltopdf/wkhtmltopdf.exe\" \"$url\"  \"$temp_file\" ");
		 export_to($fname);
		 echo file_get_contents($temp_file);
		 unlink($temp_file);
		 exit;
	 }
}
/**
 * utk form jika di variable stringnya ada kutip
 * @author Siti Hasuna <sh.hanaaa@gmail.com>
 * @param $string string yg ingin ditampilkan dalam form
 */
function quote_form($string){
	 if(is_array($string)){
		  foreach($string as $key=>$val){
				$new_str[$key] = htmlspecialchars($val, ENT_QUOTES);
		  }
		  return $new_str;
	 }
	 else{
		  return htmlspecialchars($string, ENT_QUOTES);
	 }
}
function help($modul){
	 $CI		= & get_instance();
	 $helps	= $CI->db->get_where('tooltips',"module = '$modul'")->result_array();
		foreach($helps as $help){
			$key 	= $help['field_key'];
			$tips = $help['tips'];
			$CI->data[$key] = ($tips && $help['publish']=='Yes')?"<span class='tooltips'><a href='#$key' class='tips'></a><div id='$key' style='display:none'>$tips</div></span>":'';
		}
}
function date_range2($strDateFrom,$strDateTo)
{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom)
    {
        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange,date('Y-m-d',$iDateFrom));
        }
    }
    return $aryRange;
}

function date_range($strDateFrom,$strDateTo){
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

    // echo $iDateFrom.'<br>';
    // echo $iDateTo.'<br>';
	//echo $iDateFrom;echo '<br>';
	//echo $strDateFrom;echo '<br>';
	//echo substr($strDateFrom,5,2);echo '<br>';
	//echo substr($strDateFrom,8,2);echo '<br>';
	//echo substr($strDateFrom,0,4);
	//
	//die();
    if ($iDateTo>=$iDateFrom){
        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo){
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange,date('Y-m-d',$iDateFrom));
        }
    }
    return $aryRange;
}

// include header
function panggil_banner($img){
	 $CI		= & get_instance();
	 $CI->data['img'] = $img;
	 return $CI->parser->parse('home/header.html',$CI->data,true);
}

function tgl_indo($tanggal){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);

	return $pecahkan[0] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[2];
}
function insert_log($aktifitas){
	 $CI					= & get_instance();
	 $data['activity'] 		= $aktifitas;
	 $data['detail'] 		= $CI->detail_log;
	 $data['ip'] 			= $_SERVER['REMOTE_ADDR'];
	 $data['id_auth_user'] 	= id_user();
	 $data['log_date'] =  date('Y-m-d H:i:s');
	 $CI->db->insert('access_log',$data);
}
function detail_log(){
	 $CI = & get_instance();
	 $CI->detail_log .= $CI->db->last_query() .";\n";

}
function arr_to_str($data){
	 foreach ($data as $key => $val){
		  $ret .="$key : $val <br>";
	 }
	 return $ret;
}
function selectlist2($conf){
	$CI				 = &get_instance();
	$tbl 			 = $conf['table'];
	$is_encrypt		 = ($conf['is_encrypt']) ? 1 : 0;
	$id				 = ($conf['id']) ? $conf['id'] : 'id';
	$idx             = $is_encrypt ? md5field($id,$id) : $id; 
	$name			 = ($conf['name']) ? $conf['name'] : 'name';
	$where			 = $conf['where'];
	$selected		 = $conf['selected'];
	$title			 = ($conf['title']) ? $conf['title'] : '=== Pilih ==='; //$conf['title'];
	$order			 = $conf['order'];
	$or 			 = (empty($order) ? $name : $order);
	$list 			 = $CI->db->order_by($or,'asc')->select("$idx , $name")->get_where($tbl,$where)->result_array();
	$opt 			 = $conf['no_title'] ? '' : "<option value=''>$title</option>";
	$opt			.= ($conf['add_new']) ? ("<option value='addNew'>+ Add $conf[add_new]</option>"): '';
	foreach($list as $l){
		$terpilih 	 = ($selected == $l[$id]) ? 'selected' : '';
		$opt 		.= "<option $terpilih value='$l[$id]'> $l[$name]</option>";
	}
	return $opt;
}
function selectlist2_training($conf){
	$CI				 = &get_instance();
	$tbl 			 = $conf['table'];
	$is_encrypt		 = ($conf['is_encrypt']) ? 1 : 0;
	$id				 = ($conf['id']) ? $conf['id'] : 'id';
	$idx             = $is_encrypt ? md5field($id,$id) : $id; 
	$where			 = $conf['where'];
	$selected		 = $conf['selected'];
	$title			 = ($conf['title']) ? $conf['title'] : '=== Pilih ==='; //$conf['title'];
	$order			 = $conf['order'];
	$or 			 = (empty($order) ? $name : $order);
	$list 			 = $CI->db->order_by($or,'asc')->select("$idx , starttime, endtime")->get_where($tbl,$where)->result_array();
	$opt 			 = $conf['no_title'] ? '' : "<option value=''>$title</option>";
	$opt			.= ($conf['add_new']) ? ("<option value='addNew'>+ Add $conf[add_new]</option>"): '';
	foreach($list as $l){
		$terpilih 	 = ($selected == $l[$id]) ? 'selected' : '';
		$opt 		.= "<option $terpilih value='$l[starttime] - $l[endtime]'> $l[starttime] - $l[endtime]</option>";
	}
	return $opt;
}
function selectlist2_meeting($conf){
	$CI				 = &get_instance();
	$tbl 			 = $conf['table'];
	$is_encrypt		 = ($conf['is_encrypt']) ? 1 : 0;
	$id				 = ($conf['id']) ? $conf['id'] : 'id';
	$idx             = $is_encrypt ? md5field($id,$id) : $id; 
	$name			 = ($conf['name']) ? $conf['name'] : 'name';
	$email			 = ($conf['email']) ? $conf['email'] : 'email';
	$where			 = $conf['where'];
	$selected		 = $conf['selected'];
	$title			 = ($conf['title']) ? $conf['title'] : '=== Pilih ==='; //$conf['title'];
	$order			 = $conf['order'];
	$or 			 = (empty($order) ? $name : $order);
	$list 			 = $CI->db->order_by($or,'asc')->select("$idx , $name, $email")->get_where($tbl,$where)->result_array();
	$opt 			 = $conf['no_title'] ? '' : "<option value=''>$title</option>";
	$opt			.= ($conf['add_new']) ? ("<option value='addNew'>+ Add $conf[add_new]</option>"): '';
	foreach($list as $l){
		$terpilih 	 = ($selected == $l[$id]) ? 'selected' : '';
		$opt 		.= "<option $terpilih value='$l[$id]'> $l[$email] ($l[$name])</option>";
	}
	return $opt;
}
function selectlist2_exhibitor($conf){
	$CI				 = &get_instance();
	$tbl 			 = $conf['table'];
	$is_encrypt		 = ($conf['is_encrypt']) ? 1 : 0;
	$id				 = ($conf['id']) ? $conf['id'] : 'id';
	$idx             = $is_encrypt ? md5field($id,$id) : $id; 
	$name			 = ($conf['name']) ? $conf['name'] : 'name';
	$url			 = ($conf['url']) ? $conf['url'] : 'url';
	$logo			 = ($conf['logo']) ? $conf['logo'] : 'logo';
	$category		 = ($conf['category']) ? $conf['category'] : 'category';
	$where			 = $conf['where'];
	$selected		 = $conf['selected'];
	$title			 = ($conf['title']) ? $conf['title'] : '=== Pilih ==='; //$conf['title'];
	$order			 = $conf['order'];
	$or 			 = (empty($order) ? $name : $order);
	$list 			 = $CI->db->order_by($or,'asc')->select("$idx , $name, $url, $logo, $category")->get_where($tbl,$where)->result_array();
	$opt 			 = $conf['no_title'] ? '' : "<option value=''>$title</option>";
	$opt			.= ($conf['add_new']) ? ("<option value='addNew'>+ Add $conf[add_new]</option>"): '';
	foreach($list as $l){
		$terpilih 	 = ($selected == $l[$id]) ? 'selected' : '';
		$opt 		.= "<option $terpilih data-url='$l[$url]' data-logo='$l[$logo]' data-category='$l[$category]' value='$l[$id]'> $l[$name]</option>";
	}
	return $opt;
}
function list_account($type){
	 return 		selectlist2(array('table'=>'account','name'=>'account_name','no_title'=>1,'where'=>array('type'=>$type,'company_id'=>company_id())));

}

function paging($total_row,$url,$perpage=10,$uri_segment=4){
	 $CI = &get_instance();
	 $CI->load->library('pagination');
	 $config['uri_segment'] 	= $uri_segment;
	 $config['base_url'] 		= $url;
	 $config['total_rows'] 		= $total_row;
	 $config['per_page'] 		= $perpage;
	 $config['anchor_class'] 	= 'class="paging" ';
	 $CI->pagination->initialize($config);
	 return	 $CI->pagination->create_links();
}

function current_controller($param=''){
	//$param						= '/'.$param;
	$CI 						= & get_instance();
	$dir						= $CI->router->directory;
	$class						= $CI->router->fetch_class();
	$func						= ($param=='function') ? ('/'.$CI->router->fetch_method()) : "/$param";
	$base_url					= str_replace('http://'.$_SERVER['HTTP_HOST'],'',base_url());
	$data['base_url']			= str_replace('https://'.$_SERVER['HTTP_HOST'],'',$base_url);//jika https
	return $data['base_url'].$dir.$class.$func;
}
//mygrid
function query_grid($alias,$isTotal=0){
	 $CI 					= &get_instance();
	 $param 				= $_GET;
	 $where					= where_grid($param,$alias);
	 $sort_field	= ($param['sort_field']) ? $param['sort_field'] : 'id';
	 $sort_type		= ($param['sort_type']) ? $param['sort_type'] : 'desc';
	 
	 $CI->db->order_by(str_replace('-','.',$sort_field),$sort_type);
	 if($isTotal!=1){
		$CI->db->limit($param['perpage'],$param['page']);
	 }
}

//mygrid
function query_grid_api($alias,$isTotal=0){
	 $CI         = &get_instance();
	 $param      = $_POST;
	 $where      = where_grid($param,$alias);
	 $sort_field = ($param['sort_field']) ? $param['sort_field'] : 'id';
	 $sort_type  = ($param['sort_type']) ? $param['sort_type'] : 'desc';
	 
	 $CI->db->order_by(str_replace('-','.',$sort_field),$sort_type);
	 if($isTotal!=1){
		$CI->db->limit($param['limit'],$param['offset']);
	 }
}

/**
 * Default grid in Deptech (for API only)
 * @param  [array]  	$data        	[data in array]
 * @param  integer  	$ttl_row     	[total data]
 * @param  integer 		$ttl_filter 	[total filtered data]
 * @return [array]               		[data]
 */
function ddi_grid_api($data, $ttl_row)
{
	$return['data']   = set_nomor_urut($data, $_POST['offset']);
	$return['paging'] = paging_grid_api($ttl_row, $ttl_filter);
	return $return;
}

/**
 * Paging in grid function (for API only)
 * @param  integer  	$total_row   	[total of row]
 * @param  integer 		$total_filter 	[total filtered data]
 * @return [tag html]               	[paging]
 */
function paging_grid_api($total_row, $total_filter)
{	

	$CI 	= & get_instance();
	$param 	= $_POST;
	$CI->load->library('pagination');
	$config['base_url'] 		= current_controller('function');
	$config['total_rows'] 		= $total_row;
	$config['uri_segment'] 		= 5;
	$config['attributes'] 		= array('class' => 'page-link');
	$config['per_page'] 		= $param['limit'];
	$config['first_tag_open'] 	= '<li class="page-item">';
	$config['first_tag_close'] 	= '</li>';
	$config['first_link'] 		= '<<';
	$config['last_link'] 		= '>>';
	$config['num_tag_open'] 	= '<li class="page-item">';
	$config['num_tag_close'] 	= '</li>';
	$config['last_tag_close'] 	= '</li>';
	$config['last_tag_open'] 	= '<li class="page-item">';
	$config['first_tag_close'] 	= '</li>';
	$config['first_tag_open'] 	= '<li class="page-item">';
	$config['next_link'] 		= '>';
	$config['prev_link'] 		= '<';
	$config['prev_tag_open'] 	= '<li class="page-item">';
	$config['prev_tag_close'] 	= '</li>';
	$config['next_tag_open'] 	= '<li>';
	$config['next_tag_close']	= '</li>';
	$config['next_tag_open'] 	= '<li class="page-item">';
	$config['next_tag_close']	= '</li>';
	$config['cur_tag_open'] 	= '<li class="page-item active"><a class="page-link">';
	$config['cur_tag_close'] 	= '</a></li>';
	$CI->pagination->initialize($config);
	
	$n 		 = $param['page'];
	$n2 	 = $n+1;
	$sd 	 = $n + $param['limit'];
	$sd 	 = ($total_row < $sd) ? $total_row : $sd;
	$remark	 = ($sd > 0) ? ("$n2 - $sd Total $total_row") : '';
	$paging  = '<ul class="pagination mb-0">';
	$paging .= $CI->pagination->create_links();
	$paging .= '</ul>';
	return $paging;
}
function ddi_grid($data,$ttl_row,$uri_segment=4,$extra_param=''){
	$CI 					= & get_instance();
	$pagination = (int)$CI->uri->segment(4) ? $CI->uri->segment(4) : 0;
	if ($uri_segment==5) {
		$pagination = (int)$CI->uri->segment(4) ? $CI->uri->segment(5) : 0;
	}
	$data['data'] = set_nomor_urut($data,$pagination);
	$data['paging'] = paging_grid($ttl_row,$uri_segment,'',$extra_param);
	return $data;
}
function paging_grid($total_row,$uri_segment=4,$style=0,$extra_param=''){
	$CI 	= & get_instance();
	$param 	= $_GET;
	$CI->load->library('pagination');
	// $config['base_url'] 		= current_controller('function');
	$config['base_url'] 		= current_controller('function').'/'.$extra_param.'/';
	$config['total_rows'] 		= $total_row;
	$config['uri_segment'] 		= $uri_segment;
	$config['anchor_class'] 	= 'class="tangan"';
	$config['per_page'] 		= $param['perpage'];
	$config['first_tag_open'] 	= '<li>';
	$config['first_tag_close'] 	= '</li>';
	$config['first_link'] 		= '<<';
	$config['last_link'] 		= '>>';
	$config['num_tag_open'] 	= '<li>';
	$config['num_tag_close'] 	= '</li>';
	$config['last_tag_close'] 	= '</li>';
	$config['last_tag_open'] 	= '<li>';
	$config['first_tag_close'] 	= '</li>';
	$config['first_tag_open'] 	= '<li>';
	$config['next_link'] 		= '>';
	$config['prev_link'] 		= '<';
	$config['prev_tag_open'] 	= '<li>';
	$config['prev_tag_close'] 	= '</li>';
	$config['next_tag_open'] 	= '<li>';
	$config['next_tag_close']	= '</li>';
	$config['next_tag_open'] 	= '<li>';
	$config['next_tag_close']	= '</li>';
	$config['cur_tag_open'] 	= '<li class="active"><a>';
	$config['cur_tag_close'] 	= '</a></li>';
	$CI->pagination->initialize($config);
	
	$n 		 = $param['page'];
	$n2 	 = $n+1;
	$sd 	 = $n + $param['perpage'];
	$sd 	 = ($total_row < $sd) ? $total_row : $sd;
	$remark	 = ($sd > 0) ? ("$n2 - $sd Total $total_row") : '';
	if($style==0){
		$paging  = '<div class="col-sm-6 col-md-6 col lg-6"><span class="show_page">'.$remark.'</span><span class="paging-select"></span></div>
				   <div class="paginationcol-sm-6 col-md-6 col lg-6"><ul class="pagination m-t-0 m-b-10  pull-right ">';
	} else {
		$paging  = '<div class="paginationcol-sm-6 col-md-12 col lg-6 pagination-userpage"><ul class="pagination m-t-0 m-b-10 ">';		
	}
	$paging .= $CI->pagination->create_links();
	$paging .= '</ul></div>';
	return $paging;
}
function where_grid($param,$alias,$type=0){
	// foreach($param as $key=>$val){
	// 	if(substr($key,0,6)=='search'){
	// 		$field  = ($alias[$key]!='') ? $alias[$key] : substr($key,7);
	// 		if($val){
	// 			// $where .= "and $field like '%$val%' ";
 //                $field_explode = explode('_',$field,-1);
 //                 if($field=='a.datestart'){
	// 				$CI->db->where("a.create_date >=",iso_date_custom_format($val,'Y-m-d'));
	// 			}else if($field=='a.dateend'){
	// 				$CI->db->where("a.create_date <=",iso_date_custom_format($val,'Y-m-d'));
	// 			} else if($field_explode[0]!='or'){
 //                    $CI->db->like($field, "$val");
 //                } else {
 //                    $CI->db->or_like(str_replace('or_','',$field), "$val");
 //                }
	// 		}
	// 	} else if(substr($key,0,7)=='between'){
	// 		$start = (strpos($key,'to') ? 11 : 8);
	// 		$field  = ($alias[$key]!='') ? $alias[$key] : substr($key,$start);
	// 		if($val){
	// 			$explode = explode('.', $field);
	// 			if($field == 'a.daterange'){					
	// 				$from = iso_date_custom_format($param['between_from'],'Y-m-d');
	// 				$to = iso_date_custom_format($param['between_to'],'Y-m-d','+1 day');
	// 				if($param['between_from'] != '' && $param['between_to'] != '') {
	// 					$CI->db->where("a.date between '$from' and '$to'");
	// 				} else if($param['between_from'] != '' && $param['between_to'] == '') {
	// 					$to = iso_date_custom_format(date('Y-m-d'),'Y-m-d','+1 day');
	// 					$CI->db->where("a.date between '$from' and '$from 23:59:59'");
	// 				}
	// 			} else { //$field == appropriate field name on database/not using alias name
	// 				$field_explode = (count($explode) > 1) ? '_'.$explode[1] : '_'.$explode[0];
	// 				$field = substr($field_explode,1);
	// 				$from = iso_date_custom_format($param['between_from'.$field_explode],'Y-m-d');
	// 				$to = iso_date_custom_format($param['between_to'.$field_explode],'Y-m-d','+1 day');
	// 				if($param['between_from'.$field_explode] != '' && $param['between_to'.$field_explode] != '') {
	// 					$CI->db->where('a.'.$field." between '$from' and '$to'");
	// 				} else if($param['between_from'.$field_explode] != '' && $param['between_to'.$field_explode] == '') {
	// 					$to = iso_date_custom_format(date('Y-m-d'),'Y-m-d','+1 day');
	// 					$CI->db->where('a.'.$field." between '$from' and '$from 23:59:59'");
	// 				}
	// 			}
	// 		}
	// 	}
	// }
	$CI = & get_instance();
	foreach($param as $key=>$val){
		if($type == 0) {
			if(substr($key,0,6)=='search'){
				$field  = ($alias[$key]!='') ? $alias[$key] : substr($key,7);
				if(($val or $val===0 or $val==="0") and $val != "null"){
					// $where .= "and $field like '%$val%' ";
	                $field_explode = explode('_',$field,-1);
	                if(substr($key,7)=='activity_id'){
	                	$val_data = explode(',', $val);
		                foreach($val_data as $dst){
							$data_mst[] = $dst;
						}
	                	$CI->db->where_in('b.id', $data_mst);
	                }else if($field=='publish_date_start'){
						$CI->db->where("a.expired_date >=",iso_date($val));
					}else if($field=='publish_date_end'){
						$CI->db->where("a.expired_date <=",iso_date($val));
					}else if($field=='start_date1'){
						$CI->db->where("a.start_date >=",iso_date($val));
					}else if($field=='start_date2'){
						$CI->db->where("a.start_date <=",iso_date($val));
					}else if($field=='end_date1'){
						$CI->db->where("a.end_date >=",iso_date($val));
					}else if($field=='end_date2'){
						$CI->db->where("a.end_date <=",iso_date($val));
					} else if($field=='subject') {
	                    $CI->db->where("subject like '%$val%' or description like '%$val%'");
	                } else if($field=='article') {
	                    $CI->db->where("(page_content like '%$val%' or news_title like '%$val%' or teaser like '%$val%')");
	                } else if($field=='forum') {
	                    $CI->db->where("(a.title like '%$val%' or a.description like '%$val%')");
	                } else if($field=='member_open' || $field=='admin_open') {
	                    $CI->db->where($field, $val);
	                } else if($field_explode[0]=='and'){
	                    $CI->db->where(str_replace('and_','',$field), $val);
	                } else if($field_explode[0]!='or'){
	                    $CI->db->like($field, "$val");
	                } else {
	                    $CI->db->or_like(str_replace('or_','',$field), "$val");
	                }
				}
			}
			else if(substr($key,0,7)=='between'){
				$start = (strpos($key,'to') ? 11 : 8);
				$field  = ($alias[$key]!='') ? $alias[$key] : substr($key,$start);
				if($val){
					$explode = explode('.', $field);
					if($field == 'a.daterange'){					
						$from 	= iso_date_custom_format($param['between_from'],'Y-m-d');
						$to 	= iso_date_custom_format($param['between_to'],'Y-m-d','+1 day');
						if($param['between_from'] != '' && $param['between_to'] != '') {
							$CI->db->where("a.date between '$from' and '$to'");

						} else if($param['between_from'] != '' && $param['between_to'] == '') {
							$to = iso_date_custom_format(date('Y-m-d'),'Y-m-d','+1 day');
							$CI->db->where("a.date between '$from' and '$from 23:59:59'");
						}
					} else { 
						//$field == appropriate field name on database/not using alias name
						$field_explode 	= (count($explode) > 1) ? '_'.$explode[1] : '_'.$explode[0];
						$field 			= substr($field_explode,1);
						$from 			= iso_date_custom_format($param['between_from'.$field_explode],'Y-m-d');
						$to 			= iso_date_custom_format($param['between_to'.$field_explode],'Y-m-d','+1 day');
						if($param['between_from'.$field_explode] != '' && $param['between_to'.$field_explode] != '') {
							$CI->db->where('a.'.$field." between '$from' and '$to'");
						} else if($param['between_from'.$field_explode] != '' && $param['between_to'.$field_explode] == '') {
							$to = iso_date_custom_format(date('Y-m-d'),'Y-m-d','+1 day');
							$CI->db->where('a.'.$field." between '$from' and '$from 23:59:59'");
						}
					}
				}
			}
		} else {
			if($val){
				$CI->db->like($alias[$key], "$val");
			}
		}
	}
	return $where;
}
function filename($fname){
	 //$fname = "~!@#$%^&asdfj.abc.def.ghi.asdflkj.jpg";
	 $ext 					= explode('.',$fname);
	 $length 				= count($ext)-1;
	 $extension				= $ext[$length];
	 unset($ext[$length]);
	 $fname = implode('-',$ext);
	 return date('ymdHis').'-'.url_title($fname).'.'.$extension;
		
}

function get_day($tgl=''){
	 $day_in_eng = date('l',$tgl);
	 if ($day_in_eng == 'Sunday') return "Minggu";
	 else if ($day_in_eng == 'Monday') return "Senin";
	 else if ($day_in_eng == 'Tuesday') return "Selasa";
	 else if ($day_in_eng == 'Wednesday') return "Rabu";
	 else if ($day_in_eng == 'Thursday') return "Kamis";
	 else if ($day_in_eng == 'Friday') return "Jumat";
	 else if ($day_in_eng == 'Saturday') return "Sabtu"; 
}

function number_formating($data,$field,$ttl_comma=0){
	 foreach ($data as $index => $value){
		  foreach($value as $idx => $val){
			   if($idx == $field){
					$data[$index][$idx] = number_format($val,$ttl_comma);
			   }
		  }
	 }
	 return $data;
}
function initialize_elfinder($value=''){
	$CI =& get_instance();
	$opts = array(
	    //'debug' => true, 
	    'roots' => array(
	      array( 
	        'driver' => 'LocalFileSystem', 
	        'path'   => ELFINDER_PATH_UPLOAD, 
	        'URL'    => base_url('assets/files').'/',
	        'alias' => 'Files',
			'uploadMaxSize' => '20M',
			'attributes' => array(
				array(
					'pattern' => '/\.tmb$/',
					'read' => false,
					'write' => false,
					'locked' => true,
					'hidden' => true
				),
				array(
					'pattern' => '/\.svn$/',
					'read' => false,
					'write' => false,
					'locked' => true,
					'hidden' => true
				),
				array(
					'pattern' => '/\.quarantine$/',
					'read' => false,
					'write' => false,
					'locked' => true,
					'hidden' => true
				)
			),
	      )
	    )
	);
  	$CI->load->library('elfinder_lib', $opts);
}

function get_flash_session($name){
	$CI=& get_instance();
	$data = $CI->session->userdata($name);
	//$CI->session->unset_userdata($name);
	return $data;
}

/**
* Set flash session
* @author Agung Trilaksono Suwarto Putra <agungtrilaksonosp@gmail.com>
* @return command;
* @param string $name  Flash session name to be called;
* @param string $value  Flash session value to be called;
*/
function set_flash_session($name,$value){
	$CI=& get_instance();
	return $CI->session->set_userdata($name,$value);
}

function image($img,$path,$ret=0){
	$path = "$path/";
	$path = str_replace('//', '', $path);
	$CI=& get_instance();
	$no_img = $ret == '404' ? base_url().'asset/images/404.png' : (base_url().'images/article/'.$path.'no_image.png');
	
	if($ret != 1 && $ret !== 0){
		$cekThumb = is_file_exsist(UPLOAD_DIR.$path,$ret);
		if($cekThumb) {
			$img = $ret;
		}
	}
	$cek = is_file_exsist(UPLOAD_DIR.$path,$img);
	if($ret==1){
		$data = $cek;
	}
	else{
		$data =  $cek ? (base_url().'images/article/'.$path.$img) : (base_url().'images/article/large/'.$img);
	}
    return $data;

}

function sent_email_by_category($id_ref_email_category,$data,$to,$calc=0){
    $CI=& get_instance();
    $CI->load->helper('mail');
    $CI->load->model('EmailDefaultModel');
    $CI->load->model('EmailTmpModel');

    $data_email_category = $CI->EmailDefaultModel->findById($id_ref_email_category);
    // if($data['namadepan']){
    //  $data['namadepan'] = ucwords(strtolower($data['namadepan']));
    // }
    // if($data['namabelakang']){
    //  $data['namabelakang'] = ucwords(strtolower($data['namabelakang']));
    // }
    if($data['fullname']){
        if(is_array($data['fullname'])){
            foreach ($data['fullname'] as $key => $value) {
                $data['list_name'][$key]['fullname'] = ucwords(strtolower($value));
            }
        } else {
            $data['fullname'] = ucwords(strtolower($data['fullname']));
            $data['list_name'] = '';
            $data['/list_name'] = '';
        }
    }
    
    if($data_email_category['id_email_tmp']){
        $data_email_template = $CI->EmailTmpModel->findById($data_email_category['id_email_tmp']);
        if($data_email_template){
            $email['to'] = $to;
            $config = array (
                'mailtype' => 'html',
                'charset'  => 'utf-8',
                'priority' => '1'
            );
            $CI->email->initialize($config);
            $email['subject'] = $data_email_template['subject'];
            $data['data_email_content'] = $data_email_template['page_content'];
            $path   = get_path_email_template();

	        if($calc==0){
	            $message_content['this_year'] = date('Y');
	            $message_content['content'] = $CI->parser->parse('layout/ddi/email_template/'.preg_replace("/&#?[a-z0-9]+;/i","",$data_email_template['template_name']).'.html', $data,true);   
	            $message = $CI->parser->parse('layout/ddi/email_template/default_template.html', $message_content,true);
	        } else {
	            $data['this_year'] = date('Y');
	            $message = $CI->parser->parse('layout/ddi/email_template/'.preg_replace("/&#?[a-z0-9]+;/i","",$data_email_template['template_name']).'.html', $data,true);
	        }
	        $email['content'] = $message;
	        $ret = sent_mail($email,0);
	        if(is_array($to)){
	            foreach ($to as $key => $value) {
	               $data_email[$key]['to_email'] = $value;
	               $data_email[$key]['category'] = $data_email_category['name'];
	               $data_email[$key]['process_date'] = date('Y-m-d H:i:s');
	               $data_email[$key]['from_email'] = $CI->db->query('select smtp_user from email_config')->row()->smtp_user;
	            }
	            foreach ($data_email as $key => $value) {
	                $log_email = $CI->EmailDefaultModel->insert_email_log($value);
	            }
	        } else {
	            $data_email['to_email'] = $to;
	            $data_email['category'] = $data_email_category['name'];
	            $data_email['process_date'] = date('Y-m-d H:i:s');
	            $data_email['from_email'] = $CI->db->query('select smtp_user from email_config')->row()->smtp_user;
	            $log_email = $CI->EmailDefaultModel->insert_email_log($data_email);
	        }
	        return $ret;
	    }
    }
}
function get_path_email_template(){
    return EMAIL_TEMPLATE_DIR;
}
function generate_email_template_file($file_name,$data){
    $CI=& get_instance();
    $CI->load->helper('file');
    $path	= get_path_email_template();
    if(!file_exists($path)){
        mkdir($path);
    }
    if(!is_writable($path)){//kalo ga bisa nulis
        die('ga bisa nulis!');
    }
    if(!write_file($path.preg_replace("/&#?[a-z0-9]+;/i","",$file_name).'.html', $data)){
        echo 'error create file <br>';
    }
}

function rand_code($length=12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function selectlist_enums($table,$field,$selected,$title=''){
    $enums = array();
    if ($table == '' || $field == '') return $enums;
    $CI =& get_instance();
    preg_match_all("/'(.*?)'/", $CI->db->query("SHOW COLUMNS FROM {$table} LIKE '{$field}'")->row()->Type, $matches);
    foreach ($matches[1] as $key => $value) {
        $enums[$value] = $value; 
    }
    $opt             = $title ? "<option value=''>$title</option>" : '';
    foreach (array_filter($enums) as $key => $value) {
        $terpilih    = ($selected == $value) ? 'selected' : '';
        $opt        .= "<option $terpilih value='$value'> $value</option>";
    }
    return $opt;
}
function set_response($http_code, $data, $message = '', $error='')
{
	$CI = & get_instance();

	// List client error code yang digunakan di Default API
	$error_codes   = array(400, 401, 402, 403, 404, 405, 409, 422, 500);

	// List success code yang digunakan di Default API
	$success_codes = array(200, 201);

	if ($message != '')
	{
		$return['message'] = $message;
	}
	else
	{
		if (in_array($http_code, $error_codes) && $CI->data["lang_$http_code"])
		{
			$return['message'] = $CI->data["lang_$http_code"];
		}
	}

	$return['status'] = in_array($http_code, $error_codes) ? FALSE : TRUE;
	$return['code']   = $http_code;

	if ($error && $return['status'] === FALSE)
	{
		$return['error'] = $error;
	}

	if ($data)
	{ 
		$return['result'] = $data;
	}

	return $CI->response($return, $http_code);
}

function get_user_session(){
	$CI = & get_instance();
	$user_sess = $CI->session->userdata('MEM_SESS');
	if(isset($user_sess['id'])){
		$user_sess = $CI->db->get_where('auth_user', array('id_auth_user'=>$user_sess['id']))->row_array();
	}
	return $user_sess;
}

function path_image($img,$path,$ret=0){
	$path = "$path/";
	$path = str_replace('//', '', $path);
	$CI=& get_instance();
	$data = (base_url().'images/article/'.$path.$img);
    return $data;

}