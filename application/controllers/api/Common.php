<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/** @noinspection PhpIncludeInspection */

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * Common query use
 * all done with a library 
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Siti Hasuna <sh.hanaaa@gmail.com>
 **/
class Common extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('Common_model', 'model');
    }

    /**
    * Get data from a table
    * @author     Siti Hasuna <sh.hanaaa@gmail.com>
    * @param      table name, where
    */
    public function fetchAll_get()
    {
        $error = array();
        $post  = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }

        $is_single_row = $post['is_single_row'];
        $is_total      = $post['is_total'];
        $where         = $post['where'];
        
        unset(
            $post[DEFAULT_API_KEY_NAME], 
            $post['is_single_row'], 
            $post['is_total'],
            $post['where']
        );

        if ($is_single_row == 1)
        {
            $data = $this->model->fetchAll($where, $post, 1);
        }
        else if ($is_total == 1)
        {
            $data['total'] = $this->model->fetchAll($where, $post, 2);
        }
        else
        {
            $data['data'] = $this->model->fetchAll($where, $post);
        }

        if ( ! $data)
        {
            if ($post['ret_null_empty'])
            {
                set_response(200, array());
            }
            else {
                set_response(404);
                
            }
        }
        else
        {
            set_response(200, $data);
        }
    }

    /**
    * Get data dari salah satu column
    * @author     Siti Hasuna <sh.hanaaa@gmail.com>
    * @param      table name, column name, where
    */
    public function getOneField_get()
    {
        $post    = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        
        unset($post[DEFAULT_API_KEY_NAME]);

        // $data[$post['field_name']] = $this->model->getOneField($post['table_name'], $post['field_name'], $post['where']);
        $data[$post['field_name']] = $this->model->fetchAll(
            array(
                'table' => $post['table_name'],
                'where' => $post['where']
            ), '', 1
        )[$post['field_name']];

        if ($data[$post['field_name']])
        {
            set_response(200, $data);
        }
        else
        {
            set_response(404);
        }
    }

    /**
    * Records data
    * @author     Siti Hasuna <sh.hanaaa@gmail.com>
    * @param      table name, where
    */
    public function records_post()
    {
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }

        $data = $this->model->records($post);
        
        if ( ! $data)
        {
            set_response(404);
        } 
        else 
        {
            set_response(200, $data);
        }
    }

    /**
    * Data untuk plugin selectlist2
    * @author     Siti Hasuna <sh.hanaaa@gmail.com>
    * @param      table name, where
    */
    public function selectlist2_get()
    {
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }
        
        unset($post[DEFAULT_API_KEY_NAME]);

        $tbl      = $post['table'];
        $id       = ($post['id']) ? $post['id'] : 'id';

        if (substr_count($id, '_encode')) // Jika id di encrypt
        {
            $id_column   = substr($id, 0, stripos($id, '_encode'));
            $id          = sha1field($id_column, $id_column);
        }
        else
        {
            $id_column   = $id;
        }
        
        $name     = ($post['name']) ? $post['name'] : 'name';
        $where    = $post['where'];
        $where_in = $post['where_in'];
        $selected = $post['selected'];
        $title    = ($post['title']) ? $post['title'] : 'Select';
        $order    = ($post['order']) ? $post['order'] : $post['id'];
        $or       = (empty($order)) ? $name : $order;
                    if ($where_in) {
                        $key = array_keys($where_in);
                        $val = array_values($where_in);
                        foreach ($key as $ky => $keyx) {
                            $this->db->where_in($keyx,$val[$ky]);
                        } 
                    }
        $list     = $this->db->order_by($or,'asc')
                             ->select("$id , $name")
                             ->get_where($tbl,$where)
                             ->result_array();
        $opt      = $post['no_title'] ? '' : "<option value=''>$title</option>";
        $opt     .= ($post['add_new']) ? "<option value='addNew'>+ Add $post[add_new]</option>" : '';

        if ($post['alias'])
        {
            $name = $post['alias'];
        }
        
        foreach ($list as $l)
        {
            $terpilih  = ($selected == $l[$id_column]) ? 'selected' : '';
            $opt      .= "<option $terpilih value='$l[$id_column]'>$l[$name]</option>";
        }

        $data = $opt;

        if ($data)
        {
            // OK (200) being the HTTP response code
            set_response(200, $data);
        }
        else
        {
            set_response(404);
        }
    }

    /**
    * Insert data into table
    * @author     Siti Hasuna <sh.hanaaa@gmail.com>
    */
    public function insert_post()
    {
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }

        unset($post[DEFAULT_API_KEY_NAME]);

        $this->form_validation->set_rules('table_name', 'Table Name', 'required');
        $this->form_validation->set_rules('data_insert[]', 'Data Insert', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $error_validation = $this->form_validation->error_array();
            $i = 0;
            foreach ($error_validation as $key => $value) 
            {
                $error_ret[$i]['field']   = $key;
                $error_ret[$i]['message'] = $value;
                $i++;
            }

            set_response(422, '', '', $error_ret);
        }
        else
        {
            $id_insert  = $this->model->insert($post['table_name'], $post['data_insert']);
            if ( ! $id_insert)
            {
                set_response(404, '', $this->data['lang_insert_failed']);
            } 
            else 
            {
                set_response(200, $data, $this->data['lang_insert_success']);
            }
        }
    }

    /**
    * Insert data unik, data akan dicek apakah sudah ada atau belum
    * @author     Siti Hasuna <sh.hanaaa@gmail.com>
    */
    public function insert_unique_post()
    {
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }

        $this->form_validation->set_rules('table_name', 'Table Name', 'required');
        $this->form_validation->set_rules('unique_field', 'Unique Field Name', 'required');
        $this->form_validation->set_rules('field_value', 'Field Value', 'required');
        $this->form_validation->set_rules('data_insert[]', 'Data Insert', 'required');
        if ($this->form_validation->run() === FALSE)
        {
            $error_validation = $this->form_validation->error_array();
            $i = 0;
            foreach ($error_validation as $key => $value) 
            {
                $error_ret[$i]['field']   = $key;
                $error_ret[$i]['message'] = $value;
                $i++;
            }

            set_response(422, '', '', $error_ret); 
        }
        else
        {
            $table        = $post['table_name'];
            $unique_field = $post['unique_field'];
            $field_value  = $post['field_value'];

            unset($post[DEFAULT_API_KEY_NAME], $post['table_name'], $post['unique_field'], $post['field_value']);

            $cek = db_get_one($table, 'name', "$unique_field = '$field_value'");

            if ($cek) 
            {
                set_response(409, '', 'Data "'.$field_value.'" '.$this->data['lang_exist']);
            }
            else
            {
                $save = $this->model->insert($table, set_null($post['data_insert']));
                if ($save)
                {
                    $data['id'] = sha1plus($save);
                    set_response(200, $data, $this->data['lang_insert_success']);
                }
                else
                {
                    set_response(404, '', $this->data['lang_insert_failed']);
                }
            }
        }
    }

    /**
    * Update data
    * @author     Siti Hasuna <sh.hanaaa@gmail.com>
    */
    public function update_post()
    {
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }

        unset($post[DEFAULT_API_KEY_NAME]);

        $this->form_validation->set_rules('table_name', 'Table Name', 'required');
        $this->form_validation->set_rules('data_update[]', 'Data Insert', 'required');
        if ($this->form_validation->run() === FALSE)
        {
            $error_validation = $this->form_validation->error_array();
            $i = 0;
            foreach ($error_validation as $key => $value) 
            {
                $error_ret[$i]['field']   = $key;
                $error_ret[$i]['message'] = $value;
                $i++;
            }

            set_response(422, '', '', $error_ret); 
        }
        else
        {
            $cek_data = $this->model->fetchAll(
                array(
                    'table' => $post['table_name'],
                    'where' => $post['where']
                ), '', 1
            );

            if ($cek_data)
            {
                $update = $this->model->update($post['table_name'], $post['data_update'], $post['where']);
            }

            if ( ! $update)
            {
                set_response(404, '', $this->data['lang_update_failed']);
            } 
            else 
            {
                $data['id'] = sha1plus($save);
                set_response(200, $data, $this->data['lang_update_success']);
            }
        }
    }

    /**
    * Delete data
    * @author     Siti Hasuna <sh.hanaaa@gmail.com>
    * @param      table_name   : nama table
    * @param      where        : kondisi where
    * @param      is_permanent : bernilai 1 jika delete data permanen, 
    *                            0 jika data di delete berdasarkan is_delete
    */
    public function delete_post()
    {
        $post = $this->input->post();
        if (empty($post)) 
        {
            $post = $this->input->get();
        }

        $is_permanent = empty($post['is_permanent']) ? 0 : 1;

        unset($post[DEFAULT_API_KEY_NAME]);

        $this->form_validation->set_rules('table_name', 'Table Name', 'required');
        // $this->form_validation->set_rules('where', 'Where', 'required');
        if ($this->form_validation->run() === FALSE)
        {
            $error_validation = $this->form_validation->error_array();
            $i = 0;
            foreach ($error_validation as $key => $value) 
            {
                $error_ret[$i]['field']   = $key;
                $error_ret[$i]['message'] = $value;
                $i++;
            }

            set_response(422, '', '', $error_ret);
        }
        else
        {
            $cek_data = $this->model->fetchAll(
                array(
                    'table' => $post['table_name'],
                    'where' => $post['where']
                ), '', 1
            );

            if ($cek_data)
            {
                $delete  = $this->model->delete($post['table_name'], $post['where'], $is_permanent);
            }
            
            if ( ! $delete)
            {
                set_response(200, '', $this->data['lang_delete_failed']);
            } 
            else 
            {
                set_response(200, '', $this->data['lang_delete_success']);
            }
        }
    }
}

/* End of file Query.php */
/* Location: ./application/modules/common/controllers/Query.php */