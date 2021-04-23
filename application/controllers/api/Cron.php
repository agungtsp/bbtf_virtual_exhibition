<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Cron extends REST_Controller {

    function __construct()
    {
        parent::__construct();

    }

    public function alert_sim_get(){
        $post = $this->input->get();
        $post = purify($post);
        $notif = [];
        $users = [];

        if (@$post['username'])
        {
            $users = $this->db->select('id_auth_user as id, sim_expired_date')
                ->where('LOWER(userid)', strtolower($post['username']))
                ->where('is_delete', 0)
                ->get('auth_user')
                ->result_array();
        }
        else {
            $users = $this->db->select('id_auth_user as id, sim_expired_date')
                ->group_start()
                ->where('date(sim_expired_date) = DATE(NOW()) + INTERVAL 21 DAY', NULL, FALSE)
                ->or_where('date(sim_expired_date) = DATE(NOW())', NULL, FALSE)
                ->group_end()
                ->where('is_delete', 0)
                ->get('auth_user')
                ->result_array();
        }
            
        if (!empty($users))
        {
            foreach ($users as $key => $value) {
                $left_days = diff_days(date('Y-m-d'), $value['sim_expired_date']);
                if ($left_days == 0)
                {
                    $notif[$value['id']] = [
                        'title'   => 'Masa berlaku SIM segera berakhir',
                        'content' => 'Masa berlaku SIM Anda akan berakhir {{ expired_date }}. Ayo urus sekarang!.',
                        'data'    => [
                            "{{ expired_date }}" => 'hari ini'
                        ]
                    ];
                }
                else 
                {
                    $notif[$value['id']] = [
                        'title'   => 'Masa berlaku SIM segera berakhir',
                        'content' => 'Masa berlaku SIM Anda akan berakhir dalam {{ expired_date }} hari. Ayo persiapkan waktu dan dana untuk mengurusnya.',
                        'data'    => [
                            "{{ expired_date }}" => $left_days
                        ]
                    ];
                }
            }
        }

        if (! empty($notif))
        {
            foreach($notif as $id_user => $data)
            {
                send_notification($id_user, $data);
            }
        }

        return $this->set_response(['message' => 'Notifikasi berhasil terkirim'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function alert_pajak_tahunan_get(){
        $post = $this->input->get();
        $post = purify($post);
        $notif = [];
        $motor = [];

        if (@$post['username'])
        {
            $id_user = db_get_one('auth_user', 'id_auth_user', 'LOWER(userid) = "'. strtolower($post['username']).'" AND is_delete = 0');

            if ($id_user)
            {
                $motor = $this->db->select('id_auth_user, id, tnkb_date, title')
                    ->where('id_auth_user', $id_user)
                    ->where('is_delete', 0)
                    ->get('motor_user')
                    ->result_array();
            }
        }
        else {
            $motor = $this->db->select('id_auth_user, id, tnkb_date, title')
                ->group_start()
                ->where('date(tnkb_date) = DATE(NOW()) + INTERVAL 21 DAY', NULL, FALSE)
                ->or_where('date(tnkb_date) = DATE(NOW())', NULL, FALSE)
                ->group_end()
                ->where('is_delete', 0)
                ->get('motor_user')
                ->result_array();
        }
            
        if (!empty($motor))
        {
            foreach ($motor as $key => $value) {
                $left_days = diff_days(date('Y-m-d'), $value['tnkb_date']);
                if ($left_days == 0)
                {
                    $notif[$value['id_auth_user'].'_'.$value['id']] = [
                        'title'   => 'Masa berlaku TNKB segera berakhir',
                        'content' => 'Masa berlaku TNKB si {{ motor_title }} akan berakhir {{ expired_date }}. Ayo urus sekarang!.',
                        'data'    => [
                            "{{ motor_title }}"  => $value['title'],
                            "{{ expired_date }}" => 'hari ini'
                        ]
                    ];
                }
                else 
                {
                    $notif[$value['id_auth_user'].'_'.$value['id']] = [
                        'title'   => 'Masa berlaku TNKB segera berakhir',
                        'content' => 'Masa berlaku TNKB si {{ motor_title }} akan berakhir dalam {{ expired_date }} hari. Ayo persiapkan waktu dan dana untuk mengurusnya.',
                        'data'    => [
                            "{{ motor_title }}"  => $value['title'],
                            "{{ expired_date }}" => $left_days
                        ]
                    ];
                }
            }
        }

        if (! empty($notif))
        {
            foreach($notif as $key => $data)
            {
                list($id_user, $id) = explode('_', $key);
                send_notification($id_user, $data);
            }
        }

        return $this->set_response(['message' => 'Notifikasi berhasil terkirim'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function alert_parkir_get(){
        $post = $this->input->get();
        $post = purify($post);
        $notif = [];
        $parking = [];

        if (@$post['username'])
        {
            $id_user = db_get_one('auth_user', 'id_auth_user', 'LOWER(userid) = "'. strtolower($post['username']).'" AND is_delete = 0');

            if ($id_user)
            {
                $parking = $this->db->select('a.id_auth_user, a.id, a.create_date, b.title, a.position, a.floor')
                    ->where('a.id_auth_user', $id_user)
                    ->where('a.is_delete', 0)
                    ->join('motor_user b', 'b.id = a.id_motor_user and b.is_delete = 0', 'left')
                    ->get('parking a')
                    ->result_array();
            }
        }
        else {
            $parking = $this->db->select('a.id_auth_user, a.id, a.create_date, b.title, a.position, a.floor')
                ->group_start()
                ->where('a.create_date = NOW() - INTERVAL 1 HOUR', NULL, FALSE)
                ->or_where('a.create_date = NOW() - INTERVAL 2 HOUR', NULL, FALSE)
                ->or_where('a.create_date = NOW() - INTERVAL 3 HOUR', NULL, FALSE)
                ->group_end()
                ->where('a.parking_fee <', 1)
                ->where('a.is_delete', 0)
                ->join('motor_user b', 'b.id = a.id_motor_user and b.is_delete = 0', 'left')
                ->get('parking a')
                ->result_array();
        }
            
        if (!empty($parking))
        {
            foreach ($parking as $key => $value) {
                $long_hours = diff_days($value['create_date'], 'now', '%h jam');
                if ($long_hours == '0 jam') $long_hours = diff_days($value['create_date'], 'now', '%a hari');
                $notif[$value['id_auth_user'].'_'.$value['id']] = [
                    'title'   => 'Kendaraan mu terparkir disini',
                    'content' => 'Si {{ motor_title }} sudah terparkir selama {{ parking_time }} di {{ position }} lantai {{ floor }}',
                    'data'    => [
                        "{{ motor_title }}"  => $value['title'],
                        "{{ parking_time }}" => $long_hours,
                        "{{ position }}"     => $value['position'],
                        "{{ floor }}"        => $value['floor'],
                    ]
                ];
            }
        }

        if (! empty($notif))
        {
            foreach($notif as $key => $data)
            {
                list($id_user, $id) = explode('_', $key);
                send_notification($id_user, $data);
            }
        }

        return $this->set_response(['message' => 'Notifikasi berhasil terkirim'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function alert_oli_get()
    {
        $post = $this->input->get();
        $post = purify($post);
        $notif = [];
        $parking = [];
        $users = [];

        if (@$post['username'])
        {
            $id_user = db_get_one('auth_user', 'id_auth_user', 'LOWER(userid) = "'. strtolower($post['username']).'" AND is_delete = 0');

            if ($id_user)
            {
                $query_latest = $this->db->select("max(a.service_date) as service_date, max(mileage_km) as mileage_km, id_auth_user, id_motor_user")
                    ->where('a.id_auth_user',$id_user)
                    ->where('a.is_delete',0)
                    ->where('a.oli_price >', 0)
                    ->group_by('id_auth_user, id_motor_user')
                    ->get('services a');
            }
        }
        else {
            $query_latest = $this->db->select("max(a.service_date) as service_date, max(mileage_km) as mileage_km, id_auth_user, id_motor_user")
                ->where('a.is_delete',0)
                ->where('a.oli_price >', 0)
                ->group_by('id_auth_user, id_motor_user')
                ->get('services a');
        }

        if ($query_latest->num_rows() > 0)
        {
            $ideal_km = 4000;
            $ideal_month = '+4 month';

            $latest_services = $query_latest->result_array();
            foreach ($latest_services as $key => $latest_service) {
                $latest_km          = $latest_service['mileage_km'];
                $latest_date        = $latest_service['service_date'];
                $latest_date_format = new DateTime($latest_date);
                $latest_date_v1     = new DateTime($latest_date);
                $latest_date_v1->modify($ideal_month);
                $alert_date       = $latest_date_v1->format('Y-m-d');
                $ideal_days_alert = $latest_date_format->diff($latest_date_v1)->format('%a');

                $motor = $this->db->select('title')
                    ->where('id_auth_user', $latest_service['id_auth_user'])
                    ->where('id', $latest_service['id_motor_user'])
                    ->get('motor_user')
                    ->row_array();

                $earlier_service = $this->db->select("a.service_date, mileage_km, id_auth_user, id_motor_user")
                    ->where('a.is_delete',0)
                    ->where('a.id_auth_user',$latest_service['id_auth_user'])
                    ->where('a.id_motor_user',$latest_service['id_motor_user'])
                    ->where('a.service_date <', $latest_service['service_date'])
                    ->where('a.oli_price >', 0)
                    ->limit(1)
                    ->order_by('service_date', 'desc')
                    ->get('services a')->row_array();

                if ($earlier_service)
                {
                    $earlier_km      = $earlier_service['mileage_km'];
                    $earlier_date    = $earlier_service['service_date'];
                    $diff_days       = diff_days($earlier_date, $latest_date);
                    $diff_km         = $latest_km - $earlier_km;
                    $avarage_usage   = $diff_km / $diff_days;

                    $ideal_days_alert = round($ideal_km / $avarage_usage);
                    $latest_date_v2   = new DateTime($latest_date);
                    $latest_date_v2->modify("+$ideal_days_alert days");
                    $alert_date = $latest_date_v2->format('Y-m-d');
                }

                $today = date('Y-m-d');
                if (date('Y-m-d', strtotime($alert_date.' -7 days')) == $today 
                    || $alert_date == $today
                    || @$post['username']
                ) {
                    $diff_today = diff_days($latest_date, 'now');

                    $performance_today = (($ideal_days_alert-$diff_today) / $ideal_days_alert) * 100;

                    $notif = [
                        'title'   => 'Ganti oli, agar performa mesin tetap optimal',
                        'content' => 'Performa Oli si {{ motor_title }} sudah {{ performance }}%. Pastikan Anda tidak terlambat untuk menggantinya.',
                        'data'    => [
                            "{{ motor_title }}"  => $motor['title'],
                            "{{ performance }}"  => round($performance_today),
                        ]
                    ];
                    send_notification($latest_service['id_auth_user'], $notif);
                }
            }
        }

        return $this->set_response(['message' => 'Notifikasi berhasil terkirim'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function alert_ban_get()
    {
        $post = $this->input->get();
        $post = purify($post);
        $notif = [];
        $parking = [];
        $users = [];

        if (@$post['username'])
        {
            $id_user = db_get_one('auth_user', 'id_auth_user', 'LOWER(userid) = "'. strtolower($post['username']).'" AND is_delete = 0');

            if ($id_user)
            {
                $query_ban_depan = $this->db->select("max(a.service_date) as service_date, max(mileage_km) as mileage_km, id_auth_user, id_motor_user")
                    ->where('a.id_auth_user',$id_user)
                    ->where('a.is_delete',0)
                    ->where('a.ban_depan_price >', 0)
                    ->group_by('id_auth_user, id_motor_user')
                    ->get('services a');

                $query_ban_belakang = $this->db->select("max(a.service_date) as service_date, max(mileage_km) as mileage_km, id_auth_user, id_motor_user")
                    ->where('a.id_auth_user',$id_user)
                    ->where('a.is_delete',0)
                    ->where('a.ban_belakang_price >', 0)
                    ->group_by('id_auth_user, id_motor_user')
                    ->get('services a');
            }
        }
        else {
            $query_ban_depan = $this->db->select("max(a.service_date) as service_date, max(mileage_km) as mileage_km, id_auth_user, id_motor_user")
                ->where('a.is_delete',0)
                ->where('a.ban_depan_price >', 0)
                ->group_by('id_auth_user, id_motor_user')
                ->get('services a');

            $query_ban_belakang = $this->db->select("max(a.service_date) as service_date, max(mileage_km) as mileage_km, id_auth_user, id_motor_user")
                ->where('a.is_delete',0)
                ->where('a.ban_belakang_price >', 0)
                ->group_by('id_auth_user, id_motor_user')
                ->get('services a');
        }

        $ideal_km_ban_depan       = 12000;
        $ideal_km_ban_belakang    = 10000;
        $ideal_month_ban_depan    = '+12 month';
        $ideal_month_ban_belakang = '+10 month';

        if ($query_ban_depan->num_rows() > 0)
        {
            $latest_services = $query_ban_depan->result_array();
            foreach ($latest_services as $key => $latest_service) {
                $latest_km          = $latest_service['mileage_km'];
                $latest_date        = $latest_service['service_date'];

                $motor = $this->db->select('title, purchase_date')
                    ->where('id_auth_user', $latest_service['id_auth_user'])
                    ->where('id', $latest_service['id_motor_user'])
                    ->get('motor_user')
                    ->row_array();

                $earlier_service = $this->db->select("a.service_date, mileage_km, id_auth_user, id_motor_user")
                    ->where('a.is_delete',0)
                    ->where('a.id_auth_user',$latest_service['id_auth_user'])
                    ->where('a.id_motor_user',$latest_service['id_motor_user'])
                    ->where('a.service_date <', $latest_service['service_date'])
                    ->where('a.oli_price >', 0)
                    ->limit(1)
                    ->order_by('service_date', 'desc')
                    ->get('services a')->row_array();

                if ($earlier_service)
                {
                    $earlier_km      = $earlier_service['mileage_km'];
                    $earlier_date    = $earlier_service['service_date'];
                }
                else
                {
                    $earlier_km      = 0;
                    $earlier_date    = $motor['purchase_date'];
                }

                $diff_days       = diff_days($earlier_date, $latest_date);
                $diff_km         = $latest_km - $earlier_km;
                $avarage_usage   = $diff_km / $diff_days;

                $ideal_days_alert = round($ideal_km_ban_depan / $avarage_usage);
                $latest_date_v2   = new DateTime($latest_date);
                $latest_date_v2->modify("+$ideal_days_alert days");
                $alert_date = $latest_date_v2->format('Y-m-d');

                $diff_today = diff_days($latest_date, 'now');

                $performance_ban_depan = (($ideal_days_alert-$diff_today) / $ideal_days_alert) * 100;

                $today = date('Y-m-d');
                if (date('Y-m-d', strtotime($alert_date.' -7 days')) == $today 
                    || $alert_date == $today
                    || @$post['username']
                ) {
                    $notif = [
                        'title'   => 'Ganti ban depan Anda, untuk berkendara lebih aman',
                        'content' => 'Ban Depan si {{ motor_title }} sudah {{ performance }}% pemakaian. Pastikan perjalanan Anda aman.',
                        'data'    => [
                            "{{ motor_title }}"  => $motor['title'],
                            "{{ performance }}"  => round($performance_ban_depan),
                        ]
                    ];
                    send_notification($latest_service['id_auth_user'], $notif);
                }
            }
        }

        if ($query_ban_belakang->num_rows() > 0)
        {
            $latest_services = $query_ban_belakang->result_array();
            foreach ($latest_services as $key => $latest_service) {
                $latest_km          = $latest_service['mileage_km'];
                $latest_date        = $latest_service['service_date'];

                $motor = $this->db->select('title, purchase_date')
                    ->where('id_auth_user', $latest_service['id_auth_user'])
                    ->where('id', $latest_service['id_motor_user'])
                    ->get('motor_user')
                    ->row_array();

                $earlier_service = $this->db->select("a.service_date, mileage_km, id_auth_user, id_motor_user")
                    ->where('a.is_delete',0)
                    ->where('a.id_auth_user',$latest_service['id_auth_user'])
                    ->where('a.id_motor_user',$latest_service['id_motor_user'])
                    ->where('a.service_date <', $latest_service['service_date'])
                    ->where('a.oli_price >', 0)
                    ->limit(1)
                    ->order_by('service_date', 'desc')
                    ->get('services a')->row_array();

                if ($earlier_service)
                {
                    $earlier_km      = $earlier_service['mileage_km'];
                    $earlier_date    = $earlier_service['service_date'];
                }
                else
                {
                    $earlier_km      = 0;
                    $earlier_date    = $motor['purchase_date'];
                }

                $diff_days       = diff_days($earlier_date, $latest_date);
                $diff_km         = $latest_km - $earlier_km;
                $avarage_usage   = $diff_km / $diff_days;

                $ideal_days_alert = round($ideal_km_ban_belakang / $avarage_usage);
                $latest_date_v2   = new DateTime($latest_date);
                $latest_date_v2->modify("+$ideal_days_alert days");
                $alert_date = $latest_date_v2->format('Y-m-d');

                $diff_today = diff_days($latest_date, 'now');

                $performance_ban_belakang = (($ideal_days_alert-$diff_today) / $ideal_days_alert) * 100;

                $today = date('Y-m-d');
                if (date('Y-m-d', strtotime($alert_date.' -7 days')) == $today 
                    || $alert_date == $today
                    || @$post['username']
                ) {
                    $notif = [
                        'title'   => 'Ganti ban belakang Anda, untuk berkendara lebih aman',
                        'content' => 'Ban Belakang si {{ motor_title }} sudah {{ performance }}% pemakaian. Pastikan perjalanan Anda aman.',
                        'data'    => [
                            "{{ motor_title }}"  => $motor['title'],
                            "{{ performance }}"  => round($performance_ban_belakang),
                        ]
                    ];
                    send_notification($latest_service['id_auth_user'], $notif);
                }
            }
        }

        return $this->set_response(['message' => 'Notifikasi berhasil terkirim'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function alert_aki_get()
    {
        $post = $this->input->get();
        $post = purify($post);
        $notif = [];
        $parking = [];
        $users = [];

        if (@$post['username'])
        {
            $id_user = db_get_one('auth_user', 'id_auth_user', 'LOWER(userid) = "'. strtolower($post['username']).'" AND is_delete = 0');

            if ($id_user)
            {
                $query_latest = $this->db->select("max(a.service_date) as service_date, max(mileage_km) as mileage_km, id_auth_user, id_motor_user")
                    ->where('a.id_auth_user',$id_user)
                    ->where('a.is_delete',0)
                    ->where('a.aki_price >', 0)
                    ->group_by('id_auth_user, id_motor_user')
                    ->get('services a');
            }
        }
        else {
            $query_latest = $this->db->select("max(a.service_date) as service_date, max(mileage_km) as mileage_km, id_auth_user, id_motor_user")
                ->where('a.is_delete',0)
                ->where('a.aki_price >', 0)
                ->group_by('id_auth_user, id_motor_user')
                ->get('services a');
        }

        if ($query_latest->num_rows() > 0)
        {
            $ideal_km = 20000;
            $ideal_month = '+24 month';

            $latest_services = $query_latest->result_array();
            foreach ($latest_services as $key => $latest_service) {
                $latest_km          = $latest_service['mileage_km'];
                $latest_date        = $latest_service['service_date'];
                $latest_date_format = new DateTime($latest_date);
                $latest_date_v1     = new DateTime($latest_date);
                $latest_date_v1->modify($ideal_month);
                $alert_date       = $latest_date_v1->format('Y-m-d');
                $ideal_days_alert = $latest_date_format->diff($latest_date_v1)->format('%a');

                $motor = $this->db->select('title')
                    ->where('id_auth_user', $latest_service['id_auth_user'])
                    ->where('id', $latest_service['id_motor_user'])
                    ->get('motor_user')
                    ->row_array();

                $earlier_service = $this->db->select("a.service_date, mileage_km, id_auth_user, id_motor_user")
                    ->where('a.is_delete',0)
                    ->where('a.id_auth_user',$latest_service['id_auth_user'])
                    ->where('a.id_motor_user',$latest_service['id_motor_user'])
                    ->where('a.service_date <', $latest_service['service_date'])
                    ->where('a.oli_price >', 0)
                    ->limit(1)
                    ->order_by('service_date', 'desc')
                    ->get('services a')->row_array();

                if ($earlier_service)
                {
                    $earlier_km      = $earlier_service['mileage_km'];
                    $earlier_date    = $earlier_service['service_date'];
                    $diff_days       = diff_days($earlier_date, $latest_date);
                    $diff_km         = $latest_km - $earlier_km;
                    $avarage_usage   = $diff_km / $diff_days;

                    $ideal_days_alert = round($ideal_km / $avarage_usage);
                    $latest_date_v2   = new DateTime($latest_date);
                    $latest_date_v2->modify("+$ideal_days_alert days");
                    $alert_date = $latest_date_v2->format('Y-m-d');
                }

                $today = date('Y-m-d');
                if (date('Y-m-d', strtotime($alert_date.' -7 days')) == $today 
                    || $alert_date == $today
                    || @$post['username']
                ) {
                    $diff_today = diff_days($latest_date, 'now');

                    $performance_today = (($ideal_days_alert-$diff_today) / $ideal_days_alert) * 100;

                    $notif = [
                        'title'   => 'Ganti aki, agar performa mesin tetap optimal',
                        'content' => 'Performa Aki si {{ motor_title }} sudah {{ performance }}%. Pastikan Anda tidak terlambat untuk menggantinya.',
                        'data'    => [
                            "{{ motor_title }}"  => $motor['title'],
                            "{{ performance }}"  => round($performance_today),
                        ]
                    ];
                    send_notification($latest_service['id_auth_user'], $notif);
                }
            }
        }

        return $this->set_response(['message' => 'Notifikasi berhasil terkirim'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function alert_rem_get()
    {
        $post = $this->input->get();
        $post = purify($post);
        $notif = [];
        $parking = [];
        $users = [];

        if (@$post['username'])
        {
            $id_user = db_get_one('auth_user', 'id_auth_user', 'LOWER(userid) = "'. strtolower($post['username']).'" AND is_delete = 0');

            if ($id_user)
            {
                $query_rem_depan = $this->db->select("max(a.service_date) as service_date, max(mileage_km) as mileage_km, id_auth_user, id_motor_user")
                    ->where('a.id_auth_user',$id_user)
                    ->where('a.is_delete',0)
                    ->where('a.rem_depan_price >', 0)
                    ->group_by('id_auth_user, id_motor_user')
                    ->get('services a');

                $query_rem_belakang = $this->db->select("max(a.service_date) as service_date, max(mileage_km) as mileage_km, id_auth_user, id_motor_user")
                    ->where('a.id_auth_user',$id_user)
                    ->where('a.is_delete',0)
                    ->where('a.rem_belakang_price >', 0)
                    ->group_by('id_auth_user, id_motor_user')
                    ->get('services a');
            }
        }
        else {
            $query_rem_depan = $this->db->select("max(a.service_date) as service_date, max(mileage_km) as mileage_km, id_auth_user, id_motor_user")
                ->where('a.is_delete',0)
                ->where('a.rem_depan_price >', 0)
                ->group_by('id_auth_user, id_motor_user')
                ->get('services a');

            $query_rem_belakang = $this->db->select("max(a.service_date) as service_date, max(mileage_km) as mileage_km, id_auth_user, id_motor_user")
                ->where('a.is_delete',0)
                ->where('a.rem_belakang_price >', 0)
                ->group_by('id_auth_user, id_motor_user')
                ->get('services a');
        }

        $ideal_km_rem_depan       = 3000;
        $ideal_km_rem_belakang    = 3000;
        $ideal_month_rem_depan    = '+3 month';
        $ideal_month_rem_belakang = '+3 month';

        if ($query_rem_depan->num_rows() > 0)
        {
            $latest_services = $query_rem_depan->result_array();
            foreach ($latest_services as $key => $latest_service) {
                $latest_km          = $latest_service['mileage_km'];
                $latest_date        = $latest_service['service_date'];

                $motor = $this->db->select('title, purchase_date')
                    ->where('id_auth_user', $latest_service['id_auth_user'])
                    ->where('id', $latest_service['id_motor_user'])
                    ->get('motor_user')
                    ->row_array();

                $earlier_service = $this->db->select("a.service_date, mileage_km, id_auth_user, id_motor_user")
                    ->where('a.is_delete',0)
                    ->where('a.id_auth_user',$latest_service['id_auth_user'])
                    ->where('a.id_motor_user',$latest_service['id_motor_user'])
                    ->where('a.service_date <', $latest_service['service_date'])
                    ->where('a.oli_price >', 0)
                    ->limit(1)
                    ->order_by('service_date', 'desc')
                    ->get('services a')->row_array();

                if ($earlier_service)
                {
                    $earlier_km      = $earlier_service['mileage_km'];
                    $earlier_date    = $earlier_service['service_date'];
                }
                else
                {
                    $earlier_km      = 0;
                    $earlier_date    = $motor['purchase_date'];
                }

                $diff_days       = diff_days($earlier_date, $latest_date);
                $diff_km         = $latest_km - $earlier_km;
                $avarage_usage   = $diff_km / $diff_days;

                $ideal_days_alert = round($ideal_km_rem_depan / $avarage_usage);
                $latest_date_v2   = new DateTime($latest_date);
                $latest_date_v2->modify("+$ideal_days_alert days");
                $alert_date = $latest_date_v2->format('Y-m-d');

                $diff_today = diff_days($latest_date, 'now');

                $performance_rem_depan = (($ideal_days_alert-$diff_today) / $ideal_days_alert) * 100;

                $today = date('Y-m-d');
                if (date('Y-m-d', strtotime($alert_date.' -7 days')) == $today 
                    || $alert_date == $today
                    || @$post['username']
                ) {
                    $notif = [
                        'title'   => 'Ganti rem depan Anda, untuk berkendara lebih aman',
                        'content' => 'Rem Depan si {{ motor_title }} sudah {{ performance }}% pemakaian. Pastikan perjalanan Anda aman.',
                        'data'    => [
                            "{{ motor_title }}"  => $motor['title'],
                            "{{ performance }}"  => round($performance_rem_depan),
                        ]
                    ];
                    send_notification($latest_service['id_auth_user'], $notif);
                }
            }
        }

        if ($query_rem_belakang->num_rows() > 0)
        {
            $latest_services = $query_rem_belakang->result_array();
            foreach ($latest_services as $key => $latest_service) {
                $latest_km          = $latest_service['mileage_km'];
                $latest_date        = $latest_service['service_date'];

                $motor = $this->db->select('title, purchase_date')
                    ->where('id_auth_user', $latest_service['id_auth_user'])
                    ->where('id', $latest_service['id_motor_user'])
                    ->get('motor_user')
                    ->row_array();

                $earlier_service = $this->db->select("a.service_date, mileage_km, id_auth_user, id_motor_user")
                    ->where('a.is_delete',0)
                    ->where('a.id_auth_user',$latest_service['id_auth_user'])
                    ->where('a.id_motor_user',$latest_service['id_motor_user'])
                    ->where('a.service_date <', $latest_service['service_date'])
                    ->where('a.oli_price >', 0)
                    ->limit(1)
                    ->order_by('service_date', 'desc')
                    ->get('services a')->row_array();

                if ($earlier_service)
                {
                    $earlier_km      = $earlier_service['mileage_km'];
                    $earlier_date    = $earlier_service['service_date'];
                }
                else
                {
                    $earlier_km      = 0;
                    $earlier_date    = $motor['purchase_date'];
                }

                $diff_days       = diff_days($earlier_date, $latest_date);
                $diff_km         = $latest_km - $earlier_km;
                $avarage_usage   = $diff_km / $diff_days;

                $ideal_days_alert = round($ideal_km_rem_belakang / $avarage_usage);
                $latest_date_v2   = new DateTime($latest_date);
                $latest_date_v2->modify("+$ideal_days_alert days");
                $alert_date = $latest_date_v2->format('Y-m-d');

                $diff_today = diff_days($latest_date, 'now');

                $performance_rem_belakang = (($ideal_days_alert-$diff_today) / $ideal_days_alert) * 100;

                $today = date('Y-m-d');
                if (date('Y-m-d', strtotime($alert_date.' -7 days')) == $today 
                    || $alert_date == $today
                    || @$post['username']
                ) {
                    $notif = [
                        'title'   => 'Ganti rem belakang Anda, untuk berkendara lebih aman',
                        'content' => 'Rem Belakang si {{ motor_title }} sudah {{ performance }}% pemakaian. Pastikan perjalanan Anda aman.',
                        'data'    => [
                            "{{ motor_title }}"  => $motor['title'],
                            "{{ performance }}"  => round($performance_rem_belakang),
                        ]
                    ];
                    send_notification($latest_service['id_auth_user'], $notif);
                }
            }
        }

        return $this->set_response(['message' => 'Notifikasi berhasil terkirim'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}