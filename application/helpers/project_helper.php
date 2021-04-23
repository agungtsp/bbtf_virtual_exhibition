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
    $config['allowed_types']   = $allowed_type;
    $config['max_size']        = $max_size;
    $CI->load->library('upload', $config);
    return $CI->upload->do_multi_upload($field,TRUE,$filenames);
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