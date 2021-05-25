<?php

function calculate_age($birthdate,$format=array()){
    $from = new DateTime($birthdate);
    $to   = new DateTime('today');
    if(!empty($format)){
        foreach ($format as $key => $value) {
            if(in_array($value, array('y','m','d'))){
                $age[$value] = $from->diff($to)->$value;
            }
        }
    } else {
        $age = $from->diff($to)->y;
    }
    return $age;
}
function upload_multifile($field,$path='',$allowed_type='*',$max_size=0,$upload_dir=UPLOAD_DIR,$filename=''){
    $CI        = & get_instance();
    $filenames = array();
    $counter = 0;
    $company_profile_data = [];
    if($_FILES[$field]){
        foreach ($_FILES[$field]['name'] as $key => $value) {
            if(isset($value) && trim($value)){
                $name           = strtolower($value);
                $ext            = end(explode('.',$name));
                $filename_ori   = str_replace($ext,'',$name);
                $filenames[$key] = ($filename) ? url_title($filename_ori.'_'.$filename).'.'.$ext : url_title($filename_ori).'.'.$ext;
            } else {
                unset($_FILES[$field]["name"][$key],$_FILES[$field]["type"][$key],$_FILES[$field]["tmp_name"][$key],$_FILES[$field]["error"][$key],$_FILES[$field]["size"][$key]);
            }
        }
        $CI->load->helper(array('form', 'url'));
        $config['upload_path']     = $upload_dir.$path;
        if (!is_dir($config['upload_path'])) mkdir($config['upload_path']);
        $config['allowed_types']   = $allowed_type;
        $config['max_size']        = $max_size;
        $CI->load->library('upload', $config);
        $return_data = @$CI->upload->do_multi_upload($field,TRUE,$filenames);
        foreach ($return_data as $key => $value) {
            if(isset($value['file_name'])){
                $company_profile_data[] = $value['file_name'];
            }
        }
    }
    return implode(',', $company_profile_data);
}
function get_upload_multifile($id, $data, $json_encode = 1){
    $CI        = & get_instance();
    $data_upload = [];
    $data_file = explode(',', $data);
    if($data_file){
        foreach($data_file as $key => $value){
            if($value && @filesize(UPLOAD_FILE_DIR.$id.'/'.$value)){
                $data_upload[] = array(
                    'name' => $value,
                    'size' => filesize(UPLOAD_FILE_DIR.$id.'/'.$value),
                    'url' => upload_file_url($id, $value),
                );
            }
        }
    }
    if($json_encode == 1 ){
        return json_encode($data_upload);
    } else {
        return $data_upload;
    }
}

function upload_file_url($id, $value){
    return base_url('uploads/'.$id.'/'.$value);
}
function send_notification($recipientId, $data, $fromId = null)
{
    $CI = & get_instance();

    $data['data'] = @$data['data'] ? $data['data'] : [];

    $notif['recipient_id'] = $recipientId;
    $notif['from_id']      = $fromId;
    $notif['title']        = $data['title'];
    $notif['content']      = $data['content'];
    $notif['data']         = json_encode($data['data']);
    $notif['create_date']  = date('Y-m-d H:i:s');
    $CI->db->insert('notifications', $notif);

    $fcm['title'] = $notif['title'];
    $fcm['message'] = (!empty($data['data'])) ? str_replace(
        array_keys($data['data']), 
        array_values($data['data']), 
        $data['content']
    ) : $data['content'];
    firebase_push($recipientId, $fcm);
}

/**
 * Push Firebase Cloud Messaging
 *
 * @author Siti Hasuna <sh.hanaaa@gmail.com>
 * @param int $userId  id user
 * @param array $data Notifikasi
 *
 * @return void
 */
function firebase_push($userId, $data, $sound = 0)
{
    $CI     = & get_instance();
    $query = $CI->db->select('token')->get_where('auth_user_tokens', ['id_auth_user' => $userId]);

    if ( $query->num_rows() > 0 ) 
    {
        $tokens = $query->result_array();
        $notification = [
            'title' => $data['title'],
            'body'  => $data['message'],
            'sound' => $sound
        ];

        $headers = [
            'Authorization: key='.API_FCM_KEY,
            'Content-Type: application/json'
        ];
        
        foreach ($tokens as $token) 
        {
            $fcmNotification = array(
                'to'           => $token['token'],
                'notification' => $notification
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
            curl_exec($ch);
            curl_close($ch);
        }
    }
}

function diff_days($start_date, $end_date, $format = "%a")
{
    $earlier = new DateTime($start_date);
    $later   = new DateTime($end_date);

    $diff = $later->diff($earlier)->format($format);

    return $diff;
}
function imagemanager2($field='img',$img='',$required='',$add_input_name='',$add_input_val='',$del_feature=0){
    $CI             = & get_instance();
    $is_new = ($add_input_val) ? 'hidden' : '';
    $is_exist = ($add_input_val) ? '' : 'fileupload-exists';
    $html = 
    '<div class="fileupload fileupload-new center" data-provides="fileupload">
        <div class="fileupload-new thumbnail frame-upload"><img src="'.$img.'" style="max-width:100%;" alt="avatar"></div>
        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px;max-height: 200px; line-height: 20px;width:200px; height: 200px;"></div>
        <div class="row">
          <span class="btn btn-file">
            <span class="button fileupload-new '.$is_new.'">Choose File</span>
            <span class="button '.$is_exist.'">Change File</span>
            <input name="'.$field.'" type="file" class="imgfileupload" '.$required.'>
            </span>';
    if($add_input_name){
        $html .= 
                '<input name="'.$add_input_name.'" type="hidden" value="'.$add_input_val.'">';
    }
    if($del_feature==1){
        $html .=
              '<span class="col-lg-12 col-xs-12 col-sm-12">
                <a href="#" class="btn '.$is_exist.'" data-dismiss="fileupload">Hapus</a>
              </span>';
    } 
    $html .=
        '</div>
      </div>';
    return $html;
}