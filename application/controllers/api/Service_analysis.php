<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
require APPPATH . 'libraries/JWT.php';
use \Firebase\JWT\JWT;

class Service_analysis extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Service_model', 'model');
        $this->load->model('Motor_user_model');
    }

    public function oli_performance_get(){
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        $post = purify($post);
        $kunci = $this->config->item('thekey'); //secret key for encode and decode
        $headers = $post['sess_token']; //get token from request header
        try {
            $decoded = JWT::decode($headers, $kunci, array('HS256'));
            $decoded_array = (array) $decoded;

            $this->db->select("a.service_date, mileage_km")
                ->where('a.is_delete',0)
                ->where('a.id_auth_user',$decoded_array['admin_id_auth_user'])
                ->where('b.id', $post['id_motor'])
                ->where('a.oli_price >', 0)
                ->join('motor_user b','b.id = a.id_motor_user')
                ->order_by('service_date', 'desc')
                ->limit(2);
            $query = $this->db->get($this->model->tableAs);

            $ideal_km = 4000;
            $ideal_month = '+4 month';
            $ret['today']        = 0;
            $ret['last_week']    = 0;
            $ret['last_month']   = 0;
            $ret['is_available'] = false;

            if(($total = $query->num_rows()) > 0){
                $data = $query->result_array();

                $latest_service     = $data[0];
                $latest_km          = $latest_service['mileage_km'];
                $latest_date        = $latest_service['service_date'];
                $latest_date_format = new DateTime($latest_date);
                $latest_date_v1     = new DateTime($latest_date);
                $latest_date_v1->modify($ideal_month);
                $alert_date       = $latest_date_v1->format('Y-m-d');
                $ideal_days_alert = $latest_date_format->diff($latest_date_v1)->format('%a');

                if ($total == 2)
                {
                    $earlier_service = $data[1];
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

                $diff_today = diff_days($latest_date, 'now');
                $diff_last_week = $diff_today - 7;
                $diff_last_month = $diff_today - 30;

                $performance_today = (($ideal_days_alert-$diff_today) / $ideal_days_alert) * 100;
                $performance_last_week = (($ideal_days_alert-$diff_last_week) / $ideal_days_alert) * 100;
                $performance_last_month = (($ideal_days_alert-$diff_last_month) / $ideal_days_alert) * 100;
                
                $ret['today']        = round($performance_today);
                $ret['last_week']    = round($performance_last_week);
                $ret['last_month']   = round($performance_last_month);
                $ret['is_available'] = true;
            }

        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        return $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function ban_performance_get(){
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        $post = purify($post);
        $kunci = $this->config->item('thekey'); //secret key for encode and decode
        $headers = $post['sess_token']; //get token from request header
        try {
            $decoded = JWT::decode($headers, $kunci, array('HS256'));
            $decoded_array = (array) $decoded;

            $motor = $this->Motor_user_model->findById($post['id_motor'], 'purchase_date');
            $query_ban_depan = $this->db->select("a.service_date, mileage_km")
                ->where('a.is_delete',0)
                ->where('a.id_auth_user',$decoded_array['admin_id_auth_user'])
                ->where('b.id', $post['id_motor'])
                ->where('a.ban_depan_price >', 0)
                ->join('motor_user b','b.id = a.id_motor_user')
                ->order_by('service_date', 'desc')
                ->limit(2)
                ->get($this->model->tableAs);

            $query_ban_belakang = $this->db->select("a.service_date, mileage_km")
                ->where('a.is_delete',0)
                ->where('a.id_auth_user',$decoded_array['admin_id_auth_user'])
                ->where('b.id', $post['id_motor'])
                ->where('a.ban_belakang_price >', 0)
                ->join('motor_user b','b.id = a.id_motor_user')
                ->order_by('service_date', 'desc')
                ->limit(2)
                ->get($this->model->tableAs);

            $ideal_km_ban_depan       = 12000;
            $ideal_km_ban_belakang    = 10000;
            $ideal_month_ban_depan    = '+12 month';
            $ideal_month_ban_belakang = '+10 month';
            $ret['is_available'] = false;
            $ret['ban']          = 0;
            $ret['ban_depan']    = 0;
            $ret['ban_belakang'] = 0;

            if(($total_ban_depan = $query_ban_depan->num_rows()) > 0){
                $data = $query_ban_depan->result_array();

                $latest_service = $data[0];
                $latest_km      = $latest_service['mileage_km'];
                $latest_date    = $latest_service['service_date'];

                if ($total_ban_depan == 2)
                {
                    $earlier_service = $data[1];
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
                
                $ret['ban_depan']    = round($performance_ban_depan);
                $ret['is_available'] = true;
            }
            
            if(($total_ban_belakang = $query_ban_belakang->num_rows()) > 0){
                $data = $query_ban_belakang->result_array();

                $latest_service = $data[0];
                $latest_km      = $latest_service['mileage_km'];
                $latest_date    = $latest_service['service_date'];

                if ($total_ban_belakang == 2)
                {
                    $earlier_service = $data[1];
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
                $latest_date_v2 = new DateTime($latest_date);
                $latest_date_v2->modify("+$ideal_days_alert days");
                $alert_date = $latest_date_v2->format('Y-m-d');

                $diff_today = diff_days($latest_date, 'now');

                $performance_ban_belakang = (($ideal_days_alert-$diff_today) / $ideal_days_alert) * 100;
                
                $ret['ban_belakang'] = round($performance_ban_belakang);
                $ret['is_available'] = true;
            }

            $ret['ban'] = ($ret['ban_depan'] + $ret['ban_belakang']) / 2;

        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        return $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function aki_performance_get(){
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        $post = purify($post);
        $kunci = $this->config->item('thekey'); //secret key for encode and decode
        $headers = $post['sess_token']; //get token from request header
        try {
            $decoded = JWT::decode($headers, $kunci, array('HS256'));
            $decoded_array = (array) $decoded;

            $this->db->select("a.service_date, mileage_km")
                ->where('a.is_delete',0)
                ->where('a.id_auth_user',$decoded_array['admin_id_auth_user'])
                ->where('b.id', $post['id_motor'])
                ->where('a.aki_price >', 0)
                ->join('motor_user b','b.id = a.id_motor_user')
                ->order_by('service_date', 'desc')
                ->limit(2);
            $query = $this->db->get($this->model->tableAs);

            $ideal_km = 20000;
            $ideal_month = '+24 month';
            $ret['today']        = 0;
            $ret['last_week']    = 0;
            $ret['last_month']   = 0;
            $ret['is_available'] = false;

            if(($total = $query->num_rows()) > 0){
                $data = $query->result_array();

                $latest_service     = $data[0];
                $latest_km          = $latest_service['mileage_km'];
                $latest_date        = $latest_service['service_date'];
                $latest_date_format = new DateTime($latest_date);
                $latest_date_v1     = new DateTime($latest_date);
                $latest_date_v1->modify($ideal_month);
                $alert_date       = $latest_date_v1->format('Y-m-d');
                $ideal_days_alert = $latest_date_format->diff($latest_date_v1)->format('%a');

                if ($total == 2)
                {
                    $earlier_service = $data[1];
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

                $diff_today = diff_days($latest_date, 'now');
                $diff_last_week = $diff_today - 7;
                $diff_last_month = $diff_today - 30;

                $performance_today = (($ideal_days_alert-$diff_today) / $ideal_days_alert) * 100;
                $performance_last_week = (($ideal_days_alert-$diff_last_week) / $ideal_days_alert) * 100;
                $performance_last_month = (($ideal_days_alert-$diff_last_month) / $ideal_days_alert) * 100;
                
                $ret['today']        = round($performance_today);
                $ret['last_week']    = round($performance_last_week);
                $ret['last_month']   = round($performance_last_month);
                $ret['is_available'] = true;
            }

        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        return $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function rem_performance_get(){
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        $post = purify($post);
        $kunci = $this->config->item('thekey'); //secret key for encode and decode
        $headers = $post['sess_token']; //get token from request header
        try {
            $decoded = JWT::decode($headers, $kunci, array('HS256'));
            $decoded_array = (array) $decoded;

            $motor = $this->Motor_user_model->findById($post['id_motor'], 'purchase_date');
            $query_rem_depan = $this->db->select("a.service_date, mileage_km")
                ->where('a.is_delete',0)
                ->where('a.id_auth_user',$decoded_array['admin_id_auth_user'])
                ->where('b.id', $post['id_motor'])
                ->where('a.rem_depan_price >', 0)
                ->join('motor_user b','b.id = a.id_motor_user')
                ->order_by('service_date', 'desc')
                ->limit(2)
                ->get($this->model->tableAs);

            $query_rem_belakang = $this->db->select("a.service_date, mileage_km")
                ->where('a.is_delete',0)
                ->where('a.id_auth_user',$decoded_array['admin_id_auth_user'])
                ->where('b.id', $post['id_motor'])
                ->where('a.rem_belakang_price >', 0)
                ->join('motor_user b','b.id = a.id_motor_user')
                ->order_by('service_date', 'desc')
                ->limit(2)
                ->get($this->model->tableAs);

            $ideal_km_rem_depan       = 3000;
            $ideal_km_rem_belakang    = 3000;
            $ideal_month_rem_depan    = '+3 month';
            $ideal_month_rem_belakang = '+3 month';
            $ret['is_available'] = false;
            $ret['rem']          = 0;
            $ret['rem_depan']    = 0;
            $ret['rem_belakang'] = 0;

            if(($total_rem_depan = $query_rem_depan->num_rows()) > 0){
                $data = $query_rem_depan->result_array();

                $latest_service = $data[0];
                $latest_km      = $latest_service['mileage_km'];
                $latest_date    = $latest_service['service_date'];

                if ($total_rem_depan == 2)
                {
                    $earlier_service = $data[1];
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
                
                $ret['rem_depan']    = round($performance_rem_depan);
                $ret['is_available'] = true;
            }
            
            if(($total_rem_belakang = $query_rem_belakang->num_rows()) > 0){
                $data = $query_rem_belakang->result_array();

                $latest_service = $data[0];
                $latest_km      = $latest_service['mileage_km'];
                $latest_date    = $latest_service['service_date'];

                if ($total_rem_belakang == 2)
                {
                    $earlier_service = $data[1];
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
                $latest_date_v2 = new DateTime($latest_date);
                $latest_date_v2->modify("+$ideal_days_alert days");
                $alert_date = $latest_date_v2->format('Y-m-d');

                $diff_today = diff_days($latest_date, 'now');

                $performance_rem_belakang = (($ideal_days_alert-$diff_today) / $ideal_days_alert) * 100;
                
                $ret['rem_belakang'] = round($performance_rem_belakang);
                $ret['is_available'] = true;
            }

            $ret['rem'] = ($ret['rem_depan'] + $ret['rem_belakang']) / 2;

        } catch (Exception $e) {
            $ret['msg'] = $e->getMessage(); //Respon if credential invalid
        }
        return $this->set_response($ret, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}
