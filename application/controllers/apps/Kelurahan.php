<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kelurahan extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Kelurahan_model');
        $this->load->model('Kecamatan_model');

	}

	function index()
	{

    $data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));

		render('apps/kelurahan/index', $data, 'apps');
	}

	function records()
    {
        $data = $this->Kelurahan_model->records();
        render('apps/kelurahan/records', $data, 'blank');
    }

    function proses($idedit='')
   {
       $this->layout   = 'none';
       $post           = purify($this->input->post());
       $ret['error']   = 1;

       $this->form_validation->set_rules('kd_kel', '"kd_kel"', 'required');
       $this->form_validation->set_rules('kelurahan', '"Kelurahan"', 'required');
       $this->form_validation->set_rules('kode_kelurahan', '"kode_kelurahan"', 'required');

       if ($this->form_validation->run() == FALSE)
       {
           $ret['message']  = validation_errors(' ',' ');
       }
       else
       {
           $kecamatan   = $this->Kecamatan_model->findBy(array('kode_kecamatan'=>$post['kode_kecamatan']),1);
           $this->db->trans_start();
           if ($idedit)
           {
               auth_update();
               $ret['message'] = 'Update Success';
               $act			= "Update News";
               $idedit         = $this->Kelurahan_model->update($post, $idedit);

           }
           else
           {
               auth_insert();
               $ret['message'] = 'Insert Success';
               $act			= "Insert News";
               $post['kd_kec'] = $kecamatan['kd_kec'];
               $post['kode_kecamatan'] = $kecamatan['kode_kecamatan'];
               $idedit         = $this->Kelurahan_model->insert($post);

           }

           $ret['error']   = 0;
           $this->db->trans_complete();
       }

       echo json_encode($ret);
   }


    function add($id='')
    {
        if ($id)
        {
            $data = $this->Kelurahan_model->findById($id);

            if (!$data)
            {
                die('404');
            }

            $data           = quote_form($data);
            $data['judul']  = 'Edit';
            $data['proses'] = 'Update';
        }

        else
        {
            $data['judul'] = 'Add';
            $data['proses'] = 'Save';
            $data['kd_kel'] = '';
            $data['kode_kelurahan'] = '';
            $data['kelurahan'] = '';
            $data['id_kelurahan'] = '';
            $data['kode_kecamatan'] = '';

            $data['status_publish']     = '';
            // $data['create_date']        = date("m/d/Y g:i A", strtotime(date('h:i')));
            $data['modify_date']        = date("m/d/Y g:i A", strtotime(date('h:i')));
            $data['user_id_create']     = '';
            $data['user_id_modify']     = '';
        }

        $img_thumb                      = image($data['img'],'small');
        $imagemanager                   = imagemanager('img',$img_thumb,277,150);
        $data['img']                    = $imagemanager['browse'];
        $data['imagemanager_config']    = $imagemanager['config'];
        $data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['status_publish']));

        $data['list_kecamatan'] = selectlist2(
            array(
            'table'=>'ref_kecamatan',
            'id' => 'kode_kecamatan',
            'name' => 'kecamatan',
            'title'=>'Pilih kecamatan',
            'selected'=>$data['kode_kecamatan'],
            'kecamatan'=>'kecamatan',
            'where'=> 'is_delete=0')
        );

        render('apps/kelurahan/add', $data, 'apps');
    }

   function del()
    {
        auth_delete();
        $id     = $this->input->post('iddel');
        $data   = $this->Kelurahan_model->delete($id);
        detail_log();
        insert_log("Delete Pages");
    }
}
